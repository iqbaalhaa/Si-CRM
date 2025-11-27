@extends('layouts.master')

@section('title', 'Stage / Progression')

@section('content')
    <div class="page-heading mb-3">
        <h3>Stage / Progression</h3>
    </div>

    <div class="page-content">

        {{-- Ringkasan per stage --}}
        <div class="row mb-3">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">New</small>
                        <h4 class="mb-0">{{ $stageCounts['New'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Contact</small>
                        <h4 class="mb-0">{{ $stageCounts['Contact'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Hold</small>
                        <h4 class="mb-0">{{ $stageCounts['Hold'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">No Respon</small>
                        <h4 class="mb-0">{{ $stageCounts['No Respon'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Loss</small>
                        <h4 class="mb-0">{{ $stageCounts['Loss'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card">
                    <div class="card-body py-3">
                        <small class="text-muted">Close</small>
                        <h4 class="mb-0">{{ $stageCounts['Close'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel list akun customer + stage sekarang --}}
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
                                    @forelse ($customers as $index => $customer)
                                        @php
                                            $stageName = optional($customer->stage)->name;
                                            $badgeClass = match ($stageName) {
                                                'New' => 'bg-secondary',
                                                'Contact' => 'bg-info text-dark',
                                                'Hold' => 'bg-warning text-dark',
                                                'No Respon' => 'bg-dark',
                                                'Loss' => 'bg-danger',
                                                'Close' => 'bg-success',
                                                default => 'bg-light text-dark',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ optional($customer->company)->name ?? '-' }}</td>
                                            <td>{{ $customer->phone ?? '-' }}</td>
                                            <td>
                                                @if ($stageName)
                                                    <span class="badge {{ $badgeClass }}">{{ $stageName }}</span>
                                                @else
                                                    <span class="badge bg-light text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ optional($customer->updated_at)->format('d M Y H:i') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('stages.show', $customer) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-graph-up-arrow me-1"></i>Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada customer</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                * Footer catatan: bisa diisi info SLA follow-up, definisi tiap stage, atau ringkasan pipeline.
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
