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
                        <label class="form-label">Foto Produk (URL)</label>
                        <input type="text" class="form-control" id="edit_photo_path" name="photo_path">
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
                            <!-- rows -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editDetailCount = 0;

            function addDetailRowEdit(label = '', value = '') {
                const container = document.getElementById('edit-product-details-container');
                const row = document.createElement('div');
                row.className = 'row mb-2 detail-row';
                row.innerHTML = `
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

                // set form action (adjust path if your route prefix differs)
                const form = editModal.querySelector('form');
                form.action = '/products/' + id;

                // set fields
                editModal.querySelector('#edit_product_id').value = id;
                editModal.querySelector('#edit_name').value = name;
                editModal.querySelector('#edit_base_price').value = base_price;
                editModal.querySelector('#edit_description').value = description;
                editModal.querySelector('#edit_photo_path').value = photo_path;
                editModal.querySelector('#edit_is_active').checked = !!is_active;

                // populate details
                const container = editModal.querySelector('#edit-product-details-container');
                container.innerHTML = '';
                editDetailCount = 0;
                let details = [];
                try {
                    details = JSON.parse(detailsJson);
                    if (!Array.isArray(details)) details = [];
                } catch (e) {
                    details = [];
                }
                details.forEach(d => {
                    addDetailRowEdit(d.label ?? '', d.value ?? '');
                });
            });

            // optional: clear on hide
            editModal.addEventListener('hidden.bs.modal', function() {
                const container = editModal.querySelector('#edit-product-details-container');
                container.innerHTML = '';
                editDetailCount = 0;
                editModal.querySelector('form').reset();
            });
        });
    </script>
@endpush
