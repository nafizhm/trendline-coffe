@extends('layouts.app')

@php
    $formMode = old('form_mode', 'create');
    $editingId = old('menu_category_id');
    $editingCategory = $editingId ? $categories->firstWhere('id', (int) $editingId) : null;
    $shouldOpenCreateModal = $errors->any() && $formMode === 'create';
    $shouldOpenEditModal = $errors->any() && $formMode === 'edit' && $editingCategory;
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kategori Menu</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Kelola Kategori Menu</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Kategori ini menjadi tab pada halaman publik /menu.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total: {{ $categories->count() }}
                    </div>
                    <button type="button" data-open-modal="createMenuCategoryModal" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tambah Kategori
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="menuCategoriesTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Slug</th>
                                <th class="px-5 py-4">Warna</th>
                                <th class="px-5 py-4">Urutan</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Item</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $category->name }}</td>
                                    <td class="px-5 py-4 text-slate-500">{{ $category->slug }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $category->style_class === 'cemilan' ? 'bg-amber-100 text-amber-700' : ($category->style_class === 'nonkopi' ? 'bg-teal-100 text-teal-700' : 'bg-emerald-100 text-emerald-700') }}">
                                            {{ $category->style_class }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $category->sort_order }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $category->menus_count }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-edit-modal
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-slug="{{ $category->slug }}"
                                                data-style-class="{{ $category->style_class }}"
                                                data-sort-order="{{ $category->sort_order }}"
                                                data-is-active="{{ $category->is_active ? '1' : '0' }}"
                                                data-update-url="{{ route('menu-categories.update', $category) }}"
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </button>
                                            <form action="{{ route('menu-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori menu ini? Semua item di dalamnya ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-slate-500">Belum ada kategori menu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="createMenuCategoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kategori Menu</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Tambah Kategori</h3>
                </div>
                <button type="button" data-close-modal="createMenuCategoryModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('menu-categories.store') }}" method="POST" class="space-y-4 px-6 py-6" data-loading-form>
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Kategori</label>
                    <input name="name" type="text" value="{{ $formMode === 'create' ? old('name') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create') @error('name') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
                    <input name="slug" type="text" value="{{ $formMode === 'create' ? old('slug') : '' }}" placeholder="otomatis dari nama jika kosong" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create') @error('slug') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Warna Template</label>
                    <select name="style_class" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <option value="kopi" @selected($formMode === 'create' && old('style_class', 'kopi') === 'kopi')>Kopi - Hijau</option>
                        <option value="nonkopi" @selected($formMode === 'create' && old('style_class') === 'nonkopi')>Non-Kopi - Teal</option>
                        <option value="cemilan" @selected($formMode === 'create' && old('style_class') === 'cemilan')>Cemilan - Gold</option>
                    </select>
                    @if ($formMode === 'create') @error('style_class') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Urutan</label>
                    <input name="sort_order" type="number" min="0" value="{{ $formMode === 'create' ? old('sort_order', 10) : 10 }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create') @error('sort_order') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                    <input name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300" @checked($formMode !== 'create' || old('is_active', '1'))>
                    Aktif di halaman menu
                </label>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="createMenuCategoryModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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

    <div id="editMenuCategoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Kategori Menu</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Edit Kategori</h3>
                </div>
                <button type="button" data-close-modal="editMenuCategoryModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ $editingCategory ? route('menu-categories.update', $editingCategory) : '#' }}" method="POST" class="space-y-4 px-6 py-6" id="editMenuCategoryForm" data-loading-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="menu_category_id" id="edit_menu_category_id" value="{{ $editingCategory?->id }}">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Kategori</label>
                    <input id="edit_name" name="name" type="text" value="{{ $formMode === 'edit' ? old('name', $editingCategory?->name) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit') @error('name') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
                    <input id="edit_slug" name="slug" type="text" value="{{ $formMode === 'edit' ? old('slug', $editingCategory?->slug) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit') @error('slug') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Warna Template</label>
                    <select id="edit_style_class" name="style_class" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <option value="kopi" @selected($formMode === 'edit' && old('style_class', $editingCategory?->style_class) === 'kopi')>Kopi - Hijau</option>
                        <option value="nonkopi" @selected($formMode === 'edit' && old('style_class', $editingCategory?->style_class) === 'nonkopi')>Non-Kopi - Teal</option>
                        <option value="cemilan" @selected($formMode === 'edit' && old('style_class', $editingCategory?->style_class) === 'cemilan')>Cemilan - Gold</option>
                    </select>
                    @if ($formMode === 'edit') @error('style_class') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Urutan</label>
                    <input id="edit_sort_order" name="sort_order" type="number" min="0" value="{{ $formMode === 'edit' ? old('sort_order', $editingCategory?->sort_order) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit') @error('sort_order') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror @endif
                </div>
                <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                    <input id="edit_is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300" @checked($formMode === 'edit' && old('is_active', $editingCategory?->is_active))>
                    Aktif di halaman menu
                </label>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="editMenuCategoryModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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
            createMenuCategoryModal: document.getElementById('createMenuCategoryModal'),
            editMenuCategoryModal: document.getElementById('editMenuCategoryModal'),
        };
        const editMenuCategoryForm = document.getElementById('editMenuCategoryForm');

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
                if (event.target === modal) closeModal(modal.id);
            });
        });

        document.querySelectorAll('[data-open-edit-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById('edit_menu_category_id').value = button.dataset.id;
                document.getElementById('edit_name').value = button.dataset.name;
                document.getElementById('edit_slug').value = button.dataset.slug;
                document.getElementById('edit_style_class').value = button.dataset.styleClass;
                document.getElementById('edit_sort_order').value = button.dataset.sortOrder;
                document.getElementById('edit_is_active').checked = button.dataset.isActive === '1';
                editMenuCategoryForm.action = button.dataset.updateUrl;
                openModal('editMenuCategoryModal');
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
            $('#menuCategoriesTable').DataTable({
                pageLength: 10,
                order: [[3, 'asc']],
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
            openModal('createMenuCategoryModal');
        @endif

        @if ($shouldOpenEditModal)
            editMenuCategoryForm.action = '{{ $editingCategory ? route('menu-categories.update', $editingCategory) : '#' }}';
            openModal('editMenuCategoryModal');
        @endif
    </script>
@endsection
