<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $article->title }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#0a1628] font-['Plus_Jakarta_Sans'] text-white">
    <div class="mx-auto max-w-5xl px-4 py-10 md:px-6">
        <div class="rounded-[32px] border border-[rgba(201,168,76,0.18)] bg-[linear-gradient(160deg,rgba(10,22,40,0.98)_0%,rgba(13,31,60,0.95)_60%,rgba(26,48,96,0.9)_100%)] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.28)] md:p-8">
            <a href="{{ route('public.articles.index') }}" class="inline-flex items-center justify-center rounded-full border border-[rgba(201,168,76,0.35)] px-5 py-3 text-sm font-bold text-[#c9a84c] transition hover:bg-white/5">Kembali ke Artikel</a>
            <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-white/55">
                <span>{{ $article->published_at?->format('d M Y') }}</span>
                <span class="rounded-full border border-[rgba(201,168,76,0.24)] px-3 py-1 text-[#c9a84c]">{{ $article->category?->name ?? '-' }}</span>
                <span>{{ $article->admin_name }}</span>
            </div>
            <h1 class="mt-4 font-['Playfair_Display'] text-3xl font-black leading-tight md:text-5xl">{{ $article->title }}</h1>
            <div class="mt-6 prose prose-invert max-w-none prose-p:text-white/80 prose-li:text-white/80 prose-strong:text-white">
                {!! $article->content !!}
            </div>

            @if ($attachmentUrl)
                <div class="mt-8 rounded-[28px] border border-[rgba(201,168,76,0.18)] bg-white/5 p-4">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 class="text-lg font-black text-white">Lampiran Artikel</h2>
                        <a href="{{ $attachmentUrl }}" target="_blank" rel="noreferrer" class="rounded-full border border-[rgba(201,168,76,0.35)] px-4 py-2 text-xs font-bold text-[#c9a84c] transition hover:bg-white/5">
                            Buka Penuh
                        </a>
                    </div>

                    @if ($isPdfAttachment)
                        <iframe src="{{ $attachmentUrl }}" class="h-[75vh] w-full rounded-2xl bg-white" title="{{ $article->title }} PDF"></iframe>
                    @else
                        <img src="{{ $attachmentUrl }}" alt="{{ $article->title }}" class="w-full rounded-2xl bg-white object-contain">
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>
