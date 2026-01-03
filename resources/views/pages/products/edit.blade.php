<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editProductForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_product_id" name="product_id" value="">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga Dasar <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_base_price" name="base_price"
                                    step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Produk</label>
                        <div id="photo-preview" class="mb-2" style="max-width: 150px; display: none;">
                            <img id="preview-img" src="" alt="Preview"
                                style="max-width: 100%; border-radius: 4px;">
                        </div>
                        <input type="file" class="form-control" id="edit_photo_path" name="photo_path"
                            accept="image/*">
                        <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF (Max 2MB). Biarkan kosong jika
                            tidak ingin mengubah.</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active"
                            value="1">
                        <label class="form-check-label" for="edit_is_active">Aktifkan Produk</label>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Detail Produk</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="edit-btn-add-detail">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Detail
                            </button>
                        </div>
                        <div id="edit-product-details-container">
                            <!-- rows akan ditambahkan di sini -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-edit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editDetailCount = 0;

            // Preview foto saat file dipilih
            document.getElementById('edit_photo_path').addEventListener('change', function(e) {
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

            function addDetailRowEdit(label = '', value = '', id = null) {
                const container = document.getElementById('edit-product-details-container');
                const row = document.createElement('div');
                row.className = 'row mb-2 detail-row';
                row.innerHTML = `
                    ${id ? `<input type="hidden" name="details[${editDetailCount}][id]" value="${id}">` : ''}
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" name="details[${editDetailCount}][label]" placeholder="Label (misal: Warna)" value="${label ?? ''}">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-sm" name="details[${editDetailCount}][value]" placeholder="Nilai" value="${value ?? ''}">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-detail">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                container.appendChild(row);
                editDetailCount++;

                row.querySelector('.btn-remove-detail').addEventListener('click', function() {
                    row.remove();
                });
            }

            document.getElementById('edit-btn-add-detail').addEventListener('click', function() {
                addDetailRowEdit();
            });

            const editModal = document.getElementById('editProductModal');
            const storageBase = '{{ rtrim(asset('storage'), '/') }}';
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id') || '';
                const name = button.getAttribute('data-name') || '';
                const base_price = button.getAttribute('data-base_price') || '';
                const description = button.getAttribute('data-description') || '';
                const photo_path = button.getAttribute('data-photo_path') || '';
                const is_active = button.getAttribute('data-is_active') == '1';
                const detailsJson = button.getAttribute('data-details') || '[]';

                // set form action
                const form = editModal.querySelector('form');
                form.action = '/products/' + id;

                // set fields
                editModal.querySelector('#edit_product_id').value = id;
                editModal.querySelector('#edit_name').value = name;
                editModal.querySelector('#edit_base_price').value = base_price;
                editModal.querySelector('#edit_description').value = description;
                editModal.querySelector('#edit_is_active').checked = !!is_active;
                editModal.querySelector('#edit_photo_path').value = '';

                // tampilkan preview foto existing
                const photoPreview = editModal.querySelector('#photo-preview');
                const previewImg = editModal.querySelector('#preview-img');
                if (photo_path) {
                    previewImg.src = storageBase + '/' + photo_path;
                    previewImg.onerror = function() {
                        this.src = '{{ asset('images/no-image.png') }}';
                    };
                    photoPreview.style.display = 'block';
                } else {
                    photoPreview.style.display = 'none';
                }

                // populate details
                const container = editModal.querySelector('#edit-product-details-container');
                container.innerHTML = '';
                editDetailCount = 0;

                let details = [];
                try {
                    const parsed = JSON.parse(detailsJson);
                    details = Array.isArray(parsed) ? parsed : [];
                } catch (e) {
                    console.error('Error parsing details JSON:', e, detailsJson);
                    details = [];
                }

                if (details.length > 0) {
                    details.forEach(d => {
                        addDetailRowEdit(d.label || '', d.value || '', d.id || null);
                    });
                }
            });

            // form submission
            document.getElementById('editProductForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const submitBtn = document.getElementById('btn-submit-edit');
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
                            window.location.href = '{{ route('products.index') }}';
                        } else {
                            return response.text().then(() => {
                                alert('Ada error dalam form. Silakan cek kembali.');
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

            // clear on hide
            editModal.addEventListener('hidden.bs.modal', function() {
                const container = editModal.querySelector('#edit-product-details-container');
                container.innerHTML = '';
                editDetailCount = 0;
                editModal.querySelector('form').reset();
                document.getElementById('photo-preview').style.display = 'none';
            });
        });
    </script>
@endpush
