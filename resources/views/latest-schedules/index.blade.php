@extends('layouts.app')

@php
    $formMode = old('form_mode', 'create');
    $editingId = old('latest_schedule_id');
    $editingLatestSchedule = $editingId ? $latestSchedules->firstWhere('id', (int) $editingId) : null;
    $shouldOpenCreateModal = $errors->any() && $formMode === 'create';
    $shouldOpenEditModal = $errors->any() && $formMode === 'edit' && $editingLatestSchedule;
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Jadwal</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Kelola Jadwal Terbaru</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">CRUD jadwal terbaru memakai modal untuk tambah dan edit, dengan editor deskripsi dan upload lampiran atau pamflet.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total: {{ $latestSchedules->count() }}
                    </div>
                    <button
                        type="button"
                        data-open-modal="createLatestScheduleModal"
                        class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tambah Jadwal
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="latestSchedulesTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Tanggal</th>
                                <th class="px-5 py-4">Jam</th>
                                <th class="px-5 py-4">Judul Kegiatan</th>
                                <th class="px-5 py-4">Lampiran</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($latestSchedules as $latestSchedule)
                                <tr>
                                    <td class="px-5 py-4 text-slate-500">{{ $latestSchedule->event_date?->format('d M Y') }}</td>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $latestSchedule->event_time }}</td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">{{ $latestSchedule->title }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($latestSchedule->description), 90) }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($latestSchedule->attachment_path)
                                            <a href="{{ asset('storage/' . $latestSchedule->attachment_path) }}" target="_blank" rel="noreferrer" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Lihat File
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $latestSchedule->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $latestSchedule->status === 'aktif' ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-edit-modal
                                                data-id="{{ $latestSchedule->id }}"
                                                data-title="{{ $latestSchedule->title }}"
                                                data-event-date="{{ $latestSchedule->event_date?->format('Y-m-d') }}"
                                                data-event-time="{{ $latestSchedule->event_time }}"
                                                data-description="{{ e($latestSchedule->description) }}"
                                                data-status="{{ $latestSchedule->status }}"
                                                data-attachment-path="{{ $latestSchedule->attachment_path ? asset('storage/' . $latestSchedule->attachment_path) : '' }}"
                                                data-update-url="{{ route('latest-schedules.update', $latestSchedule) }}"
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </button>
                                            <form action="{{ route('latest-schedules.destroy', $latestSchedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada jadwal terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="createLatestScheduleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-4xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Jadwal</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Tambah Jadwal Terbaru</h3>
                </div>
                <button type="button" data-close-modal="createLatestScheduleModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('latest-schedules.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 px-6 py-6" data-loading-form>
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Judul Kegiatan</label>
                        <input name="title" type="text" value="{{ $formMode === 'create' ? old('title') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('title')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                            <option value="aktif" @selected($formMode === 'create' && old('status', 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected($formMode === 'create' && old('status') === 'nonaktif')>Non Aktif</option>
                        </select>
                        @if ($formMode === 'create')
                            @error('status')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Kegiatan</label>
                        <input name="event_date" type="date" value="{{ $formMode === 'create' ? old('event_date', now()->format('Y-m-d')) : now()->format('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('event_date')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Jam Kegiatan</label>
                        <input name="event_time" type="time" value="{{ $formMode === 'create' ? old('event_time') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('event_time')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Kegiatan</label>
                    <textarea name="description" class="js-summernote-create">{{ $formMode === 'create' ? old('description') : '' }}</textarea>
                    @if ($formMode === 'create')
                        @error('description')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Lampiran / Pamflet</label>
                    <input name="attachment" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
                    <p class="mt-2 text-xs text-slate-400">Format yang didukung: JPG, PNG, WEBP, PDF. Maksimal 4 MB.</p>
                    @if ($formMode === 'create')
                        @error('attachment')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="createLatestScheduleModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                        <span class="submit-label">Simpan</span>
                        <span class="submit-spinner hidden items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Proses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editLatestScheduleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-4xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Jadwal</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Edit Jadwal Terbaru</h3>
                </div>
                <button type="button" data-close-modal="editLatestScheduleModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ $editingLatestSchedule ? route('latest-schedules.update', $editingLatestSchedule) : '#' }}" method="POST" enctype="multipart/form-data" class="space-y-4 px-6 py-6" id="editLatestScheduleForm" data-loading-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="latest_schedule_id" id="edit_latest_schedule_id" value="{{ $editingLatestSchedule?->id }}">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Judul Kegiatan</label>
                        <input id="edit_title" name="title" type="text" value="{{ $formMode === 'edit' ? old('title', $editingLatestSchedule?->title) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('title')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                        <select id="edit_status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                            <option value="aktif" @selected($formMode === 'edit' && old('status', $editingLatestSchedule?->status) === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected($formMode === 'edit' && old('status', $editingLatestSchedule?->status) === 'nonaktif')>Non Aktif</option>
                        </select>
                        @if ($formMode === 'edit')
                            @error('status')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Kegiatan</label>
                        <input id="edit_event_date" name="event_date" type="date" value="{{ $formMode === 'edit' ? old('event_date', $editingLatestSchedule?->event_date?->format('Y-m-d')) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('event_date')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Jam Kegiatan</label>
                        <input id="edit_event_time" name="event_time" type="time" value="{{ $formMode === 'edit' ? old('event_time', $editingLatestSchedule?->event_time) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('event_time')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Kegiatan</label>
                    <textarea id="edit_description" name="description" class="js-summernote-edit">{{ $formMode === 'edit' ? old('description', $editingLatestSchedule?->description) : '' }}</textarea>
                    @if ($formMode === 'edit')
                        @error('description')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Lampiran / Pamflet</label>
                    <div id="edit_attachment_wrapper" class="{{ $editingLatestSchedule?->attachment_path ? '' : 'hidden' }} mb-3">
                        <a id="edit_attachment_link" href="{{ $editingLatestSchedule?->attachment_path ? asset('storage/' . $editingLatestSchedule->attachment_path) : '#' }}" target="_blank" rel="noreferrer" class="inline-flex rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Lihat lampiran saat ini
                        </a>
                    </div>
                    <input name="attachment" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
                    <p class="mt-2 text-xs text-slate-400">Kosongkan jika lampiran tidak ingin diganti.</p>
                    @if ($formMode === 'edit')
                        @error('attachment')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="editLatestScheduleModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                        <span class="submit-label">Simpan Perubahan</span>
                        <span class="submit-spinner hidden items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Proses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <script>
        const modalElements = {
            createLatestScheduleModal: document.getElementById('createLatestScheduleModal'),
            editLatestScheduleModal: document.getElementById('editLatestScheduleModal'),
        };
        const editLatestScheduleForm = document.getElementById('editLatestScheduleForm');
        const editAttachmentWrapper = document.getElementById('edit_attachment_wrapper');
        const editAttachmentLink = document.getElementById('edit_attachment_link');

        function openModal(id) {
            const modal = modalElements[id];
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(id) {
            const modal = modalElements[id];
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function initEditors() {
            if (!(window.jQuery && $.fn.summernote)) return;

            $('.js-summernote-create').summernote({
                placeholder: 'Tulis deskripsi kegiatan di sini...',
                height: 240,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table', 'hr']],
                    ['view', ['codeview']]
                ]
            });

            $('.js-summernote-edit').summernote({
                placeholder: 'Tulis deskripsi kegiatan di sini...',
                height: 240,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table', 'hr']],
                    ['view', ['codeview']]
                ]
            });
        }

        document.querySelectorAll('[data-open-modal]').forEach((button) => {
            button.addEventListener('click', () => openModal(button.dataset.openModal));
        });

        document.querySelectorAll('[data-close-modal]').forEach((button) => {
            button.addEventListener('click', () => closeModal(button.dataset.closeModal));
        });

        Object.values(modalElements).forEach((modal) => {
            if (!modal) return;
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        document.querySelectorAll('[data-open-edit-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('edit_latest_schedule_id').value = button.dataset.id;
                document.getElementById('edit_title').value = button.dataset.title;
                document.getElementById('edit_event_date').value = button.dataset.eventDate;
                document.getElementById('edit_event_time').value = button.dataset.eventTime;
                document.getElementById('edit_status').value = button.dataset.status;

                if (window.jQuery && $.fn.summernote) {
                    $('#edit_description').summernote('code', button.dataset.description);
                } else {
                    document.getElementById('edit_description').value = button.dataset.description;
                }

                if (button.dataset.attachmentPath) {
                    editAttachmentLink.href = button.dataset.attachmentPath;
                    editAttachmentWrapper.classList.remove('hidden');
                } else {
                    editAttachmentLink.href = '#';
                    editAttachmentWrapper.classList.add('hidden');
                }

                editLatestScheduleForm.action = button.dataset.updateUrl;
                openModal('editLatestScheduleModal');
            });
        });

        document.querySelectorAll('[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                if (window.jQuery && $.fn.summernote) {
                    $('.js-summernote-create').each(function () {
                        $(this).val($(this).summernote('code'));
                    });
                    $('.js-summernote-edit').each(function () {
                        $(this).val($(this).summernote('code'));
                    });
                }

                const submitButton = form.querySelector('[data-submit-button]');
                const label = form.querySelector('.submit-label');
                const spinner = form.querySelector('.submit-spinner');

                if (!submitButton || !label || !spinner) return;

                submitButton.disabled = true;
                submitButton.classList.add('cursor-not-allowed', 'opacity-80');
                label.classList.add('hidden');
                spinner.classList.remove('hidden');
                spinner.classList.add('inline-flex');
            });
        });

        initEditors();

        if (window.jQuery) {
            $('#latestSchedulesTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Belum ada data',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        previous: 'Sebelumnya',
                        next: 'Berikutnya'
                    }
                }
            });
        }

        @if ($shouldOpenCreateModal)
            openModal('createLatestScheduleModal');
        @endif

        @if ($shouldOpenEditModal)
            editLatestScheduleForm.action = '{{ $editingLatestSchedule ? route('latest-schedules.update', $editingLatestSchedule) : '#' }}';
            openModal('editLatestScheduleModal');
        @endif
    </script>
@endsection
