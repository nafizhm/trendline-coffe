@extends('layouts.app')

@php
    $formMode = old('form_mode', 'create');
    $editingId = old('account_id');
    $editingAccount = $editingId ? $accounts->firstWhere('id', (int) $editingId) : null;
    $shouldOpenCreateModal = $errors->any() && $formMode === 'create';
    $shouldOpenEditModal = $errors->any() && $formMode === 'edit' && $editingAccount;
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Pengaturan Akun</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Kelola Akun</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Tambah dan edit akun sekarang memakai modal agar lebih cepat dikelola dari satu halaman.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total: {{ $accounts->count() }}
                    </div>
                    <button
                        type="button"
                        data-open-modal="createAccountModal"
                        class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tambah Akun
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Nama</th>
                                <th class="px-5 py-4">Username</th>
                                <th class="px-5 py-4">Email</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($accounts as $account)
                                <tr>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $account->name }}</td>
                                    <td class="px-5 py-4 font-semibold text-amber-700">{{ '@'.$account->username }}</td>
                                    <td class="px-5 py-4 text-slate-500">{{ $account->email }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                data-open-edit-modal
                                                data-id="{{ $account->id }}"
                                                data-name="{{ $account->name }}"
                                                data-username="{{ $account->username }}"
                                                data-email="{{ $account->email }}"
                                                data-update-url="{{ route('accounts.update', $account) }}"
                                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </button>
                                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
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
                                    <td colspan="4" class="px-5 py-8 text-center text-slate-500">Belum ada akun.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div id="createAccountModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Pengaturan Akun</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Tambah Akun</h3>
                </div>
                <button type="button" data-close-modal="createAccountModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ route('accounts.store') }}" method="POST" class="space-y-4 px-6 py-6" data-loading-form>
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                    <input name="name" type="text" value="{{ $formMode === 'create' ? old('name') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'create')
                        @error('name')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
                        <input name="username" type="text" value="{{ $formMode === 'create' ? old('username') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('username')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input name="email" type="email" value="{{ $formMode === 'create' ? old('email') : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('email')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                        <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'create')
                            @error('password')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                        <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="createAccountModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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

    <div id="editAccountModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/45 p-4">
        <div class="w-full max-w-2xl rounded-[28px] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Pengaturan Akun</p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900">Edit Akun</h3>
                </div>
                <button type="button" data-close-modal="editAccountModal" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">Tutup</button>
            </div>

            <form action="{{ $editingAccount ? route('accounts.update', $editingAccount) : '#' }}" method="POST" class="space-y-4 px-6 py-6" id="editAccountForm" data-loading-form>
                @csrf
                @method('PUT')
                <input type="hidden" name="form_mode" value="edit">
                <input type="hidden" name="account_id" id="edit_account_id" value="{{ $editingAccount?->id }}">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                    <input id="edit_name" name="name" type="text" value="{{ $formMode === 'edit' ? old('name', $editingAccount?->name) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    @if ($formMode === 'edit')
                        @error('name')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
                        <input id="edit_username" name="username" type="text" value="{{ $formMode === 'edit' ? old('username', $editingAccount?->username) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('username')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input id="edit_email" name="email" type="email" value="{{ $formMode === 'edit' ? old('email', $editingAccount?->email) : '' }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('email')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Password Baru</label>
                        <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @if ($formMode === 'edit')
                            @error('password')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                        <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-close-modal="editAccountModal" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
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

    <script>
        const modalElements = {
            createAccountModal: document.getElementById('createAccountModal'),
            editAccountModal: document.getElementById('editAccountModal'),
        };
        const editAccountForm = document.getElementById('editAccountForm');

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
                document.getElementById('edit_account_id').value = button.dataset.id;
                document.getElementById('edit_name').value = button.dataset.name;
                document.getElementById('edit_username').value = button.dataset.username;
                document.getElementById('edit_email').value = button.dataset.email;
                editAccountForm.action = button.dataset.updateUrl;
                openModal('editAccountModal');
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

        @if ($shouldOpenCreateModal)
            openModal('createAccountModal');
        @endif

        @if ($shouldOpenEditModal)
            editAccountForm.action = '{{ $editingAccount ? route('accounts.update', $editingAccount) : '#' }}';
            openModal('editAccountModal');
        @endif
    </script>
@endsection
