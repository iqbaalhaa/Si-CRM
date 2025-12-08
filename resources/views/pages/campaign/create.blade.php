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
            {{-- LEFT: Form utama --}}
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h5 class="mb-1">Informasi Utama Campaign</h5>
                                <div class="small text-muted">
                                    Step 1 dari 3 — Isi dasar campaign, channel, jadwal, dan target.
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-fill-example">
                                <i class="bi bi-magic me-1"></i>Gunakan contoh "Winter Sale 2025"
                            </button>
                        </div>

                        {{-- PURE FRONTEND ONLY (no real action yet) --}}
                        <form id="form-create-campaign" action="javascript:void(0)" method="POST">
                            @csrf

                            {{-- STEP 1: Nama & Tipe --}}
                            <div class="border rounded p-3 mb-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="small fw-semibold text-uppercase text-muted">
                                        Step 1 • Nama & Tipe Campaign
                                    </div>
                                </div>

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
                            </div>

                            {{-- STEP 2: Jadwal & Audience --}}
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="small fw-semibold text-uppercase text-muted">
                                        Step 2 • Jadwal & Audience
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary btn-quick-date"
                                                data-range="this-month">
                                            Bulan ini
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-quick-date"
                                                data-range="next-month">
                                            Bulan depan
                                        </button>
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
                                               placeholder="Customer baru, umur 18–30, dll">
                                        <small class="text-muted">
                                            Contoh: <em>Lead dari iklan FB di Sumatera, usia 18–30</em>.
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Owner Campaign</label>
                                        <input type="text" name="owner" class="form-control"
                                               placeholder="Admin Depati / Tim Marketing">
                                        <small class="text-muted">
                                            Orang yang bertanggung jawab atas campaign ini.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 3: Target, Goal, Product --}}
                            <div class="border rounded p-3 mb-3 bg-light">
                                <div class="small fw-semibold text-uppercase text-muted mb-2">
                                    Step 3 • Target, Goal & Product
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Target Contact (estimasi)</label>
                                        <input type="number" name="target_contacts" class="form-control"
                                               placeholder="cth: 500">
                                        <small class="text-muted">
                                            Estimasi berapa contact yang akan masuk ke campaign ini.
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Target Hasil</label>
                                        <input type="text" name="goal" class="form-control"
                                               placeholder="cth: 50 deal / 20 closing / Rp 50jt">
                                        <small class="text-muted">
                                            Bisa dalam bentuk closing, omzet, atau jumlah peserta.
                                        </small>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Product Campaign (opsional)</label>
                                    <select name="products[]" id="campaign-products" class="form-select" multiple>
                                        <option value="Paket Winter Class">Paket Winter Class</option>
                                        <option value="Add-on Support 3 Bulan">Add-on Support 3 Bulan</option>
                                        <option value="Paket Premium">Paket Premium</option>
                                        <option value="Kelas Online Mandiri">Kelas Online Mandiri</option>
                                    </select>
                                    <small class="text-muted">
                                        Product yang menjadi fokus utama campaign ini. Di detail campaign, product bisa
                                        diatur lagi per contact.
                                    </small>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Deskripsi / Catatan</label>
                                <textarea name="description" rows="3" class="form-control"
                                          placeholder="Tujuan campaign, script singkat, penawaran utama, CTA, dll."></textarea>
                                <small class="text-muted">
                                    Bagusnya berisi: problem yang disasar, offer utama, bonus, dan CTA.
                                </small>
                            </div>

                            <hr>
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                <div class="d-flex flex-column">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" value="1"
                                               id="auto-assign-team">
                                        <label class="form-check-label" for="auto-assign-team">
                                            Otomatis invite tim ke campaign ini (UI Only).
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                               id="product-per-contact">
                                        <label class="form-check-label" for="product-per-contact">
                                            Izinkan product berbeda per contact (relasi many-to-many).
                                        </label>
                                    </div>
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

            {{-- RIGHT: Preview + quick tips --}}
            <div class="col-lg-4">
                {{-- Preview Campaign Card --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Preview Campaign</h6>
                        <p class="text-muted small mb-2">
                            Simulasi bagaimana campaign ini akan tampil di daftar
                            <strong>Campaign Active</strong>.
                        </p>

                        <div class="campaign-card border rounded p-3" id="campaign-preview">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="preview-name">Nama Campaign</strong>
                                <span class="badge bg-success preview-status">Draft</span>
                            </div>
                            <div class="small text-muted mb-1 preview-type-channel">
                                Tipe tidak diatur • Channel tidak diatur
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-2 small">
                                <span class="badge bg-light text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <span class="preview-dates">Tanggal belum diatur</span>
                                </span>
                                <span class="badge bg-light text-muted">
                                    <i class="bi bi-people me-1"></i>
                                    Target: <span class="preview-target">0</span> kontak
                                </span>
                            </div>

                            <div class="small mb-1">
                                <i class="bi bi-bullseye me-1"></i>
                                Goal: <span class="preview-goal">-</span>
                            </div>

                            <div class="small mb-2">
                                <i class="bi bi-box-seam me-1"></i>
                                Product: <span class="preview-products text-muted">Belum dipilih</span>
                            </div>

                            <hr class="my-2">
                            <div class="small text-muted preview-desc">
                                Deskripsi singkat campaign akan tampil di sini.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick setup tips --}}
                <div class="card mb-0">
                    <div class="card-body">
                        <h6 class="mb-2">Best Practice Singkat</h6>
                        <ul class="small mb-2 ps-3">
                            <li>Jelaskan penawaran utama dengan jelas dan spesifik.</li>
                            <li>Tentukan periode campaign yang realistis (tidak terlalu pendek).</li>
                            <li>Pastikan target audience sempit & relevan.</li>
                            <li>Siapkan script / template WA atau email di deskripsi.</li>
                        </ul>
                        <p class="xsmall text-muted mb-0">
                            Di versi production, halaman ini bisa terhubung langsung dengan
                            <strong>contacts</strong>, <strong>products</strong>, dan <strong>teams</strong>.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .campaign-card {
            border-radius: 0.9rem;
            border: 1px solid var(--bs-border-color);
            background-color: var(--bs-body-bg);
            transition: box-shadow 0.15s ease, transform 0.15s ease, border-color 0.15s ease;
        }

        .campaign-card:hover {
            box-shadow: 0 0.5rem 1.25rem rgba(15, 23, 42, 0.08);
            transform: translateY(-1px);
            border-color: rgba(255, 156, 0, 0.4);
        }

        .xsmall {
            font-size: 0.7rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('form-create-campaign');
            const $ = (sel) => document.querySelector(sel);

            const typeLabelMap = {
                'promo': 'Promo / Diskon',
                'retention': 'Retention / Follow Up',
                'launch': 'Product Launch',
                'event': 'Event / Webinar'
            };

            const channelLabelMap = {
                'whatsapp': 'WhatsApp Blast',
                'email': 'Email Marketing',
                'telemarketing': 'Telemarketing',
                'social': 'Social Media DM'
            };

            function getSelectedProducts() {
                const select = document.getElementById('campaign-products');
                if (!select) return [];
                return Array.from(select.selectedOptions).map(o => o.value);
            }

            function syncPreview() {
                const name = form.name.value || 'Nama Campaign';
                const type = form.type.value;
                const channel = form.channel.value;
                const start = form.start_date.value;
                const end = form.end_date.value;
                const target = form.target_contacts.value || '0';
                const goal = form.goal.value || '-';
                const desc = form.description.value || 'Deskripsi singkat campaign akan tampil di sini.';
                const products = getSelectedProducts();

                const typeLabel = type ? typeLabelMap[type] || type : 'Tipe tidak diatur';
                const channelLabel = channel ? channelLabelMap[channel] || channel : 'Channel tidak diatur';

                $('.preview-name').innerText = name;
                $('.preview-type-channel').innerText = `${typeLabel} • ${channelLabel}`;
                $('.preview-target').innerText = target;
                $('.preview-goal').innerText = goal;

                if (start && end) {
                    $('.preview-dates').innerText = `${start} s/d ${end}`;
                } else if (start) {
                    $('.preview-dates').innerText = `Mulai: ${start}`;
                } else {
                    $('.preview-dates').innerText = 'Tanggal belum diatur';
                }

                if (products.length > 0) {
                    $('.preview-products').innerText = products.join(', ');
                } else {
                    $('.preview-products').innerText = 'Belum dipilih';
                }

                $('.preview-desc').innerText = desc;
            }

            // Live preview on input
            form.addEventListener('input', syncPreview);
            syncPreview();

            // Quick date helpers (prototype only, no real date logic — bisa diupgrade nanti)
            document.querySelectorAll('.btn-quick-date').forEach(btn => {
                btn.addEventListener('click', function () {
                    const range = this.getAttribute('data-range');
                    const today = new Date();
                    let start = new Date(today);
                    let end = new Date(today);

                    if (range === 'this-month') {
                        start = new Date(today.getFullYear(), today.getMonth(), 1);
                        end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    } else if (range === 'next-month') {
                        start = new Date(today.getFullYear(), today.getMonth() + 1, 1);
                        end = new Date(today.getFullYear(), today.getMonth() + 2, 0);
                    }

                    const toInputDate = (d) => d.toISOString().slice(0, 10);

                    form.start_date.value = toInputDate(start);
                    form.end_date.value = toInputDate(end);
                    syncPreview();
                });
            });

            // Fill example "Winter Sale 2025"
            document.getElementById('btn-fill-example').addEventListener('click', function () {
                form.name.value = 'Winter Sale 2025';
                form.type.value = 'promo';
                form.channel.value = 'whatsapp';
                form.start_date.value = '2025-12-01';
                form.end_date.value = '2025-12-31';
                form.audience.value = 'Customer lama & lead baru, wilayah Sumatera';
                form.owner.value = 'Admin Depati';
                form.target_contacts.value = 500;
                form.goal.value = '50 closing / Rp 75jt omzet';
                form.description.value =
                    'Campaign diskon akhir tahun untuk mendorong repeat order dan aktivasi lead baru.\n' +
                    '- Offer: Diskon 20–30% paket utama\n' +
                    '- Bonus: Konsultasi 1x gratis via Zoom\n' +
                    '- CTA utama: Balas WA dengan kata "WINTER"';

                const productsSelect = document.getElementById('campaign-products');
                Array.from(productsSelect.options).forEach(o => {
                    o.selected = (o.value === 'Paket Winter Class' || o.value === 'Add-on Support 3 Bulan');
                });

                syncPreview();
                Swal.fire({
                    icon: 'info',
                    title: 'Contoh diisi',
                    text: 'Form sudah diisi dengan contoh "Winter Sale 2025" (Prototype).'
                });
            });

            // Submit (prototype only)
            form.addEventListener('submit', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Campaign dibuat (Prototype)',
                    text: 'Di versi production, campaign ini akan tersimpan dan muncul di daftar Campaign Active.'
                });
            });
        });
    </script>
@endpush
