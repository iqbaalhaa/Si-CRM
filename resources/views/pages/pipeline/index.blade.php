@extends('layouts.master')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1">Pipeline</h3>
        </div>
    </div>

    <div class="page-content">
        <div class="row g-3 align-items-stretch">

            @php
                $user = auth()->user();
                $canCreatePipeline = $user->can('create pipelines');
                $canUpdatePipeline = $user->can('update pipelines');
                $canDeletePipeline = $user->can('delete pipelines');
                $isLeadOperations = $user->hasRole(['lead-operations']);
                $showActions = ($canUpdatePipeline || $canDeletePipeline) && !$isLeadOperations;
                $isEditable = $canUpdatePipeline && !$isLeadOperations;
                $orderedStages = $stages->sortBy(fn($s) => $s->sort_order ?? 0)->values();
            @endphp

            {{-- LEFT: Create form --}}
            @if ($canCreatePipeline && !$isLeadOperations)
                <div class="col-12 col-lg-4">
                    <div class="card card-soft h-100 equal-card">
                        <div class="card-header">
                            <div class="fw-semibold">Buat Pipeline Stage</div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pipeline-stages.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Stage <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Stage</label>
                                    <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                                        <option value="">Pilih tipe (opsional)</option>
                                        <option value="lead" {{ old('type') === 'lead' ? 'selected' : '' }}>Lead</option>
                                        <option value="prospect" {{ old('type') === 'prospect' ? 'selected' : '' }}>Prospect</option>
                                        <option value="negotiation" {{ old('type') === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                        <option value="won" {{ old('type') === 'won' ? 'selected' : '' }}>Won</option>
                                        <option value="lost" {{ old('type') === 'lost' ? 'selected' : '' }}>Lost</option>
                                    </select>
                                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Urutan</label>
                                    <input type="number" id="sort_order" name="sort_order"
                                           class="form-control @error('sort_order') is-invalid @enderror"
                                           value="{{ old('sort_order') }}" min="0">
                                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Simpan Stage
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- RIGHT: Premium board --}}
            <div class="col-12 {{ $canCreatePipeline && !$isLeadOperations ? 'col-lg-8' : 'col-lg-12' }}">
                <div class="card card-soft h-100 equal-card">
                    <div class="card-header">
                        <div class="fw-semibold">Stage Board</div>
                    </div>

                    <div class="card-body">
                        @if ($orderedStages->isEmpty())
                            <div class="empty-box">
                                <div class="fw-semibold mb-1">Belum ada stage pipeline</div>
                            </div>
                        @else
                            {{-- Toolbar --}}
                            <div class="board-toolbar mb-3">
                                <div class="board-search">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="stageSearch" class="form-control form-control-sm"
                                           placeholder="Cari stage... (nama / tipe / perusahaan)">
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <select id="typeFilter" class="form-select form-select-sm board-filter">
                                        <option value="">Semua tipe</option>
                                        <option value="lead">Lead</option>
                                        <option value="prospect">Prospect</option>
                                        <option value="negotiation">Negotiation</option>
                                        <option value="won">Won</option>
                                        <option value="lost">Lost</option>
                                        <option value="-">Tanpa tipe</option>
                                    </select>

                                    @if($isEditable)
                                        <button type="button" class="btn btn-primary btn-sm" id="btnSaveOrder">
                                            <i class="bi bi-save me-1"></i> Simpan Urutan
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Stage list (draggable) --}}
                            <div id="stageList" class="stage-list {{ $isEditable ? '' : 'is-readonly' }}">
                                @foreach ($orderedStages as $idx => $stage)
                                    @php
                                        $type = $stage->type ?? '-';
                                        $pill = match ($type) {
                                            'lead' => 'primary',
                                            'prospect' => 'info',
                                            'negotiation' => 'warning',
                                            'won' => 'success',
                                            'lost' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <div class="stage-card"
                                         data-id="{{ $stage->id }}"
                                         data-name="{{ strtolower($stage->name) }}"
                                         data-type="{{ strtolower($type) }}"
                                         data-company="{{ strtolower(optional($stage->company)->name ?? '-') }}">

                                        <div class="stage-left">
                                            <div class="stage-title">
                                                <span class="drag-handle {{ $isEditable ? '' : 'disabled' }}" title="Drag untuk pindah urutan">
                                                    <i class="bi bi-grip-vertical"></i>
                                                </span>
                                                <span class="fw-semibold">{{ $stage->name }}</span>

                                                @if($stage->is_default)
                                                    <span class="badge bg-success-soft ms-2">
                                                        <i class="bi bi-star-fill me-1"></i> Default
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="stage-meta">
                                                <span class="badge bg-{{ $pill }} badge-soft">{{ $type }}</span>
                                                <span class="meta-dot">•</span>
                                                <span class="text-muted small">
                                                    <i class="bi bi-building me-1"></i> {{ optional($stage->company)->name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="stage-right">
                                            <span class="order-pill" title="Urutan saat ini">#<span class="order-num">{{ $idx }}</span></span>

                                            @if ($showActions)
                                                <div class="stage-actions">
                                                    @if ($canDeletePipeline)
                                                        <form action="{{ route('pipeline-stages.destroy', $stage->id) }}"
                                                              method="POST" class="d-inline pipeline-delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-icon btn-icon-danger" title="Hapus stage">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-soft{
            border-radius:16px;
            border:1px solid #e5e7eb !important;
            background:#ffffff;
            box-shadow:0 10px 25px rgba(15,23,42,.04);
        }
        .card-soft .card-header{
            border-bottom:1px solid #e5e7eb !important;
            font-weight:600;
            padding:.5rem .75rem;
            min-height:44px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            background:transparent;
        }
        .card-soft .card-header .fw-semibold{ font-size:.9rem; line-height:1.2; }
        .card-soft .card-header .sub-text{
            font-size:.78rem;
            color:var(--bs-secondary-color);
            font-weight:400;
        }
        .form-hint{
            font-size:.78rem;
            color:var(--bs-secondary-color);
            margin-top:.35rem;
        }
        .card-soft .form-label{
            margin-bottom:.5rem;
            display:inline-block;
        }
        .empty-box{
            border:1px dashed var(--bs-border-color);
            border-radius:12px;
            padding:1rem;
            background:var(--bs-secondary-bg);
        }
        .equal-card{
            min-height: clamp(280px, 34vh, 480px);
            display:flex;
            flex-direction:column;
        }
        .equal-card .card-body{
            flex:1;
        }
        .card-soft .card-body{
            padding-top:1rem !important;
        }

        .board-toolbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            flex-wrap:wrap;
        }
        .board-search{
            position:relative;
            display:flex;
            align-items:center;
            gap:.55rem;
            padding:.55rem .75rem;
            border-radius:14px;
            border:1px solid var(--bs-border-color);
            background:var(--bs-secondary-bg);
            min-width:260px;
            flex:1;
            max-width:520px;
        }
        .board-search i{ color: var(--bs-secondary-color); }
        .board-search input{
            border:none;
            background:transparent;
            outline:none;
            padding:0;
            box-shadow:none !important;
        }
        .board-filter{ border-radius:12px; min-width:160px; }

        .stage-list{
            display:flex;
            flex-direction:column;
            gap:.6rem;
        }
        .stage-card{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            padding:.85rem .95rem;
            border-radius:16px;
            border:1px solid var(--bs-border-color);
            background:var(--bs-body-bg);
            box-shadow:0 10px 22px rgba(15,23,42,.04);
            transition:transform .14s ease, box-shadow .14s ease, border-color .14s ease;
        }
        .stage-card:hover{
            transform:translateY(-1px);
            box-shadow:0 16px 30px rgba(15,23,42,.08);
            border-color:var(--bs-primary);
        }

        .stage-left{ display:flex; flex-direction:column; gap:.35rem; min-width:0; }
        .stage-title{
            display:flex;
            align-items:center;
            gap:.55rem;
            flex-wrap:wrap;
        }

        .drag-handle{
            width:34px;height:34px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:12px;
            border:1px solid var(--bs-border-color);
            background:var(--bs-secondary-bg);
            color:var(--bs-body-color);
            cursor:grab;
            user-select:none;
        }
        .drag-handle:active{ cursor:grabbing; }
        .drag-handle.disabled{
            opacity:.5;
            cursor:not-allowed;
        }

        .stage-meta{
            display:flex;
            align-items:center;
            gap:.55rem;
            flex-wrap:wrap;
        }
        .meta-dot{ color: var(--bs-secondary-color); }

        .badge-soft{
            border-radius:999px;
            padding:.35rem .65rem;
            font-weight:600;
        }
        .bg-success-soft{
            background: var(--bs-success-bg-subtle) !important;
            border:1px solid var(--bs-success-border-subtle);
            color: var(--bs-success-text-emphasis) !important;
            border-radius:999px;
            padding:.25rem .55rem;
            font-weight:700;
            font-size:.75rem;
        }

        .stage-right{
            display:flex;
            align-items:center;
            gap:.6rem;
            flex-shrink:0;
        }
        .order-pill{
            border-radius:999px;
            border:1px solid var(--bs-border-color);
            background:var(--bs-secondary-bg);
            padding:.25rem .6rem;
            font-size:.78rem;
            font-weight:700;
            color:var(--bs-body-color);
        }

        .btn-icon{
            width:36px;height:36px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:12px;
            border:1px solid var(--bs-border-color);
            background:var(--bs-secondary-bg);
            color:var(--bs-body-color);
            transition:transform .12s ease, background .12s ease, border-color .12s ease;
        }
        .btn-icon:hover{
            transform:translateY(-1px);
            border-color:var(--bs-primary);
            background:var(--bs-tertiary-bg);
        }
        .btn-icon-danger{
            border-color:var(--bs-danger);
            background:var(--bs-danger-bg-subtle);
        }
        .btn-icon-danger:hover{
            border-color:var(--bs-danger);
            background:var(--bs-tertiary-bg);
        }

        .sortable-ghost{
            opacity:.65;
            transform:scale(.995);
        }
        .sortable-chosen{
            box-shadow:0 22px 44px rgba(15,23,42,.14);
            border-color:var(--bs-primary);
        }
        .stage-list.is-readonly .stage-card{ cursor:default; }

        @media (max-width: 575.98px){
            .board-search{ min-width: 100%; }
            .stage-card{ align-items:flex-start; }
            .stage-right{ margin-top:.25rem; }
        }
    </style>
@endpush

@push('scripts')
    {{-- SortableJS (drag & drop) --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        (function(){
            const isEditable = @json($isEditable);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const listEl = document.getElementById('stageList');
            const searchEl = document.getElementById('stageSearch');
            const filterEl = document.getElementById('typeFilter');
            const btnSave = document.getElementById('btnSaveOrder');

            function refreshOrderBadges(){
                if(!listEl) return;
                const items = Array.from(listEl.querySelectorAll('.stage-card'));
                items.forEach((el, idx) => {
                    const num = el.querySelector('.order-num');
                    if(num) num.textContent = idx; // 0-based sesuai kebiasaan sort_order min 0
                });
            }

            function applyFilters(){
                if(!listEl) return;
                const q = (searchEl?.value || '').trim().toLowerCase();
                const type = (filterEl?.value || '').trim().toLowerCase();

                Array.from(listEl.querySelectorAll('.stage-card')).forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const t = card.getAttribute('data-type') || '';
                    const comp = card.getAttribute('data-company') || '';

                    const hitQuery = !q || name.includes(q) || t.includes(q) || comp.includes(q);
                    const hitType = !type || (type === '-' ? (t === '-' || t === '') : t === type);

                    card.style.display = (hitQuery && hitType) ? '' : 'none';
                });
            }

            if(searchEl) searchEl.addEventListener('input', applyFilters);
            if(filterEl) filterEl.addEventListener('change', applyFilters);

            if(listEl && isEditable){
                new Sortable(listEl, {
                    animation: 160,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function(){
                        refreshOrderBadges();
                    }
                });
            }

            async function saveOrder(){
                if(!listEl) return;

                const ids = Array.from(listEl.querySelectorAll('.stage-card'))
                    .map(el => el.getAttribute('data-id'))
                    .filter(Boolean);

                if(ids.length === 0) return;

                const result = await Swal.fire({
                    icon: 'question',
                    title: 'Simpan urutan stage?',
                    text: 'Urutan akan diperbarui sesuai drag & drop.',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal'
                });
                if(!result.isConfirmed) return;

                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try{
                    // update satu-per-satu mengikuti backend kamu (PUT /pipeline-stages/{id})
                    for(let i=0; i<ids.length; i++){
                        const id = ids[i];
                        const res = await fetch(`{{ url('/pipeline-stages') }}/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams({
                                _token: csrfToken,
                                sort_order: i
                            })
                        });

                        if(!res.ok) throw new Error('Failed at id=' + id);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Urutan stage sudah diperbarui.'
                    });
                }catch(e){
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Tidak dapat menyimpan perubahan urutan.'
                    });
                }
            }

            if(btnSave){
                btnSave.addEventListener('click', saveOrder);
            }

            // Delete confirm (tetap)
            document.addEventListener('submit', function(e){
                const form = e.target;
                if(!form.classList.contains('pipeline-delete-form')) return;

                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus stage?',
                    text: 'Tindakan tidak dapat dibatalkan',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33'
                }).then((r) => {
                    if(r.isConfirmed) form.submit();
                });
            });

            refreshOrderBadges();
        })();
    </script>
@endpush
