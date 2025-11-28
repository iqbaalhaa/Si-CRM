@extends('layouts.master')

@section('title', 'Assign To')

@section('content')
    <div class="page-heading mb-3">
        <h3>Assign Customer ke CS / FO</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">

                <div class="alert alert-info d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <strong>Petunjuk:</strong> Pilih CS / FO untuk setiap customer pada kolom
                        <span class="badge bg-primary">Assigned To</span>, lalu klik tombol
                        <span class="badge bg-secondary">Simpan</span>.
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                            <div>
                                <h5 class="mb-0">Daftar Customer</h5>
                                <small class="text-muted">Prototype halaman assign customer ke tim</small>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-secondary">Belum di-assign</span>
                                <span class="badge bg-success">Sudah di-assign</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table-assign">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Customer</th>
                                        <th>Perusahaan</th>
                                        <th>Telepon</th>
                                        <th>Stage</th>
                                        <th style="width: 260px;">Assigned To</th>
                                        <th style="width: 120px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Contoh row 1 - belum di-assign --}}
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>Andi Pratama</td>
                                        <td>PT Contoh Jaya</td>
                                        <td>0812-3456-7890</td>
                                        <td><span class="badge bg-warning text-dark">Prospecting</span></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <select class="form-select form-select-sm">
                                                    <option value="">-- Pilih CS / FO --</option>
                                                    <option>Rina (CS)</option>
                                                    <option>Dwi (FO)</option>
                                                    <option>Budi (CS)</option>
                                                </select>
                                                <button type="button" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save"></i>
                                                    <span class="d-none d-xl-inline">Simpan</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">Belum di-assign</span>
                                        </td>
                                    </tr>

                                    {{-- Contoh row 2 - sudah di-assign --}}
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>Siti Rahma</td>
                                        <td>CV Digital Maju</td>
                                        <td>0852-1111-2222</td>
                                        <td><span class="badge bg-info text-dark">Follow Up</span></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <select class="form-select form-select-sm">
                                                    <option value="">-- Pilih CS / FO --</option>
                                                    <option selected>Rina (CS)</option>
                                                    <option>Dwi (FO)</option>
                                                    <option>Budi (CS)</option>
                                                </select>
                                                <button type="button" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save"></i>
                                                    <span class="d-none d-xl-inline">Simpan</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Rina (CS)</span>
                                        </td>
                                    </tr>

                                    {{-- Contoh row 3 --}}
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>Joko Santoso</td>
                                        <td>-</td>
                                        <td>0878-9999-0000</td>
                                        <td><span class="badge bg-secondary">New Lead</span></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <select class="form-select form-select-sm">
                                                    <option value="">-- Pilih CS / FO --</option>
                                                    <option>Rina (CS)</option>
                                                    <option selected>Dwi (FO)</option>
                                                    <option>Budi (CS)</option>
                                                </select>
                                                <button type="button" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save"></i>
                                                    <span class="d-none d-xl-inline">Simpan</span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">Dwi (FO)</span>
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
            $('#table-assign').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
@endpush
