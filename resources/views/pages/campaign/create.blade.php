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
                            
                        </div>

                        <form id="form-create-campaign" action="{{ route('campaign.store') }}" method="POST">
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

                                
                            </div>

                            {{-- STEP 2: Jadwal & Audience --}}
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="small fw-semibold text-uppercase text-muted">
                                        Step 2 • Jadwal & Audience
                                    </div>
                                    
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>

                                
                            </div>

                            {{-- STEP 3: Product --}}
                            <div class="border rounded p-3 mb-3 bg-light">
                                <div class="small fw-semibold text-uppercase text-muted mb-2">Product Campaign</div>

                                
                                <div class="mt-2">
                                    <label class="form-label">Product Campaign (opsional)</label>
                                    <select class="form-select js-select2-products" name="products[]" multiple style="width: 100%;">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Simpan Campaign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2">Preview Campaign</h6>
                        <div class="campaign-card border rounded p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong id="preview-name">Nama Campaign</strong>
                                <span class="badge bg-success">Draft</span>
                            </div>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-calendar-event me-1"></i>
                                <span id="preview-dates">Tanggal belum diatur</span>
                            </div>
                            <div class="small">
                                <i class="bi bi-box-seam me-1"></i>
                                Product: <span id="preview-products" class="text-muted">Belum dipilih</span>
                            </div>
                        </div>
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
            const token = form.querySelector('input[name=_token]')?.value;
            if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                $('.js-select2-products').select2({
                    placeholder: 'Pilih product campaign',
                    width: '100%',
                    ajax: {
                        url: "{{ route('campaign.products.search') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results || [],
                                pagination: { more: data.pagination && data.pagination.more }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0
                });
            }

            let timer;
            function schedulePreview() {
                clearTimeout(timer);
                timer = setTimeout(sendPreview, 200);
            }

            async function sendPreview() {
                const name = form.name.value;
                const start_date = form.start_date.value;
                const end_date = form.end_date.value;
                const productsSelect = form.querySelector('select[name="products[]"]');
                const products = productsSelect ? Array.from(productsSelect.selectedOptions).map(el => el.value) : [];

                try {
                    const resp = await fetch("{{ route('campaign.preview') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name, start_date, end_date, products })
                    });
                    const data = await resp.json();
                    document.getElementById('preview-name').innerText = data.name || 'Nama Campaign';
                    document.getElementById('preview-dates').innerText = data.dates || 'Tanggal belum diatur';
                    const prodEl = document.getElementById('preview-products');
                    prodEl.innerText = (data.products && data.products.length) ? data.products.join(', ') : 'Belum dipilih';
                } catch (e) {
                    // silent
                }
            }

            form.addEventListener('input', schedulePreview);
            form.addEventListener('change', schedulePreview);
            sendPreview();
        });
    </script>
@endpush
