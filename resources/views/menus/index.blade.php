@extends('layouts.app')

@php
    $formMode = old('form_mode', 'create');
    $editingId = old('menu_id');
    $editingMenu = $editingId ? $menus->firstWhere('id', (int) $editingId) : null;
    $shouldOpenCreateModal = $errors->any() && $formMode === 'create';
    $shouldOpenEditModal = $errors->any() && $formMode === 'edit' && $editingMenu;
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Menu</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Kelola Menu</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Item publish akan tampil di halaman publik /menu sesuai urutan dan kategori.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('public.menu.index') }}" target="_blank" rel="noreferrer" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Lihat /menu</a>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">Total: {{ $menus->count() }}</div>
                    <button type="button" data-open-modal="createMenuModal" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">Tambah Menu</button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="menusTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Foto</th>
                                <th class="px-5 py-4">Menu</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Harga</th>
                                <th class="px-5 py-4">Ilustrasi</th>
                                <th class="px-5 py-4">Urutan</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($menus as $menu)
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="h-16 w-16 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                            @if ($menu->photo_path)
                                                <img src="{{ route('public.menus.photo.show', $menu) }}" alt="{{ $menu->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-xs font-bold text-slate-400">No</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">{{ $menu->name }}</div>
                                        <div class="mt-1 max-w-md text-xs text-slate-500">{{ $menu->short_description }}</div>
                                        @if ($menu->tag)
                                            <span class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">{{ $menu->tag }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $menu->category?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 font-bold text-emerald-700">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 font-semibold text-amber-700">{{ $menu->hero }}</td>
                                    <td class="px-5 py-4 text-slate-500">{{ $menu->sort_order }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $menu->status === 'publish' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ ucfirst($menu->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-edit-modal
                                                data-id="{{ $menu->id }}"
                                                data-menu-category-id="{{ $menu->menu_category_id }}"
                                                data-name="{{ $menu->name }}"
                                                data-short-description="{{ $menu->short_description }}"
                                                data-long-description="{{ $menu->long_description }}"
                                                data-price="{{ $menu->price }}"
                                                data-hero="{{ $menu->hero }}"
                                                data-photo-url="{{ $menu->photo_path ? route('public.menus.photo.show', $menu) : '' }}"
                                                data-tag="{{ $menu->tag }}"
                                                data-sort-order="{{ $menu->sort_order }}"
                                                data-status="{{ $menu->status }}"
                                                data-update-url="{{ route('menus.update', $menu) }}"
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </button>
                                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-8 text-center text-slate-500">Belum ada item menu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="createMenuModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="max-h-[92vh] w-full max-w-4xl overflow-y-auto rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Menu</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Tambah Menu</h3>
                </div>
                <button type="button" data-close-modal="createMenuModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 px-6 py-6 lg:grid-cols-2" data-loading-form>
                @csrf
                <input type="hidden" name="form_mode" value="create">
                @include('menus.partials.form-fields', ['menu' => null, 'categories' => $categories, 'heroes' => $heroes, 'mode' => 'create'])
                <div class="flex justify-end gap-3 pt-2 lg:col-span-2">
                    <button type="button" data-close-modal="createMenuModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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

    <div id="editMenuModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="max-h-[92vh] w-full max-w-4xl overflow-y-auto rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Menu</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Edit Menu</h3>
                </div>
                <button type="button" data-close-modal="editMenuModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ $editingMenu ? route('menus.update', $editingMenu) : '#' }}" method="POST" enctype="multipart/form-data" class="grid gap-4 px-6 py-6 lg:grid-cols-2" id="editMenuForm" data-loading-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="menu_id" id="edit_menu_id" value="{{ $editingMenu?->id }}">
                @include('menus.partials.form-fields', ['menu' => $editingMenu, 'categories' => $categories, 'heroes' => $heroes, 'mode' => 'edit'])
                <div class="flex justify-end gap-3 pt-2 lg:col-span-2">
                    <button type="button" data-close-modal="editMenuModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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
            createMenuModal: document.getElementById('createMenuModal'),
            editMenuModal: document.getElementById('editMenuModal'),
        };
        const editMenuForm = document.getElementById('editMenuForm');

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
                document.getElementById('edit_menu_id').value = button.dataset.id;
                document.getElementById('edit_menu_category_id').value = button.dataset.menuCategoryId;
                document.getElementById('edit_name').value = button.dataset.name;
                document.getElementById('edit_short_description').value = button.dataset.shortDescription;
                document.getElementById('edit_long_description').value = button.dataset.longDescription;
                document.getElementById('edit_price').value = button.dataset.price;
                document.getElementById('edit_hero').value = button.dataset.hero;
                const editPhotoPreviewWrapper = document.getElementById('edit_photo_preview_wrapper');
                const editPhotoPreview = document.getElementById('edit_photo_preview');
                if (button.dataset.photoUrl) {
                    editPhotoPreview.src = button.dataset.photoUrl;
                    editPhotoPreviewWrapper.classList.remove('hidden');
                } else {
                    editPhotoPreview.src = '';
                    editPhotoPreviewWrapper.classList.add('hidden');
                }
                document.getElementById('edit_tag').value = button.dataset.tag;
                document.getElementById('edit_sort_order').value = button.dataset.sortOrder;
                document.getElementById('edit_status').value = button.dataset.status;
                editMenuForm.action = button.dataset.updateUrl;
                openModal('editMenuModal');
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
            $('#menusTable').DataTable({
                pageLength: 10,
                order: [[2, 'asc'], [5, 'asc']],
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
            openModal('createMenuModal');
        @endif

        @if ($shouldOpenEditModal)
            editMenuForm.action = '{{ $editingMenu ? route('menus.update', $editingMenu) : '#' }}';
            openModal('editMenuModal');
        @endif
    </script>
@endsection
