@extends('layouts.master')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center mb-3 mb-lg-4">
        <div>
            <h3 class="mb-1">Kontak</h3>
            <p class="text-muted mb-0">Pilih tipe kontak terlebih dahulu, lalu isi form.</p>
        </div>
    </div>

    <div class="page-content">
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-1">Pilih Tipe Kontak</h5>
                        <p class="text-muted small mb-0">Tipe yang dipilih akan menentukan label dan field pada form.</p>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check type-card rounded-3 p-3 h-100 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="radio" name="type_select" id="type_individual" value="individual">
                                    <label class="form-check-label ms-1 flex-grow-1" for="type_individual">
                                        <div class="fw-semibold d-flex align-items-center">
                                            <i class="bi bi-person-fill me-2 fs-5"></i> Individu
                                        </div>
                                        <small class="text-muted d-block mt-1">Customer perorangan</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check type-card rounded-3 p-3 h-100 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="radio" name="type_select" id="type_company" value="company">
                                    <label class="form-check-label ms-1 flex-grow-1" for="type_company">
                                        <div class="fw-semibold d-flex align-items-center">
                                            <i class="bi bi-building-fill me-2 fs-5"></i> Perusahaan
                                        </div>
                                        <small class="text-muted d-block mt-1">Client berbentuk badan usaha</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check type-card rounded-3 p-3 h-100 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="radio" name="type_select" id="type_organization" value="organization">
                                    <label class="form-check-label ms-1 flex-grow-1" for="type_organization">
                                        <div class="fw-semibold d-flex align-items-center">
                                            <i class="bi bi-collection-fill me-2 fs-5"></i> Organisasi
                                        </div>
                                        <small class="text-muted d-block mt-1">Komunitas, yayasan, dan sejenisnya</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="small text-muted mt-3">Tipe yang dipilih akan menentukan label pada form.</div>
                    </div>
                </div>
            </div>

            <div class="col-12" id="contactFormWrapper" style="display:none">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pb-0">
                        <div>
                            <h5 class="card-title mb-1">Form Kontak</h5>
                            <p class="text-muted small mb-0">Lengkapi informasi dasar dan detail sesuai tipe kontak.</p>
                        </div>
                        <a href="{{ route('contacts.create') }}" class="btn btn-light btn-sm border">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Pilihan
                        </a>
                    </div>
                    <div class="card-body pt-3">
                        <form action="{{ route('contacts.store') }}" method="POST" id="contactForm" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="type" id="type_hidden" value="{{ old('type') }}">

                            <div class="row g-3 mb-2">
                                <div class="col-12">
                                    <span class="text-uppercase text-muted small fw-semibold">Informasi Utama</span>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="name" id="name_label" class="form-label">Nama</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{ old('name') }}" required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mt-2" id="fields_individual" style="display:none">
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <span class="text-uppercase text-muted small fw-semibold">Detail Individu</span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat lengkap</label>
                                            <input type="text" name="alamat_lengkap" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat_lengkap') }}">
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <input type="text" name="kota_kabupaten" class="form-control" placeholder="Kota / Kabupaten" value="{{ old('kota_kabupaten') }}">
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Provinsi</label>
                                            <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" value="{{ old('provinsi') }}">
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Negara</label>
                                            <input type="text" name="negara" class="form-control" placeholder="Negara" value="{{ old('negara', 'Indonesia') }}">
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Jenis kelamin</label>
                                            <select name="jenis_kelamin" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                <option value="Lainnya" {{ old('jenis_kelamin')=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal lahir</label>
                                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                                        </div>
                                        @error('tanggal_lahir')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Agama</label>
                                            <select name="agama" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Islam" {{ old('agama')=='Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama')=='Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama')=='Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama')=='Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama')=='Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ old('agama')=='Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                                <option value="Lainnya" {{ old('agama')=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('agama')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Status pernikahan</label>
                                            <select name="status_pernikahan" class="form-select" required>
                                                <option value="">Pilih</option>
                                                <option value="Belum Menikah" {{ old('status_pernikahan')=='Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                                <option value="Menikah" {{ old('status_pernikahan')=='Menikah' ? 'selected' : '' }}>Menikah</option>
                                                <option value="Cerai" {{ old('status_pernikahan')=='Cerai' ? 'selected' : '' }}>Cerai</option>
                                                <option value="Lainnya" {{ old('status_pernikahan')=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        @error('status_pernikahan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2" id="fields_company" style="display:none">
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <span class="text-uppercase text-muted small fw-semibold">Detail Perusahaan</span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nama brand</label>
                                            <input type="text" name="nama_brand" class="form-control" placeholder="Nama brand" value="{{ old('nama_brand') }}">
                                        </div>
                                        @error('nama_brand')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Industri</label>
                                            <input type="text" name="industri" class="form-control" placeholder="Industri" value="{{ old('industri') }}">
                                        </div>
                                        @error('industri')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">NPWP</label>
                                            <input type="text" name="npwp" class="form-control" placeholder="NPWP" value="{{ old('npwp') }}">
                                        </div>
                                        @error('npwp')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat lengkap</label>
                                            <input type="text" name="alamat_lengkap" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat_lengkap') }}">
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <input type="text" name="kota_kabupaten" class="form-control" placeholder="Kota / Kabupaten" value="{{ old('kota_kabupaten') }}">
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Provinsi</label>
                                            <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" value="{{ old('provinsi') }}">
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Negara</label>
                                            <input type="text" name="negara" class="form-control" placeholder="Negara" value="{{ old('negara', 'Indonesia') }}">
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2" id="fields_organization" style="display:none">
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <span class="text-uppercase text-muted small fw-semibold">Detail Organisasi</span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Tipe organisasi</label>
                                            <input type="text" name="tipe_organisasi" class="form-control" placeholder="Tipe organisasi" value="{{ old('tipe_organisasi') }}">
                                        </div>
                                        @error('tipe_organisasi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Bidang kegiatan</label>
                                            <input type="text" name="bidang_kegiatan" class="form-control" placeholder="Bidang kegiatan" value="{{ old('bidang_kegiatan') }}">
                                        </div>
                                        @error('bidang_kegiatan')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah anggota</label>
                                            <input type="number" name="jumlah_anggota" class="form-control" placeholder="Jumlah anggota" value="{{ old('jumlah_anggota') }}">
                                        </div>
                                        @error('jumlah_anggota')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat lengkap</label>
                                            <input type="text" name="alamat_lengkap" class="form-control" placeholder="Alamat lengkap" value="{{ old('alamat_lengkap') }}">
                                        </div>
                                        @error('alamat_lengkap')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <input type="text" name="kota_kabupaten" class="form-control" placeholder="Kota / Kabupaten" value="{{ old('kota_kabupaten') }}">
                                        </div>
                                        @error('kota_kabupaten')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Provinsi</label>
                                            <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" value="{{ old('provinsi') }}">
                                        </div>
                                        @error('provinsi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Negara</label>
                                            <input type="text" name="negara" class="form-control" placeholder="Negara" value="{{ old('negara', 'Indonesia') }}">
                                        </div>
                                        @error('negara')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mt-2">
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <span class="text-uppercase text-muted small fw-semibold">Informasi Kontak</span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">
                                                <i class="bi bi-at"></i>
                                            </span>
                                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                        </div>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">
                                                <i class="bi bi-whatsapp"></i>
                                            </span>
                                            <input type="text" name="phone" class="form-control" placeholder="Nomor WhatsApp / Telepon" value="{{ old('phone') }}">
                                        </div>
                                        @error('phone')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">
                                                <i class="bi bi-instagram"></i>
                                            </span>
                                            <input type="text" class="form-control" name="details[value][]" placeholder="@username" value="{{ old('details.value.0') }}">
                                            <input type="hidden" name="details[label][]" value="Instagram">
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-lg-6">
                                        <label class="form-label">Tambah kanal sosial</label>
                                        <select id="contactChannelSelect" class="form-select">
                                            <option value="">Pilih kanal</option>
                                        
                                            <option value="facebook">Facebook</option>
                                            <option value="tiktok">TikTok</option>
                                            <option value="twitter">Twitter/X</option>
                                            <option value="whatsapp">WhatsApp</option>
                                            <option value="telegram">Telegram</option>
                                            <option value="linkedin">LinkedIn</option>
                                            <option value="youtube">YouTube</option>
                                            <option value="website">Website</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="contactChannels" class="row g-3 mt-1"></div>
                            </div>
                            
                            <hr class="my-4">

                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Tambah Field Kustom</h6>
                                    <span class="text-muted small">Opsional, untuk info tambahan</span>
                                </div>
                                <div id="extraDetails" class="mb-2"></div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addDetail">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Field
                                </button>
                                @error('details.label.*')
                                    <div class="text-danger small mt-1">Label duplikat atau tidak valid</div>
                                @enderror
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('contacts.create') }}" class="btn btn-light border px-4">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .page-content {
                background-color: transparent;
                border-radius: 1rem;
            }

            .type-card {
                border: 1px solid var(--bs-border-color);
                background-color: transparent;
                cursor: pointer;
                transition: all .18s ease-in-out;
            }

            .page-content .card,
            .page-content .card-header,
            .page-content .card-body {
                background-color: transparent;
            }

            .type-card:hover {
                box-shadow: 0 .75rem 1.5rem rgba(15, 23, 42, .08);
                transform: translateY(-2px);
                border-color: var(--bs-primary);
            }

            .type-card .form-check-input {
                margin-top: 0;
                width: 1.1rem;
                height: 1.1rem;
            }

            .type-card input[type="radio"]:checked + label {
                color: var(--bs-primary);
            }

            #contactFormWrapper {
                animation: fadeIn .25s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(4px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            #contactForm .form-control,
            #contactForm .form-select {
                border-radius: .75rem;
            }

            #contactForm .input-group-text {
                border-radius: .75rem 0 0 .75rem;
            }

            #contactForm .input-group .form-control {
                border-radius: 0 .75rem .75rem 0;
            }

            #contactForm .btn {
                border-radius: .75rem;
            }

            #extraDetails .form-control {
                border-radius: .75rem;
            }

            

            #contactForm .form-control,
            #contactForm .form-select {
                background-color: transparent;
                border-color: var(--bs-border-color);
                color: var(--bs-body-color);
            }

            #contactForm .form-control:focus,
            #contactForm .form-select:focus {
                border-color: var(--bs-primary);
                box-shadow: 0 .2rem .6rem rgba(15, 23, 42, .08);
            }

            #contactForm .input-group .form-control {
                background-color: transparent;
                border-color: var(--bs-border-color);
                color: var(--bs-body-color);
            }

            #contactForm .input-group-text {
                background-color: transparent;
                border-color: var(--bs-border-color);
                color: var(--bs-body-color);
            }

            .page-content span.text-uppercase.text-muted.small.fw-semibold {
                color: var(--bs-body-color);
                opacity: .7;
                letter-spacing: .08em;
            }

            .page-content hr {
                border-color: var(--bs-border-color);
                opacity: .4;
            }

            .page-content .btn-light {
                background-color: transparent;
                color: var(--bs-body-color);
                border-color: var(--bs-border-color);
            }

            .page-content .btn-light:hover,
            .page-content .btn-light:focus {
                background-color: transparent;
                color: var(--bs-body-color);
                border-color: var(--bs-border-color);
                box-shadow: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const typeRadios = document.querySelectorAll('input[name="type_select"]');
            const formWrapper = document.getElementById('contactFormWrapper');
            const typeHidden = document.getElementById('type_hidden');
            const nameLabel = document.getElementById('name_label');
            const fieldsIndividual = document.getElementById('fields_individual');
            const fieldsCompany = document.getElementById('fields_company');
            const fieldsOrganization = document.getElementById('fields_organization');
            const addDetailBtn = document.getElementById('addDetail');
            const extraDetails = document.getElementById('extraDetails');

            function updateLabel(type) {
                if (type === 'company') nameLabel.textContent = 'Nama Perusahaan';
                else if (type === 'individual') nameLabel.textContent = 'Nama Individu';
                else if (type === 'organization') nameLabel.textContent = 'Nama Organisasi';
                else nameLabel.textContent = 'Nama';
            }

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

            typeRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    formWrapper.style.display = 'block';
                    typeHidden.value = radio.value;
                    updateLabel(radio.value);
                    toggleTypeFields(radio.value);
                });
            });

            // Restore selection when validation error occurs
            const oldType = typeHidden.value;
            if (oldType) {
                const oldRadio = document.querySelector(`input[name="type_select"][value="${oldType}"]`);
                if (oldRadio) {
                    oldRadio.checked = true;
                    formWrapper.style.display = 'block';
                    updateLabel(oldType);
                    toggleTypeFields(oldType);
                }
            } else {
                // nonaktifkan semua fieldset sampai tipe dipilih
                setDisabled(fieldsIndividual, true);
                setDisabled(fieldsCompany, true);
                setDisabled(fieldsOrganization, true);
            }

            addDetailBtn.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2';
                row.innerHTML = `
                    <div class="col-md-4">
                        <input type="text" name="details[label][]" class="form-control" placeholder="Label">
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="details[value][]" class="form-control" placeholder="Value">
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-outline-danger">Hapus</button>
                    </div>
                `;
                const btn = row.querySelector('button');
                btn.addEventListener('click', () => row.remove());
                extraDetails.appendChild(row);
            });

            // Dynamic contact channels
            const channelSelect = document.getElementById('contactChannelSelect');
            const contactChannels = document.getElementById('contactChannels');

            const channelTemplates = {
                instagram: { label: 'Instagram', placeholder: '@username', icon: 'at' },
                facebook: { label: 'Facebook', placeholder: 'https://facebook.com/username', icon: 'link' },
                tiktok: { label: 'TikTok', placeholder: '@username', icon: 'at' },
                twitter: { label: 'Twitter/X', placeholder: '@username', icon: 'at' },
                whatsapp: { label: 'WhatsApp', placeholder: '08xxxxxxxxxx', icon: 'telephone' },
                telegram: { label: 'Telegram', placeholder: '@username', icon: 'at' },
                linkedin: { label: 'LinkedIn', placeholder: 'https://linkedin.com/in/username', icon: 'link' },
                youtube: { label: 'YouTube', placeholder: 'https://youtube.com/@channel', icon: 'link' },
                website: { label: 'Website', placeholder: 'https://domain.com', icon: 'globe' }
            };

            function addChannelField(key) {
                const tpl = channelTemplates[key];
                if (!tpl || !contactChannels) return;
                const opt = channelSelect?.querySelector(`option[value="${key}"]`);
                if (opt) opt.disabled = true;

                const col = document.createElement('div');
                col.className = 'col-lg-4';
                col.dataset.channel = key;
                col.innerHTML = `
                    <div class="mb-2">
                        <label class="form-label">${tpl.label}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-${tpl.icon}"></i></span>
                            <input type="text" class="form-control" name="details[value][]" placeholder="${tpl.placeholder}">
                            <button type="button" class="btn btn-outline-danger" data-remove>Hapus</button>
                        </div>
                        <input type="hidden" name="details[label][]" value="${tpl.label}">
                    </div>
                `;
                const removeBtn = col.querySelector('[data-remove]');
                removeBtn?.addEventListener('click', () => {
                    col.remove();
                    if (opt) opt.disabled = false;
                });
                contactChannels.appendChild(col);
            }

            channelSelect?.addEventListener('change', (e) => {
                const key = e.target.value;
                if (key) {
                    addChannelField(key);
                    channelSelect.value = '';
                }
            });

        </script>
    @endpush
@endsection
