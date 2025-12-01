@extends('layouts.master')

@section('title', 'Campaign Create')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Buat Campaign Baru</h3>
            <p class="text-muted mb-0">
                Rancang campaign seperti <strong>Winter Sale</strong>, lalu assign ke tim dan target contact.
            </p>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">Informasi Utama Campaign</h5>

                        {{-- PURE FRONTEND ONLY (no real action yet) --}}
                        <form id="form-create-campaign" action="javascript:void(0)" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Campaign <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Winter Sale 2025" required>
                                <small class="text-muted">
                                    Nama campaign yang akan muncul di daftar Campaign Active.
                                </small>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tipe Campaign</label>
                                    <select name="type" class="form-select">
                                        <option value="">Pilih tipe</option>
                                        <option value="promo">Promo / Diskon</option>
                                        <option value="retention">Retention / Follow Up</option>
                                        <option value="launch">Product Launch</option>
                                        <option value="event">Event / Webinar</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Channel Utama</label>
                                    <select name="channel" class="form-select">
                                        <option value="">Pilih channel</option>
                                        <option value="whatsapp">WhatsApp Blast</option>
                                        <option value="email">Email Marketing</option>
                                        <option value="telemarketing">Telemarketing</option>
                                        <option value="social">Social Media DM</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Target Audience</label>
                                    <input type="text" name="audience" class="form-control"
                                        placeholder="Customer baru, usia 18-30, dll">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Owner Campaign</label>
                                    <input type="text" name="owner" class="form-control"
                                        placeholder="Nama PIC / Admin">
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Target Contact (estimasi)</label>
                                    <input type="number" name="target_contacts" class="form-control"
                                        placeholder="cth: 500">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Target Hasil</label>
                                    <input type="text" name="goal" class="form-control"
                                        placeholder="cth: 50 deal / 20 closing / Rp 50jt">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Deskripsi / Catatan</label>
                                <textarea name="description" rows="3" class="form-control"
                                    placeholder="Tujuan campaign, script singkat, penawaran utama, dll."></textarea>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1"
                                        id="auto-assign-team">
                                    <label class="form-check-label" for="auto-assign-team">
                                        Otomatis invite tim ke campaign ini (UI Only).
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-rocket-takeoff me-1"></i>
                                    Simpan & Aktifkan (Prototype)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- SIDE PREVIEW --}}
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Preview Campaign</h6>
                        <p class="text-muted small mb-2">
                            Simulasi ringkasan campaign untuk ditampilkan di daftar <strong>Campaign Active</strong>.
                        </p>
                        <div class="border rounded p-3" id="campaign-preview">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="preview-name">Nama Campaign</strong>
                                <span class="badge bg-success preview-status">Active</span>
                            </div>
                            <div class="small text-muted mb-1 preview-type-channel">
                                Tipe - Channel
                            </div>
                            <div class="small">
                                <i class="bi bi-calendar-event me-1"></i>
                                <span class="preview-dates">Tanggal belum diatur</span>
                            </div>
                            <div class="small mt-1">
                                <i class="bi bi-people me-1"></i>
                                Target: <span class="preview-target">0</span> kontak
                            </div>
                            <div class="small mt-1">
                                <i class="bi bi-bullseye me-1"></i>
                                Goal: <span class="preview-goal">-</span>
                            </div>
                            <hr class="my-2">
                            <div class="small text-muted preview-desc">
                                Deskripsi singkat campaign akan tampil di sini.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-0">
                    <div class="card-body">
                        <h6 class="mb-2">Best Practice Singkat</h6>
                        <ul class="small mb-0 ps-3">
                            <li>Jelaskan penawaran utama dengan jelas.</li>
                            <li>Tentukan periode campaign yang realistis.</li>
                            <li>Pastikan target audience spesifik.</li>
                            <li>Siapkan script / template WA atau email di deskripsi.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Super simple live preview (frontend only)
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-create-campaign');

            const $ = (sel) => document.querySelector(sel);

            const syncPreview = () => {
                const name = form.name.value || 'Nama Campaign';
                const type = form.type.value || 'Tipe tidak diatur';
                const channel = form.channel.value || 'Channel tidak diatur';
                const start = form.start_date.value;
                const end = form.end_date.value;
                const target = form.target_contacts.value || '0';
                const goal = form.goal.value || '-';
                const desc = form.description.value || 'Deskripsi singkat campaign akan tampil di sini.';

                $('.preview-name').innerText = name;
                $('.preview-type-channel').innerText = `${type} • ${channel}`;
                $('.preview-target').innerText = target;
                $('.preview-goal').innerText = goal;

                if (start && end) {
                    $('.preview-dates').innerText = `${start} s/d ${end}`;
                } else if (start) {
                    $('.preview-dates').innerText = `Mulai: ${start}`;
                } else {
                    $('.preview-dates').innerText = 'Tanggal belum diatur';
                }

                $('.preview-desc').innerText = desc;
            };

            form.addEventListener('input', syncPreview);
            syncPreview();

            form.addEventListener('submit', function() {
                // Prototype only: show fake success
                Swal.fire({
                    icon: 'success',
                    title: 'Campaign dibuat (Prototype)',
                    text: 'Di versi production, campaign ini akan muncul di daftar Campaign Active.'
                });
            });
        });
    </script>
@endpush
