{{-- resources/views/pages/tasks/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Tasks & To-Do')

@section('content')
    <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3>Tasks & To-Do</h3>
            <p class="text-muted mb-0">
                Kelola tugas follow up, pengiriman proposal, dan PR lain agar tim tidak ada yang terlewat.
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
            {{-- RINGKASAN TASK --}}
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

            {{-- FILTER & LIST --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- FILTER BAR SEDERHANA --}}
                        <form id="form-filter-tasks" action="javascript:void(0)" class="row g-2 align-items-end mb-3">
                            <div class="col-md-4">
                                <label class="form-label mb-1">Cari task</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Judul task, contact, campaign...">
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-brand btn-sm flex-grow-1">
                                    <i class="bi bi-funnel-fill me-1"></i>Filter
                                </button>
                                <button type="button" class="btn btn-light btn-sm"
                                        onclick="document.getElementById('form-filter-tasks').reset()">
                                    Reset
                                </button>
                            </div>
                        </form>

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
                                        <th>Relasi</th>
                                        <th>Campaign</th>
                                        <th>Priority</th>
                                        <th>Due Date</th>
                                        <th>PIC</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ROW 1 --}}
                                    <tr class="task-row task-priority-high">
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
                                            <div>Contact: <strong>Fathiya</strong></div>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-mag">
                                                Webinar Magang Nasional Batch 3
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill priority-high">High</span>
                                        </td>
                                        <td class="small">
                                            <div>Hari ini</div>
                                            <div class="text-danger small">Overdue 3 jam</div>
                                        </td>
                                        <td class="small">
                                            {{-- UI assign: badge + ikon ganti --}}
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
                                            <span class="badge rounded-pill status-open">Open</span>
                                        </td>
                                    </tr>

                                    {{-- ROW 2 --}}
                                    <tr class="task-row task-priority-medium">
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
                                            <div>Customer: <strong>PT Bonafide Media Pos</strong></div>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-smart">
                                                Smart Course - Promo Akhir Tahun
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill priority-medium">Medium</span>
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
                                            <span class="badge rounded-pill status-progress">In Progress</span>
                                        </td>
                                    </tr>

                                    {{-- ROW 3 --}}
                                    <tr class="task-row task-priority-low">
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
                                            <div>Segmen: <strong>Alumni Depati Akademi</strong></div>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-none">
                                                Tidak terkait campaign
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill priority-low">Low</span>
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
                                            <span class="badge rounded-pill status-open">Open</span>
                                        </td>
                                    </tr>

                                    {{-- ROW 4 (Done) --}}
                                    <tr class="task-row task-priority-medium">
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
                                            <div>List: <strong>Peserta Terdaftar Magang</strong></div>
                                        </td>
                                        <td class="small">
                                            <span class="campaign-pill campaign-mag">
                                                Webinar Magang Nasional Batch 3
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill priority-medium">Medium</span>
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
                                            <span class="badge rounded-pill status-done">Done</span>
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

                            {{-- JUDUL TASK --}}
                            <div class="col-12">
                                <label class="form-label">Judul Task <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="Contoh: Follow up pembayaran Magang Nasional">
                            </div>

                            {{-- PRIORITY & DUE DATE --}}
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

                            {{-- ASSIGN KE (INI UI ASSIGN) --}}
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

                            <hr class="mt-3 mb-1">

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
                                            Campaign
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

                            {{-- CONTACT / CUSTOMER INPUT --}}
                            <div class="col-md-6">
                                <label class="form-label">Pilih Contact / Customer</label>
                                <input type="text" class="form-control form-control-sm"
                                       placeholder="Cari nama contact / customer...">
                                <div class="form-text small">
                                    Nanti dihubungkan ke master Contact / Customers.
                                </div>
                            </div>

                            {{-- CAMPIGN SELECT (HUBUNGAN DENGAN CAMPAIGN) --}}
                            <div class="col-md-6">
                                <label class="form-label">Terkait Campaign</label>
                                <select class="form-select form-select-sm">
                                    <option value="">Tidak terkait campaign</option>
                                    <option>Webinar Magang Nasional Batch 3</option>
                                    <option>Smart Course - Promo Akhir Tahun</option>
                                    <option>Depati Akademi - Kelas Laravel Intensif</option>
                                </select>
                                <div class="form-text small">
                                    Jika task ini bagian dari campaign tertentu, pilih di sini.
                                </div>
                            </div>

                            {{-- CATATAN TASK --}}
                            <div class="col-12">
                                <label class="form-label">Catatan / Instruksi</label>
                                <textarea class="form-control form-control-sm" rows="3"
                                          placeholder="Detail yang perlu dilakukan, skrip telepon, link dokumen, dll."></textarea>
                            </div>

                            {{-- OPSI SINKRON KE ACTIVITIES NANTI (INFO SAJA) --}}
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

    {{-- CSS ringan khusus Tasks --}}
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
        .priority-high {
            background-color: rgba(220, 53, 69, 0.08);
            color: #dc3545;
        }
        .priority-medium {
            background-color: rgba(255, 193, 7, 0.08);
            color: #fd7e14;
        }
        .priority-low {
            background-color: rgba(25, 135, 84, 0.08);
            color: #198754;
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
    </style>
@endsection
