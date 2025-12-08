<!-- filepath: d:\laragon\www\Si-CRM\resources\views\pages\products\create.blade.php -->
<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createProductForm" method="POST" action="{{ route('products.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" required value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="base_price" class="form-label">Harga Dasar <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('base_price') is-invalid @enderror"
                                    id="base_price" name="base_price" step="0.01" min="0" required
                                    value="{{ old('base_price') }}">
                                @error('base_price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="photo_path" class="form-label">Foto Produk</label>
                        <div id="photo-preview" class="mb-2" style="max-width: 150px; display: none;">
                            <img id="preview-img" src="" alt="Preview"
                                style="max-width: 100%; border-radius: 4px;">
                        </div>
                        <input type="file" class="form-control @error('photo_path') is-invalid @enderror"
                            id="photo_path" name="photo_path" accept="image/*">
                        <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF (Max 2MB)</small>
                        @error('photo_path')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                            checked>
                        <label class="form-check-label" for="is_active">
                            Aktifkan Produk
                        </label>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Detail Produk (Opsional)</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-detail">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Detail
                            </button>
                        </div>
                        <small class="text-muted d-block mb-2">Tambahkan label dan nilai untuk detail produk (misal:
                            Warna, Ukuran, dll)</small>

                        <div id="product-details-container">
                            <!-- Detail rows akan ditambahkan di sini -->
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let detailCount = 0;
            const createModal = document.getElementById('createProductModal');

            // Preview foto saat file dipilih
            document.getElementById('photo_path').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('photo-preview');
                const previewImg = document.getElementById('preview-img');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });

            // Tambah detail row
            document.getElementById('btn-add-detail').addEventListener('click', function() {
                addDetailRow();
            });

            function addDetailRow(label = '', value = '') {
                const container = document.getElementById('product-details-container');
                const row = document.createElement('div');
                row.className = 'row mb-2 detail-row';
                row.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm" name="details[${detailCount}][label]" placeholder="Label (misal: Warna)" value="${label}">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" name="details[${detailCount}][value]" placeholder="Nilai" value="${value}">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger btn-remove-detail">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
                container.appendChild(row);
                detailCount++;

                // Event listener untuk tombol hapus
                row.querySelector('.btn-remove-detail').addEventListener('click', function() {
                    row.remove();
                });
            }

            // Hapus detail row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-detail')) {
                    e.preventDefault();
                    e.target.closest('.detail-row').remove();
                }
            });

            // Submit form
            document.getElementById('createProductForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const submitBtn = document.querySelector('#createProductModal button[type="submit"]');
                const submitText = submitBtn.textContent;

                // Disable button dan tampilkan loader
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.href = response.url || '{{ route('products.index') }}';
                        } else {
                            return response.text().then(html => {
                                alert('Ada error dalam form. Silakan cek kembali.');
                                console.error(html);
                                // Reset button
                                submitBtn.disabled = false;
                                submitBtn.textContent = submitText;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                        // Reset button
                        submitBtn.disabled = false;
                        submitBtn.textContent = submitText;
                    });
            });

            // Reset form dan clear preview saat modal ditutup
            createModal.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('createProductForm');
                form.reset();
                document.getElementById('photo-preview').style.display = 'none';
                const detailContainer = document.getElementById('product-details-container');
                detailContainer.innerHTML = '';
                detailCount = 0;
            });
        });
    </script>
@endpush
