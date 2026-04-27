@extends('layouts.app')

@php
    $fileCards = [
        [
            'label' => 'Upload Foto Owner',
            'name' => 'owner_photo',
            'deleteFormId' => 'delete-owner-photo-form',
            'path' => $setting->owner_photo_path,
            'viewRoute' => route('settings.files.show', 'owner-photo'),
            'deleteRoute' => route('settings.files.destroy', 'owner-photo'),
            'accept' => 'image/*',
        ],
        [
            'label' => 'Upload Logo',
            'name' => 'logo',
            'deleteFormId' => 'delete-logo-form',
            'path' => $setting->logo_path,
            'viewRoute' => route('settings.files.show', 'logo'),
            'deleteRoute' => route('settings.files.destroy', 'logo'),
            'accept' => 'image/*',
        ],
    ];
@endphp

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Pengaturan</p>
                <h2 class="mt-2 text-3xl font-black text-slate-900">Pengaturan Aplikasi</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                    Menu ini langsung menampilkan form utama untuk identitas aplikasi, kontak WhatsApp, file logo, dan link sosial media.
                </p>
            </div>
        </section>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" data-loading-form>
            @csrf
            @method('PUT')

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="app_name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Aplikasi</label>
                        <input id="app_name" name="app_name" type="text" value="{{ old('app_name', $setting->app_name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('app_name')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="direct_wa_number" class="mb-2 block text-sm font-semibold text-slate-700">No Direct WA</label>
                        <input id="direct_wa_number" name="direct_wa_number" type="text" value="{{ old('direct_wa_number', $setting->direct_wa_number) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('direct_wa_number')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h3 class="text-lg font-black text-slate-900">Lokasi Trendline</h3>
                    <p class="mt-1 text-sm text-slate-600">Data di bawah ini akan langsung tampil pada section lokasi di halaman depan.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="address" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                        <textarea id="address" name="address" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">{{ old('address', $setting->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="operational_hours" class="mb-2 block text-sm font-semibold text-slate-700">Jam Operasional</label>
                        <input id="operational_hours" name="operational_hours" type="text" value="{{ old('operational_hours', $setting->operational_hours) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('operational_hours')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reservation_info" class="mb-2 block text-sm font-semibold text-slate-700">Info Reservasi</label>
                        <textarea id="reservation_info" name="reservation_info" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">{{ old('reservation_info', $setting->reservation_info) }}</textarea>
                        @error('reservation_info')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="google_maps_embed" class="mb-2 block text-sm font-semibold text-slate-700">Embed Lokasi Google Map</label>
                        <textarea id="google_maps_embed" name="google_maps_embed" rows="6" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 font-mono text-sm text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">{{ old('google_maps_embed', $setting->google_maps_embed) }}</textarea>
                        <p class="mt-2 text-xs text-slate-500">Paste kode embed dari Google Maps, misalnya tag iframe lengkap.</p>
                        @error('google_maps_embed')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-2">
                @foreach ($fileCards as $fileCard)
                    <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">{{ $fileCard['label'] }}</label>
                        <input name="{{ $fileCard['name'] }}" type="file" accept="{{ $fileCard['accept'] }}" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-slate-800">
                        @error($fileCard['name'])
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror

                        @if ($fileCard['path'])
                            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                                <p class="text-sm font-semibold text-emerald-700">File sudah terupload.</p>
                                <div class="mt-3 flex flex-wrap gap-3">
                                    <a href="{{ $fileCard['viewRoute'] }}" target="_blank" class="rounded-xl border border-emerald-300 px-4 py-2 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100">
                                        View
                                    </a>
                                    <button
                                        type="submit"
                                        form="{{ $fileCard['deleteFormId'] }}"
                                        onclick="return confirm('Hapus file ini?')"
                                        class="rounded-xl border border-rose-200 px-4 py-2 text-sm font-bold text-rose-600 transition hover:bg-rose-50"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="forex_referral_link" class="mb-2 block text-sm font-semibold text-slate-700">Link Referal Forex</label>
                        <input id="forex_referral_link" name="forex_referral_link" type="url" value="{{ old('forex_referral_link', $setting->forex_referral_link) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('forex_referral_link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ihsg_stock_referral_link" class="mb-2 block text-sm font-semibold text-slate-700">Link Referal Saham IHSG</label>
                        <input id="ihsg_stock_referral_link" name="ihsg_stock_referral_link" type="url" value="{{ old('ihsg_stock_referral_link', $setting->ihsg_stock_referral_link) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('ihsg_stock_referral_link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="wa_group_link" class="mb-2 block text-sm font-semibold text-slate-700">Link Group WA</label>
                        <input id="wa_group_link" name="wa_group_link" type="url" value="{{ old('wa_group_link', $setting->wa_group_link) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('wa_group_link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telegram_group_link" class="mb-2 block text-sm font-semibold text-slate-700">Link Group Telegram</label>
                        <input id="telegram_group_link" name="telegram_group_link" type="url" value="{{ old('telegram_group_link', $setting->telegram_group_link) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('telegram_group_link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instagram_link" class="mb-2 block text-sm font-semibold text-slate-700">Link Akun IG</label>
                        <input id="instagram_link" name="instagram_link" type="url" value="{{ old('instagram_link', $setting->instagram_link) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('instagram_link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tiktok_link" class="mb-2 block text-sm font-semibold text-slate-700">Link Akun Tiktok</label>
                        <input id="tiktok_link" name="tiktok_link" type="url" value="{{ old('tiktok_link', $setting->tiktok_link) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-amber-400 focus:outline-none">
                        @error('tiktok_link')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-800" data-submit-button>
                        <span class="submit-label">Simpan</span>
                        <span class="submit-spinner hidden items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </section>
        </form>

        @foreach ($fileCards as $fileCard)
            @if ($fileCard['path'])
                <form id="{{ $fileCard['deleteFormId'] }}" action="{{ $fileCard['deleteRoute'] }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        @endforeach
    </div>

    <script>
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

        @if (session('status_alert'))
            alert(@json(session('status_alert')));
        @endif
    </script>
@endsection
