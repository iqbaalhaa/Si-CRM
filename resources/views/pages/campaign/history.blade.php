@extends('layouts.master')

@section('title', 'History Campaign')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>History Campaign</h3>
            <p class="text-muted mb-0">
                Arsip campaign yang sudah selesai / dihentikan. Cocok untuk evaluasi dan re-use.
            </p>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">

                {{-- Summary cards (dummy) --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Total Campaign di Arsip</div>
                                    <h4 class="mb-0">3</h4>
                                    <small class="text-muted">Siap dijadikan referensi & template.</small>
                                </div>
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="bi bi-archive fs-4 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Rata-rata Closing</div>
                                    <h4 class="mb-0">~17%</h4>
                                    <small class="text-muted">Estimasi dari seluruh campaign selesai.</small>
                                </div>
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Campaign Paling Sukses</div>
                                    <h6 class="mb-0">Ramadhan Sale 2025</h6>
                                    <small class="text-muted">Closing 72 dari 450 kontak.</small>
                                </div>
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="bi bi-star-fill fs-4 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <form class="row g-2 align-items-end" id="form-filter-history">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Cari Campaign</label>
                                <input type="text" class="form-control form-control-sm"
                                       id="history-filter-search"
                                       placeholder="Nama campaign / deskripsi">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Channel</label>
                                <select class="form-select form-select-sm" id="history-filter-channel">
                                    <option value="">Semua Channel</option>
                                    <option value="whatsapp">WhatsApp</option>
                                    <option value="email">Email</option>
                                    <option value="telemarketing">Telemarketing</option>
                                    <option value="social">Social Media</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Tahun</label>
                                <select class="form-select form-select-sm" id="history-filter-year">
                                    <option value="">Semua Tahun</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Hasil</label>
                                <select class="form-select form-select-sm" id="history-filter-result">
                                    <option value="">Semua</option>
                                    <option value="success">Sukses / di atas target</option>
                                    <option value="medium">Cukup (mendekati target)</option>
                                    <option value="low">Kurang (di bawah target)</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Campaign history list as cards --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                            <div>
                                <h5 class="mb-0">Daftar Campaign Selesai</h5>
                                <small class="text-muted">
                                    Klik <strong>Detail</strong> untuk melihat isi campaign, atau gunakan
                                    <strong>Jadikan Template</strong> untuk membuat campaign baru dengan setting serupa.
                                </small>
                            </div>
                            <div class="small text-muted">
                                <span id="history-count">3</span> campaign ditampilkan
                            </div>
                        </div>

                        <div class="row g-3" id="history-list">
                            {{-- Campaign History 1 --}}
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="campaign-card h-100 p-3 history-card"
                                     data-name="Ramadhan Sale 2025"
                                     data-desc="Paket promo menjelang Idul Fitri"
                                     data-channel="whatsapp"
                                     data-year="2025"
                                     data-result="success">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Ramadhan Sale 2025</h6>
                                                <span class="badge bg-success">Selesai • Sukses</span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                Paket promo menjelang Idul Fitri.
                                            </p>
                                        </div>
                                        <a href="{{ url('/campaigns/ramadhan-sale-2025') }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 small">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-whatsapp me-1"></i>WhatsApp Blast
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>01 Mar 2025 - 10 Apr 2025
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            2025
                                        </span>
                                    </div>

                                    {{-- Metrics --}}
                                    <div class="row small mb-2">
                                        <div class="col-6">
                                            <div class="text-muted xsmall mb-1">Kontak</div>
                                            <div class="fw-semibold">450</div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="text-muted xsmall mb-1">Closing</div>
                                            <div class="fw-semibold">72 (~16%)</div>
                                        </div>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%;"
                                             aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2 xsmall text-muted">
                                        <span><i class="bi bi-flag-fill me-1 text-success"></i>Di atas target</span>
                                        <span>Performa baik untuk seasonal promo</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex align-items-center gap-2 small">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                 style="width: 26px; height: 26px;">
                                                AD
                                            </div>
                                            <div>
                                                <div class="fw-semibold small mb-0">Admin Depati</div>
                                                <div class="text-muted xsmall">Owner</div>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-history-template"
                                                    data-template-name="Ramadhan Sale 2025">
                                                <i class="bi bi-clipboard-plus"></i>
                                                <span class="d-none d-lg-inline"> Template</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Campaign History 2 --}}
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="campaign-card h-100 p-3 history-card"
                                     data-name="Back to School 2024"
                                     data-desc="Promo pelajar & mahasiswa"
                                     data-channel="email"
                                     data-year="2024"
                                     data-result="medium">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Back to School 2024</h6>
                                                <span class="badge bg-warning text-dark">Selesai • Cukup</span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                Promo pelajar & mahasiswa.
                                            </p>
                                        </div>
                                        <a href="{{ url('/campaigns/back-to-school-2024') }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 small">
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="bi bi-envelope me-1"></i>Email Campaign
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>01 Jul 2024 - 31 Aug 2024
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            2024
                                        </span>
                                    </div>

                                    <div class="row small mb-2">
                                        <div class="col-6">
                                            <div class="text-muted xsmall mb-1">Kontak</div>
                                            <div class="fw-semibold">320</div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="text-muted xsmall mb-1">Closing</div>
                                            <div class="fw-semibold">44 (~13%)</div>
                                        </div>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 45%;"
                                             aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2 xsmall text-muted">
                                        <span><i class="bi bi-dash-circle-fill me-1 text-warning"></i>Mendekati target</span>
                                        <span>Butuh optimasi subject & offer</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex align-items-center gap-2 small">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                                 style="width: 26px; height: 26px;">
                                                MK
                                            </div>
                                            <div>
                                                <div class="fw-semibold small mb-0">Marketing Team</div>
                                                <div class="text-muted xsmall">Owner</div>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-history-template"
                                                    data-template-name="Back to School 2024">
                                                <i class="bi bi-clipboard-plus"></i>
                                                <span class="d-none d-lg-inline"> Template</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Campaign History 3 --}}
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="campaign-card h-100 p-3 history-card"
                                     data-name="Q4 Retention Push 2023"
                                     data-desc="Re-activate customer lama"
                                     data-channel="telemarketing"
                                     data-year="2023"
                                     data-result="low">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="mb-0">Q4 Retention Push 2023</h6>
                                                <span class="badge bg-danger">Selesai • Kurang</span>
                                            </div>
                                            <p class="text-muted small mb-1">
                                                Re-activate customer lama.
                                            </p>
                                        </div>
                                        <a href="{{ url('/campaigns/q4-retention-2023') }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 small">
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="bi bi-telephone-outbound me-1"></i>Telemarketing
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>01 Oct 2023 - 31 Dec 2023
                                        </span>
                                        <span class="badge bg-light text-muted">
                                            2023
                                        </span>
                                    </div>

                                    <div class="row small mb-2">
                                        <div class="col-6">
                                            <div class="text-muted xsmall mb-1">Kontak</div>
                                            <div class="fw-semibold">270</div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="text-muted xsmall mb-1">Closing</div>
                                            <div class="fw-semibold">20 (~7%)</div>
                                        </div>
                                    </div>
                                    <div class="progress mb-2" style="height: 6px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%;"
                                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2 xsmall text-muted">
                                        <span><i class="bi bi-x-circle-fill me-1 text-danger"></i>Di bawah target</span>
                                        <span>Perlu revisi segmentasi & skrip</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <div class="d-flex align-items-center gap-2 small">
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                                 style="width: 26px; height: 26px;">
                                                CS
                                            </div>
                                            <div>
                                                <div class="fw-semibold small mb-0">CS Team</div>
                                                <div class="text-muted xsmall">Owner</div>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-history-template"
                                                    data-template-name="Q4 Retention Push 2023">
                                                <i class="bi bi-clipboard-plus"></i>
                                                <span class="d-none d-lg-inline"> Template</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> {{-- end row --}}
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
            cursor: default;
        }

        .campaign-card:hover {
            box-shadow: 0 0.5rem 1.25rem rgba(15, 23, 42, 0.08);
            transform: translateY(-2px);
            border-color: rgba(255, 156, 0, 0.4);
        }

        .xsmall {
            font-size: 0.7rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admindash/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script>
        $(function () {
            const $cards = $('#history-list .history-card');

            function applyHistoryFilters() {
                const search = $('#history-filter-search').val().toLowerCase();
                const channel = $('#history-filter-channel').val();
                const year = $('#history-filter-year').val();
                const result = $('#history-filter-result').val();

                let visibleCount = 0;

                $cards.each(function () {
                    const $c = $(this);
                    const name = ($c.data('name') || '').toString().toLowerCase();
                    const desc = ($c.data('desc') || '').toString().toLowerCase();
                    const cChannel = ($c.data('channel') || '').toString();
                    const cYear = ($c.data('year') || '').toString();
                    const cResult = ($c.data('result') || '').toString();

                    let show = true;

                    if (search) {
                        show = name.indexOf(search) !== -1 || desc.indexOf(search) !== -1;
                    }

                    if (show && channel) {
                        show = (cChannel === channel);
                    }

                    if (show && year) {
                        show = (cYear === year);
                    }

                    if (show && result) {
                        show = (cResult === result);
                    }

                    if (show) {
                        $c.closest('.col-12').show();
                        visibleCount++;
                    } else {
                        $c.closest('.col-12').hide();
                    }
                });

                $('#history-count').text(visibleCount);
            }

            $('#history-filter-search').on('keyup', function () {
                applyHistoryFilters();
            });
            $('#history-filter-channel, #history-filter-year, #history-filter-result').on('change', function () {
                applyHistoryFilters();
            });

            // Jadikan Template (Prototype)
            $(document).on('click', '.btn-history-template', function () {
                const name = $(this).data('template-name') || 'Campaign';
                Swal.fire({
                    icon: 'success',
                    title: 'Jadikan Template (Prototype)',
                    text: 'Di versi production, akan dibuat draft campaign baru berdasarkan "' + name + '".'
                });
            });

            // Initial count
            applyHistoryFilters();
        });
    </script>
@endpush
