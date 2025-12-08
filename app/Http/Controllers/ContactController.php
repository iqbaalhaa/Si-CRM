<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactChannel;
use App\Models\ContactDetail;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ContactController extends Controller
{
    protected function ensureRoleIdsAllowed(): void
    {
        $user = Auth::user();
        if (!$user) abort(403);
        $allowed = $user->roles()->whereIn('id', [2, 3])->exists();
        if (!$allowed) abort(403);
    }

    public function index(Request $request)
    {
        $this->ensureRoleIdsAllowed();

        $type = $request->query('type');

        $query = $this->buildSearchQuery($request)
            ->with([
                'company',
                'channels' => fn($q) => $q->where('is_primary', 1)
            ])
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        $contacts = $query->get();

        return view('pages.contact.list', compact('contacts', 'type'));
    }

    public function create()
    {
        $this->ensureRoleIdsAllowed();
        return view('pages.contact.index');
    }

    public function store(Request $request)
    {
        $this->ensureRoleIdsAllowed();
        $baseRules = [
            'type' => 'required|in:individual,company,organization',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:191',
            'details.label.*' => 'nullable|string|max:50|distinct',
            'details.value.*' => 'nullable|string',
        ];

        if ($request->input('type') === 'individual') {
            $typeRules = [
                'alamat_lengkap' => 'required|string',
                'kota_kabupaten' => 'required|string',
                'provinsi' => 'required|string',
                'negara' => 'required|string',
                'jenis_kelamin' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'agama' => 'required|string',
                'status_pernikahan' => 'required|string',
            ];
        } elseif ($request->input('type') === 'company') {
            $typeRules = [
                'nama_brand' => 'required|string',
                'industri' => 'required|string',
                'npwp' => 'required|string',
                'alamat_lengkap' => 'required|string',
                'kota_kabupaten' => 'required|string',
                'provinsi' => 'required|string',
                'negara' => 'required|string',
            ];
        } else {
            $typeRules = [
                'tipe_organisasi' => 'required|string',
                'bidang_kegiatan' => 'required|string',
                'jumlah_anggota' => 'required|integer',
                'alamat_lengkap' => 'required|string',
                'kota_kabupaten' => 'required|string',
                'provinsi' => 'required|string',
                'negara' => 'required|string',
            ];
        }

        $validated = $request->validate(array_merge($baseRules, $typeRules));

        $user = Auth::user();
        $companyId = $user?->company_id;

        $contact = Contact::create([
            'company_id' => $companyId,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'is_active' => true,
            'created_by' => $user?->id,
        ]);

        if (!empty($validated['email'])) {
            ContactChannel::create([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'label' => 'email',
                'value' => $validated['email'],
                'is_primary' => true,
            ]);
        }

        if (!empty($validated['phone'])) {
            ContactChannel::create([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'label' => 'phone',
                'value' => $validated['phone'],
                'is_primary' => true,
            ]);
        }

        $detailLabels = [];
        if ($validated['type'] === 'individual') {
            $detailLabels = [
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
                'jenis_kelamin',
                'tanggal_lahir',
                'agama',
                'status_pernikahan',
            ];
        } elseif ($validated['type'] === 'company') {
            $detailLabels = [
                'nama_brand',
                'industri',
                'npwp',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ];
        } else {
            $detailLabels = [
                'tipe_organisasi',
                'bidang_kegiatan',
                'jumlah_anggota',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ];
        }

        foreach ($detailLabels as $label) {
            $val = $validated[$label] ?? null;
            if ($val !== null && $val !== '') {
                ContactDetail::create([
                    'company_id' => $companyId,
                    'contact_id' => $contact->id,
                    'label' => $label,
                    'value' => (string) $val,
                ]);
            }
        }

        $labels = $request->input('details.label', []);
        $values = $request->input('details.value', []);
        for ($i = 0; $i < count($labels); $i++) {
            $label = $labels[$i] ?? null;
            $value = $values[$i] ?? null;
            if (!$label) continue;
            ContactDetail::create([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'label' => $label,
                'value' => (string) $value,
            ]);
        }

        return redirect()->route('contacts.index')->with('success', 'Kontak berhasil dibuat');
    }

    public function show(Contact $contact)
    {
        $this->ensureRoleIdsAllowed();
        $this->authorizeCompany($contact);
        $contact->load(['channels', 'details', 'creator']);
        return view('pages.contact.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $this->ensureRoleIdsAllowed();
        $this->authorizeCompany($contact);
        $contact->load(['channels', 'details']);
        $email = $contact->channels->firstWhere('label', 'email');
        $phone = $contact->channels->firstWhere('label', 'phone');
        return view('pages.contact.edit', compact('contact', 'email', 'phone'));
    }

    public function update(Request $request, Contact $contact)
    {
        $this->ensureRoleIdsAllowed();
        $this->authorizeCompany($contact);

        $baseRules = [
            'type' => 'required|in:individual,company,organization',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:191',
            'details.label.*' => 'nullable|string|max:50|distinct',
            'details.value.*' => 'nullable|string',
        ];

        if ($request->input('type') === 'individual') {
            $typeRules = [
                'alamat_lengkap' => 'required|string',
                'kota_kabupaten' => 'required|string',
                'provinsi' => 'required|string',
                'negara' => 'required|string',
                'jenis_kelamin' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'agama' => 'required|string',
                'status_pernikahan' => 'required|string',
            ];
        } elseif ($request->input('type') === 'company') {
            $typeRules = [
                'nama_brand' => 'required|string',
                'industri' => 'required|string',
                'npwp' => 'required|string',
                'alamat_lengkap' => 'required|string',
                'kota_kabupaten' => 'required|string',
                'provinsi' => 'required|string',
                'negara' => 'required|string',
            ];
        } else {
            $typeRules = [
                'tipe_organisasi' => 'required|string',
                'bidang_kegiatan' => 'required|string',
                'jumlah_anggota' => 'required|integer',
                'alamat_lengkap' => 'required|string',
                'kota_kabupaten' => 'required|string',
                'provinsi' => 'required|string',
                'negara' => 'required|string',
            ];
        }

        $validated = $request->validate(array_merge($baseRules, $typeRules));

        $contact->update([
            'type' => $validated['type'],
            'name' => $validated['name'],
        ]);

        $companyId = Auth::user()->company_id;

        // Upsert email channel
        if (array_key_exists('email', $validated)) {
            $this->upsertChannel($contact, 'email', $validated['email'], $companyId);
        }

        // Upsert phone channel
        if (array_key_exists('phone', $validated)) {
            $this->upsertChannel($contact, 'phone', $validated['phone'], $companyId);
        }

        $detailLabels = [];
        if ($validated['type'] === 'individual') {
            $detailLabels = [
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
                'jenis_kelamin',
                'tanggal_lahir',
                'agama',
                'status_pernikahan',
            ];
        } elseif ($validated['type'] === 'company') {
            $detailLabels = [
                'nama_brand',
                'industri',
                'npwp',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ];
        } else {
            $detailLabels = [
                'tipe_organisasi',
                'bidang_kegiatan',
                'jumlah_anggota',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ];
        }

        foreach ($detailLabels as $label) {
            $val = $validated[$label] ?? null;
            $existing = $contact->details()->where('label', $label)->first();
            if ($val === null || $val === '') {
                if ($existing) $existing->delete();
                continue;
            }
            if ($existing) {
                $existing->update(['value' => (string) $val]);
            } else {
                ContactDetail::create([
                    'company_id' => $companyId,
                    'contact_id' => $contact->id,
                    'label' => $label,
                    'value' => (string) $val,
                ]);
            }
        }

        $labels = $request->input('details.label', []);
        $values = $request->input('details.value', []);
        $postedCustom = [];
        for ($i = 0; $i < count($labels); $i++) {
            $label = $labels[$i] ?? null;
            $value = $values[$i] ?? null;
            if (!$label) continue;
            if (in_array($label, $detailLabels, true)) continue;
            $postedCustom[] = $label;
            $existing = $contact->details()->where('label', $label)->first();
            if ($existing) {
                $existing->update(['value' => (string) $value]);
            } else {
                ContactDetail::create([
                    'company_id' => $companyId,
                    'contact_id' => $contact->id,
                    'label' => $label,
                    'value' => (string) $value,
                ]);
            }
        }

        $contact->details()
            ->whereNotIn('label', $detailLabels)
            ->whereNotIn('label', $postedCustom)
            ->delete();

        return redirect()->route('contacts.show', $contact)->with('success', 'Kontak diperbarui');
    }

    protected function upsertChannel(Contact $contact, string $label, ?string $value, int $companyId): void
    {
        $existing = $contact->channels()->where('label', $label)->first();

        if (!$value) {
            if ($existing) $existing->delete();
            return;
        }

        if ($existing) {
            $existing->update([
                'value' => $value,
                'is_primary' => true,
            ]);
        } else {
            ContactChannel::create([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'label' => $label,
                'value' => $value,
                'is_primary' => true,
            ]);
        }
    }

    protected function authorizeCompany(Contact $contact): void
    {
        $companyId = Auth::user()->company_id;
        if ($contact->company_id !== $companyId) {
            abort(403);
        }
    }

    protected function buildSearchQuery(Request $request)
    {
        $user = auth()->user();
        $companyId = $user?->company_id ?? (Perusahaan::query()->value('id'));
        $showAll = $request->boolean('all') && $user && $user->hasRole('super-admin');

        $query = Contact::query();
        if (!$showAll) {
            $query->where('company_id', $companyId);
        }

        // filter aktif (opsional)
        if ($request->boolean('only_active')) {
            $query->where('is_active', 1);
        }

        // ===== GENERAL KEYWORD SEARCH =====
        if ($request->filled('q')) {
            $raw = $request->input('q');
            $parts = array_map('trim', explode(',', $raw));
            $keywords = array_filter($parts, fn ($v) => $v !== '');

            foreach ($keywords as $kw) {
                // dukung pencarian umur: "umur:25", "umur 25", "umur>30", "umur<=40"
                if (preg_match('/^umur\s*(?:(>=|<=|>|<|:)?\s*)?(\d{1,3})$/i', $kw, $m)) {
                    $op = $m[1] ?: ':';
                    $age = (int) $m[2];
                    $now = now();
                    if ($op === ':' ) {
                        $upper = $now->copy()->subYears($age)->toDateString();
                        $lower = $now->copy()->subYears($age + 1)->addDay()->toDateString();
                        $query->whereHas('details', function ($q) use ($lower, $upper) {
                            $q->where('label', 'tanggal_lahir')
                              ->whereBetween('value', [$lower, $upper]);
                        });
                    } elseif ($op === '>' ) {
                        $threshold = $now->copy()->subYears($age + 1)->toDateString();
                        $query->whereHas('details', function ($q) use ($threshold) {
                            $q->where('label', 'tanggal_lahir')
                              ->where('value', '<=', $threshold);
                        });
                    } elseif ($op === '>=' ) {
                        $threshold = $now->copy()->subYears($age)->toDateString();
                        $query->whereHas('details', function ($q) use ($threshold) {
                            $q->where('label', 'tanggal_lahir')
                              ->where('value', '<=', $threshold);
                        });
                    } elseif ($op === '<' ) {
                        $threshold = $now->copy()->subYears($age)->addDay()->toDateString();
                        $query->whereHas('details', function ($q) use ($threshold) {
                            $q->where('label', 'tanggal_lahir')
                              ->where('value', '>=', $threshold);
                        });
                    } elseif ($op === '<=' ) {
                        $threshold = $now->copy()->subYears($age)->toDateString();
                        $query->whereHas('details', function ($q) use ($threshold) {
                            $q->where('label', 'tanggal_lahir')
                              ->where('value', '>=', $threshold);
                        });
                    }
                    continue;
                }

                $query->where(function ($sub) use ($kw) {
                    $sub->where('name', 'like', "%{$kw}%")
                        ->orWhereHas('details', function ($q) use ($kw) {
                            $q->where('value', 'like', "%{$kw}%");
                        })
                        ->orWhereHas('channels', function ($q) use ($kw) {
                            $q->where('value', 'like', "%{$kw}%");
                        });
                });
            }
        }

        return $query;
    }

    public function advancedIndex(Request $request)
    {
        $type = $request->query('type');

        $query = $this->buildSearchQuery($request)
            ->with([
                'company',
                'channels' => fn($q) => $q->where('is_primary', 1)
            ])
            ->orderBy('name');

        if ($type) {
            $query->where('type', $type);
        }

        $contacts = $query->get();
        if ($request->wantsJson()) {
            $items = $contacts->map(function ($c) {
                $email = optional($c->channels->firstWhere('label', 'email'))->value;
                $phone = optional($c->channels->firstWhere('label', 'phone'))->value;
                $wa = optional($c->channels->firstWhere('label', 'whatsapp'))->value;
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'type' => $c->type,
                    'company' => optional($c->company)->name,
                    'is_active' => (bool) $c->is_active,
                    'email' => $email,
                    'phone' => $phone,
                    'whatsapp' => $wa,
                ];
            });
            return response()->json(['items' => $items]);
        }
        return view('pages.contact.list', compact('contacts', 'type'));
    }

    public function export(Request $request)
    {
        $type = $request->query('type');
        $format = $request->query('format', 'csv');

        $query = $this->buildSearchQuery($request)
            ->with(['channels', 'details'])
            ->orderBy('name');

        if ($type) {
            $query->where('type', $type);
        }

        $contacts = $query->get();

        $detailLabels = collect($contacts)
            ->flatMap(function ($c) { return $c->details->pluck('label'); })
            ->unique()
            ->values()
            ->all();

        $headers = [
            'contact_id',
            'company_id',
            'type',
            'name',
            'is_active',
            'umur',
            'created_at',
            'updated_at',
            'email_primary',
            'phone_primary',
            'whatsapp_primary',
            'emails',
            'phones',
            'whatsapps',
        ];
        $headers = array_merge($headers, $detailLabels);

        $rows = [];
        $rows[] = $headers;
        foreach ($contacts as $contact) {
            $primaryByLabel = $contact->channels->where('is_primary', 1)->keyBy('label');
            if ($primaryByLabel->isEmpty()) {
                $firstByLabel = $contact->channels->groupBy('label')->map(function ($grp) { return $grp->first(); });
                $primaryByLabel = $firstByLabel;
            }
            $allEmails = $contact->channels->where('label', 'email')->pluck('value')->filter()->values()->all();
            $allPhones = $contact->channels->where('label', 'phone')->pluck('value')->filter()->values()->all();
            $allWhats = $contact->channels->where('label', 'whatsapp')->pluck('value')->filter()->values()->all();

            $dob = optional($contact->details->firstWhere('label', 'tanggal_lahir'))->value;
            $ageVal = '';
            if ($dob) {
                try {
                    $ageVal = \Carbon\Carbon::parse($dob)->age;
                } catch (\Exception $e) {
                    $ageVal = '';
                }
            }

            $row = [
                $contact->id,
                $contact->company_id,
                $contact->type,
                $contact->name,
                $contact->is_active ? 1 : 0,
                $ageVal,
                optional($contact->created_at)->toDateTimeString(),
                optional($contact->updated_at)->toDateTimeString(),
                optional($primaryByLabel->get('email'))->value ?? '',
                optional($primaryByLabel->get('phone'))->value ?? '',
                optional($primaryByLabel->get('whatsapp'))->value ?? '',
                implode(', ', $allEmails),
                implode(', ', $allPhones),
                implode(', ', $allWhats),
            ];

            foreach ($detailLabels as $label) {
                $row[] = optional($contact->details->firstWhere('label', $label))->value ?? '';
            }

            $rows[] = $row;
        }

        if ($format === 'xlsx') {
            return $this->streamXlsx($rows, 'contacts_' . now()->format('Ymd_His') . '.xlsx');
        }

        $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $this->ensureRoleIdsAllowed();

        $request->validate([
            'type' => 'required|in:individual,company,organization',
            'file' => 'required|file|mimes:xlsx',
        ]);

        $user = Auth::user();
        if (! $user || ! $user->company_id) {
            abort(403);
        }

        $companyId = $user->company_id;
        $createdBy = $user->id;

        $type = strtolower($request->input('type'));

        $detailLabels = [];
        if ($type === 'individual') {
            $detailLabels = [
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
                'jenis_kelamin',
                'tanggal_lahir',
                'agama',
                'status_pernikahan',
            ];
        } elseif ($type === 'company') {
            $detailLabels = [
                'nama_brand',
                'industri',
                'npwp',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ];
        } else {
            $detailLabels = [
                'tipe_organisasi',
                'bidang_kegiatan',
                'jumlah_anggota',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ];
        }

        $file = $request->file('file');
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $headers = [];
        for ($col = 1; $col <= $highestColIndex; $col++) {
            $val = (string) $sheet->getCellByColumnAndRow($col, 1)->getValue();
            $norm = strtolower(trim($val));
            $norm = str_replace([' ', '-', '/'], '_', $norm);
            $headers[$col] = $norm;
        }

        $required = ['name'];
        foreach ($required as $req) {
            if (! in_array($req, $headers, true)) {
                return redirect()->route('contacts.index')->with('error', 'Header tidak valid.');
            }
        }

        $created = 0;
        for ($row = 3; $row <= $highestRow; $row++) {
            $rowVals = [];
            $isEmptyRow = true;
            for ($col = 1; $col <= $highestColIndex; $col++) {
                $v = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                $v = is_string($v) ? trim($v) : $v;
                if ($v !== null && $v !== '') $isEmptyRow = false;
                $rowVals[$headers[$col] ?? ''] = $v;
            }
            if ($isEmptyRow) continue;

            $name = (string) ($rowVals['name'] ?? '');
            if ($name === '') continue;

            $contact = Contact::create([
                'company_id' => $companyId,
                'type' => $type,
                'name' => $name,
                'is_active' => true,
                'created_by' => $createdBy,
            ]);

            $email = (string) ($rowVals['email'] ?? '');
            if ($email !== '') {
                ContactChannel::create([
                    'company_id' => $companyId,
                    'contact_id' => $contact->id,
                    'label' => 'email',
                    'value' => $email,
                    'is_primary' => true,
                ]);
            }

            $phone = (string) ($rowVals['phone'] ?? '');
            if ($phone !== '') {
                ContactChannel::create([
                    'company_id' => $companyId,
                    'contact_id' => $contact->id,
                    'label' => 'phone',
                    'value' => $phone,
                    'is_primary' => true,
                ]);
            }

            foreach ($detailLabels as $label) {
                $val = $rowVals[$label] ?? null;
                if ($val === null || $val === '') continue;
                if ($label === 'jumlah_anggota') {
                    if (is_numeric($val)) $val = (string) (int) $val;
                }
                if ($label === 'tanggal_lahir') {
                    try {
                        $dt = \Carbon\Carbon::parse((string) $val);
                        $val = $dt->toDateString();
                    } catch (\Exception $e) {
                        $val = (string) $val;
                    }
                }
                ContactDetail::create([
                    'company_id' => $companyId,
                    'contact_id' => $contact->id,
                    'label' => $label,
                    'value' => (string) $val,
                ]);
            }

            $created++;
        }

        return redirect()->route('contacts.index')->with('success', 'Import selesai: ' . $created . ' baris.');
    }

    protected function xlsxCol(int $index): string
    {
        $s = '';
        while ($index > 0) {
            $index--;
            $s = chr(65 + ($index % 26)) . $s;
            $index = intdiv($index, 26);
        }
        return $s;
    }

    public function downloadTemplate(string $type)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $type = strtolower($type);

        // Kolom dasar untuk semua tipe kontak
        $baseColumns = [
            'name',   // Nama kontak
            'email',  // Email utama
            'phone',  // Nomor telepon / WhatsApp
        ];

        // Tambahan kolom per tipe kontak
        $extraColumnsByType = [
            'individual' => [
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
                'jenis_kelamin',
                'tanggal_lahir',
                'agama',
                'status_pernikahan',
            ],
            'company' => [
                'nama_brand',
                'industri',
                'npwp',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ],
            'organization' => [
                'tipe_organisasi',
                'bidang_kegiatan',
                'jumlah_anggota',
                'alamat_lengkap',
                'kota_kabupaten',
                'provinsi',
                'negara',
            ],
        ];

        if (! array_key_exists($type, $extraColumnsByType)) {
            abort(404);
        }

        $headers = array_merge($baseColumns, $extraColumnsByType[$type]);

        // Baris 2: keterangan / contoh isi (supaya user paham)
        $hintsBase = [
            'name'  => 'Nama lengkap kontak',
            'email' => 'Email aktif (boleh dikosongkan)',
            'phone' => 'Nomor HP / WhatsApp',
        ];

        $hintsByType = [
            'individual' => [
                'alamat_lengkap'    => 'Alamat rumah lengkap',
                'kota_kabupaten'    => 'Kota / Kabupaten',
                'provinsi'          => 'Provinsi',
                'negara'            => 'Negara',
                'jenis_kelamin'     => 'L / P',
                'tanggal_lahir'     => 'Format: YYYY-MM-DD (contoh: 1990-01-31)',
                'agama'             => 'Agama',
                'status_pernikahan' => 'Lajang / Menikah / Dll',
            ],
            'company' => [
                'nama_brand'      => 'Nama brand / nama dagang',
                'industri'        => 'Industri (mis: F&B, IT, Pendidikan)',
                'npwp'            => 'Nomor NPWP perusahaan',
                'alamat_lengkap'  => 'Alamat kantor',
                'kota_kabupaten'  => 'Kota / Kabupaten',
                'provinsi'        => 'Provinsi',
                'negara'          => 'Negara',
            ],
            'organization' => [
                'tipe_organisasi'  => 'Mis: Komunitas, Yayasan, LSM, dll',
                'bidang_kegiatan'  => 'Bidang kegiatan utama',
                'jumlah_anggota'   => 'Perkiraan jumlah anggota',
                'alamat_lengkap'   => 'Alamat sekretariat / kantor',
                'kota_kabupaten'   => 'Kota / Kabupaten',
                'provinsi'         => 'Provinsi',
                'negara'           => 'Negara',
            ],
        ];

        $hints = [];
        foreach ($headers as $col) {
            $hints[] = $hintsBase[$col]
                ?? $hintsByType[$type][$col]
                ?? ''; // default kosong
        }

        // rows: baris 1 = header, baris 2 = hint
        $rows = [
            $headers,
            $hints,
        ];

        $filename = 'contacts_template_' . $type . '_' . now()->format('Ymd_His') . '.xlsx';

        return $this->streamXlsx($rows, $filename);
    }

    public function destroy(Contact $contact)
    {
        $this->ensureRoleIdsAllowed();
        $this->authorizeCompany($contact);

        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Kontak dihapus');
    }

    protected function streamXlsx(array $rows, string $filename)
    {
        // Buat workbook baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Contacts');

        // Isi data baris per baris
        foreach ($rows as $rowIndex => $row) {
            $excelRow = $rowIndex + 1; // Excel mulai dari 1
            foreach ($row as $colIndex => $value) {
                $excelCol = $colIndex + 1;
                $sheet->setCellValueByColumnAndRow($excelCol, $excelRow, $value);
            }
        }

        // Optional: bikin header bold biar enak dipakai
        $headerColumnCount = count($rows[0] ?? []);
        if ($headerColumnCount > 0) {
            $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($headerColumnCount);
            $sheet->getStyle("A1:{$lastColLetter}1")->getFont()->setBold(true);
        }

        // Stream ke browser
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            // Tulis langsung ke output, bukan ke file sementara
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

}
