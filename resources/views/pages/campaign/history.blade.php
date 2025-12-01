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
                                <label class="form-label mb-1 small">Tahun</label>
                                <select class="form-select form-select-sm">
                                    <option>2025</option>
                                    <option>2024</option>
                                    <option>2023</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="form-label mb-1 small">Hasil</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option>Sukses / di atas target</option>
                                    <option>Cukup (mendekati target)</option>
                                    <option>Kurang (di bawah target)</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0">Daftar Campaign Selesai</h5>
                                <small class="text-muted">Prototype history campaign, bisa dipakai lagi sebagai template.</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-campaign-history">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 40px;">#</th>
                                        <th>Nama Campaign</th>
                                        <th>Periode</th>
                                        <th>Channel</th>
                                        <th>Owner</th>
                                        <th>Kontak</th>
                                        <th>Closing</th>
                                        <th>Hasil</th>
                                        <th style="width: 180px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dummy rows --}}
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            <strong>Ramadhan Sale 2025</strong><br>
                                            <small class="text-muted">Paket promo menjelang Idul Fitri.</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">01 Mar 2025 - 10 Apr 2025</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success">WhatsApp</span>
                                        </td>
                                        <td class="text-center">Admin Depati</td>
                                        <td class="text-center">450</td>
                                        <td class="text-center">72</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Sukses</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ url('/campaigns/ramadhan-sale-2025') }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-secondary">
                                                    <i class="bi bi-clipboard-plus"></i>
                                                    <span class="d-none d-lg-inline"> Jadikan Template</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>
                                            <strong>Back to School 2024</strong><br>
                                            <small class="text-muted">Promo pelajar & mahasiswa.</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">01 Jul 2024 - 31 Aug 2024</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">Email</span>
                                        </td>
                                        <td class="text-center">Marketing Team</td>
                                        <td class="text-center">320</td>
                                        <td class="text-center">44</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">Cukup</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ url('/campaigns/back-to-school-2024') }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-secondary">
                                                    <i class="bi bi-clipboard-plus"></i>
                                                    <span class="d-none d-lg-inline"> Jadikan Template</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>
                                            <strong>Q4 Retention Push 2023</strong><br>
                                            <small class="text-muted">Re-activate customer lama.</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">01 Oct 2023 - 31 Dec 2023</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-subtle text-warning">Telemarketing</span>
                                        </td>
                                        <td class="text-center">CS Team</td>
                                        <td class="text-center">270</td>
                                        <td class="text-center">20</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">Kurang</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ url('/campaigns/q4-retention-2023') }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-secondary">
                                                    <i class="bi bi-clipboard-plus"></i>
                                                    <span class="d-none d-lg-inline"> Jadikan Template</span>
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
            $('#table-campaign-history').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    targets: [0, 2, 3, 4, 5, 6, 7, 8],
                    className: 'text-center'
                }]
            });
        });
    </script>
@endpush
