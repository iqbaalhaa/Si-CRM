{{-- resources/views/pages/tasks/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Tasks & To-Do')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Tasks & To-Do</h3>
            <p class="text-muted mb-0">
                Kelola tugas follow up dan PR harian tim supaya tidak ada lead yang terlewat.
            </p>
        </div>
        <div class="text-end">
            <button class="btn btn-outline-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-task">
                <i class="bi bi-plus-lg me-1"></i>Tambah Task
            </button>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            {{-- RINGKASAN TASK (FULL WIDTH, mirip Ringkasan Aktivitas) --}}
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0">Ringkasan Task</h6>
                            <small class="text-muted">Periode: keseluruhan</small>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-2 rounded border small h-100">
                                    <div class="text-muted mb-1">Total Task</div>
                                    <div class="fw-bold fs-5">72</div>
                                    <div class="small text-muted">Semua status</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-2 rounded border small h-100">
                                    <div class="text-muted mb-1">Task Terbuka</div>
                                    <div class="fw-bold fs-5">31</div>
                                    <div class="small text-muted">Open & In Progress</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-2 rounded border small h-100">
                                    <div class="text-muted mb-1">Overdue</div>
                                    <div class="fw-bold fs-5 text-danger">7</div>
                                    <div class="small text-muted">Lewat jatuh tempo</div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="p-2 rounded border small h-100">
                                    <div class="text-muted mb-1">Selesai</div>
                                    <div class="fw-bold fs-5 text-success">41</div>
                                    <div class="small text-muted">Closed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER & LIST TASK --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- FILTER BAR SEDERHANA --}}
                        <form id="form-filter-tasks" action="javascript:void(0)" class="row g-2 align-items-end mb-3">
                            <div class="col-md-4">
                                <label class="form-label mb-1">Cari task</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Judul task, contact, campaign...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1">Status</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option>Open</option>
                                    <option>In Progress</option>
                                    <option>Done</option>
                                    <option>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Assign ke</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua PIC</option>
                                    <option>Saya sendiri</option>
                                    <option>Rifki Dermawan</option>
                                    <option>Marketing 1</option>
                                    <option>CS 1</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Campaign</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Semua campaign</option>
                                    <option>Webinar Magang Nasional Batch 3</option>
                                    <option>Smart Course - Promo Akhir Tahun</option>
                                    <option>Depati Akademi - Kelas Laravel Intensif</option>
                                    <option>Tidak terkait campaign</option>
                                </select>
                            </div>
                        </form>

                        {{-- LEGEND CAMPAIGN (SAMA DENGAN ACTIVITIES) --}}
                        <div class="mb-2">
                            <div class="d-flex flex-wrap align-items-center gap-3 small">
                                <span class="text-muted me-1">Legenda campaign:</span>
                                <span class="campaign-legend campaign-mag">
                                    <span class="legend-dot"></span> Webinar Magang Nasional
                                </span>
                                <span class="campaign-legend campaign-smart">
                                    <span class="legend-dot"></span> Smart Course - Promo Akhir Tahun
                                </span>
                                <span class="campaign-legend campaign-laravel">
                                    <span class="legend-dot"></span> Depati Akademi - Kelas Laravel Intensif
                                </span>
                                <span class="campaign-legend campaign-none">
                                    <span class="legend-dot"></span> Tanpa campaign
                                </span>
                            </div>
                        </div>

                        {{-- QUICK TAB: TASK SAYA / SEMUA TASK --}}
                        <ul class="nav nav-pills mb-3 small">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:void(0)">Task Saya</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)">Semua Task</a>
                            </li>
                        </ul>

                        {{-- LIST TASKS --}}
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 32px;"></th>
                                        <th>Task</th>
                                        <th>Terkait</th>
                                        <th>Campaign</th>
                                        <th>Due</th>
                                        <th>PIC</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ROW 1 --}}
                                    <tr class="task-row task-priority-high activity-campaign-mag">
                                        <td>
                                            <input type="checkbox" class="form-check-input">
                                        </td>
                                        <td>
                                            <div class="fw-semibold small">
                                                Follow up pembayaran Magang Nasional
                                            </div>
                                            <div class="text-muted small">
                                                Hubungi via WhatsApp untuk konfirmasi bukti transfer.
                                            </div>
                                        </td>
                                        <td class="small">
                                            Contact: <strong>Fathiya</strong>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-mag">
                                                Webinar Magang Nasional Batch 3
                                            </span>
                                        </td>
                                        <td class="small">
                                            <div>Hari ini</div>
                                            <div class="text-danger small">Overdue 3 jam</div>
                                        </td>
                                        <td class="small">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="avatar-circle">RD</span>
                                                <button class="btn btn-link btn-sm p-0 small text-muted"
                                                        type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-change-assignee">
                                                    Ubah
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm px-2 py-1 rounded-pill status-badge status-open"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-change-status">
                                                Open
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ROW 2 --}}
                                    <tr class="task-row task-priority-medium activity-campaign-smart">
                                        <td>
                                            <input type="checkbox" class="form-check-input">
                                        </td>
                                        <td>
                                            <div class="fw-semibold small">
                                                Kirim proposal paket Smart Course ke Bonafide
                                            </div>
                                            <div class="text-muted small">
                                                Email + lampiran PDF proposal, cc ke lead-operations.
                                            </div>
                                        </td>
                                        <td class="small">
                                            Customer: <strong>PT Bonafide Media Pos</strong>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-smart">
                                                Smart Course - Promo Akhir Tahun
                                            </span>
                                        </td>
                                        <td class="small">
                                            <div>Besok</div>
                                            <div class="text-muted small">Jam 16.00</div>
                                        </td>
                                        <td class="small">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="avatar-circle avatar-muted">M1</span>
                                                <button class="btn btn-link btn-sm p-0 small text-muted"
                                                        type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-change-assignee">
                                                    Ubah
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm px-2 py-1 rounded-pill status-badge status-progress"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-change-status">
                                                In Progress
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ROW 3 --}}
                                    <tr class="task-row task-priority-low activity-campaign-none">
                                        <td>
                                            <input type="checkbox" class="form-check-input">
                                        </td>
                                        <td>
                                            <div class="fw-semibold small">
                                                Susun list alumni potensial mentor
                                            </div>
                                            <div class="text-muted small">
                                                Filter dari contact alumni batch 1 dan tandai minat mentoring.
                                            </div>
                                        </td>
                                        <td class="small">
                                            Segmen: <strong>Alumni Depati Akademi</strong>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-none">
                                                Tidak terkait campaign
                                            </span>
                                        </td>
                                        <td class="small">
                                            <div>7 hari lagi</div>
                                            <div class="text-muted small">Fleksibel</div>
                                        </td>
                                        <td class="small">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="avatar-circle avatar-muted">SP</span>
                                                <button class="btn btn-link btn-sm p-0 small text-muted"
                                                        type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-change-assignee">
                                                    Ubah
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm px-2 py-1 rounded-pill status-badge status-open"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-change-status">
                                                Open
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- ROW 4 (Done) --}}
                                    <tr class="task-row task-priority-medium activity-campaign-mag">
                                        <td>
                                            <input type="checkbox" class="form-check-input" checked>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-muted text-decoration-line-through">
                                                Reminder jadwal webinar via email
                                            </div>
                                            <div class="text-muted small">
                                                Email sudah terkirim ke seluruh peserta.
                                            </div>
                                        </td>
                                        <td class="small">
                                            List: <strong>Peserta Terdaftar Magang</strong>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-mag">
                                                Webinar Magang Nasional Batch 3
                                            </span>
                                        </td>
                                        <td class="small">
                                            <div>2 hari lalu</div>
                                        </td>
                                        <td class="small">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="avatar-circle avatar-muted">RD</span>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm px-2 py-1 rounded-pill status-badge status-done"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal-change-status">
                                                Done
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>{{-- card-body --}}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: TAMBAH TASK --}}
    <div class="modal fade" id="modal-task" tabindex="-1" aria-labelledby="modalTaskLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTaskLabel">Tambah Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                {{-- PURE FRONTEND ONLY --}}
                <form id="form-create-task" action="javascript:void(0)" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">

                            {{-- ROW 1: JUDUL TASK + ASSIGN KE --}}
                            <div class="col-md-8">
                                <label class="form-label">Judul Task <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Contoh: Follow up pembayaran Magang Nasional">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Assign ke</label>
                                <select class="form-select form-select-sm">
                                    <option value="me">Saya sendiri</option>
                                    <option value="rifki">Rifki Dermawan</option>
                                    <option value="m1">Marketing 1</option>
                                    <option value="cs1">CS 1</option>
                                </select>
                                <div class="form-text small">
                                    Task akan muncul di daftar <strong>Task</strong> user yang dipilih.
                                </div>
                            </div>

                            {{-- ROW 2: PRIORITY & DUE DATE --}}
                            <div class="col-md-4">
                                <label class="form-label">Priority</label>
                                <select class="form-select form-select-sm">
                                    <option>Medium</option>
                                    <option>High</option>
                                    <option>Low</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                {{-- Optional kolom kosong / nanti bisa diisi Reminders --}}
                            </div>

                            {{-- ROW 3: TERKAIT CAMPAIGN (FULL WIDTH, PILL BUTTON) --}}
                            <div class="col-12">
                                <label class="form-label">Terkait Campaign</label>
                                <div class="d-flex flex-wrap gap-2 small js-campaign-selector">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary campaign-pill-btn active"
                                            data-value="">
                                        Tanpa campaign
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary campaign-pill-btn"
                                            data-value="magang">
                                        Webinar Magang Nasional Batch 3
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-warning campaign-pill-btn"
                                            data-value="smart">
                                        Smart Course - Promo Akhir Tahun
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger campaign-pill-btn"
                                            data-value="laravel">
                                        Depati Akademi - Kelas Laravel Intensif
                                    </button>
                                </div>
                                <input type="hidden" name="campaign_id" id="campaign_id" value="">
                                <div class="form-text small">
                                    Pilih satu context campaign utama untuk task ini.
                                </div>
                            </div>

                            {{-- GARIS PEMISAH --}}
                            <div class="col-12">
                                <hr class="mt-2 mb-1">
                            </div>

                            {{-- TERKAIT DENGAN APA (RELATION) --}}
                            <div class="col-12">
                                <label class="form-label">Terkait Dengan</label>
                                <div class="d-flex flex-wrap gap-3 small">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="task_related_type" id="task_rel_contact" checked>
                                        <label class="form-check-label" for="task_rel_contact">
                                            Contact
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="task_related_type" id="task_rel_customer">
                                        <label class="form-check-label" for="task_rel_customer">
                                            Customer / Deal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="task_related_type" id="task_rel_campaign">
                                        <label class="form-check-label" for="task_rel_campaign">
                                            Campaign saja
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="task_related_type" id="task_rel_general">
                                        <label class="form-check-label" for="task_rel_general">
                                            Umum (tanpa relasi)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- CONTACT / CUSTOMER INPUT (Select2 multiple) --}}
                            <div class="col-12">
                                <label class="form-label">Pilih Contact / Customer</label>
                                <select class="form-select form-select-sm js-select2-contacts" multiple style="width: 100%;">
                                    {{-- Nanti diisi dari backend / AJAX --}}
                                    <option value="1" selected>Fathiya (Contact)</option>
                                    <option value="2">PT Bonafide Media Pos (Customer)</option>
                                    <option value="3">Alumni Batch 1 (Segmen)</option>
                                </select>
                                <div class="form-text small">
                                    Bisa pilih lebih dari satu (misalnya beberapa peserta dalam satu follow up).
                                    Nanti gunakan <strong>Select2</strong> / autocomplete untuk pencarian cepat.
                                </div>
                            </div>

                            {{-- CATATAN TASK --}}
                            <div class="col-12">
                                <label class="form-label">Catatan / Instruksi</label>
                                <textarea class="form-control form-control-sm" rows="3"
                                        placeholder="Detail yang perlu dilakukan, skrip telepon, link dokumen, dll."></textarea>
                            </div>

                            {{-- OPSI SINKRON KE ACTIVITIES --}}
                            <div class="col-12">
                                <div class="form-check small">
                                    <input class="form-check-input" type="checkbox" id="task_auto_activity" checked>
                                    <label class="form-check-label" for="task_auto_activity">
                                        Setelah ditandai selesai, buat catatan di <strong>Activities</strong>.
                                    </label>
                                </div>
                            </div>

                        </div> {{-- .row --}}
                    </div> {{-- .modal-body --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- MODAL: GANTI ASSIGNEE (UI ASSIGN CEPAT DARI LIST) --}}
    <div class="modal fade" id="modal-change-assignee" tabindex="-1" aria-labelledby="modalChangeAssigneeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangeAssigneeLabel">Ubah PIC / Assign Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="javascript:void(0)" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="small text-muted mb-2">
                            Pilih user yang akan menjadi PIC utama untuk task ini.
                        </p>
                        <div class="mb-3">
                            <label class="form-label">Assign ke</label>
                            <select class="form-select form-select-sm">
                                <option value="me">Saya sendiri</option>
                                <option value="rifki">Rifki Dermawan</option>
                                <option value="m1">Marketing 1</option>
                                <option value="cs1">CS 1</option>
                            </select>
                        </div>
                        <div class="form-check small">
                            <input class="form-check-input" type="checkbox" id="notifyAssignee" checked>
                            <label class="form-check-label" for="notifyAssignee">
                                Kirim notifikasi ke user tersebut (mis. via email/WA).
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand btn-sm">
                            <i class="bi bi-arrow-repeat me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: UBAH STATUS TASK + CATATAN (LOG KE ACTIVITIES) --}}
    <div class="modal fade" id="modal-change-status" tabindex="-1" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangeStatusLabel">Ubah Status Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="javascript:void(0)" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2 small">
                            <div class="text-muted">Task:</div>
                            <div class="fw-semibold">
                                {{-- Nanti bisa diisi dinamis pake JS --}}
                                Follow up pembayaran Magang Nasional
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status baru</label>
                            <select class="form-select form-select-sm">
                                <option>Open</option>
                                <option>In Progress</option>
                                <option selected>Done</option>
                                <option>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan perubahan</label>
                            <textarea class="form-control form-control-sm" rows="2"
                                      placeholder="Contoh: Sudah dihubungi, konfirmasi transfer besok pagi."></textarea>
                        </div>

                        <div class="form-check small">
                            <input class="form-check-input" type="checkbox" id="logStatusToActivity" checked>
                            <label class="form-check-label" for="logStatusToActivity">
                                Catat perubahan ini ke <strong>Activities</strong>.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand btn-sm">
                            <i class="bi bi-check2-circle me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CSS ringan Tasks, reuse dari Activities supaya konsisten --}}
    <style>
        .task-row.task-priority-high {
            border-left: 3px solid rgba(220, 53, 69, 0.8);
        }
        .task-row.task-priority-medium {
            border-left: 3px solid rgba(255, 193, 7, 0.8);
        }
        .task-row.task-priority-low {
            border-left: 3px solid rgba(25, 135, 84, 0.7);
        }

        .status-badge {
            border: 0;
            font-size: 0.75rem;
        }

        .status-open {
            background-color: rgba(13, 110, 253, 0.08);
            color: #0d6efd;
        }
        .status-progress {
            background-color: rgba(102, 16, 242, 0.08);
            color: #6610f2;
        }
        .status-done {
            background-color: rgba(25, 135, 84, 0.08);
            color: #198754;
        }

        .campaign-legend {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
            background-color: rgba(0, 0, 0, 0.02);
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .campaign-mag .legend-dot {
            background-color: #2e65b7;
        }

        .campaign-smart .legend-dot {
            background-color: #f39c12;
        }

        .campaign-laravel .legend-dot {
            background-color: #c0392b;
        }

        .campaign-none .legend-dot {
            background-color: #6c757d;
        }

        .campaign-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
            font-size: 0.7rem;
            border: 1px solid transparent;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .campaign-pill.campaign-mag {
            background-color: rgba(46, 101, 183, 0.06);
            border-color: rgba(46, 101, 183, 0.25);
            color: #2e65b7;
        }

        .campaign-pill.campaign-smart {
            background-color: rgba(243, 156, 18, 0.06);
            border-color: rgba(243, 156, 18, 0.25);
            color: #e67e22;
        }

        .campaign-pill.campaign-laravel {
            background-color: rgba(192, 57, 43, 0.06);
            border-color: rgba(192, 57, 43, 0.25);
            color: #c0392b;
        }

        .campaign-pill.campaign-none {
            background-color: rgba(108, 117, 125, 0.04);
            border-color: rgba(108, 117, 125, 0.25);
            color: #6c757d;
        }

        .avatar-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            background-color: #0d6efd;
            color: #fff;
        }
        .avatar-circle.avatar-muted {
            background-color: #6c757d;
        }

        /* Campaign selector pills (modal tambah task) */
        .js-campaign-selector .btn.active {
            /* Bootstrap sudah kasih style active, ini cuma penguat */
            box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.15);
        }
        /* Campaign selector pills di modal */
        .campaign-pill-btn {
            border-radius: 999px;
            font-size: 0.75rem;
            padding-inline: 0.75rem;
        }
    
        .js-campaign-selector .campaign-pill-btn.active {
            box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.15);
        }
    
        /* Styling dasar Select2 multiple biar nyaru dengan form-control-sm */
        .select2-container--default .select2-selection--multiple {
            min-height: calc(1.5em + .5rem + 2px);
            border-color: #ced4da;
            border-radius: 0.25rem;
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
            font-size: .875rem;
        }
    
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            border-radius: 999px;
            padding: 0 0.5rem;
            margin-top: 0.15rem;
        }
    
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 0.25rem;
        }
    </style>
    
@endsection

@push('scripts')
    {{-- Kalau pakai Select2, pastikan JS-nya sudah dimuat sekali di layout.master --}}
    {{-- <script src="{{ asset('path/to/select2.min.js') }}"></script> --}}

    <script>
        (function () {
            // ========== TOGGLE CAMPAIGN PILL -> #campaign_id ==========
            const campaignButtons = document.querySelectorAll('#modal-task .js-campaign-selector .campaign-pill-btn');
            const campaignInput   = document.getElementById('campaign_id');

            if (campaignButtons.length && campaignInput) {
                campaignButtons.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        // hapus active di semua
                        campaignButtons.forEach(function (b) {
                            b.classList.remove('active');
                        });

                        // kasih active di yang diklik
                        this.classList.add('active');

                        // set value hidden input
                        const val = this.getAttribute('data-value') || '';
                        campaignInput.value = val;
                    });
                });
            }

            // ========== INIT SELECT2 UNTUK PILIH CONTACT / CUSTOMER ==========
            // pastikan jQuery & Select2 sudah ada
            if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                $('.js-select2-contacts').select2({
                    placeholder: 'Cari contact / customer...',
                    width: '100%',
                    dropdownParent: $('#modal-task') // penting supaya dropdown nggak keluar modal
                });
            }
        })();
    </script>
@endpush
