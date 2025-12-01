@extends('layouts.master')

@section('title', 'Campaign Active')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Campaign Active</h3>
            <p class="text-muted mb-0">
                Pantau semua campaign yang sedang berjalan dan klik untuk lihat detail & kontak.
            </p>
        </div>
        <a href="{{ url('/campaigns/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Buat Campaign Baru
        </a>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">

                {{-- Summary cards (pure frontend, angka dummy) --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Total Campaign Active</div>
                                    <h4 class="mb-0">3</h4>
                                </div>
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="bi bi-bullseye fs-4 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Total Kontak di Campaign</div>
                                    <h4 class="mb-0">1.250</h4>
                                </div>
                                <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                                    <i class="bi bi-people fs-4 text-secondary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Perkiraan Closing Rate</div>
                                    <h4 class="mb-0">18%</h4>
                                </div>
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <form class="row g-2 align-items-center">
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Cari Campaign</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Nama campaign">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Channel</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua Channel</option>
                                    <option>WhatsApp</option>
                                    <option>Email</option>
                                    <option>Telemarketing</option>
                                    <option>Social Media</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Periode</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option>Bulan ini</option>
                                    <option>3 bulan terakhir</option>
                                    <option>6 bulan terakhir</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Status</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Aktif</option>
                                    <option>Pause</option>
                                    <option>Draft</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Table --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0">Daftar Campaign Active</h5>
                                <small class="text-muted">Prototype daftar campaign, klik <strong>Detail</strong> untuk lihat kontak.</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-campaign-active">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 40px;">#</th>
                                        <th>Nama Campaign</th>
                                        <th>Periode</th>
                                        <th>Channel</th>
                                        <th>Owner</th>
                                        <th>Kontak</th>
                                        <th>Status</th>
                                        <th style="width: 160px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dummy rows --}}
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            <strong>Winter Sale 2025</strong><br>
                                            <small class="text-muted">Diskon akhir tahun untuk customer lama & baru.</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">01 Dec 2025 - 31 Dec 2025</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success">WhatsApp</span>
                                        </td>
                                        <td class="text-center">Admin Depati</td>
                                        <td class="text-center">520</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ url('/campaigns/1') }}" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                    <span class="d-none d-lg-inline"> Detail</span>
                                                </a>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="bi bi-pause-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="bi bi-files"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>
                                            <strong>Onboarding Client Baru Q1</strong><br>
                                            <small class="text-muted">Follow up semua lead yang masuk dari iklan.</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">15 Nov 2025 - 15 Jan 2026</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">Email</span>
                                        </td>
                                        <td class="text-center">Marketing Team</td>
                                        <td class="text-center">380</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ url('/campaigns/2') }}" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                    <span class="d-none d-lg-inline"> Detail</span>
                                                </a>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="bi bi-pause-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="bi bi-files"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>
                                            <strong>Upsell Paket Premium</strong><br>
                                            <small class="text-muted">Naikkan ARPU dari customer aktif.</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">01 Oct 2025 - 31 Dec 2025</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-subtle text-warning">Telemarketing</span>
                                        </td>
                                        <td class="text-center">CS Team</td>
                                        <td class="text-center">350</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ url('/campaigns/3') }}" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                    <span class="d-none d-lg-inline"> Detail</span>
                                                </a>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="bi bi-pause-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary">
                                                    <i class="bi bi-files"></i>
                                                </button>
                                            </div>
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
            $('#table-campaign-active').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    targets: [0, 2, 3, 5, 6, 7],
                    className: 'text-center'
                }]
            });
        });
    </script>
@endpush
