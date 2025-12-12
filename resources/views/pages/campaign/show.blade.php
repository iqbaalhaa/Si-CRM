@extends('layouts.master')

@section('title', 'Detail Campaign')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>{{ $campaign->name }}</h3>
            <p class="text-muted mb-0">
                Detail campaign, daftar contact, product, dan kolaborasi tim.
            </p>
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
                                    <h5 class="mb-0">{{ $campaign->name }}</h5>
                                    <span class="badge {{ $campaign->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $campaign->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                                <div class="small text-muted mb-1">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ optional($campaign->from)->format('d M Y') }} - {{ optional($campaign->to)->format('d M Y') }}
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-megaphone me-1"></i>
                                    {{ $campaign->products->count() > 0 ? 'Product Campaign' : 'Tanpa Product' }}
                                </div>
                                <div class="small text-muted">
                                    Owner:
                                    <strong>{{ $campaign->creator->name ?? 'Unknown' }}</strong>
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
                                <h6 class="mb-1">Pengaturan Tim</h6>
                                <p class="small text-muted mb-0">Atur leader dan anggota campaign.</p>
                            </div>
                        </div>
                        @php
                            $leaderId = optional($campaign->teams->firstWhere('role', 'leader'))->user_id;
                            $memberIds = $campaign->teams->where('role','member')->pluck('user_id')->all();
                        @endphp
                        <div class="mb-2">
                            <label class="form-label mb-1 small">Leader</label>
                            <select id="team-leader" class="form-select form-select-sm">
                                @foreach($companyUsers as $u)
                                    <option value="{{ $u->id }}" {{ $leaderId===$u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1 small">Members</label>
                            <div class="border rounded p-2" style="max-height: 160px; overflow: auto;">
                                @foreach($companyUsers as $u)
                                    <div class="form-check">
                                        <input class="form-check-input team-member" type="checkbox" value="{{ $u->id }}" id="mem_{{ $u->id }}" {{ in_array($u->id, $memberIds) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="mem_{{ $u->id }}">{{ $u->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" id="btn-save-team" class="btn btn-sm btn-primary">Simpan Tim</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-1">Assign Contact ke Campaign</h6>
                                <p class="small text-muted mb-0">Hanya contact milik Anda.</p>
                            </div>
                        </div>
                        @php $assignedIds = $campaign->contacts->pluck('contact_id')->all(); @endphp
                        <div class="mb-2">
                            <input type="text" id="assign-search" class="form-control form-control-sm" placeholder="Cari contact">
                        </div>
                        <div id="assign-list" class="border rounded p-2" style="max-height: 220px; overflow: auto;">
                            @foreach($availableContacts as $c)
                                @if(!in_array($c->id, $assignedIds))
                                    <div class="form-check">
                                        <input class="form-check-input assign-contact" type="checkbox" value="{{ $c->id }}" id="ac_{{ $c->id }}">
                                        <label class="form-check-label small" for="ac_{{ $c->id }}">{{ $c->name }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" id="btn-assign-contacts" class="btn btn-sm btn-primary">Assign</button>
                        </div>
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
                            
                        </div>

                        {{-- Bulk tools (1 blok kecil supaya tidak crowded) --}}
                        

                        {{-- TABEL: pusat perhatian --}}
                                <div class="table-responsive" data-campaign-id="{{ $campaign->id }}">
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
                                    @foreach($campaign->contacts as $cc)
                                        <tr data-cc-id="{{ $cc->id }}" data-contact-id="{{ $cc->contact_id }}">
                                            <td class="text-center">
                                                <input type="checkbox" class="row-check">
                                            </td>
                                            <td>
                                                <strong>{{ $cc->contact->name ?? 'Unknown' }}</strong><br>
                                                <small class="text-muted">ID: {{ $cc->contact_id }}</small>
                                            </td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @php
                                                        $assigned = $campaign->productContacts
                                                            ->where('contact_id', $cc->contact_id)
                                                            ->map(function($pc){ return optional($pc->campaignProduct)->product_id; })
                                                            ->filter()
                                                            ->values()
                                                            ->all();
                                                    @endphp
                                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                                        @forelse($campaign->products as $cp)
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input contact-product"
                                                                       type="checkbox"
                                                                       value="{{ $cp->product_id }}"
                                                                       {{ in_array($cp->product_id, $assigned) ? 'checked' : '' }}>
                                                                <label class="form-check-label small">{{ $cp->product->name ?? 'Product' }}</label>
                                                            </div>
                                                        @empty
                                                            <span class="badge bg-secondary-subtle text-secondary small">Belum ada product campaign</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">-</td>
                                            <td>
                                                <select class="form-select form-select-sm contact-stage">
                                                    @php $stage = $cc->status ?? 'New'; @endphp
                                                    <option value="New" {{ $stage==='New' ? 'selected' : '' }}>New</option>
                                                    <option value="Contacted" {{ $stage==='Contacted' ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow Up" {{ $stage==='Follow Up' ? 'selected' : '' }}>Follow Up</option>
                                                    <option value="Deal" {{ $stage==='Deal' ? 'selected' : '' }}>Deal</option>
                                                    <option value="Loss" {{ $stage==='Loss' ? 'selected' : '' }}>Loss</option>
                                                    <option value="No Response" {{ $stage==='No Response' ? 'selected' : '' }}>No Response</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-outline-secondary" title="Chat / Activity" href="{{ route('campaign.contacts.pipeline', [$campaign->id, $cc->id]) }}">
                                                    <i class="bi bi-chat-dots"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-secondary" title="Riwayat Stage" href="{{ route('campaign.contacts.pipeline', [$campaign->id, $cc->id]) }}">
                                                    <i class="bi bi-clock-history"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = document.getElementById('table-campaign-contacts');
            const wrap = table.closest('[data-campaign-id]');
            const campaignId = wrap.getAttribute('data-campaign-id');

            const token = '{{ csrf_token() }}';

            function updateProductsForContact(row) {
                const contactId = row.getAttribute('data-contact-id');
                const checked = Array.from(row.querySelectorAll('.contact-product:checked')).map(el => parseInt(el.value, 10));
                fetch(`{{ url('/campaigns') }}/${campaignId}/contacts/${contactId}/products`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ products: checked })
                }).catch(() => {});
            }

            table.addEventListener('change', function (e) {
                const target = e.target;
                const row = target.closest('tr');
                if (target.classList.contains('contact-product')) {
                    updateProductsForContact(row);
                }
                if (target.classList.contains('contact-stage')) {
                    const ccId = row.getAttribute('data-cc-id');
                    const status = target.value;
                    const notes = prompt('Catat notes perubahan stage (opsional):');
                    fetch(`{{ url('/campaigns') }}/${campaignId}/contacts/${ccId}/stage`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ status, notes })
                    }).catch(() => {});
                }
            });

            const btnSaveTeam = document.getElementById('btn-save-team');
            if (btnSaveTeam) {
                btnSaveTeam.addEventListener('click', function () {
                    const leaderId = parseInt(document.getElementById('team-leader').value, 10);
                    const members = Array.from(document.querySelectorAll('.team-member:checked')).map(el => parseInt(el.value, 10));
                    fetch(`{{ url('/campaigns') }}/${campaignId}/team`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ leader_id: leaderId, members })
                    }).catch(() => {});
                });
            }

            const assignSearch = document.getElementById('assign-search');
            const assignList = document.getElementById('assign-list');
            const btnAssign = document.getElementById('btn-assign-contacts');
            if (assignSearch && assignList && btnAssign) {
                assignSearch.addEventListener('input', function(){
                    const q = this.value.toLowerCase();
                    assignList.querySelectorAll('.form-check').forEach(item => {
                        const text = item.querySelector('label').innerText.toLowerCase();
                        item.style.display = text.includes(q) ? '' : 'none';
                    });
                });
                btnAssign.addEventListener('click', function(){
                    const ids = Array.from(document.querySelectorAll('.assign-contact:checked')).map(el => parseInt(el.value, 10));
                    fetch(`{{ route('campaign.contacts.assign', $campaign->id) }}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ contacts: ids })
                    }).then(() => { location.reload(); }).catch(() => {});
                });
            }
        });
    </script>
@endpush

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
