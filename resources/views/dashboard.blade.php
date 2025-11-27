@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>

    <div class="page-content">
        <section class="row">
            {{-- copy isi card, chart, table, dll dari index.html di sini --}}
            {{-- contoh awal: --}}
            <div class="col-12 col-lg-9">
                {{-- semua card statistik, chart, latest comments --}}
                {{-- ... (copy dari index.html) --}}
            </div>

            <div class="col-12 col-lg-3">
                {{-- profile, recent messages, visitors profile --}}
                {{-- ... --}}
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    {{-- script khusus halaman dashboard --}}
    <script src="{{ asset('admindash/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admindash/assets/static/js/pages/dashboard.js') }}"></script>
@endpush
