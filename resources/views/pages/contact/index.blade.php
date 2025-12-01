@extends('layouts.master')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Kontak</h3>
            <p class="text-muted mb-0">Pilih tipe kontak terlebih dahulu, lalu isi form.</p>
        </div>
    </div>

    <div class="page-content">
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pilih Tipe Kontak</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check border rounded p-3 h-100">
                                    <input class="form-check-input" type="radio" name="type_select" id="type_company" value="company">
                                    <label class="form-check-label ms-2" for="type_company">
                                        <i class="bi bi-building-fill me-1"></i> Perusahaan
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check border rounded p-3 h-100">
                                    <input class="form-check-input" type="radio" name="type_select" id="type_individual" value="individual">
                                    <label class="form-check-label ms-2" for="type_individual">
                                        <i class="bi bi-person-fill me-1"></i> Individu
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check border rounded p-3 h-100">
                                    <input class="form-check-input" type="radio" name="type_select" id="type_organization" value="organization">
                                    <label class="form-check-label ms-2" for="type_organization">
                                        <i class="bi bi-collection-fill me-1"></i> Organisasi
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="small text-muted mt-2">Tipe yang dipilih akan menentukan label pada form.</div>
                    </div>
                </div>
            </div>

            <div class="col-12" id="contactFormWrapper" style="display:none">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Form Kontak</h5>
                        <a href="{{ route('contact.index') }}" class="btn btn-secondary">Reset Pilihan</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                            @csrf
                            <input type="hidden" name="type" id="type_hidden" value="{{ old('type') }}">

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{ old('name') }}" required>
                                        <label for="name" id="name_label">Nama</label>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" name="phone" class="form-control" placeholder="Telepon" value="{{ old('phone') }}">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('contact.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const typeRadios = document.querySelectorAll('input[name="type_select"]');
            const formWrapper = document.getElementById('contactFormWrapper');
            const typeHidden = document.getElementById('type_hidden');
            const nameLabel = document.getElementById('name_label');

            function updateLabel(type) {
                if (type === 'company') nameLabel.textContent = 'Nama Perusahaan';
                else if (type === 'individual') nameLabel.textContent = 'Nama Individu';
                else if (type === 'organization') nameLabel.textContent = 'Nama Organisasi';
                else nameLabel.textContent = 'Nama';
            }

            typeRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    formWrapper.style.display = 'block';
                    typeHidden.value = radio.value;
                    updateLabel(radio.value);
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
                }
            }
        </script>
    @endpush
@endsection
