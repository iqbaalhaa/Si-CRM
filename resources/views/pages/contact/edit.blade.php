@extends('layouts.master')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center mb-3 mb-lg-4">
        <div>
            <h3 class="mb-1">Edit Kontak</h3>
            <p class="text-muted mb-0">Perbarui informasi kontak yang sudah ada.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-outline-info btn-sm">
                <i class="bi bi-eye me-1"></i> Detail
            </a>
            <a href="{{ route('contacts.index') }}" class="btn btn-light btn-sm border">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-content">
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-1">Form Edit Kontak</h5>
                        <p class="text-muted small mb-0">Sesuaikan informasi dasar, detail tipe, dan field kustom kontak.</p>
                    </div>

                    <div class="card-body pt-3">
                        <form action="{{ route('contacts.update', $contact->id) }}" method="POST" id="contactEditForm">
                            @csrf
                            @method('PUT')

                            {{-- Informasi utama --}}
                            <div class="row g-3 mb-2">
                                <div class="col-12">
                                    <span class="text-uppercase text-muted small fw-semibold">Informasi Utama</span>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Tipe Kontak</label>
                                        <select name="type" id="type" class="form-select" required>
                                            <option value="individual" {{ $contact->type === 'individual' ? 'selected' : '' }}>Individu</option>
                                            <option value="company" {{ $contact->type === 'company' ? 'selected' : '' }}>Perusahaan</option>
                                            <option value="organization" {{ $contact->type === 'organization' ? 'selected' : '' }}>Organisasi</option>
                                        </select>
                                    </div>
                                    @error('type')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                               placeholder="Nama kontak"
                                               value="{{ old('name', $contact->name) }}" required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label">Email</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                                        <input type="email" name="email" class="form-control"
                                               placeholder="Email"
                                               value="{{ old('email', optional($email)->value) }}">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label">Nomor WhatsApp / Telepon</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" name="phone" class="form-control"
                                               placeholder="Nomor WhatsApp / Telepon"
                                               value="{{ old('phone', optional($phone)->value) }}">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- DETAIL INDIVIDUAL --}}
                            <div class="mt-2" id="fields_individual" style="display:none">
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <span class="text-uppercase text-muted small fw-semibold">Detail Individu</span>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat lengkap</label>
                                            <input type="text" name="alamat_lengkap" class="form-control"
                                                   placeholder="Alamat lengkap"
                                                   value="{{ old('alamat_lengkap', optional($contact->details->firstWhere('label','alamat_lengkap'))->value) }}">
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <input type="text" name="kota_kabupaten" class="form-control"
                                                   placeholder="Kota / Kabupaten"
                                                   value="{{ old('kota_kabupaten', optional($contact->details->firstWhere('label','kota_kabupaten'))->value) }}">
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Provinsi</label>
                                            <input type="text" name="provinsi" class="form-control"
                                                   placeholder="Provinsi"
                                                   value="{{ old('provinsi', optional($contact->details->firstWhere('label','provinsi'))->value) }}">
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Negara</label>
                                            <input type="text" name="negara" class="form-control"
                                                   placeholder="Negara"
                                                   value="{{ old('negara', optional($contact->details->firstWhere('label','negara'))->value ?? 'Indonesia') }}">
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal lahir</label>
                                            <input type="date" name="tanggal_lahir" class="form-control"
                                                   value="{{ old('tanggal_lahir', optional($contact->details->firstWhere('label','tanggal_lahir'))->value) }}">
                                        </div>
                                        @error('tanggal_lahir')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Jenis kelamin</label>
                                            @php $jk = optional($contact->details->firstWhere('label','jenis_kelamin'))->value; @endphp
                                            <select name="jenis_kelamin" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin', $jk)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin', $jk)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                <option value="Lainnya" {{ old('jenis_kelamin', $jk)=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Agama</label>
                                            @php $ag = optional($contact->details->firstWhere('label','agama'))->value; @endphp
                                            <select name="agama" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Islam" {{ old('agama', $ag)=='Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama', $ag)=='Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama', $ag)=='Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama', $ag)=='Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama', $ag)=='Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ old('agama', $ag)=='Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                                <option value="Lainnya" {{ old('agama', $ag)=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('agama')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Status pernikahan</label>
                                            @php $sp = optional($contact->details->firstWhere('label','status_pernikahan'))->value; @endphp
                                            <select name="status_pernikahan" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Belum Menikah" {{ old('status_pernikahan', $sp)=='Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                                <option value="Menikah" {{ old('status_pernikahan', $sp)=='Menikah' ? 'selected' : '' }}>Menikah</option>
                                                <option value="Cerai" {{ old('status_pernikahan', $sp)=='Cerai' ? 'selected' : '' }}>Cerai</option>
                                                <option value="Lainnya" {{ old('status_pernikahan', $sp)=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('status_pernikahan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- DETAIL COMPANY --}}
                            <div class="mt-2" id="fields_company" style="display:none">
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <span class="text-uppercase text-muted small fw-semibold">Detail Perusahaan</span>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nama brand</label>
                                            <input type="text" name="nama_brand" class="form-control"
                                                   placeholder="Nama brand"
                                                   value="{{ old('nama_brand', optional($contact->details->firstWhere('label','nama_brand'))->value) }}">
                                        </div>
                                        @error('nama_brand')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Industri</label>
                                            <input type="text" name="industri" class="form-control"
                                                   placeholder="Industri"
                                                   value="{{ old('industri', optional($contact->details->firstWhere('label','industri'))->value) }}">
                                        </div>
                                        @error('industri')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">NPWP</label>
                                            <input type="text" name="npwp" class="form-control"
                                                   placeholder="NPWP"
                                                   value="{{ old('npwp', optional($contact->details->firstWhere('label','npwp'))->value) }}">
                                        </div>
                                        @error('npwp')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat lengkap</label>
                                            <input type="text" name="alamat_lengkap" class="form-control"
                                                   placeholder="Alamat lengkap"
                                                   value="{{ old('alamat_lengkap', optional($contact->details->firstWhere('label','alamat_lengkap'))->value) }}">
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <input type="text" name="kota_kabupaten" class="form-control"
                                                   placeholder="Kota / Kabupaten"
                                                   value="{{ old('kota_kabupaten', optional($contact->details->firstWhere('label','kota_kabupaten'))->value) }}">
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Provinsi</label>
                                            <input type="text" name="provinsi" class="form-control"
                                                   placeholder="Provinsi"
                                                   value="{{ old('provinsi', optional($contact->details->firstWhere('label','provinsi'))->value) }}">
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Negara</label>
                                            <input type="text" name="negara" class="form-control"
                                                   placeholder="Negara"
                                                   value="{{ old('negara', optional($contact->details->firstWhere('label','negara'))->value ?? 'Indonesia') }}">
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- DETAIL ORGANIZATION --}}
                            <div class="mt-2" id="fields_organization" style="display:none">
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <span class="text-uppercase text-muted small fw-semibold">Detail Organisasi</span>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tipe organisasi</label>
                                            <input type="text" name="tipe_organisasi" class="form-control"
                                                   placeholder="Tipe organisasi"
                                                   value="{{ old('tipe_organisasi', optional($contact->details->firstWhere('label','tipe_organisasi'))->value) }}">
                                        </div>
                                        @error('tipe_organisasi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Bidang kegiatan</label>
                                            <input type="text" name="bidang_kegiatan" class="form-control"
                                                   placeholder="Bidang kegiatan"
                                                   value="{{ old('bidang_kegiatan', optional($contact->details->firstWhere('label','bidang_kegiatan'))->value) }}">
                                        </div>
                                        @error('bidang_kegiatan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah anggota</label>
                                            <input type="number" name="jumlah_anggota" class="form-control"
                                                   placeholder="Jumlah anggota"
                                                   value="{{ old('jumlah_anggota', optional($contact->details->firstWhere('label','jumlah_anggota'))->value) }}">
                                        </div>
                                        @error('jumlah_anggota')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat lengkap</label>
                                            <input type="text" name="alamat_lengkap" class="form-control"
                                                   placeholder="Alamat lengkap"
                                                   value="{{ old('alamat_lengkap', optional($contact->details->firstWhere('label','alamat_lengkap'))->value) }}">
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <input type="text" name="kota_kabupaten" class="form-control"
                                                   placeholder="Kota / Kabupaten"
                                                   value="{{ old('kota_kabupaten', optional($contact->details->firstWhere('label','kota_kabupaten'))->value) }}">
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Provinsi</label>
                                            <input type="text" name="provinsi" class="form-control"
                                                   placeholder="Provinsi"
                                                   value="{{ old('provinsi', optional($contact->details->firstWhere('label','provinsi'))->value) }}">
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Negara</label>
                                            <input type="text" name="negara" class="form-control"
                                                   placeholder="Negara"
                                                   value="{{ old('negara', optional($contact->details->firstWhere('label','negara'))->value ?? 'Indonesia') }}">
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- FIELD KUSTOM --}}
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Tambah Field Kustom</h6>
                                    <span class="text-muted small">Opsional, untuk info tambahan</span>
                                </div>

                                <div id="extraDetails" class="mb-2">
                                    @php
                                        $defaultLabels = [
                                            'alamat_lengkap','kota_kabupaten','provinsi','negara',
                                            'jenis_kelamin','tanggal_lahir','agama','status_pernikahan',
                                            'nama_brand','industri','npwp',
                                            'tipe_organisasi','bidang_kegiatan','jumlah_anggota'
                                        ];
                                    @endphp
                                    @foreach($contact->details->whereNotIn('label', $defaultLabels) as $d)
                                        <div class="row g-2 mb-2">
                                            <div class="col-md-4">
                                                <input type="text" name="details[label][]" class="form-control"
                                                       placeholder="Label" value="{{ $d->label }}">
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" name="details[value][]" class="form-control"
                                                       placeholder="Value" value="{{ $d->value }}">
                                            </div>
                                            <div class="col-md-1 d-grid">
                                                <button type="button" class="btn btn-outline-danger">Hapus</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm" id="addDetail">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Field
                                </button>

                                @error('details.label.*')
                                    <div class="text-danger small mt-1">Label duplikat atau tidak valid</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('contacts.index') }}" class="btn btn-light border px-4">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .page-content {
                background-color: transparent;
                border-radius: 1rem;
            }

            .page-content .card,
            .page-content .card-header,
            .page-content .card-body {
                background-color: transparent;
            }

            #contactEditForm .form-control,
            #contactEditForm .form-select {
                border-radius: .75rem;
            }

            #contactEditForm .input-group-text {
                border-radius: .75rem 0 0 .75rem;
            }

            #contactEditForm .input-group .form-control {
                border-radius: 0 .75rem .75rem 0;
            }

            #contactEditForm .btn {
                border-radius: .75rem;
            }

            #extraDetails .form-control {
                border-radius: .75rem;
            }

            #contactEditForm .form-control,
            #contactEditForm .form-select {
                background-color: transparent;
                border-color: var(--bs-border-color);
                color: var(--bs-body-color);
            }

            #contactEditForm .form-control:focus,
            #contactEditForm .form-select:focus {
                border-color: var(--bs-primary);
                box-shadow: 0 .2rem .6rem rgba(15, 23, 42, .08);
            }

            #contactEditForm .input-group .form-control {
                background-color: transparent;
                border-color: var(--bs-border-color);
                color: var(--bs-body-color);
            }

            #contactEditForm .input-group-text {
                background-color: transparent;
                border-color: var(--bs-border-color);
                color: var(--bs-body-color);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const typeSelect = document.getElementById('type');
            const fieldsIndividual = document.getElementById('fields_individual');
            const fieldsCompany = document.getElementById('fields_company');
            const fieldsOrganization = document.getElementById('fields_organization');
            const addDetailBtn = document.getElementById('addDetail');
            const extraDetails = document.getElementById('extraDetails');

            function setDisabled(container, disabled) {
                if (!container) return;
                container.querySelectorAll('input, select, textarea').forEach(el => {
                    el.disabled = disabled;
                });
            }

            function toggleTypeFields(type) {
                fieldsIndividual.style.display = type === 'individual' ? 'block' : 'none';
                fieldsCompany.style.display = type === 'company' ? 'block' : 'none';
                fieldsOrganization.style.display = type === 'organization' ? 'block' : 'none';

                setDisabled(fieldsIndividual, type !== 'individual');
                setDisabled(fieldsCompany, type !== 'company');
                setDisabled(fieldsOrganization, type !== 'organization');
            }

            typeSelect.addEventListener('change', () => toggleTypeFields(typeSelect.value));
            toggleTypeFields(typeSelect.value);

            // tombol tambah field kustom
            addDetailBtn.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2';
                row.innerHTML = `
                    <div class="col-md-4">
                        <input type="text" name="details[label][]" class="form-control" placeholder="Label">
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="details[value][]" class="form-control" placeholder="Value">
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-outline-danger">Hapus</button>
                    </div>
                `;
                row.querySelector('button').addEventListener('click', () => row.remove());
                extraDetails.appendChild(row);
            });

            // aktifkan tombol hapus pada field kustom yang sudah ada
            extraDetails.querySelectorAll('.btn-outline-danger').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const row = e.target.closest('.row');
                    if (row) row.remove();
                });
            });
        </script>
    @endpush
@endsection
