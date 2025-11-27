@extends('layouts.master')

@section('title', 'Stage / Progression')

@section('content')
    <div class="page-heading mb-3">
        <h3>Stage / Progression</h3>
    </div>

    <div class="page-content">
        <div class="row mb-3">
            {{-- Kartu ringkasan per stage (dummy data) --}}
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">New</small>
                        <h4 class="mb-0">12</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Contact</small>
                        <h4 class="mb-0">8</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Hold</small>
                        <h4 class="mb-0">5</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">No Respon</small>
                        <h4 class="mb-0">3</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Loss</small>
                        <h4 class="mb-0">2</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Close</small>
                        <h4 class="mb-0">4</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel list akun customer + progresi sekarang --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                            <div>
                                <h5 class="mb-0">Daftar Akun Customer</h5>
                                <small class="text-muted">
                                    Menampilkan stage CRM setiap akun. Klik <strong>Detail Progress</strong> untuk melihat
                                    histori per akunnya.
                                </small>
                            </div>
                            <div>
                                <span class="badge bg-secondary me-1">New</span>
                                <span class="badge bg-info text-dark me-1">Contact</span>
                                <span class="badge bg-warning text-dark me-1">Hold</span>
                                <span class="badge bg-dark me-1">No Respon</span>
                                <span class="badge bg-danger me-1">Loss</span>
                                <span class="badge bg-success">Close</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-stages">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Customer</th>
                                        <th>Perusahaan</th>
                                        <th>Telepon</th>
                                        <th>Stage Sekarang</th>
                                        <th>Update Terakhir</th>
                                        <th style="width: 140px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Dummy row 1 --}}
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>Andi Pratama</td>
                                        <td>PT Contoh Jaya</td>
                                        <td>0812-3456-7890</td>
                                        <td>
                                            <span class="badge bg-info text-dark">Contact</span>
                                        </td>
                                        <td>27 Nov 2025 10:15</td>
                                        <td class="text-center">
                                            <a href="{{ url('/stages-single') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-graph-up-arrow me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>

                                    {{-- Dummy row 2 --}}
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>Siti Rahma</td>
                                        <td>CV Digital Maju</td>
                                        <td>0852-1111-2222</td>
                                        <td>
                                            <span class="badge bg-success">Close</span>
                                        </td>
                                        <td>26 Nov 2025 16:30</td>
                                        <td class="text-center">
                                            <a href="{{ url('/stages-single') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-graph-up-arrow me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>

                                    {{-- Dummy row 3 --}}
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>Joko Santoso</td>
                                        <td>-</td>
                                        <td>0878-9999-0000</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">Hold</span>
                                        </td>
                                        <td>25 Nov 2025 09:20</td>
                                        <td class="text-center">
                                            <a href="{{ url('/stages-single') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-graph-up-arrow me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>

                                    {{-- Dummy row 4 --}}
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td>Maria Ulfa</td>
                                        <td>PT Sejahtera Abadi</td>
                                        <td>0813-9999-1111</td>
                                        <td>
                                            <span class="badge bg-secondary">New</span>
                                        </td>
                                        <td>27 Nov 2025 08:05</td>
                                        <td class="text-center">
                                            <a href="{{ url('/stages-single') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-graph-up-arrow me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>

                                    {{-- Dummy row 5 --}}
                                    <tr>
                                        <td class="text-center">5</td>
                                        <td>Rudi Hartono</td>
                                        <td>CV Maju Bersama</td>
                                        <td>0812-0000-5555</td>
                                        <td>
                                            <span class="badge bg-dark">No Respon</span>
                                        </td>
                                        <td>24 Nov 2025 14:10</td>
                                        <td class="text-center">
                                            <a href="{{ url('/stages-single') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-graph-up-arrow me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                *ini catatan utk keperlian footer.
                            </small>
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
            $('#table-stages').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
@endpush
