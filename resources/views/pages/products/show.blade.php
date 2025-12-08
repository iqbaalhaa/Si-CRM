<div class="modal fade" id="showProductModal" tabindex="-1" role="dialog" aria-labelledby="showProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4 text-center">
                        <img id="show_photo" src="" alt="Foto Produk"
                            style="max-width: 100%; border-radius: 4px;">
                    </div>
                    <div class="col-md-8">
                        <div class="mb-2">
                            <label class="fw-bold">Nama Produk:</label>
                            <p id="show_name"></p>
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold">Harga Dasar:</label>
                            <p id="show_base_price"></p>
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold">Status:</label>
                            <p id="show_status"></p>
                        </div>
                        <div class="mb-2">
                            <label class="fw-bold">Deskripsi:</label>
                            <p id="show_description" style="white-space: pre-wrap;"></p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h6 class="mb-2">Detail Produk</h6>
                    <div id="show_details_container">
                        <p class="text-muted">-</p>
                    </div>
                </div>

                <hr>

                <div class="row text-muted small">
                    <div class="col-md-6">
                        <p><strong>Dibuat:</strong> <span id="show_created_at"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Diupdate:</strong> <span id="show_updated_at"></span></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showModal = document.getElementById('showProductModal');
            const storageBase = '{{ rtrim(asset('storage'), '/') }}';

            showModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const data = {
                    name: button.getAttribute('data-name') || '-',
                    base_price: button.getAttribute('data-base_price') || '0',
                    description: button.getAttribute('data-description') || '-',
                    photo_url: button.getAttribute('data-photo_url') || '',
                    photo_path: button.getAttribute('data-photo_path') || '',
                    is_active: button.getAttribute('data-is_active') == '1',
                    detailsJson: button.getAttribute('data-details') || '[]',
                    created_at: button.getAttribute('data-created_at') || '-',
                    updated_at: button.getAttribute('data-updated_at') || '-',
                };

                // Set fields
                showModal.querySelector('#show_name').textContent = data.name;
                showModal.querySelector('#show_base_price').textContent = 'Rp ' + parseInt(data.base_price)
                    .toLocaleString('id-ID');
                showModal.querySelector('#show_description').textContent = data.description;
                showModal.querySelector('#show_status').innerHTML = data.is_active ?
                    '<span class="badge bg-success">Aktif</span>' :
                    '<span class="badge bg-secondary">Nonaktif</span>';
                showModal.querySelector('#show_created_at').textContent = data.created_at;
                showModal.querySelector('#show_updated_at').textContent = data.updated_at;

                // Set photo: build URL from storage + photo_path; fallback to placeholder
                const photoImg = showModal.querySelector('#show_photo');
                if (data.photo_path) {
                    photoImg.src = storageBase + '/' + data.photo_path;
                    photoImg.onerror = function() {
                        this.src = '{{ asset('images/no-image.png') }}';
                    };
                    photoImg.style.display = 'block';
                } else {
                    photoImg.src = '{{ asset('images/no-image.png') }}';
                    photoImg.style.display = 'block';
                }

                // Set details
                const detailsContainer = showModal.querySelector('#show_details_container');
                let details = [];
                try {
                    const parsed = JSON.parse(data.detailsJson);
                    details = Array.isArray(parsed) ? parsed : [];
                } catch (e) {
                    details = [];
                }

                if (details.length > 0) {
                    detailsContainer.innerHTML = '';
                    details.forEach(d => {
                        const row = document.createElement('div');
                        row.className = 'mb-2';
                        row.innerHTML = `
                            <strong>${d.label || '-'}:</strong> ${d.value || '-'}
                        `;
                        detailsContainer.appendChild(row);
                    });
                } else {
                    detailsContainer.innerHTML = '<p class="text-muted">-</p>';
                }
            });
        });
    </script>
@endpush
