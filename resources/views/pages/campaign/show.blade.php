@extends('layouts.master')

@section('title', 'Detail Campaign')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Winter Sale 2025</h3>
            <p class="text-muted mb-0">
                Detail campaign, daftar contact, product, dan kolaborasi tim.
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </button>
            <button class="btn btn-outline-secondary btn-sm btn-download-doc">
                <i class="bi bi-download me-1"></i>Download Dokumen
            </button>
            <button class="btn btn-outline-danger btn-sm">
                <i class="bi bi-stop-circle me-1"></i>Akhiri Campaign
            </button>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            {{-- LEFT: Fokus utama di contacts table --}}
            <div class="col-lg-4">
                {{-- Header card (ringkas) --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h5 class="mb-0">Winter Sale 2025</h5>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <div class="small text-muted mb-1">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    01 Dec 2025 - 31 Dec 2025
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-megaphone me-1"></i> WhatsApp Blast
                                </div>
                                <div class="small text-muted">
                                    Target: <strong>500 kontak</strong> • Goal: <strong>50 closing</strong> • Owner:
                                    <strong>Admin Depati</strong>
                                </div>
                            </div>
                            <div class="text-md-end">
                                <div class="small text-muted mb-1">Progress Campaign</div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 35%;"
                                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="small text-muted mt-1">
                                    35% kontak sudah mencapai stage <strong>Contacted+</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-1">Tim Campaign</h6>
                                <p class="small text-muted mb-0">
                                    Klik avatar untuk melihat detail peran & akses.
                                </p>
                            </div>
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal" data-bs-target="#modal-invite-team">
                                <i class="bi bi-person-plus me-1"></i>Invite
                            </button>
                        </div>
            
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            {{-- Owner --}}
                            <button type="button"
                                    class="btn p-0 border-0 bg-transparent team-member-avatar"
                                    data-bs-toggle="modal" data-bs-target="#modal-team-member"
                                    data-name="Admin Depati"
                                    data-role="Owner Campaign"
                                    data-email="admin@depati.co.id"
                                    data-notes="Mengatur strategi & keputusan utama campaign."
                                    data-permissions="Full access, manage contact, manage team, export data">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                    style="width: 36px; height: 36px;">
                                    AD
                                </div>
                            </button>
            
                            {{-- Marketing --}}
                            <button type="button"
                                    class="btn p-0 border-0 bg-transparent team-member-avatar"
                                    data-bs-toggle="modal" data-bs-target="#modal-team-member"
                                    data-name="Tim Marketing"
                                    data-role="Marketing"
                                    data-email="marketing@depati.co.id"
                                    data-notes="Fokus di follow up awal, blast campaign, dan nurture lead."
                                    data-permissions="Edit contact, ubah stage, tambah contact ke campaign">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                    style="width: 36px; height: 36px;">
                                    MK
                                </div>
                            </button>
            
                            {{-- CS --}}
                            <button type="button"
                                    class="btn p-0 border-0 bg-transparent team-member-avatar"
                                    data-bs-toggle="modal" data-bs-target="#modal-team-member"
                                    data-name="Tim CS"
                                    data-role="Customer Service"
                                    data-email="cs@depati.co.id"
                                    data-notes="Handle closing, onboarding, dan after sales."
                                    data-permissions="Edit contact, ubah stage, lihat riwayat campaign">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                    style="width: 36px; height: 36px;">
                                    CS
                                </div>
                            </button>
            
                            {{-- Kalau mau indikator tambahan member lain --}}
                            <span class="badge bg-light text-muted small">
                                + 2 anggota lain
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Catatan Campaign</h6>
                        <textarea class="form-control form-control-sm mb-2" rows="3"
                            placeholder="Catatan singkat untuk tim, misalnya skrip WA, keberatan umum, dll."></textarea>
                        <button class="btn btn-sm btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-save me-1"></i>Simpan Catatan (UI Only)
                        </button>
                        <div class="small text-muted mb-1">Aktivitas Terakhir (mockup):</div>
                        <ul class="small mb-0 ps-3">
                            <li>Admin Depati mengubah stage 20 kontak menjadi <strong>Follow Up</strong>.</li>
                            <li>Tim CS menandai 5 kontak sebagai <strong>Deal</strong>.</li>
                            <li>Tim Marketing menambahkan 50 kontak dari <strong>Facebook Ads</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- add space in between the cards --}}
            <div class="col-lg-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        {{-- Toolbar atas --}}
                        <div
                            class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-3">
                            <div>
                                <h6 class="mb-0">Kontak dalam Campaign</h6>
                                <small class="text-muted">
                                    Ubah stage & product per contact, atau sekaligus untuk banyak contact.
                                </small>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#modal-add-contact">
                                    <i class="bi bi-person-plus me-1"></i>Tambah Manual
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#modal-import-csv">
                                    <i class="bi bi-upload me-1"></i>Import CSV
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#modal-select-contact">
                                    <i class="bi bi-database-down me-1"></i>Ambil dari Contact
                                </button>
                            </div>
                        </div>

                        {{-- Bulk tools (1 blok kecil supaya tidak crowded) --}}
                        <div class="border rounded p-2 p-md-3 mb-3 bg-light">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <div class="small mb-1 fw-semibold">
                                        Bulk Update Stage
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <select id="bulk-stage-select" class="form-select form-select-sm"
                                            style="max-width: 220px;">
                                            <option value="">Pilih stage baru</option>
                                            <option value="New">New</option>
                                            <option value="Contacted">Contacted</option>
                                            <option value="Follow Up">Follow Up</option>
                                            <option value="Deal">Deal</option>
                                            <option value="Loss">Loss</option>
                                            <option value="No Response">No Response</option>
                                        </select>
                                        <button id="btn-set-selected" class="btn btn-sm btn-primary">
                                            Untuk yang dipilih
                                        </button>
                                        <button id="btn-set-all" class="btn btn-sm btn-outline-primary">
                                            Untuk semua
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="small mb-1 fw-semibold">
                                        Product Campaign
                                        <small class="text-muted">(opsional)</small>
                                    </div>
                                
                                    <div class="small text-muted mb-2">
                                        Pilih product yang akan ditempel ke banyak contact sekaligus.
                                    </div>
                                
                                    {{-- Chip product campaign --}}
                                    <div id="bulk-product-chip-container" class="d-flex flex-wrap gap-2 mb-2">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary bulk-product-chip"
                                                data-product="Paket Winter Class">
                                            Paket Winter Class
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary bulk-product-chip"
                                                data-product="Add-on Support 3 Bulan">
                                            Add-on Support 3 Bulan
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary bulk-product-chip"
                                                data-product="Paket Premium">
                                            Paket Premium
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary bulk-product-chip"
                                                data-product="Kelas Online Mandiri">
                                            Kelas Online Mandiri
                                        </button>
                                    </div>
                                
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="bulk-product-new-input"
                                               placeholder="Tambah product baru lalu Enter"
                                               style="max-width: 260px;">
                                        <button id="btn-product-all" class="btn btn-sm btn-outline-secondary">
                                            Terapkan ke semua
                                        </button>
                                        <button id="btn-product-clear-all" class="btn btn-sm btn-outline-secondary">
                                            Clear semua
                                        </button>
                                    </div>
                                
                                    <small class="text-muted">
                                        Biru = product terpilih. Tombol <strong>Terapkan ke semua</strong> akan mengganti product di semua contact.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL: pusat perhatian --}}
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-campaign-contacts">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="check-all">
                                        </th>
                                        <th>Nama Contact</th>
                                        <th>Perusahaan</th>
                                        <th>Telepon</th>
                                        <th style="width: 220px;">Product</th>
                                        <th>Source</th>
                                        <th style="width: 160px;">Stage</th>
                                        <th style="width: 90px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dummy rows --}}
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="row-check">
                                        </td>
                                        <td>
                                            <strong>Ahmad Fauzi</strong><br>
                                            <small class="text-muted">Lead dari iklan Facebook</small>
                                        </td>
                                        <td>PT Sejahtera Jaya</td>
                                        <td>0812-3456-7890</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                <span class="badge bg-primary-subtle text-primary small product-badge"
                                                      data-product="Paket Winter Class">
                                                    Paket Winter Class
                                                </span>
                                                <button type="button"
                                                        class="btn btn-xs btn-outline-secondary btn-sm btn-manage-product"
                                                        data-contact="Ahmad Fauzi">
                                                    <i class="bi bi-sliders"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">Facebook Ads</span>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm stage-select">
                                                <option value="New">New</option>
                                                <option value="Contacted" selected>Contacted</option>
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deal">Deal</option>
                                                <option value="Loss">Loss</option>
                                                <option value="No Response">No Response</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary" title="Chat / Activity">
                                                <i class="bi bi-chat-dots"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="row-check">
                                        </td>
                                        <td>
                                            <strong>Siti Rahma</strong><br>
                                            <small class="text-muted">Customer lama (repeat order)</small>
                                        </td>
                                        <td>CV Makmur Sentosa</td>
                                        <td>0821-2345-6789</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                <span class="badge bg-primary-subtle text-primary small product-badge"
                                                      data-product="Paket Winter Class">
                                                    Paket Winter Class
                                                </span>
                                                <span class="badge bg-primary-subtle text-primary small product-badge"
                                                      data-product="Add-on Support 3 Bulan">
                                                    Add-on Support 3 Bulan
                                                </span>
                                                <button type="button"
                                                        class="btn btn-xs btn-outline-secondary btn-sm btn-manage-product"
                                                        data-contact="Siti Rahma">
                                                    <i class="bi bi-sliders"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success">Existing Customer</span>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm stage-select">
                                                <option value="New">New</option>
                                                <option value="Contacted">Contacted</option>
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deal" selected>Deal</option>
                                                <option value="Loss">Loss</option>
                                                <option value="No Response">No Response</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-chat-dots"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="row-check">
                                        </td>
                                        <td>
                                            <strong>Budi Santoso</strong><br>
                                            <small class="text-muted">Database lama (belum pernah follow up)</small>
                                        </td>
                                        <td>-</td>
                                        <td>0878-1234-5678</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary small placeholder-product">
                                                    Belum ada product
                                                </span>
                                                <button type="button"
                                                        class="btn btn-xs btn-outline-secondary btn-sm btn-manage-product"
                                                        data-contact="Budi Santoso">
                                                    <i class="bi bi-sliders"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary-subtle text-secondary">Old DB</span>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm stage-select">
                                                <option value="New" selected>New</option>
                                                <option value="Contacted">Contacted</option>
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deal">Deal</option>
                                                <option value="Loss">Loss</option>
                                                <option value="No Response">No Response</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-chat-dots"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Add contact manually --}}
    <div class="modal fade" id="modal-add-contact" tabindex="-1" aria-labelledby="modal-add-contact-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form action="javascript:void(0)" id="form-add-contact">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-add-contact-label">Tambah Contact ke Campaign (Manual)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Contact</label>
                                <input type="text" class="form-control" placeholder="Nama lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Perusahaan</label>
                                <input type="text" class="form-control" placeholder="Nama perusahaan (opsional)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon / WhatsApp</label>
                                <input type="text" class="form-control" placeholder="08xx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Source</label>
                                <select class="form-select">
                                    <option>Facebook Ads</option>
                                    <option>Instagram</option>
                                    <option>Landing Page</option>
                                    <option>Existing Customer</option>
                                    <option>Database Lama</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stage Awal</label>
                                <select class="form-select">
                                    <option>New</option>
                                    <option>Contacted</option>
                                    <option>Follow Up</option>
                                    <option>Deal</option>
                                    <option>Loss</option>
                                    <option>No Response</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product</label>
                                <select class="form-select" multiple>
                                    <option>Paket Winter Class</option>
                                    <option>Add-on Support 3 Bulan</option>
                                    <option>Paket Premium</option>
                                    <option>Kelas Online Mandiri</option>
                                </select>
                                <small class="text-muted">Bisa lebih dari satu product untuk 1 contact.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">PIC di Tim</label>
                                <select class="form-select">
                                    <option>Admin Depati</option>
                                    <option>Tim Marketing</option>
                                    <option>Tim CS</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" rows="2" placeholder="Catatan singkat untuk contact ini."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Tambah ke Campaign (UI Only)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Import CSV --}}
    <div class="modal fade" id="modal-import-csv" tabindex="-1" aria-labelledby="modal-import-csv-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form action="javascript:void(0)" id="form-import-csv">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-csv-label">Import Contact dari CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">File CSV</label>
                            <input type="file" class="form-control" accept=".csv">
                            <small class="text-muted">
                                Format minimal: <strong>name, phone, company, source</strong>. Opsional:
                                <strong>stage, product, notes</strong>.
                            </small>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Stage Default</label>
                                <select class="form-select">
                                    <option>New</option>
                                    <option>Contacted</option>
                                    <option>Follow Up</option>
                                    <option>Deal</option>
                                    <option>Loss</option>
                                    <option>No Response</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Default (opsional)</label>
                                <select class="form-select" multiple>
                                    <option>Paket Winter Class</option>
                                    <option>Add-on Support 3 Bulan</option>
                                    <option>Paket Premium</option>
                                    <option>Kelas Online Mandiri</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">PIC Default</label>
                                <select class="form-select">
                                    <option>Admin Depati</option>
                                    <option>Tim Marketing</option>
                                    <option>Tim CS</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <p class="small text-muted mb-0">
                            Prototype: di versi production, CSV akan dibaca dan contact dimasukkan ke campaign.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i>Proses Import (UI Only)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Select contact from DB --}}
    <div class="modal fade" id="modal-select-contact" tabindex="-1" aria-labelledby="modal-select-contact-label"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form action="javascript:void(0)" id="form-select-contact">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-select-contact-label">Pilih Contact dari Database</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Filter --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-1 small">Cari Nama / Telepon</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Nama / no. WA">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-1 small">Perusahaan</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Nama perusahaan">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-1 small">Kategori / Tag</label>
                                        <select class="form-select form-select-sm">
                                            <option>Semua</option>
                                            <option>Lead Baru</option>
                                            <option>Existing Customer</option>
                                            <option>Database Lama</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Table contact (mock dari DB contact) --}}
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-select-contacts">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="select-all-contacts">
                                        </th>
                                        <th>Nama Contact</th>
                                        <th>Perusahaan</th>
                                        <th>Telepon</th>
                                        <th>Tag / Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="select-contact-check">
                                        </td>
                                        <td>
                                            <strong>Rina Putri</strong><br>
                                            <small class="text-muted">Lead baru dari landing page</small>
                                        </td>
                                        <td>PT Nusantara Digital</td>
                                        <td>0813-0000-1111</td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">Lead Baru</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="select-contact-check">
                                        </td>
                                        <td>
                                            <strong>Doni Saputra</strong><br>
                                            <small class="text-muted">Customer aktif paket basic</small>
                                        </td>
                                        <td>CV Amanah Jaya</td>
                                        <td>0812-9999-8888</td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success">Existing Customer</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="select-contact-check">
                                        </td>
                                        <td>
                                            <strong>Melati Ayu</strong><br>
                                            <small class="text-muted">Database lama (belum dihubungi tahun ini)</small>
                                        </td>
                                        <td>-</td>
                                        <td>0822-7777-6666</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary-subtle text-secondary">Database Lama</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <p class="small text-muted mb-0">
                            Prototype: di versi production, daftar ini di-load dari tabel <strong>contacts</strong>.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Tambahkan ke Campaign (UI Only)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Manage Product per Contact --}}
    <div class="modal fade" id="modal-manage-product" tabindex="-1"
        aria-labelledby="modal-manage-product-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="javascript:void(0)" id="form-manage-product">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-manage-product-label">Atur Product Contact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small mb-2">
                            Contact: <strong id="product-contact-name">-</strong>
                        </p>
                    
                        <div class="mb-3">
                            <label class="form-label small">Pilih Product</label>
                            {{-- Chip container --}}
                            <div id="product-chip-container" class="d-flex flex-wrap gap-2 mb-2">
                                {{-- Chip product akan di-render via JS --}}
                            </div>
                            <small class="text-muted d-block mb-2">
                                Klik chip untuk aktif/nonaktif. Biru = terpilih, outline = tidak terpilih.
                            </small>
                    
                            <label class="form-label small">Tambah Product Baru</label>
                            <input type="text" class="form-control form-control-sm" id="product-new-input"
                                   placeholder="Ketik nama product lalu Enter">
                            <small class="text-muted">
                                Product baru akan muncul sebagai pilihan dan langsung terpilih.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan (UI Only)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Invite Team (pilih dari data yang ada) --}}
    <div class="modal fade" id="modal-invite-team" tabindex="-1" aria-labelledby="modal-invite-team-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form action="javascript:void(0)" id="form-invite-team">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-invite-team-label">Pilih Tim untuk Campaign Ini</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Filter sederhana --}}
                        <div class="card mb-3">
                            <div class="card-body py-2">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <label class="form-label mb-1 small">Cari Nama / Email</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="Ketik nama atau email"
                                            id="invite-team-search-helper">
                                        <small class="text-muted small">
                                            Atau gunakan search bawaan tabel di kanan atas.
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-1 small">Filter Role</label>
                                        <select class="form-select form-select-sm" id="invite-team-role-filter">
                                            <option value="">Semua Role</option>
                                            <option value="Owner">Owner</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="CS">CS</option>
                                            <option value="FO">Front Office</option>
                                            <option value="Viewer">Viewer Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel daftar user internal (dummy) --}}
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-invite-team">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="invite-select-all">
                                        </th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th style="width: 120px;">Role Sistem</th>
                                        <th style="width: 140px;">Role di Campaign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-role-campaign="Owner">
                                        <td class="text-center">
                                            <input type="checkbox" class="invite-member-check">
                                        </td>
                                        <td>
                                            <strong>Admin Depati</strong>
                                        </td>
                                        <td>admin@depati.co.id</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary">Super Admin</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary small">Owner</span>
                                        </td>
                                    </tr>
                                    <tr data-role-campaign="Marketing">
                                        <td class="text-center">
                                            <input type="checkbox" class="invite-member-check">
                                        </td>
                                        <td>
                                            <strong>Tim Marketing</strong>
                                        </td>
                                        <td>marketing@depati.co.id</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary-subtle text-secondary">Marketing</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary-subtle text-secondary small">Marketing</span>
                                        </td>
                                    </tr>
                                    <tr data-role-campaign="CS">
                                        <td class="text-center">
                                            <input type="checkbox" class="invite-member-check">
                                        </td>
                                        <td>
                                            <strong>Tim CS</strong>
                                        </td>
                                        <td>cs@depati.co.id</td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success">CS</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success small">CS</span>
                                        </td>
                                    </tr>
                                    <tr data-role-campaign="FO">
                                        <td class="text-center">
                                            <input type="checkbox" class="invite-member-check">
                                        </td>
                                        <td>
                                            <strong>Front Office</strong>
                                        </td>
                                        <td>fo@depati.co.id</td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">FO</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info small">FO</span>
                                        </td>
                                    </tr>
                                    <tr data-role-campaign="Viewer">
                                        <td class="text-center">
                                            <input type="checkbox" class="invite-member-check">
                                        </td>
                                        <td>
                                            <strong>Owner Bisnis</strong>
                                        </td>
                                        <td>owner@client.co.id</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-muted">Client</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-muted small">Viewer</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <p class="small text-muted mb-0">
                            Prototype: di versi production, data ini diambil dari tabel <strong>users</strong>,
                            dan yang terpilih akan di-assign sebagai member campaign.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Tambahkan ke Campaign (UI Only)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- MODAL: Detail Member Tim --}}
    <div class="modal fade" id="modal-team-member" tabindex="-1" aria-labelledby="modal-team-member-label" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-team-member-label">Detail Member Tim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div id="team-member-avatar-preview"
                            class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                        AD
                    </div>
                    <div>
                        <div class="fw-semibold" id="team-member-name">Nama Member</div>
                        <div class="small text-muted" id="team-member-role">Role di Campaign</div>
                        <div class="small text-muted" id="team-member-email">email@example.com</div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="small fw-semibold mb-1">Catatan Peran</div>
                    <p class="small mb-0" id="team-member-notes">
                        Deskripsi singkat tentang tugas member ini di campaign.
                    </p>
                </div>
                <hr>
                <div>
                    <div class="small fw-semibold mb-1">Hak Akses</div>
                    <p class="small mb-0" id="team-member-permissions">
                        Bisa edit stage contact, mengelola tim, dan export data.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('admindash/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(function() {
            // ==========================
            //  Datatables
            // ==========================
            $('#table-campaign-contacts').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'asc']],
                columnDefs: [
                    { targets: [0, 5, 7], className: 'text-center' }
                ]
            });

            $('#table-select-contacts').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25],
                order: [[1, 'asc']],
                columnDefs: [
                    { targets: [0, 4], className: 'text-center' }
                ]
            });

            const inviteTable = $('#table-invite-team').length
                ? $('#table-invite-team').DataTable({
                    pageLength: 5,
                    lengthMenu: [5, 10, 25],
                    order: [[1, 'asc']],
                    columnDefs: [
                        { targets: [0, 3, 4], className: 'text-center' }
                    ]
                })
                : null;

            // Helper filter (kalau tabel invite ada)
            if (inviteTable) {
                $('#invite-team-search-helper').on('keyup', function() {
                    inviteTable.search(this.value).draw();
                });

                $('#invite-team-role-filter').on('change', function() {
                    const val = $(this).val();
                    if (!val) {
                        inviteTable.column(4).search('').draw();
                    } else {
                        inviteTable.column(4).search(val, true, false).draw();
                    }
                });
            }

            // ==========================
            //  Checkbox helpers
            // ==========================
            $('#check-all').on('change', function() {
                $('.row-check').prop('checked', $(this).is(':checked'));
            });

            $('#select-all-contacts').on('change', function() {
                $('.select-contact-check').prop('checked', $(this).is(':checked'));
            });

            $('#invite-select-all').on('change', function() {
                $('.invite-member-check').prop('checked', $(this).is(':checked'));
            });

            // ==========================
            //  Bulk Stage
            // ==========================
            function setStageFor(selector) {
                const selectedStage = $('#bulk-stage-select').val();
                if (!selectedStage) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih stage dulu',
                        text: 'Silakan pilih stage baru di dropdown.'
                    });
                    return;
                }

                $(selector).each(function() {
                    $(this).val(selectedStage);
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Stage diperbarui (Prototype)',
                    text: 'Di versi production, perubahan ini akan tersimpan ke database.'
                });
            }

            $('#btn-set-selected').on('click', function() {
                setStageFor('tbody tr:has(.row-check:checked) .stage-select');
            });

            $('#btn-set-all').on('click', function() {
                setStageFor('tbody tr .stage-select');
            });

            // ==========================
            //  Bulk Product (campaign level)
            // ==========================
            function getBulkProducts() {
                const products = [];
                $('#bulk-product-chip-container .bulk-product-chip.btn-primary').each(function() {
                    products.push($(this).data('product'));
                });
                return products;
            }

            $(document).on('click', '.bulk-product-chip', function() {
                const $chip = $(this);
                if ($chip.hasClass('btn-outline-primary')) {
                    $chip.removeClass('btn-outline-primary')
                        .addClass('btn-primary text-white');
                } else {
                    $chip.removeClass('btn-primary text-white')
                        .addClass('btn-outline-primary');
                }
            });

            // Tambah product baru di bulk bar
            $('#bulk-product-new-input').on('keypress', function(e) {
                if (e.which === 13) { // Enter
                    e.preventDefault();
                    const val = $(this).val().trim();
                    if (!val) return;

                    // Masukkan ke master list kalau belum ada
                    if (MASTER_PRODUCTS.indexOf(val) === -1) {
                        MASTER_PRODUCTS.push(val);
                    }

                    // Tambah chip baru (langsung terpilih = biru)
                    const $chip = $('<button type="button" class="btn btn-sm bulk-product-chip me-1 mb-1 btn-primary text-white"></button>')
                        .text(val)
                        .attr('data-product', val);

                    $('#bulk-product-chip-container').append($chip);
                    $(this).val('');
                }
            });

            $('#btn-product-all').on('click', function() {
                const products = getBulkProducts();
                if (products.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih product dulu',
                        text: 'Silakan pilih minimal satu product.'
                    });
                    return;
                }

                $('#table-campaign-contacts tbody tr').each(function() {
                    const container = $(this).find('td:nth-child(5) > .d-flex');
                    container.find('.product-badge, .placeholder-product').remove();

                    products.forEach(function(p) {
                        $('<span class="badge bg-primary-subtle text-primary small product-badge me-1"></span>')
                            .text(p)
                            .attr('data-product', p)
                            .insertBefore(container.find('button.btn-manage-product'));
                    });
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Product diterapkan (Prototype)',
                    text: 'Di versi production, relasi product-contact akan diperbarui.'
                });
            });

            $('#btn-product-clear-all').on('click', function() {
                $('#table-campaign-contacts tbody tr').each(function() {
                    const container = $(this).find('td:nth-child(5) > .d-flex');
                    container.find('.product-badge').remove();
                    if (container.find('.placeholder-product').length === 0) {
                        $('<span class="badge bg-secondary-subtle text-secondary small placeholder-product"></span>')
                            .text('Belum ada product')
                            .insertBefore(container.find('button.btn-manage-product'));
                    }
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Product dihapus (Prototype)',
                    text: 'Di versi production, relasi product-contact akan dikosongkan.'
                });
            });

            // ==========================
            //  Modal Add / Import / Select Contacts
            // ==========================
            $('#form-add-contact').on('submit', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Contact ditambahkan (Prototype)',
                    text: 'Di versi production, contact ini akan muncul di tabel.'
                });
                $('#modal-add-contact').modal('hide');
            });

            $('#form-import-csv').on('submit', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Import CSV diproses (Prototype)',
                    text: 'Di versi production, CSV akan di-parse dan contact ditambahkan.'
                });
                $('#modal-import-csv').modal('hide');
            });

            $('#form-select-contact').on('submit', function() {
                const selectedCount = $('.select-contact-check:checked').length;
                Swal.fire({
                    icon: 'success',
                    title: selectedCount + ' contact dipilih (Prototype)',
                    text: 'Di versi production, contact terpilih akan ditambahkan ke campaign.'
                });
                $('#modal-select-contact').modal('hide');
            });

            // Download document (dummy)
            $('.btn-download-doc').on('click', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Download Dokumen (Prototype)',
                    text: 'Nanti bisa diisi export PDF/Excel untuk laporan campaign.'
                });
            });

            // ==========================
            //  PRODUCT PER CONTACT – CHIP UI
            // ==========================
            let currentProductRow = null;

            // Master list product (bisa diperluas dari data row)
            let MASTER_PRODUCTS = [
                'Paket Winter Class',
                'Add-on Support 3 Bulan',
                'Paket Premium',
                'Kelas Online Mandiri'
            ];

            function openProductModalForRow(row, contactName) {
                currentProductRow = row;
                $('#product-contact-name').text(contactName || '-');

                // Ambil product yang sudah terpasang di row
                const existingProducts = [];
                row.find('.product-badge').each(function() {
                    const p = $(this).data('product');
                    if (p) existingProducts.push(p);
                });

                // Masukkan ke master list kalau belum ada
                existingProducts.forEach(function(p) {
                    if (MASTER_PRODUCTS.indexOf(p) === -1) {
                        MASTER_PRODUCTS.push(p);
                    }
                });

                // Render chip
                const $container = $('#product-chip-container');
                $container.empty();

                MASTER_PRODUCTS.forEach(function(p) {
                    const isActive = existingProducts.indexOf(p) !== -1;
                    const $chip = $('<button type="button" class="btn btn-sm product-chip me-1 mb-1"></button>')
                        .text(p)
                        .attr('data-product', p);

                    if (isActive) {
                        $chip.addClass('btn-primary text-white');
                    } else {
                        $chip.addClass('btn-outline-primary');
                    }

                    $container.append($chip);
                });

                $('#product-new-input').val('');
                $('#modal-manage-product').modal('show');
            }

            // Klik tombol kelola product di tabel
            $(document).on('click', '.btn-manage-product', function() {
                const row = $(this).closest('tr');
                const contactName = $(this).data('contact') ||
                    row.find('td:nth-child(2) strong').text() || '-';

                openProductModalForRow(row, contactName);
            });

            // Klik chip product → toggle aktif / nonaktif
            $(document).on('click', '.product-chip', function() {
                const $chip = $(this);
                if ($chip.hasClass('btn-outline-primary')) {
                    $chip.removeClass('btn-outline-primary').addClass('btn-primary text-white');
                } else {
                    $chip.removeClass('btn-primary text-white').addClass('btn-outline-primary');
                }
            });

            // Tambah product baru via input
            $('#product-new-input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const val = $(this).val().trim();
                    if (!val) return;

                    if (MASTER_PRODUCTS.indexOf(val) === -1) {
                        MASTER_PRODUCTS.push(val);
                    }

                    // Tambah chip di modal contact
                    const $container = $('#product-chip-container');
                    const $chip = $('<button type="button" class="btn btn-sm product-chip me-1 mb-1 btn-primary text-white"></button>')
                        .text(val)
                        .attr('data-product', val);
                    $container.append($chip);
                    $(this).val('');

                    // OPTIONAL: juga tambahkan ke chip bulk kalau belum ada
                    if ($('#bulk-product-chip-container').length) {
                        const existsBulk = $('#bulk-product-chip-container .bulk-product-chip').filter(function() {
                            return $(this).data('product') === val;
                        }).length;

                        if (!existsBulk) {
                            const $bulkChip = $('<button type="button" class="btn btn-sm bulk-product-chip me-1 mb-1 btn-outline-primary"></button>')
                                .text(val)
                                .attr('data-product', val);
                            $('#bulk-product-chip-container').append($bulkChip);
                        }
                    }
                }
            });


            // Submit modal product → update badge di row
            $('#form-manage-product').on('submit', function() {
                if (!currentProductRow) return;

                const selectedProducts = [];
                $('#product-chip-container .product-chip.btn-primary').each(function() {
                    selectedProducts.push($(this).data('product'));
                });

                const container = currentProductRow.find('td:nth-child(5) > .d-flex');
                container.find('.product-badge, .placeholder-product').remove();

                if (selectedProducts.length === 0) {
                    $('<span class="badge bg-secondary-subtle text-secondary small placeholder-product"></span>')
                        .text('Belum ada product')
                        .insertBefore(container.find('button.btn-manage-product'));
                } else {
                    selectedProducts.forEach(function(p) {
                        $('<span class="badge bg-primary-subtle text-primary small product-badge me-1"></span>')
                            .text(p)
                            .attr('data-product', p)
                            .insertBefore(container.find('button.btn-manage-product'));
                    });
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Product contact diperbarui (Prototype)',
                    text: 'Di versi production, product untuk contact ini akan disimpan.'
                });
                $('#modal-manage-product').modal('hide');
            });

            // ==========================
            //  Invite Team
            // ==========================
            $('#form-invite-team').on('submit', function() {
                const selectedCount = $('.invite-member-check:checked').length;
                Swal.fire({
                    icon: 'success',
                    title: selectedCount + ' member ditambahkan (Prototype)',
                    text: 'Di versi production, user terpilih akan di-assign ke campaign ini.'
                });
                $('#modal-invite-team').modal('hide');
            });

            // ==========================
            //  Detail Member Tim (avatar)
            // ==========================
            $('.team-member-avatar').on('click', function() {
                const $btn = $(this);
                const name = $btn.data('name') || '-';
                const role = $btn.data('role') || '-';
                const email = $btn.data('email') || '-';
                const notes = $btn.data('notes') || '';
                const perms = $btn.data('permissions') || '';

                $('#team-member-name').text(name);
                $('#team-member-role').text(role);
                $('#team-member-email').text(email);
                $('#team-member-notes').text(notes);
                $('#team-member-permissions').text(perms);

                const initials = name
                    .split(' ')
                    .filter(Boolean)
                    .map(function(w) { return w[0]; })
                    .join('')
                    .substring(0, 2)
                    .toUpperCase();

                $('#team-member-avatar-preview').text(initials);
            });
        });
    </script>
@endpush


