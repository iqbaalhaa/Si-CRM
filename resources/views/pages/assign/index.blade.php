@extends('layouts.master')

@section('title', 'Assign To')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Assign Customer ke CS / FO</h3>
            <p class="text-muted mb-0">Atur penugasan customer ke tim CS / FO kamu.</p>
        </div>
        @if (session('success'))
            <span class="badge bg-success px-3 py-2">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </span>
        @endif
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
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge bg-secondary">
                                    <i class="bi bi-circle me-1"></i>Belum di-assign
                                </span>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Sudah di-assign
                                </span>
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
                                    @forelse ($customers as $customer)
                                        @php
                                            $isAssigned = !is_null($customer->assigned_to_id);
                                        @endphp
                                        <tr class="{{ $isAssigned ? '' : 'table-warning' }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->company->name ?? '-' }}</td>
                                            <td>{{ $customer->phone ?? '-' }}</td>
                                            <td class="text-center">
                                                @if ($customer->stage)
                                                    <span class="badge bg-info text-dark">
                                                        {{ $customer->stage->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('assign.store', $customer) }}" method="POST">
                                                    @csrf
                                                    <div class="d-flex gap-2">
                                                        <select name="assigned_to_id"
                                                            class="form-select form-select-sm @error('assigned_to_id') is-invalid @enderror">
                                                            <option value="">-- Pilih CS / FO --</option>
                                                            @foreach ($assignableUsers as $user)
                                                                <option value="{{ $user->id }}"
                                                                    @selected($customer->assigned_to_id == $user->id)>
                                                                    {{ $user->name }}
                                                                    @php
                                                                        $roles = $user->getRoleNames()->toArray();
                                                                    @endphp
                                                                    @if (count($roles))
                                                                        ({{ implode(', ', $roles) }})
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-save"></i>
                                                            <span class="d-none d-xl-inline"> Simpan</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                @if ($customer->assignedTo)
                                                    <span class="badge bg-success">
                                                        {{ $customer->assignedTo->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Belum di-assign</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                Belum ada data customer.
                                            </td>
                                        </tr>
                                    @endforelse
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
                ],
                columnDefs: [{
                    targets: [0, 4, 6],
                    className: 'text-center'
                }]
            });
        });
    </script>
@endpush
