@extends('layouts.app')

@php
    $formMode = old('form_mode', 'create');
    $editingId = old('referral_link_id');
    $editingReferralLink = $editingId ? $referralLinks->firstWhere('id', (int) $editingId) : null;
    $shouldOpenCreateModal = $errors->any() && $formMode === 'create';
    $shouldOpenEditModal = $errors->any() && $formMode === 'edit' && $editingReferralLink;
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Referal</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Kelola Link Referal</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">CRUD link referal memakai modal untuk tambah dan edit, sementara data ditampilkan dengan DataTable agar mudah dicari dan dikelola.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total: {{ $referralLinks->count() }}
                    </div>
                    <button
                        type="button"
                        data-open-modal="createReferralLinkModal"
                        class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tambah Link Referal
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="referralLinksTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Logo</th>
                                <th class="px-5 py-4">Nama Referal</th>
                                <th class="px-5 py-4">Jenis</th>
                                <th class="px-5 py-4">Link Referal</th>
                                <th class="px-5 py-4">Keterangan</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Dibuat</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($referralLinks as $referralLink)
                                <tr>
                                    <td class="px-5 py-4">
                                        @if ($referralLink->logo_path)
                                            <img src="{{ asset('storage/' . $referralLink->logo_path) }}" alt="{{ $referralLink->name }} logo" class="h-12 w-12 rounded-2xl border border-slate-200 object-contain bg-white p-1">
                                        @else
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-dashed border-slate-200 text-xs font-semibold text-slate-400">
                                                -
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $referralLink->name }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $referralLink->type === 'forex' ? 'bg-amber-100 text-amber-700' : 'bg-sky-100 text-sky-700' }}">
                                            {{ $referralLink->type === 'forex' ? 'Forex' : 'Saham' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <a href="{{ $referralLink->link }}" target="_blank" rel="noreferrer" class="text-sky-700 underline underline-offset-4">
                                            {{ $referralLink->link }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $referralLink->description ?: '-' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $referralLink->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $referralLink->status === 'aktif' ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500">{{ $referralLink->created_at?->format('d M Y H:i') }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-edit-modal
                                                data-id="{{ $referralLink->id }}"
                                                data-name="{{ $referralLink->name }}"
                                                data-type="{{ $referralLink->type }}"
                                                data-link="{{ $referralLink->link }}"
                                                data-description="{{ $referralLink->description }}"
                                                data-status="{{ $referralLink->status }}"
                                                data-logo-url="{{ $referralLink->logo_path ? asset('storage/' . $referralLink->logo_path) : '' }}"
                                                data-update-url="{{ route('referral-links.update', $referralLink) }}"
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </button>
                                            <form action="{{ route('referral-links.destroy', $referralLink) }}" method="POST" onsubmit="return confirm('Hapus link referal ini?')">
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
                                    <td colspan="8" class="px-5 py-8 text-center text-slate-500">Belum ada link referal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="createReferralLinkModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Referal</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Tambah Link Referal</h3>
                </div>
                <button type="button" data-close-modal="createReferralLinkModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('referral-links.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 px-6 py-6" data-loading-form>
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Referal</label>
                    <input name="name" type="text" value="{{ $formMode === 'create' ? old('name') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create')
                        @error('name')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis</label>
                    <select name="type" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <option value="forex" {{ $formMode === 'create' && old('type', 'forex') === 'forex' ? 'selected' : '' }}>Forex</option>
                        <option value="saham" {{ $formMode === 'create' && old('type') === 'saham' ? 'selected' : '' }}>Saham</option>
                    </select>
                    @if ($formMode === 'create')
                        @error('type')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Link Referal</label>
                    <input name="link" type="url" value="{{ $formMode === 'create' ? old('link') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create')
                        @error('link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Keterangan</label>
                    <textarea name="description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">{{ $formMode === 'create' ? old('description') : '' }}</textarea>
                    @if ($formMode === 'create')
                        @error('description')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Logo Referal</label>
                    <input name="logo" type="file" accept="image/*" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
                    @if ($formMode === 'create')
                        @error('logo')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <option value="aktif" {{ $formMode === 'create' && old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $formMode === 'create' && old('status') === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                    @if ($formMode === 'create')
                        @error('status')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="createReferralLinkModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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

    <div id="editReferralLinkModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Referal</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Edit Link Referal</h3>
                </div>
                <button type="button" data-close-modal="editReferralLinkModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ $editingReferralLink ? route('referral-links.update', $editingReferralLink) : '#' }}" method="POST" enctype="multipart/form-data" class="space-y-4 px-6 py-6" id="editReferralLinkForm" data-loading-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="referral_link_id" id="edit_referral_link_id" value="{{ $editingReferralLink?->id }}">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Referal</label>
                    <input id="edit_name" name="name" type="text" value="{{ $formMode === 'edit' ? old('name', $editingReferralLink?->name) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit')
                        @error('name')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Jenis</label>
                    <select id="edit_type" name="type" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <option value="forex" {{ $formMode === 'edit' && old('type', $editingReferralLink?->type) === 'forex' ? 'selected' : '' }}>Forex</option>
                        <option value="saham" {{ $formMode === 'edit' && old('type', $editingReferralLink?->type) === 'saham' ? 'selected' : '' }}>Saham</option>
                    </select>
                    @if ($formMode === 'edit')
                        @error('type')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Link Referal</label>
                    <input id="edit_link" name="link" type="url" value="{{ $formMode === 'edit' ? old('link', $editingReferralLink?->link) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit')
                        @error('link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Keterangan</label>
                    <textarea id="edit_description" name="description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">{{ $formMode === 'edit' ? old('description', $editingReferralLink?->description) : '' }}</textarea>
                    @if ($formMode === 'edit')
                        @error('description')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Logo Referal</label>
                    <div id="edit_logo_preview_wrapper" class="{{ $editingReferralLink?->logo_path ? '' : 'hidden' }} mb-3">
                        <img id="edit_logo_preview" src="{{ $editingReferralLink?->logo_path ? asset('storage/' . $editingReferralLink->logo_path) : '' }}" alt="Preview logo referal" class="h-16 w-16 rounded-2xl border border-slate-200 object-contain bg-white p-1">
                    </div>
                    <input name="logo" type="file" accept="image/*" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
                    <p class="mt-2 text-xs text-slate-500">Kosongkan jika logo tidak ingin diganti.</p>
                    @if ($formMode === 'edit')
                        @error('logo')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
                    <select id="edit_status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-amber-400 focus:outline-none">
                        <option value="aktif" {{ $formMode === 'edit' && old('status', $editingReferralLink?->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $formMode === 'edit' && old('status', $editingReferralLink?->status) === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                    @if ($formMode === 'edit')
                        @error('status')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="editReferralLinkModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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
            createReferralLinkModal: document.getElementById('createReferralLinkModal'),
            editReferralLinkModal: document.getElementById('editReferralLinkModal'),
        };
        const editReferralLinkForm = document.getElementById('editReferralLinkForm');
        const editLogoPreview = document.getElementById('edit_logo_preview');
        const editLogoPreviewWrapper = document.getElementById('edit_logo_preview_wrapper');

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
                document.getElementById('edit_referral_link_id').value = button.dataset.id;
                document.getElementById('edit_name').value = button.dataset.name;
                document.getElementById('edit_type').value = button.dataset.type;
                document.getElementById('edit_link').value = button.dataset.link;
                document.getElementById('edit_description').value = button.dataset.description;
                document.getElementById('edit_status').value = button.dataset.status;
                if (button.dataset.logoUrl) {
                    editLogoPreview.src = button.dataset.logoUrl;
                    editLogoPreviewWrapper.classList.remove('hidden');
                } else {
                    editLogoPreview.src = '';
                    editLogoPreviewWrapper.classList.add('hidden');
                }
                editReferralLinkForm.action = button.dataset.updateUrl;
                openModal('editReferralLinkModal');
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
            $('#referralLinksTable').DataTable({
                pageLength: 10,
                order: [[5, 'desc']],
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
            openModal('createReferralLinkModal');
        @endif

        @if ($shouldOpenEditModal)
            editReferralLinkForm.action = '{{ $editingReferralLink ? route('referral-links.update', $editingReferralLink) : '#' }}';
            openModal('editReferralLinkModal');
        @endif
    </script>
@endsection
