<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Artikel Edukasi</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#0a1628] font-['Plus_Jakarta_Sans'] text-white">
    <div class="mx-auto max-w-6xl px-4 py-10 md:px-6">
        <div class="rounded-[32px] border border-[rgba(201,168,76,0.18)] bg-[linear-gradient(160deg,rgba(10,22,40,0.98)_0%,rgba(13,31,60,0.95)_60%,rgba(26,48,96,0.9)_100%)] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.28)] md:p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="inline-flex rounded-full bg-[linear-gradient(135deg,#c9a84c,#f0c060)] px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.24em] text-[#0a1628]">Artikel</p>
                    <h1 class="mt-4 font-['Playfair_Display'] text-3xl font-black md:text-5xl">Artikel Edukasi</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/65 md:text-base">Kumpulan artikel terbaru dari admin untuk Forex, Saham, dan Emas.</p>
                </div>
                <a href="{{ route('home') }}#edukasi" class="inline-flex items-center justify-center rounded-full border border-[rgba(201,168,76,0.35)] px-5 py-3 text-sm font-bold text-[#c9a84c] transition hover:bg-white/5">Kembali ke Home</a>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('public.articles.index') }}" class="rounded-full px-4 py-2 text-sm font-bold transition {{ blank($activeCategoryKey) ? 'bg-[linear-gradient(135deg,#c9a84c,#f0c060)] text-[#0a1628]' : 'border border-[rgba(201,168,76,0.3)] text-white/75 hover:bg-white/5' }}">Semua</a>
                @foreach ($categories as $category)
                    @php($categoryKey = strtolower($category->name))
                    <a href="{{ route('public.articles.index', ['category' => $categoryKey]) }}" class="rounded-full px-4 py-2 text-sm font-bold transition {{ $activeCategoryKey === $categoryKey ? 'bg-[linear-gradient(135deg,#c9a84c,#f0c060)] text-[#0a1628]' : 'border border-[rgba(201,168,76,0.3)] text-white/75 hover:bg-white/5' }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($articles as $article)
                <article class="rounded-[28px] border border-[rgba(201,168,76,0.18)] bg-white/5 p-5 backdrop-blur-md">
                    <div class="flex items-center justify-between gap-3 text-xs text-white/50">
                        <span>{{ $article->published_at?->format('d M Y') }}</span>
                        <span class="rounded-full border border-[rgba(201,168,76,0.24)] px-3 py-1 text-[#c9a84c]">{{ $article->category?->name ?? '-' }}</span>
                    </div>
                    <h2 class="mt-4 text-lg font-black leading-7 text-white">{{ $article->title }}</h2>
                    <p class="mt-3 text-sm leading-7 text-white/65">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 150) }}</p>
                    <div class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-white/35">{{ $article->admin_name }}</div>
                </article>
            @empty
                <div class="col-span-full rounded-[28px] border border-[rgba(201,168,76,0.18)] bg-white/5 p-8 text-center text-white/60">
                    Belum ada artikel publish untuk kategori ini.
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
