@extends('layouts.master')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <h3>Edit Perusahaan</h3>
        <p class="text-muted mb-0">Perbarui detail perusahaan.</p>
    </div>
    <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Form Perusahaan</span>
                    <span class="badge bg-primary">Edit</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('perusahaan.update', $perusahaan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{ old('name', $perusahaan->name) }}" required>
                                    <label for="name">Nama</label>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" name="code" class="form-control text-uppercase" placeholder="Kode" value="{{ old('code', $perusahaan->code) }}" required>
                                </div>
                                @error('code')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="address" id="address" class="form-control" placeholder="Alamat" style="height: 100px" required>{{ old('address', $perusahaan->address) }}</textarea>
                                    <label for="address">Alamat</label>
                                </div>
                                @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" class="form-control" placeholder="Telepon" value="{{ old('phone', $perusahaan->phone) }}">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $perusahaan->email) }}">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-floating">
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="Aktif" {{ old('status', $perusahaan->status)==='Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Nonaktif" {{ old('status', $perusahaan->status)==='Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
