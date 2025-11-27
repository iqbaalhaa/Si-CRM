@extends('layouts.master')

@section('title', 'Detail Progression')

@section('content')
    <div class="page-heading mb-3">
        <h3>Detail Progression Customer</h3>
    </div>

    <div class="page-content">
        <div class="row g-3">
            {{-- Info akun customer --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-1">Andi Pratama</h5>
                                <small class="text-muted">PT Contoh Jaya</small>
                            </div>
                            <span class="badge bg-info text-dark">Contact</span>
                        </div>

                        <hr>

                        <div class="mb-2">
                            <small class="text-muted d-block">Telepon</small>
                            <span>0812-3456-7890</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Email</small>
                            <span>andi@contoh.com</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">PIC Internal</small>
                            <span>Rina (CS)</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Stage Awal</small>
                            <span class="badge bg-secondary">New</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Stage Terakhir</small>
                            <span class="badge bg-info text-dark">Contact</span>
                            <small class="d-block text-muted mt-1">Update: 27 Nov 2025 10:15</small>
                        </div>

                        <hr>

                        <small class="text-muted d-block mb-1">Ringkasan Perjalanan</small>
                        <ul class="mb-0">
                            <li>New &rarr; Contact dalam 2 hari</li>
                            <li>Sempat <span class="badge bg-warning text-dark">Hold</span> 3 hari</li>
                            <li>Saat ini aktif follow up</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Histori progression --}}
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Histori Stage / Progression</h5>
                            <span class="badge bg-light text-dark border">
                                New → Contact → Hold → Contact
                            </span>
                        </div>
                        <small class="text-muted d-block mb-3">
                            Menampilkan perubahan stage dari waktu ke waktu beserta catatan singkat.
                        </small>

                        {{-- Timeline progression (dummy) --}}
                        <div class="timeline">
                            <style>
                                .timeline-line {
                                    border-left: 2px solid #dee2e6;
                                    margin-left: 12px;
                                    padding-left: 20px;
                                }

                                .timeline-item {
                                    position: relative;
                                    margin-bottom: 1.5rem;
                                }

                                .timeline-item::before {
                                    content: '';
                                    position: absolute;
                                    left: -12px;
                                    top: 4px;
                                    width: 12px;
                                    height: 12px;
                                    border-radius: 50%;
                                    background-color: var(--bs-primary);
                                }

                                .timeline-time {
                                    font-size: 0.8rem;
                                    color: #6c757d;
                                }
                            </style>

                            <div class="timeline-line">

                                {{-- Step 1 --}}
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <strong>New</strong>
                                            <span class="badge bg-secondary ms-2">New</span>
                                        </div>
                                        <span class="timeline-time">24 Nov 2025 09:00</span>
                                    </div>
                                    <p class="mb-1">
                                        Akun dibuat oleh <strong>Admin</strong>. Lead masuk dari form landing page.
                                    </p>
                                    <small class="text-muted">Catatan: calon klien tertarik paket CRM Basic.</small>
                                </div>

                                {{-- Step 2 --}}
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <strong>Contacted</strong>
                                            <span class="badge bg-info text-dark ms-2">Contact</span>
                                        </div>
                                        <span class="timeline-time">25 Nov 2025 10:30</span>
                                    </div>
                                    <p class="mb-1">
                                        Follow up pertama oleh <strong>Rina (CS)</strong> via WhatsApp & telepon.
                                    </p>
                                    <small class="text-muted">Catatan: minta dikirim proposal via email.</small>
                                </div>

                                {{-- Step 3 --}}
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <strong>Hold</strong>
                                            <span class="badge bg-warning text-dark ms-2">Hold</span>
                                        </div>
                                        <span class="timeline-time">26 Nov 2025 14:00</span>
                                    </div>
                                    <p class="mb-1">
                                        Client sedang diskusi internal soal budget. Diminta follow up minggu depan.
                                    </p>
                                    <small class="text-muted">Catatan: potensi ambil paket Pro bila disetujui.</small>
                                </div>

                                {{-- Step 4 --}}
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <strong>Contact (Follow Up)</strong>
                                            <span class="badge bg-info text-dark ms-2">Contact</span>
                                        </div>
                                        <span class="timeline-time">27 Nov 2025 10:15</span>
                                    </div>
                                    <p class="mb-1">
                                        Follow up kedua. Client minta simulasi harga untuk 10 user dan integrasi WhatsApp.
                                    </p>
                                    <small class="text-muted">Catatan: jadwalkan demo online hari Jumat.</small>
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ url('/stages') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali ke daftar
                            </a>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Tambah Catatan (UI Saja)
                                </button>
                                <button type="button" class="btn btn-sm btn-primary">
                                    <i class="bi bi-graph-up-arrow me-1"></i>
                                    Update Stage (UI Saja)
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
