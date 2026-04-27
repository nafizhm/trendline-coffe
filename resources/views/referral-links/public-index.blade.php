<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $appSetting = \App\Models\AppSetting::query()->first();
        $appName = $appSetting?->app_name ?: 'Trendline';
        $pageTitle = $type === 'forex' ? 'Buka Akun Forex' : 'Buka Akun Saham';
        $pageDescription = $type === 'forex'
            ? 'Pilih broker forex resmi dari tim ' . $appName . ' lalu klik logo untuk melanjutkan pendaftaran.'
            : 'Pilih mitra saham resmi dari tim ' . $appName . ' lalu klik logo untuk melanjutkan pendaftaran.';
    @endphp
    <title>{{ $pageTitle }} - {{ $appName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top, #173158 0%, #0d1f3c 48%, #091426 100%);
            color: #fff;
        }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen">
    <div class="mx-auto max-w-6xl px-4 py-8 md:px-6 md:py-12">
        <a href="{{ route('home') }}#broker" class="inline-flex items-center rounded-full border border-[rgba(201,168,76,0.35)] px-4 py-2 text-sm font-semibold text-[#f0c060] transition hover:bg-white/5">
            Kembali
        </a>

        <div class="mt-8 text-center">
            <span class="inline-flex rounded-full bg-[rgba(201,168,76,0.15)] px-4 py-1 text-xs font-extrabold tracking-[0.28em] text-[#f0c060]">
                {{ strtoupper($type) }}
            </span>
            <h1 class="font-display mt-5 text-4xl font-black md:text-6xl">{{ $pageTitle }}</h1>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-white/68 md:text-base">
                {{ $pageDescription }}
            </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($referralLinks as $referralLink)
                <a
                    href="{{ $referralLink->link }}"
                    target="_blank"
                    rel="noreferrer"
                    class="group rounded-[30px] border border-[rgba(201,168,76,0.18)] bg-white/5 p-6 shadow-2xl shadow-black/10 backdrop-blur-sm transition hover:-translate-y-1 hover:border-[rgba(240,192,96,0.55)] hover:bg-white/10"
                >
                    <div class="flex h-28 items-center justify-center rounded-[24px] bg-white/95 p-5">
                        @if ($referralLink->logo_path)
                            <img src="{{ asset('storage/' . $referralLink->logo_path) }}" alt="{{ $referralLink->name }} logo" class="max-h-full w-auto object-contain">
                        @else
                            <div class="text-center">
                                <div class="text-lg font-black text-slate-900">{{ $referralLink->name }}</div>
                                <div class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">No Logo</div>
                            </div>
                        @endif
                    </div>
                    <div class="mt-5">
                        <h2 class="text-xl font-extrabold text-white">{{ $referralLink->name }}</h2>
                        <p class="mt-2 text-sm leading-7 text-white/65">{{ $referralLink->description ?: 'Klik logo untuk membuka halaman pendaftaran resmi.' }}</p>
                        <div class="mt-4 text-sm font-bold text-[#f0c060] transition group-hover:translate-x-1">
                            Klik untuk daftar ->
                        </div>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 rounded-[30px] border border-[rgba(201,168,76,0.18)] bg-white/5 p-8 text-center text-sm leading-7 text-white/68 backdrop-blur-sm">
                    Belum ada link referal {{ $type === 'forex' ? 'Forex' : 'Saham' }} yang aktif.
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
