@extends('layouts.master')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Kontak</h3>
            <p class="text-muted mb-0">Perbarui informasi kontak.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-info">Detail</a>
            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card" style="background: transparent; box-shadow: none;">
                    <div class="card-body">
                        <form action="{{ route('contacts.update', $contact->id) }}" method="POST" id="contactEditForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="form-floating">
                                        <select name="type" id="type" class="form-select" required>
                                            <option value="individual" {{ $contact->type === 'individual' ? 'selected' : '' }}>Individu</option>
                                            <option value="company" {{ $contact->type === 'company' ? 'selected' : '' }}>Perusahaan</option>
                                            <option value="organization" {{ $contact->type === 'organization' ? 'selected' : '' }}>Organisasi</option>
                                        </select>
                                        <label for="type">Tipe Kontak</label>
                                    </div>
                                    @error('type')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-8">
                                    <div class="form-floating">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{ old('name', $contact->name) }}" required>
                                        <label for="name">Nama</label>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', optional($email)->value) }}">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" name="phone" class="form-control" placeholder="Telepon" value="{{ old('phone', optional($phone)->value) }}">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4" id="fields_individual" style="display:none">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="alamat_lengkap" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat_lengkap', optional($contact->details->firstWhere('label','alamat_lengkap'))->value) }}">
                                            <label>Alamat lengkap</label>
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="kota_kabupaten" class="form-control" placeholder="Kota / Kabupaten" value="{{ old('kota_kabupaten', optional($contact->details->firstWhere('label','kota_kabupaten'))->value) }}">
                                            <label>Kota / Kabupaten</label>
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" value="{{ old('provinsi', optional($contact->details->firstWhere('label','provinsi'))->value) }}">
                                            <label>Provinsi</label>
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="negara" class="form-control" placeholder="Negara" value="{{ old('negara', optional($contact->details->firstWhere('label','negara'))->value ?? 'Indonesia') }}">
                                            <label>Negara</label>
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating">
                                            @php $jk = optional($contact->details->firstWhere('label','jenis_kelamin'))->value; @endphp
                                            <select name="jenis_kelamin" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin', $jk)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin', $jk)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                <option value="Lainnya" {{ old('jenis_kelamin', $jk)=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <label>Jenis kelamin</label>
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating">
                                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($contact->details->firstWhere('label','tanggal_lahir'))->value) }}">
                                            <label>Tanggal lahir</label>
                                        </div>
                                        @error('tanggal_lahir')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating">
                                            @php $ag = optional($contact->details->firstWhere('label','agama'))->value; @endphp
                                            <select name="agama" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Islam" {{ old('agama', $ag)=='Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama', $ag)=='Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama', $ag)=='Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama', $ag)=='Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama', $ag)=='Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ old('agama', $ag)=='Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                                <option value="Lainnya" {{ old('agama', $ag)=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <label>Agama</label>
                                        </div>
                                        @error('agama')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-floating">
                                            @php $sp = optional($contact->details->firstWhere('label','status_pernikahan'))->value; @endphp
                                            <select name="status_pernikahan" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Belum Menikah" {{ old('status_pernikahan', $sp)=='Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                                <option value="Menikah" {{ old('status_pernikahan', $sp)=='Menikah' ? 'selected' : '' }}>Menikah</option>
                                                <option value="Cerai" {{ old('status_pernikahan', $sp)=='Cerai' ? 'selected' : '' }}>Cerai</option>
                                                <option value="Lainnya" {{ old('status_pernikahan', $sp)=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <label>Status pernikahan</label>
                                        </div>
                                        @error('status_pernikahan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4" id="fields_company" style="display:none">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="nama_brand" class="form-control" placeholder="Nama brand" value="{{ old('nama_brand', optional($contact->details->firstWhere('label','nama_brand'))->value) }}">
                                            <label>Nama brand</label>
                                        </div>
                                        @error('nama_brand')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="industri" class="form-control" placeholder="Industri" value="{{ old('industri', optional($contact->details->firstWhere('label','industri'))->value) }}">
                                            <label>Industri</label>
                                        </div>
                                        @error('industri')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="npwp" class="form-control" placeholder="NPWP" value="{{ old('npwp', optional($contact->details->firstWhere('label','npwp'))->value) }}">
                                            <label>NPWP</label>
                                        </div>
                                        @error('npwp')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="alamat_lengkap" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat_lengkap', optional($contact->details->firstWhere('label','alamat_lengkap'))->value) }}">
                                            <label>Alamat lengkap</label>
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="kota_kabupaten" class="form-control" placeholder="Kota / Kabupaten" value="{{ old('kota_kabupaten', optional($contact->details->firstWhere('label','kota_kabupaten'))->value) }}">
                                            <label>Kota / Kabupaten</label>
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" value="{{ old('provinsi', optional($contact->details->firstWhere('label','provinsi'))->value) }}">
                                            <label>Provinsi</label>
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="negara" class="form-control" placeholder="Negara" value="{{ old('negara', optional($contact->details->firstWhere('label','negara'))->value ?? 'Indonesia') }}">
                                            <label>Negara</label>
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4" id="fields_organization" style="display:none">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="tipe_organisasi" class="form-control" placeholder="Tipe organisasi" value="{{ old('tipe_organisasi', optional($contact->details->firstWhere('label','tipe_organisasi'))->value) }}">
                                            <label>Tipe organisasi</label>
                                        </div>
                                        @error('tipe_organisasi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="bidang_kegiatan" class="form-control" placeholder="Bidang kegiatan" value="{{ old('bidang_kegiatan', optional($contact->details->firstWhere('label','bidang_kegiatan'))->value) }}">
                                            <label>Bidang kegiatan</label>
                                        </div>
                                        @error('bidang_kegiatan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="number" name="jumlah_anggota" class="form-control" placeholder="Jumlah anggota" value="{{ old('jumlah_anggota', optional($contact->details->firstWhere('label','jumlah_anggota'))->value) }}">
                                            <label>Jumlah anggota</label>
                                        </div>
                                        @error('jumlah_anggota')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="alamat_lengkap" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat_lengkap', optional($contact->details->firstWhere('label','alamat_lengkap'))->value) }}">
                                            <label>Alamat lengkap</label>
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="kota_kabupaten" class="form-control" placeholder="Kota / Kabupaten" value="{{ old('kota_kabupaten', optional($contact->details->firstWhere('label','kota_kabupaten'))->value) }}">
                                            <label>Kota / Kabupaten</label>
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" value="{{ old('provinsi', optional($contact->details->firstWhere('label','provinsi'))->value) }}">
                                            <label>Provinsi</label>
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating">
                                            <input type="text" name="negara" class="form-control" placeholder="Negara" value="{{ old('negara', optional($contact->details->firstWhere('label','negara'))->value ?? 'Indonesia') }}">
                                            <label>Negara</label>
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h6 class="mb-2">Tambah Field Kustom</h6>
                                <div id="extraDetails" class="mb-2">
                                    @php
                                        $defaultLabels = ['alamat_lengkap','kota_kabupaten','provinsi','negara','jenis_kelamin','tanggal_lahir','agama','status_pernikahan','nama_brand','industri','npwp','tipe_organisasi','bidang_kegiatan','jumlah_anggota'];
                                    @endphp
                                    @foreach($contact->details->whereNotIn('label', $defaultLabels) as $d)
                                        <div class="row g-2 mb-2">
                                            <div class="col-md-4">
                                                <input type="text" name="details[label][]" class="form-control" placeholder="Label" value="{{ $d->label }}">
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" name="details[value][]" class="form-control" placeholder="Value" value="{{ $d->value }}">
                                            </div>
                                            <div class="col-md-1 d-grid">
                                                <button type="button" class="btn btn-outline-danger">Hapus</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addDetail">Tambah Field</button>
                                @error('details.label.*')
                                    <div class="text-danger small mt-1">Label duplikat atau tidak valid</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script>
        const typeSelect = document.getElementById('type');
        const fieldsIndividual = document.getElementById('fields_individual');
        const fieldsCompany = document.getElementById('fields_company');
        const fieldsOrganization = document.getElementById('fields_organization');
        const addDetailBtn = document.getElementById('addDetail');
        const extraDetails = document.getElementById('extraDetails');

        function setDisabled(container, disabled) {
            if (!container) return;
            container.querySelectorAll('input, select, textarea').forEach(el => {
                el.disabled = disabled;
            });
        }

        function toggleTypeFields(type) {
            fieldsIndividual.style.display = type === 'individual' ? 'block' : 'none';
            fieldsCompany.style.display = type === 'company' ? 'block' : 'none';
            fieldsOrganization.style.display = type === 'organization' ? 'block' : 'none';
            setDisabled(fieldsIndividual, type !== 'individual');
            setDisabled(fieldsCompany, type !== 'company');
            setDisabled(fieldsOrganization, type !== 'organization');
        }

        typeSelect.addEventListener('change', () => toggleTypeFields(typeSelect.value));
        toggleTypeFields(typeSelect.value);

        addDetailBtn.addEventListener('click', () => {
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2';
            row.innerHTML = `
                <div class=\"col-md-4\">
                    <input type=\"text\" name=\"details[label][]\" class=\"form-control\" placeholder=\"Label\">
                </div>
                <div class=\"col-md-7\">
                    <input type=\"text\" name=\"details[value][]\" class=\"form-control\" placeholder=\"Value\">
                </div>
                <div class=\"col-md-1 d-grid\">
                    <button type=\"button\" class=\"btn btn-outline-danger\">Hapus</button>
                </div>
            `;
            row.querySelector('button').addEventListener('click', () => row.remove());
            extraDetails.appendChild(row);
        });
    </script>
@endpush
@endsection
