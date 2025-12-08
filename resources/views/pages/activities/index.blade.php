{{-- resources/views/pages/activities/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Activities & Timeline')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Activities & Timeline</h3>
            <p class="text-muted mb-0">
                Pantau seluruh aktivitas tim seperti <strong>telepon, WA, meeting</strong> dalam satu timeline rapi.
            </p>
        </div>
        <div class="text-end">
            <button class="btn btn-outline-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-activity">
                <i class="bi bi-plus-lg me-1"></i>Tambah Aktivitas
            </button>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            {{-- FILTER PANEL (DISIMPLIFY) --}}
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="form-filter-activities" action="javascript:void(0)" class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label mb-1">Cari</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama, catatan, dst.">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-1">Jenis Aktivitas</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option>WhatsApp</option>
                                    <option>Telepon</option>
                                    <option>Meeting</option>
                                    <option>Email</option>
                                    <option>Catatan</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-1">Campaign</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua campaign</option>
                                    <option>Webinar Magang Nasional Batch 3</option>
                                    <option>Smart Course - Promo Akhir Tahun</option>
                                    <option>Depati Akademi - Kelas Laravel Intensif</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-brand btn-sm flex-grow-1">
                                    <i class="bi bi-funnel-fill me-1"></i>Filter
                                </button>
                                <button type="button" class="btn btn-light btn-sm"
                                        onclick="document.getElementById('form-filter-activities').reset()">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="col-lg-8">

                {{-- LEGEND CAMPAIGN --}}
                <div class="mb-3">
                    <div class="d-flex flex-wrap align-items-center gap-3 small">
                        <span class="text-muted me-1">Legenda campaign:</span>
                        <span class="campaign-legend campaign-mag">
                            <span class="legend-dot"></span> Webinar Magang Nasional
                        </span>
                        <span class="campaign-legend campaign-smart">
                            <span class="legend-dot"></span> Smart Course - Promo Akhir Tahun
                        </span>
                        <span class="campaign-legend campaign-laravel">
                            <span class="legend-dot"></span> Depati Akademi - Kelas Laravel Intensif
                        </span>
                        <span class="campaign-legend campaign-none">
                            <span class="legend-dot"></span> Tanpa campaign
                        </span>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Timeline Aktivitas</h6>
                        <small class="text-muted">Urut dari yang terbaru</small>
                    </div>
                    <div class="card-body">

                        {{-- NANTI DIGANTI @foreach($activities as $activity) --}}
                        <div class="activity-timeline">
                            {{-- ITEM 1 - CAMPAIGN MAGANG --}}
                            <div class="activity-item activity-campaign-mag d-flex position-relative pb-4">
                                <div class="timeline-dot bg-primary-subtle text-primary">
                                    <i class="bi bi-whatsapp"></i>
                                </div>
                                <div class="flex-grow-1 ps-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <div>
                                                <span class="badge bg-light text-dark border me-2">WhatsApp</span>
                                                <strong>Follow up Magang Nasional</strong>
                                            </div>
                                            <span class="campaign-pill campaign-mag mt-1">
                                                Webinar Magang Nasional Batch 3
                                            </span>
                                        </div>
                                        <small class="text-muted">Hari ini, 14.32</small>
                                    </div>
                                    <div class="mt-1 small text-muted">
                                        Ke: <strong>Fathiya</strong> (Contact)
                                    </div>
                                    <p class="mt-2 mb-1 small">
                                        Menanyakan kesiapan dokumen untuk pendaftaran dan mengingatkan jadwal webinar malam ini.
                                    </p>
                                    <div class="small text-muted">
                                        Oleh: <strong>Rifki Dermawan</strong> • Stage:
                                        <span class="badge bg-success-subtle text-success border">Hot Lead</span>
                                    </div>
                                </div>
                            </div>

                            {{-- ITEM 2 - CAMPAIGN SMART COURSE --}}
                            <div class="activity-item activity-campaign-smart d-flex position-relative pb-4">
                                <div class="timeline-dot bg-warning-subtle text-warning">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="flex-grow-1 ps-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <div>
                                                <span class="badge bg-light text-dark border me-2">Telepon</span>
                                                <strong>Diskusi kebutuhan pelatihan kantor</strong>
                                            </div>
                                            <span class="campaign-pill campaign-smart mt-1">
                                                Smart Course - Promo Akhir Tahun
                                            </span>
                                        </div>
                                        <small class="text-muted">Hari ini, 10.05</small>
                                    </div>
                                    <div class="mt-1 small text-muted">
                                        Ke: <strong>PT Bonafide Media Pos</strong> (Company) • Terkait:
                                        <strong>Customer: Pelatihan Microsoft Office</strong>
                                    </div>
                                    <p class="mt-2 mb-1 small">
                                        Klien tertarik paket inhouse training 3 hari untuk 15 orang staf administrasi.
                                    </p>
                                    <div class="small text-muted">
                                        Oleh: <strong>Marketing 1</strong> • Durasi: ± 18 menit
                                    </div>
                                </div>
                            </div>

                            {{-- ITEM 3 - CAMPAIGN LARAVEL --}}
                            <div class="activity-item activity-campaign-laravel d-flex position-relative pb-4">
                                <div class="timeline-dot bg-info-subtle text-info">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="flex-grow-1 ps-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <div>
                                                <span class="badge bg-light text-dark border me-2">Meeting</span>
                                                <strong>Briefing batch baru Laravel intensif</strong>
                                            </div>
                                            <span class="campaign-pill campaign-laravel mt-1">
                                                Depati Akademi - Kelas Laravel Intensif
                                            </span>
                                        </div>
                                        <small class="text-muted">Kemarin, 19.30</small>
                                    </div>
                                    <div class="mt-1 small text-muted">
                                        Terkait: <strong>Segmen: Calon Peserta Bootcamp</strong>
                                    </div>
                                    <p class="mt-2 mb-1 small">
                                        Menjelaskan alur belajar, sistem tugas, dan skema pembayaran cicilan ke peserta batch baru.
                                    </p>
                                    <div class="small text-muted">
                                        Oleh: <strong>Lead Operations</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- ITEM 4 - TANPA CAMPAIGN --}}
                            <div class="activity-item activity-campaign-none d-flex position-relative pb-0">
                                <div class="timeline-dot bg-secondary-subtle text-secondary">
                                    <i class="bi bi-sticky-fill"></i>
                                </div>
                                <div class="flex-grow-1 ps-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <div>
                                                <span class="badge bg-light text-dark border me-2">Catatan</span>
                                                <strong>Update data contact alumni batch 1</strong>
                                            </div>
                                            <span class="campaign-pill campaign-none mt-1">
                                                Tidak terkait campaign
                                            </span>
                                        </div>
                                        <small class="text-muted">2 hari lalu, 09.20</small>
                                    </div>
                                    <div class="mt-1 small text-muted">
                                        Terkait: <strong>Segmen: Alumni Depati Akademi</strong>
                                    </div>
                                    <p class="mt-2 mb-1 small">
                                        Menandai 12 contact sebagai alumni aktif dan menambah tag "Potensial Mentor".
                                    </p>
                                    <div class="small text-muted">
                                        Oleh: <strong>Staff Pengembangan Mutu</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KALAU DATA KOSONG NANTI --}}
                        {{-- 
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inboxes fs-2 d-block mb-2"></i>
                            Belum ada aktivitas tercatat. Mulai dengan menambahkan aktivitas baru.
                        </div>
                        --}}
                    </div>
                </div>
            </div>
            

            {{-- RIGHT SIDEBAR: OVERALL SUMMARY --}}
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0">Ringkasan Aktivitas</h6>
                            <small class="text-muted">Periode: keseluruhan</small>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <div class="p-2 rounded border small">
                                    <div class="text-muted mb-1">Total Aktivitas</div>
                                    <div class="fw-bold fs-5">342</div>
                                    <div class="small text-muted">Semua waktu</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded border small">
                                    <div class="text-muted mb-1">Contact Terjamah</div>
                                    <div class="fw-bold fs-5">128</div>
                                    <div class="small text-muted">Unik</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded border small mt-2">
                                    <div class="text-muted mb-1">Campaign Aktif</div>
                                    <div class="fw-bold fs-6">3</div>
                                    <div class="small text-muted">Berjalan</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded border small mt-2">
                                    <div class="text-muted mb-1">WhatsApp vs Meeting</div>
                                    <div class="fw-bold fs-6">210 / 54</div>
                                    <div class="small text-muted">WA / Meeting</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- (Quick Filter dihapus sesuai catatan) --}}
            </div>

        </div> {{-- .row --}}
    </div> {{-- .page-content --}}

    {{-- MODAL: TAMBAH AKTIVITAS (tetap sama seperti sebelumnya) --}}
    {{-- ... modal-activity dari kode kamu sebelumnya, boleh pakai yang kemarin persis ... --}}
    {{-- MODAL: TAMBAH AKTIVITAS --}}
    <div class="modal fade" id="modal-activity" tabindex="-1" aria-labelledby="modalActivityLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalActivityLabel">Tambah Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                {{-- PURE FRONTEND ONLY --}}
                <form id="form-create-activity" action="javascript:void(0)" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">

                            {{-- JENIS AKTIVITAS --}}
                            <div class="col-md-4">
                                <label class="form-label">Jenis Aktivitas <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm">
                                    <option value="">Pilih...</option>
                                    <option>WhatsApp</option>
                                    <option>Telepon</option>
                                    <option>Meeting</option>
                                    <option>Email</option>
                                    <option>Catatan</option>
                                </select>
                            </div>

                            {{-- TANGGAL & WAKTU --}}
                            <div class="col-md-4">
                                <label class="form-label">Tanggal & Waktu <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control form-control-sm">
                            </div>

                            {{-- PIC / USER --}}
                            <div class="col-md-4">
                                <label class="form-label">PIC / User</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Saya sendiri</option>
                                    <option>Rifki Dermawan</option>
                                    <option>Marketing 1</option>
                                    <option>CS 1</option>
                                </select>
                            </div>

                            <hr class="mt-3 mb-1">

                            {{-- TERKAIT DENGAN APA --}}
                            <div class="col-12">
                                <label class="form-label">Terkait Dengan</label>
                                <div class="d-flex flex-wrap gap-3 small">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="related_type" id="rel_contact" checked>
                                        <label class="form-check-label" for="rel_contact">
                                            Contact
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="related_type" id="rel_customer">
                                        <label class="form-check-label" for="rel_customer">
                                            Customer / Deal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="related_type" id="rel_campaign">
                                        <label class="form-check-label" for="rel_campaign">
                                            Campaign
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- CONTACT / CUSTOMER INPUT (nanti bisa jadi select2) --}}
                            <div class="col-md-6">
                                <label class="form-label">Pilih Contact / Customer</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Cari nama contact / customer...">
                                <div class="form-text small">
                                    Nanti dihubungkan ke master Contact / Customers.
                                </div>
                            </div>

                            {{-- CAMPAIGN (UNTUK BANYAK CAMPAIGN BERJALAN) --}}
                            <div class="col-md-6">
                                <label class="form-label">Terkait Campaign</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Tidak terkait campaign</option>
                                    <option>Webinar Magang Nasional Batch 3</option>
                                    <option>Smart Course - Promo Akhir Tahun</option>
                                    <option>Depati Akademi - Kelas Laravel Intensif</option>
                                </select>
                                <div class="form-text small">
                                    Jika user punya 2+ campaign aktif, semuanya muncul di sini.
                                </div>
                            </div>

                            {{-- RINGKASAN / JUDUL --}}
                            <div class="col-12">
                                <label class="form-label">Judul Singkat Aktivitas <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Contoh: Follow up pembayaran, kirim proposal, reminder webinar, dll.">
                            </div>

                            {{-- CATATAN DETAIL --}}
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control form-control-sm" rows="3"
                                        placeholder="Isi ringkasan pembicaraan, respon client, komitmen, dsb."></textarea>
                            </div>

                            {{-- HASIL / OUTCOME --}}
                            <div class="col-md-6">
                                <label class="form-label">Outcome / Hasil</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Pilih (opsional)</option>
                                    <option>Berhasil dihubungi</option>
                                    <option>Tidak diangkat</option>
                                    <option>Follow up lagi</option>
                                    <option>Deal (WON)</option>
                                    <option>Lost / Tidak tertarik</option>
                                </select>
                            </div>

                            {{-- NEXT ACTION / FOLLOW UP --}}
                            <div class="col-md-6">
                                <label class="form-label">Jadwalkan Follow Up Berikutnya?</label>
                                <input type="datetime-local" class="form-control form-control-sm">
                                <div class="form-text small">
                                    Nanti bisa otomatis jadi <strong>task</strong> kalau diaktifkan.
                                </div>
                            </div>

                        </div> {{-- .row --}}
                    </div> {{-- .modal-body --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Aktivitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CSS ringan untuk timeline & campaign (bisa kamu pindah ke file CSS global) --}}
    <style>
        .activity-timeline {
            position: relative;
            margin-left: 0.75rem;
        }

        .activity-timeline::before {
            content: "";
            position: absolute;
            left: 0.6rem;
            top: 0.2rem;
            bottom: 0.2rem;
            width: 2px;
            background-color: rgba(0, 0, 0, 0.06);
        }

        .activity-item {
            margin-left: 0.4rem;
            border-radius: 0.5rem;
            padding-left: 0.5rem;
        }

        .activity-item:last-child {
            padding-bottom: 0 !important;
        }

        .timeline-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 0 0 3px #fff;
        }

        .bg-primary-subtle {
            background-color: rgba(46, 101, 183, 0.08) !important;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.08) !important;
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.08) !important;
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.08) !important;
        }

        .bg-secondary-subtle {
            background-color: rgba(108, 117, 125, 0.08) !important;
        }

        /* LEGEND & CAMPAIGN COLORING */
        .campaign-legend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
            background-color: rgba(0, 0, 0, 0.02);
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .campaign-mag .legend-dot {
            background-color: #2e65b7;
        }

        .campaign-smart .legend-dot {
            background-color: #f39c12;
        }

        .campaign-laravel .legend-dot {
            background-color: #c0392b;
        }

        .campaign-none .legend-dot {
            background-color: #6c757d;
        }

        .campaign-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
            font-size: 0.7rem;
            border: 1px solid transparent;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .campaign-pill.campaign-mag {
            background-color: rgba(46, 101, 183, 0.06);
            border-color: rgba(46, 101, 183, 0.25);
            color: #2e65b7;
        }

        .campaign-pill.campaign-smart {
            background-color: rgba(243, 156, 18, 0.06);
            border-color: rgba(243, 156, 18, 0.25);
            color: #e67e22;
        }

        .campaign-pill.campaign-laravel {
            background-color: rgba(192, 57, 43, 0.06);
            border-color: rgba(192, 57, 43, 0.25);
            color: #c0392b;
        }

        .campaign-pill.campaign-none {
            background-color: rgba(108, 117, 125, 0.04);
            border-color: rgba(108, 117, 125, 0.25);
            color: #6c757d;
        }

        .activity-campaign-mag {
            border-left: 3px solid rgba(46, 101, 183, 0.8);
        }

        .activity-campaign-smart {
            border-left: 3px solid rgba(243, 156, 18, 0.8);
        }

        .activity-campaign-laravel {
            border-left: 3px solid rgba(192, 57, 43, 0.8);
        }

        .activity-campaign-none {
            border-left: 3px solid rgba(108, 117, 125, 0.6);
        }
    </style>
@endsection
