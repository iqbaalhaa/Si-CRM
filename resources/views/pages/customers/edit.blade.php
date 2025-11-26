@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h3>Edit Customer</h3>
        <p class="text-muted mb-0">Perbarui data pelanggan.</p>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{ old('name', $customer->name) }}">
                                    <label for="name">Nama</label>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" class="form-control" placeholder="Telepon" value="{{ old('phone', $customer->phone) }}">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $customer->email) }}">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" name="source" id="source" class="form-control" placeholder="Sumber" value="{{ old('source', $customer->source) }}">
                                    <label for="source">Sumber</label>
                                </div>
                                @error('source')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" name="tag" id="tag" class="form-control" placeholder="Tag" value="{{ old('tag', $customer->tag) }}">
                                    <label for="tag">Tag</label>
                                </div>
                                @error('tag')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="notes" id="notes" class="form-control" placeholder="Catatan" style="height: 100px">{{ old('notes', $customer->notes) }}</textarea>
                                    <label for="notes">Catatan</label>
                                </div>
                                @error('notes')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

