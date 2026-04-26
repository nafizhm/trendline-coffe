@extends('layouts.app')

@php
    $formMode = old('form_mode', 'create');
    $editingId = old('video_id');
    $editingVideo = $editingId ? $videos->firstWhere('id', (int) $editingId) : null;
    $shouldOpenCreateModal = $errors->any() && $formMode === 'create';
    $shouldOpenEditModal = $errors->any() && $formMode === 'edit' && $editingVideo;
    $defaultAdminName = auth()->user()?->name ?? '';
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Video</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Kelola Video Youtube</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Menu ini menyimpan video dari Youtube dengan input kode video saja, lalu thumbnail otomatis tampil di tabel.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total: {{ $videos->count() }}
                    </div>
                    <button
                        type="button"
                        data-open-modal="createVideoModal"
                        class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tambah Video
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="videosTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Thumbnail</th>
                                <th class="px-5 py-4">Tanggal</th>
                                <th class="px-5 py-4">Judul Video</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Kode Youtube</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Nama Admin</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($videos as $video)
                                <tr>
                                    <td class="px-5 py-4">
                                        <a href="https://www.youtube.com/watch?v={{ $video->youtube_code }}" target="_blank" rel="noreferrer" class="block w-28 overflow-hidden rounded-2xl border border-slate-200">
                                            <img src="https://img.youtube.com/vi/{{ $video->youtube_code }}/hqdefault.jpg" alt="{{ $video->title }}" class="h-16 w-full object-cover">
                                        </a>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $video->published_at?->format('d M Y') }}</td>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $video->title }}</td>
                                    <td class="px-5 py-4 text-slate-500">{{ $video->category?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 font-semibold text-amber-700">{{ $video->youtube_code }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $video->status === 'publish' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ ucfirst($video->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $video->admin_name }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-edit-modal
                                                data-id="{{ $video->id }}"
                                                data-published-at="{{ $video->published_at?->format('Y-m-d') }}"
                                                data-title="{{ $video->title }}"
                                                data-category-id="{{ $video->category_id }}"
                                                data-youtube-code="{{ $video->youtube_code }}"
                                                data-status="{{ $video->status }}"
                                                data-admin-name="{{ $video->admin_name }}"
                                                data-update-url="{{ route('videos.update', $video) }}"
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </button>
                                            <form action="{{ route('videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Hapus video ini?')">
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
                                    <td colspan="8" class="px-5 py-8 text-center text-slate-500">Belum ada video.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="createVideoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-3xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Video</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Tambah Video</h3>
                </div>
                <button type="button" data-close-modal="createVideoModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('videos.store') }}" method="POST" class="space-y-4 px-6 py-6" data-loading-form>
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
                        <input name="published_at" type="date" value="{{ $formMode === 'create' ? old('published_at', now()->format('Y-m-d')) : now()->format('Y-m-d') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('published_at')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                            <option value="">Pilih status</option>
                            <option value="publish" @selected($formMode === 'create' && old('status') === 'publish')>Publish</option>
                            <option value="arsip" @selected($formMode === 'create' && old('status') === 'arsip')>Arsip</option>
                        </select>
                        @if ($formMode === 'create')
                            @error('status')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Judul Video</label>
                    <input name="title" type="text" value="{{ $formMode === 'create' ? old('title') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create')
                        @error('title')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
                        <select name="category_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($formMode === 'create' && (string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if ($formMode === 'create')
                            @error('category_id')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Kode Youtube</label>
                        <input name="youtube_code" type="text" value="{{ $formMode === 'create' ? old('youtube_code') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <p class="mt-2 text-xs text-slate-400">Isi hanya kode video, misalnya `dQw4w9WgXcQ`.</p>
                        @if ($formMode === 'create')
                            @error('youtube_code')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Admin</label>
                    <input name="admin_name" type="text" value="{{ $formMode === 'create' ? old('admin_name', $defaultAdminName) : $defaultAdminName }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create')
                        @error('admin_name')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="createVideoModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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

    <div id="editVideoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-3xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Video</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Edit Video</h3>
                </div>
                <button type="button" data-close-modal="editVideoModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ $editingVideo ? route('videos.update', $editingVideo) : '#' }}" method="POST" class="space-y-4 px-6 py-6" id="editVideoForm" data-loading-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="video_id" id="edit_video_id" value="{{ $editingVideo?->id }}">

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
                        <input id="edit_published_at" name="published_at" type="date" value="{{ $formMode === 'edit' ? old('published_at', $editingVideo?->published_at?->format('Y-m-d')) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('published_at')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                        <select id="edit_status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                            <option value="">Pilih status</option>
                            <option value="publish" @selected($formMode === 'edit' && old('status', $editingVideo?->status) === 'publish')>Publish</option>
                            <option value="arsip" @selected($formMode === 'edit' && old('status', $editingVideo?->status) === 'arsip')>Arsip</option>
                        </select>
                        @if ($formMode === 'edit')
                            @error('status')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Judul Video</label>
                    <input id="edit_title" name="title" type="text" value="{{ $formMode === 'edit' ? old('title', $editingVideo?->title) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit')
                        @error('title')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
                        <select id="edit_category_id" name="category_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($formMode === 'edit' && (string) old('category_id', $editingVideo?->category_id) === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if ($formMode === 'edit')
                            @error('category_id')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Kode Youtube</label>
                        <input id="edit_youtube_code" name="youtube_code" type="text" value="{{ $formMode === 'edit' ? old('youtube_code', $editingVideo?->youtube_code) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <p class="mt-2 text-xs text-slate-400">Isi hanya kode video, misalnya `dQw4w9WgXcQ`.</p>
                        @if ($formMode === 'edit')
                            @error('youtube_code')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Admin</label>
                    <input id="edit_admin_name" name="admin_name" type="text" value="{{ $formMode === 'edit' ? old('admin_name', $editingVideo?->admin_name) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit')
                        @error('admin_name')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="editVideoModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        const modalElements = {
            createVideoModal: document.getElementById('createVideoModal'),
            editVideoModal: document.getElementById('editVideoModal'),
        };
        const editVideoForm = document.getElementById('editVideoForm');

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
                document.getElementById('edit_video_id').value = button.dataset.id;
                document.getElementById('edit_published_at').value = button.dataset.publishedAt;
                document.getElementById('edit_title').value = button.dataset.title;
                document.getElementById('edit_category_id').value = button.dataset.categoryId;
                document.getElementById('edit_youtube_code').value = button.dataset.youtubeCode;
                document.getElementById('edit_status').value = button.dataset.status;
                document.getElementById('edit_admin_name').value = button.dataset.adminName;
                editVideoForm.action = button.dataset.updateUrl;
                openModal('editVideoModal');
            });
        });

        document.querySelectorAll('[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
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

        if (window.jQuery) {
            $('#videosTable').DataTable({
                pageLength: 10,
                order: [[1, 'desc']],
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
            openModal('createVideoModal');
        @endif

        @if ($shouldOpenEditModal)
            editVideoForm.action = '{{ $editingVideo ? route('videos.update', $editingVideo) : '#' }}';
            openModal('editVideoModal');
        @endif
    </script>
@endsection
