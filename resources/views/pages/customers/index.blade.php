@extends('layouts.master')

@section('content')
    <div class="page-heading">
        <h3>Customer</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <x-table :headers="['No', 'Nama', 'Perusahaan', 'Alamat', 'Telepon', 'Email', 'Stage', 'Aksi']" :rows="$tableRows" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
