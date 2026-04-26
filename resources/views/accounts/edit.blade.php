@extends('layouts.app')

@section('content')
    <div class="mb-6 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Pengaturan Akun</p>
        <h2 class="mt-2 text-3xl font-black text-slate-900">Edit Akun</h2>
        <p class="mt-3 text-sm leading-7 text-slate-600">Perbarui data akun di bawah ini. Kosongkan password jika tidak ingin mengganti password.</p>
    </div>

    <section class="max-w-3xl rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
                <input name="name" type="text" value="{{ old('name', $account->name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                @error('name')
                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
                <input name="username" type="text" value="{{ old('username', $account->username) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                @error('username')
                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input name="email" type="email" value="{{ old('email', $account->email) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                @error('email')
                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Password Baru</label>
                <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                @error('password')
                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                    Update Akun
                </button>
                <a href="{{ route('accounts.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </form>
    </section>
@endsection
