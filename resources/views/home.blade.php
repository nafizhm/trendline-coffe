<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
@php
  $appSetting = \App\Models\AppSetting::query()->first();
  $appName = $appSetting?->app_name ?: 'Trendline';
  $waNumber = preg_replace('/\D+/', '', $appSetting?->direct_wa_number ?? '');
  $waMessage = filled($appSetting?->direct_wa_message)
    ? $appSetting->direct_wa_message
    : 'Halo admin, saya tertarik belajar trading di ' . $appName;
  $waLink = $waNumber ? 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage) : '#';
  $logoUrl = $appSetting?->logo_path ? route('public.settings.files.show', 'logo') : null;
  $ownerPhotoUrl = $appSetting?->owner_photo_path ? route('public.settings.files.show', 'owner-photo') : null;
  $primaryBrokerLink = route('public.referral-links.index', ['type' => 'forex']);
  $contentByType = \App\Models\Content::query()
    ->get()
    ->mapWithKeys(fn ($item) => [strtolower(trim($item->type)) => $item->content]);
  $homeIntroContent = $contentByType->get('beranda');
  $coffeeCommunityContent = $contentByType->get('coffee & community');
  $smartTradingContent = $contentByType->get('trading cerdas');
  $officeAddress = $appSetting?->address ?: 'Trendline Coffee, Jl. Contoh Alamat Lengkap No. 123, Jakarta Selatan, DKI Jakarta 12345';
  $operationalHours = $appSetting?->operational_hours ?: 'Senin - Sabtu, 09.00 - 21.00 WIB';
  $reservationInfo = $appSetting?->reservation_info ?: 'Hubungi admin WhatsApp untuk konfirmasi kunjungan atau jadwal kelas offline.';
  preg_match('/src=\"([^\"]+)\"/', $appSetting?->google_maps_embed ?? '', $googleMapsMatches);
  $googleMapsEmbedUrl = $googleMapsMatches[1] ?? ('https://www.google.com/maps?q=' . rawurlencode($officeAddress) . '&output=embed');
  $latestSchedules = \App\Models\LatestSchedule::query()
    ->where('status', 'aktif')
    ->orderBy('event_date')
    ->orderBy('event_time')
    ->take(3)
    ->get();
  $forexSignals = \App\Models\DailySignal::query()
    ->where('type', 'forex')
    ->orderBy('sort_order')
    ->orderBy('id')
    ->get();
  $stockSignals = \App\Models\DailySignal::query()
    ->where('type', 'saham')
    ->orderBy('sort_order')
    ->orderBy('id')
    ->get();
  $formatSignalDateTime = function ($date, $time) {
    $formattedDate = null;

    if (! blank($date)) {
      try {
        $parsedDate = $date instanceof \Carbon\CarbonInterface
          ? $date
          : \Illuminate\Support\Carbon::parse($date);

        $bulanIndonesia = [
          1 => 'Januari',
          2 => 'Februari',
          3 => 'Maret',
          4 => 'April',
          5 => 'Mei',
          6 => 'Juni',
          7 => 'Juli',
          8 => 'Agustus',
          9 => 'September',
          10 => 'Oktober',
          11 => 'November',
          12 => 'Desember',
        ];

        $formattedDate = $parsedDate->day . ' ' . $bulanIndonesia[$parsedDate->month] . ' ' . $parsedDate->year;
      } catch (\Throwable $e) {
        $formattedDate = (string) $date;
      }
    }

    $formattedTime = ! blank($time) ? substr((string) $time, 0, 5) . ' WITA' : null;

    return collect([$formattedDate, $formattedTime])->filter()->implode(' • ');
  };
  $categoryCollection = \App\Models\Category::query()->orderBy('name')->get();
  $categoryLookup = $categoryCollection->mapWithKeys(fn ($category) => [strtolower($category->name) => $category]);
  $educationTabs = collect([
    'forex' => ['label' => 'Forex'],
    'saham' => ['label' => 'Saham'],
    'emas' => ['label' => 'Emas'],
  ])->map(function (array $tab, string $key) use ($categoryLookup) {
    $category = $categoryLookup->get($key);

    return [
      'label' => $tab['label'],
      'category' => $category,
      'articles' => $category
        ? \App\Models\Article::query()
            ->with('category')
            ->where('status', 'publish')
            ->where('category_id', $category->id)
            ->orderByDesc('published_at')
            ->latest('id')
            ->take(3)
            ->get()
        : collect(),
      'videos' => $category
        ? \App\Models\Video::query()
            ->with('category')
            ->where('status', 'publish')
            ->where('category_id', $category->id)
            ->orderByDesc('published_at')
            ->latest('id')
            ->take(2)
            ->get()
        : collect(),
    ];
  });
@endphp
<title>{{ $appName }} - Coffee & Trading Hub</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --navy: #0a1628;
    --navy2: #0d1f3c;
    --gold: #c9a84c;
    --gold2: #f0c060;
  }
  * { box-sizing: border-box; }
  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--navy);
    color: #fff;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
  }
  .font-display { font-family: 'Playfair Display', serif; }

  .btn-gold {
    background: linear-gradient(135deg, #c9a84c, #f0c060, #c9a84c);
    background-size: 200%;
    color: #0a1628;
    font-weight: 700;
    transition: background-position 0.4s, transform 0.2s;
    display: inline-block;
  }
  .btn-gold:hover { background-position: right; transform: translateY(-2px); }

  .card-glass {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(201,168,76,0.2);
  }

  .hero-bg {
    background: linear-gradient(160deg, #0a1628 0%, #0d2248 60%, #1a3060 100%);
    position: relative;
    overflow: hidden;
  }
  .hero-bg::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);
    pointer-events: none;
  }

  .navbar { background: rgba(10,22,40,0.97); backdrop-filter: blur(12px); }

  .signal-card {
    background: linear-gradient(135deg, rgba(13,31,60,0.9), rgba(10,22,40,0.9));
    border-left: 3px solid var(--gold);
  }

  .tab-active { background: linear-gradient(135deg, #c9a84c, #f0c060); color: #0a1628; }

  /* TICKER */
  .ticker-wrap { overflow: hidden; white-space: nowrap; }
  .ticker { display: inline-block; animation: ticker 25s linear infinite; }
  @keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

  /* GOLD SHINE TEXT */
  .gold-shine {
    background: linear-gradient(90deg, #c9a84c, #fde68a, #c9a84c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* SCROLL REVEAL */
  .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.55s ease, transform 0.55s ease; }
  .reveal.visible { opacity: 1; transform: translateY(0); }

  /* BOTTOM NAV */
  .bottom-nav {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    z-index: 100;
    background: rgba(10,22,40,0.98);
    border-top: 1px solid rgba(201,168,76,0.25);
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 8px 0 max(8px, env(safe-area-inset-bottom));
    height: 64px;
  }
  .bottom-nav a {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    font-size: 10px;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    flex: 1;
    transition: color 0.2s;
  }
  .bottom-nav a.active, .bottom-nav a:hover { color: #c9a84c; }
  .bottom-nav .nav-center-btn {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, #c9a84c, #f0c060);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 10px;
    box-shadow: 0 4px 20px rgba(201,168,76,0.5);
  }

  /* WA FLOAT – above bottom nav on mobile */
  .wa-float {
    position: fixed;
    bottom: 76px;
    right: 16px;
    z-index: 99;
    width: 52px; height: 52px;
    background: #25D366;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 20px rgba(37,211,102,0.5);
    animation: pulse-wa 2.5s infinite;
    text-decoration: none;
  }
  @keyframes pulse-wa {
    0%,100% { box-shadow: 0 0 0 0 rgba(37,211,102,0.4); }
    50% { box-shadow: 0 0 0 10px rgba(37,211,102,0); }
  }

  /* WA TOP BUTTON */
  .wa-top {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 200;
    background: #fff;
    display: flex; align-items: center;
    padding: 10px 16px;
    gap: 10px;
    font-weight: 600;
    font-size: 14px;
    color: #333;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  }
  .wa-top svg { width: 28px; height: 28px; }

  /* BADGE */
  .badge-new {
    background: linear-gradient(135deg, #c9a84c, #f0c060);
    color: #0a1628;
    font-size: 10px; font-weight: 800;
    padding: 2px 8px; border-radius: 99px;
  }

  /* DISCLAIMER */
  .disclaimer { border-left: 3px solid #f59e0b; background: rgba(245,158,11,0.08); }

  /* OWNER SECTION MOBILE */
  .owner-img-wrap {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }
  .owner-img-wrap img {
    width: min(100%, 300px);
    height: 340px;
    object-fit: contain;
    object-position: top;
    filter: drop-shadow(0 0 24px rgba(201,168,76,0.35));
    position: relative;
    z-index: 1;
  }
  .owner-photo-stage {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    padding-top: 8px;
    padding-bottom: 34px;
  }
  .owner-photo-glow {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 260px;
    height: 260px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(201,168,76,0.18) 0%, transparent 70%);
    pointer-events: none;
  }
  .owner-badge {
    position: absolute;
    left: 50%;
    bottom: 18px;
    transform: translateX(-50%);
    z-index: 3;
    min-width: 185px;
    padding: 12px 26px 10px;
    border-radius: 999px;
    text-align: center;
    box-shadow: 0 18px 30px rgba(0, 0, 0, 0.25);
  }
  .owner-badge-name {
    font-weight: 900;
    font-size: 13px;
    letter-spacing: 0.08em;
    line-height: 1;
  }
  .owner-badge-role {
    margin-top: 6px;
    font-size: 10px;
    opacity: 0.88;
    line-height: 1.35;
  }

  /* HERO on mobile: stack vertical, text first */
  @media (max-width: 767px) {
    .hero-inner { flex-direction: column !important; }
    .hero-text { order: 2; text-align: center; }
    .hero-photo { order: 1; }
    .hero-cta { justify-content: center; }
    .hero-stats { justify-content: center; }
    .owner-img-wrap img { width: min(100%, 290px); height: 360px; }
    .owner-photo-stage { margin-bottom: 8px; padding-bottom: 38px; }
    .owner-photo-glow { width: 280px; height: 280px; top: 38%; }
    .owner-badge {
      width: min(100%, 240px);
      bottom: 14px;
      padding: 12px 18px 10px;
      border-radius: 999px;
    }
    .owner-badge-name { font-size: 13px; }
    .owner-badge-role { font-size: 10px; }
  }

  /* Section divider */
  .sec-div { border-top: 1px solid rgba(201,168,76,0.15); }

  /* Content padding for bottom nav */
  .page-content { padding-bottom: 72px; }

  /* Smooth scroll */
  html { scroll-behavior: smooth; }

  /* MOBILE TOP NAV TABS */
  .top-tabs {
    display: flex; overflow-x: auto; gap: 6px;
    padding: 8px 12px;
    scrollbar-width: none;
    background: rgba(13,31,60,0.8);
  }
  .top-tabs::-webkit-scrollbar { display: none; }
  .top-tab {
    white-space: nowrap; font-size: 12px; font-weight: 600;
    padding: 6px 14px; border-radius: 99px;
    border: 1px solid rgba(201,168,76,0.35);
    color: rgba(255,255,255,0.7);
    cursor: pointer; text-decoration: none;
    flex-shrink: 0;
    transition: all 0.2s;
  }
  .top-tab.active, .top-tab:hover {
    background: linear-gradient(135deg, #c9a84c, #f0c060);
    color: #0a1628; border-color: transparent;
  }
</style>
</head>
<body class="bg-[#0a1628]">

<!-- WA TOP BAR (mobile style) -->
<div class="wa-top">
  <svg viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  <a href="{{ $waLink }}" target="_blank" rel="noreferrer" style="color:inherit;text-decoration:none;">Hubungi Admin WA</a>
</div>

<!-- MAIN WRAPPER with top padding for WA bar -->
<div class="page-content" style="padding-top: 48px;">
  <!-- TICKER -->
  <div class="ticker-wrap py-1.5 border-b border-[rgba(201,168,76,0.2)]" style="background: rgba(13,31,60,0.6);">
    <div class="ticker text-[11px] text-[#c9a84c]/70 font-medium">
      <span class="mx-5">📈 XAU/USD: 3.320 <span class="text-green-400">+0.82%</span></span>
      <span class="mx-5">💹 EUR/USD: 1.0852 <span class="text-red-400">-0.12%</span></span>
      <span class="mx-5">📊 GBP/USD: 1.2741 <span class="text-green-400">+0.25%</span></span>
      <span class="mx-5">🏦 USD/JPY: 154.32 <span class="text-red-400">-0.08%</span></span>
      <span class="mx-5">💰 BTC: 94.280 <span class="text-green-400">+1.45%</span></span>
      <span class="mx-5">📈 IHSG: 7.124 <span class="text-green-400">+0.55%</span></span>
      <span class="mx-5">📈 XAU/USD: 3.320 <span class="text-green-400">+0.82%</span></span>
      <span class="mx-5">💹 EUR/USD: 1.0852 <span class="text-red-400">-0.12%</span></span>
      <span class="mx-5">📊 GBP/USD: 1.2741 <span class="text-green-400">+0.25%</span></span>
      <span class="mx-5">🏦 USD/JPY: 154.32 <span class="text-red-400">-0.08%</span></span>
    </div>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar sticky top-0 z-50 px-4 py-2.5 flex items-center justify-between" style="top: 48px;">
    <img src="{{ $logoUrl ?: 'https://placehold.co/160x56/0a1628/c9a84c?text=' . urlencode($appName) }}" alt="{{ $appName }}" class="h-9 w-auto">
    <!-- Desktop nav links -->
    <div class="hidden md:flex items-center gap-6 text-sm font-medium text-white/80">
      <a href="#home" class="hover:text-[#c9a84c] transition-colors">Beranda</a>
      <a href="#edukasi" class="hover:text-[#c9a84c] transition-colors">Edukasi</a>
      <a href="#sinyal" class="hover:text-[#c9a84c] transition-colors">Sinyal Harian</a>
      <a href="#komunitas" class="hover:text-[#c9a84c] transition-colors">Komunitas</a>
      <a href="#sosmed" class="hover:text-[#c9a84c] transition-colors">Sosial Media</a>
      <a href="#jadwal-terbaru" class="hover:text-[#c9a84c] transition-colors">Jadwal</a>
      <a href="#lokasi" class="hover:text-[#c9a84c] transition-colors">Lokasi</a>
    </div>
    <a href="#broker" class="btn-gold px-4 py-2 rounded-full text-xs md:text-sm font-bold whitespace-nowrap">Buka Akun Trading</a>
  </nav>

  <!-- MOBILE TOP TABS -->
  <div class="md:hidden top-tabs">
    <a href="#home" class="top-tab active">Beranda</a>
    <a href="#edukasi" class="top-tab">Edukasi</a>
    <a href="#sinyal" class="top-tab">Sinyal Harian</a>
    <a href="#komunitas" class="top-tab">Komunitas</a>
    <a href="#sosmed" class="top-tab">Sosial Media</a>
    <a href="#jadwal-terbaru" class="top-tab">Jadwal</a>
    <a href="#lokasi" class="top-tab">Lokasi</a>
  </div>

  <!-- ===== HERO ===== -->
  <section id="home" class="hero-bg px-4 pt-8 pb-10 md:py-20">
    <div class="max-w-5xl mx-auto">
      <div class="hero-inner flex md:flex-row flex-col items-center gap-6 md:gap-10">

        <!-- PHOTO (top on mobile) -->
        <div class="hero-photo owner-img-wrap md:order-2 md:flex-1">
          <div class="owner-photo-stage">
            <div class="owner-photo-glow"></div>
            <img src="{{ $ownerPhotoUrl ?: 'https://placehold.co/420x520/0d1f3c/c9a84c?text=Owner+Photo' }}" alt="{{ $appName }} owner">
            <div class="owner-badge btn-gold">
              <div class="owner-badge-name">SAHIR</div>
              <div class="owner-badge-role">Chief Executive Officer (CEO)</div>
            </div>
          </div>
        </div>

        <!-- TEXT (below photo on mobile) -->
        <div class="hero-text md:order-1 md:flex-1">
          <div class="flex flex-wrap gap-2 mb-4 hero-cta" style="justify-content:inherit;">
            <span style="border:1px solid rgba(201,168,76,0.5); color:#c9a84c; font-size:11px; padding:4px 10px; border-radius:99px;">☕ Edukasi Forex</span>
            <span style="border:1px solid rgba(201,168,76,0.5); color:#c9a84c; font-size:11px; padding:4px 10px; border-radius:99px;">📈 Edukasi Saham</span>
            <span style="border:1px solid rgba(201,168,76,0.5); color:#c9a84c; font-size:11px; padding:4px 10px; border-radius:99px;">🥇 Edukasi Emas</span>
          </div>
          <h1 class="font-display font-black leading-tight mb-3" style="font-size: clamp(28px, 8vw, 52px);">
            <span class="gold-shine">Belajar Trading</span><br>
            <span>Bersama Trendline</span>
          </h1>
          <div style="color:rgba(255,255,255,0.7); font-size:14px; line-height:1.7; margin-bottom:24px; max-width:420px;">
            {!! $homeIntroContent ?: 'Ngopi santai sambil belajar saham, forex, dan emas bersama komunitas trader. Dipandu langsung oleh mentor berpengalaman.' !!}
          </div>

        </div>

      </div>
    </div>
  </section>

  <!-- SLIDER DOTS (decorative) -->
  <div class="flex justify-center gap-2 py-3" style="background:rgba(13,31,60,0.6);">
    <span style="width:20px;height:6px;border-radius:3px;background:#c9a84c;display:inline-block;"></span>
    <span style="width:8px;height:6px;border-radius:3px;background:rgba(201,168,76,0.35);display:inline-block;"></span>
    <span style="width:8px;height:6px;border-radius:3px;background:rgba(201,168,76,0.35);display:inline-block;"></span>
  </div>

  <!-- ===== ABOUT ===== -->
  <section id="about" class="py-12 px-4 sec-div" style="background: #0d1f3c;">
    <div class="max-w-4xl mx-auto">
      <div class="reveal flex items-center gap-3 mb-5">
        <div class="btn-gold w-8 h-8 rounded-full flex items-center justify-center font-black text-sm flex-shrink-0">→</div>
        <h2 class="font-display font-black text-xl md:text-2xl">About {{ $appName }}</h2>
      </div>
      <div class="reveal grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="card-glass p-5 rounded-2xl">
          <div class="text-3xl mb-2">☕</div>
          <h3 class="font-bold text-[#c9a84c] mb-1 text-sm md:text-base">Coffee & Community</h3>
          <div style="color:rgba(255,255,255,0.65); font-size:13px; line-height:1.65;">{!! $coffeeCommunityContent ?: ($appName . ' adalah tempat ngopi santai, diskusi analisa pasar, dan belajar trading bersama.') !!}</div>
        </div>
        <div class="card-glass p-5 rounded-2xl">
          <div class="text-3xl mb-2">📈</div>
          <h3 class="font-bold text-[#c9a84c] mb-1 text-sm md:text-base">Trading Cerdas</h3>
          <div style="color:rgba(255,255,255,0.65); font-size:13px; line-height:1.65;">{!! $smartTradingContent ?: 'Dipandu CEO Sahirudin, kami menyediakan <strong class="text-white">edukasi Forex, Saham & Emas</strong> dari pemula hingga mahir.' !!}</div>
        </div>
      </div>
      <div class="disclaimer reveal mt-6 rounded-2xl px-5 py-5 md:px-6 md:py-6">
        <p style="color:#fbbf24; font-size:14px; line-height:1.85;">⚠️ <strong style="font-size:15px;">Disclaimer:</strong> Trading mengandung risiko tinggi. Pastikan memahami risiko sebelum berpartisipasi. Semua konten bersifat edukatif, bukan saran investasi.</p>
      </div>

    </div>
  </section>

  <!-- ===== EDUKASI ===== -->
  <section id="edukasi-legacy" class="hidden py-12 px-4 sec-div">
    <div class="max-w-4xl mx-auto">
      <div class="reveal text-center mb-8">
        <span class="badge-new mb-3 inline-block">🎓 EDUCATION</span>
        <h2 class="font-display font-black text-2xl md:text-3xl">Pilih Jalur Belajarmu</h2>
        <p style="color:rgba(255,255,255,0.55); font-size:13px; margin-top:6px;">Materi terstruktur dari nol hingga mahir</p>
      </div>
      <div class="reveal flex gap-2 mb-5 justify-center flex-wrap">
        <button onclick="showEdu('forex')" id="tab-forex-legacy" class="tab-active px-5 py-2 rounded-full text-xs font-bold">Forex</button>
        <button onclick="showEdu('saham')" id="tab-saham-legacy" class="top-tab text-xs" style="font-size:12px;">Saham</button>
        <button onclick="showEdu('emas')" id="tab-emas-legacy" class="top-tab text-xs" style="font-size:12px;">Emas</button>
      </div>
      <div id="edu-forex-legacy" class="reveal grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">📊</div>
          <h3 class="font-bold text-sm mb-1">Analisa Teknikal</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">Chart pattern, candlestick, indikator populer</p>
          <div style="font-size:11px; color:#c9a84c;">12 Materi <span class="badge-new ml-1">NEW</span></div>
        </div>
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">💰</div>
          <h3 class="font-bold text-sm mb-1">Money Management</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">Risk/reward, lot sizing, capital protection</p>
          <div style="font-size:11px; color:#c9a84c;">8 Materi</div>
        </div>
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">🧠</div>
          <h3 class="font-bold text-sm mb-1">Psikologi Trading</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">Mindset, emosi, disiplin trading</p>
          <div style="font-size:11px; color:#c9a84c;">6 Materi</div>
        </div>
      </div>
      <div id="edu-saham-legacy" class="hidden reveal grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">🔍</div>
          <h3 class="font-bold text-sm mb-1">Analisa Fundamental</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">Laporan keuangan, valuasi, rasio keuangan</p>
          <div style="font-size:11px; color:#c9a84c;">10 Materi</div>
        </div>
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">📉</div>
          <h3 class="font-bold text-sm mb-1">Valuasi Saham</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">PER, PBV, DCF, target harga wajar</p>
          <div style="font-size:11px; color:#c9a84c;">7 Materi</div>
        </div>
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">🎯</div>
          <h3 class="font-bold text-sm mb-1">Strategi Investasi</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">DCA, growth investing, dividend investing</p>
          <div style="font-size:11px; color:#c9a84c;">9 Materi</div>
        </div>
      </div>
      <div id="edu-emas-legacy" class="hidden reveal grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">🥇</div>
          <h3 class="font-bold text-sm mb-1">Dasar Trading Emas</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">XAUUSD, faktor penggerak harga emas</p>
          <div style="font-size:11px; color:#c9a84c;">8 Materi</div>
        </div>
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">📐</div>
          <h3 class="font-bold text-sm mb-1">Teknikal XAUUSD</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">Support/resistance, trend, entry timing</p>
          <div style="font-size:11px; color:#c9a84c;">6 Materi</div>
        </div>
        <div class="card-glass p-4 rounded-2xl hover:border-[#c9a84c]/50 transition-all">
          <div class="text-2xl mb-2">🌍</div>
          <h3 class="font-bold text-sm mb-1">Makro & Geopolitik</h3>
          <p style="color:rgba(255,255,255,0.55); font-size:12px; margin-bottom:10px;">Pengaruh berita global terhadap emas</p>
          <div style="font-size:11px; color:#c9a84c;">5 Materi</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== EDUKASI ===== -->
  <section id="edukasi" class="py-12 px-4 sec-div">
    <div class="max-w-4xl mx-auto">
      <div class="reveal text-center mb-8">
        <span class="badge-new mb-3 inline-block">EDUCATION</span>
        <h2 class="font-display font-black text-2xl md:text-3xl">Artikel & Video Edukasi</h2>
        <p style="color:rgba(255,255,255,0.55); font-size:13px; margin-top:6px;">Konten terbaru dari admin untuk setiap kategori pembelajaran</p>
      </div>
      <div class="reveal flex gap-2 mb-5 justify-center flex-wrap">
        <button onclick="showEdu('forex')" id="tab-forex" class="tab-active px-5 py-2 rounded-full text-xs font-bold">Forex</button>
        <button onclick="showEdu('saham')" id="tab-saham" class="top-tab text-xs" style="font-size:12px;">Saham</button>
        <button onclick="showEdu('emas')" id="tab-emas" class="top-tab text-xs" style="font-size:12px;">Emas</button>
      </div>
      @foreach ($educationTabs as $key => $tab)
        <div id="edu-{{ $key }}" class="{{ $loop->first ? '' : 'hidden ' }}reveal space-y-4">
          <div class="grid gap-4 lg:grid-cols-[1.35fr_1fr]">
            <div class="card-glass rounded-[28px] p-5">
              <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#c9a84c]">Artikel {{ $tab['label'] }}</p>
                  <h3 class="mt-1 text-lg font-black text-white">Berita & Insight Terbaru</h3>
                </div>
                <a href="{{ route('public.articles.index', ['category' => $key]) }}" class="rounded-full border border-[rgba(201,168,76,0.28)] px-4 py-2 text-xs font-bold text-[#c9a84c] transition hover:bg-white/5">Index Berita</a>
              </div>
              <div class="space-y-3">
                @forelse ($tab['articles'] as $article)
                  <a href="{{ route('public.articles.show', $article) }}" class="block rounded-2xl border border-[rgba(201,168,76,0.14)] bg-[rgba(255,255,255,0.03)] p-4 transition hover:border-[rgba(201,168,76,0.4)] hover:bg-[rgba(255,255,255,0.06)]">
                    <div class="flex items-center justify-between gap-3 text-[11px]" style="color:rgba(255,255,255,0.45);">
                      <span>{{ $article->published_at?->format('d M Y') }}</span>
                      <span>{{ $article->admin_name }}</span>
                    </div>
                    <h4 class="mt-2 text-sm font-bold leading-6 text-white md:text-base">{{ $article->title }}</h4>
                    <p class="mt-2 text-xs leading-6 md:text-sm" style="color:rgba(255,255,255,0.58);">
                      {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 120) }}
                    </p>
                  </a>
                @empty
                  <div class="rounded-2xl border border-dashed border-[rgba(201,168,76,0.2)] px-4 py-8 text-center text-sm" style="color:rgba(255,255,255,0.55);">
                    Belum ada artikel publish untuk kategori {{ $tab['label'] }}.
                  </div>
                @endforelse
              </div>
            </div>
            <div class="card-glass rounded-[28px] p-5">
              <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#c9a84c]">Video {{ $tab['label'] }}</p>
                  <h3 class="mt-1 text-lg font-black text-white">Tonton Materi Pilihan</h3>
                </div>
                <a href="{{ route('public.videos.index', ['category' => $key]) }}" class="rounded-full border border-[rgba(201,168,76,0.28)] px-4 py-2 text-xs font-bold text-[#c9a84c] transition hover:bg-white/5">Index Video</a>
              </div>
              <div class="space-y-3">
                @forelse ($tab['videos'] as $video)
                  <a href="https://www.youtube.com/watch?v={{ $video->youtube_code }}" target="_blank" rel="noreferrer" class="block overflow-hidden rounded-2xl border border-[rgba(201,168,76,0.14)] bg-[rgba(255,255,255,0.03)] transition hover:border-[rgba(201,168,76,0.4)]">
                    <img src="https://img.youtube.com/vi/{{ $video->youtube_code }}/hqdefault.jpg" alt="{{ $video->title }}" class="h-36 w-full object-cover">
                    <div class="p-4">
                      <div class="text-[11px]" style="color:rgba(255,255,255,0.45);">{{ $video->published_at?->format('d M Y') }} | {{ $video->admin_name }}</div>
                      <h4 class="mt-2 text-sm font-bold leading-6 text-white">{{ $video->title }}</h4>
                    </div>
                  </a>
                @empty
                  <div class="rounded-2xl border border-dashed border-[rgba(201,168,76,0.2)] px-4 py-8 text-center text-sm" style="color:rgba(255,255,255,0.55);">
                    Belum ada video publish untuk kategori {{ $tab['label'] }}.
                  </div>
                @endforelse
              </div>
            </div>
          </div>
          <div class="grid gap-3 md:grid-cols-2">
            <a href="{{ route('public.articles.index', ['category' => $key]) }}" class="card-glass rounded-2xl px-5 py-4 text-sm font-bold text-white transition hover:border-[#c9a84c]/50">
              Lihat semua artikel {{ $tab['label'] }} ->
            </a>
            <a href="{{ route('public.videos.index', ['category' => $key]) }}" class="card-glass rounded-2xl px-5 py-4 text-sm font-bold text-white transition hover:border-[#c9a84c]/50">
              Lihat semua video {{ $tab['label'] }} ->
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  <!-- ===== SIGNAL ===== -->
  <section id="sinyal" class="py-12 px-4 sec-div" style="background:#0d1f3c;">
    <div class="max-w-4xl mx-auto">
      <div class="reveal flex items-start justify-between mb-6 flex-wrap gap-3">
        <div>
          <span class="badge-new inline-block mb-2">📈 DAILY SIGNALS</span>
          <h2 class="font-display font-black text-2xl">Sinyal Harian</h2>
          <p style="color:rgba(255,255,255,0.45); font-size:11px; margin-top:3px;">Update setiap hari kerja • Bukan jaminan profit</p>
        </div>
        <div class="flex gap-2">
          <button onclick="showSignal('forex')" id="sig-forex" class="tab-active px-4 py-1.5 rounded-full text-xs font-bold">Forex</button>
          <button onclick="showSignal('saham')" id="sig-saham" class="top-tab text-xs" style="font-size:11px; padding: 6px 14px;">Saham</button>
        </div>
      </div>
      <div id="signal-forex" class="reveal space-y-3">
        @forelse ($forexSignals as $signal)
          <div class="signal-card p-4 rounded-xl">
            <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
              <div class="flex items-center gap-2 flex-wrap">
                <span style="background:rgba(201,168,76,0.15);color:#c9a84c;font-weight:900;font-size:12px;padding:3px 10px;border-radius:8px;">{{ $signal->symbol }}</span>
                <span style="color:rgba(255,255,255,0.45);font-size:11px;">{{ $signal->pair_name }}</span>
              </div>
              <span style="display:flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:{{ $signal->position === 'buy' ? '#4ade80' : '#f87171' }};"><span style="width:7px;height:7px;border-radius:50%;background:{{ $signal->position === 'buy' ? '#4ade80' : '#f87171' }};display:inline-block;"></span>{{ strtoupper($signal->position) }}</span>
            </div>
            @php($signalDateTime = $formatSignalDateTime($signal->signal_date, $signal->signal_time))
            @if ($signalDateTime)
              <div style="text-align:center;color:rgba(255,255,255,0.68);font-size:11px;font-weight:600;margin:-2px 0 10px;">
                {{ $signalDateTime }}
              </div>
            @endif
            <div class="grid grid-cols-3 gap-2 text-center mb-2">
              <div style="background:rgba(255,255,255,0.05);border-radius:8px;padding:6px;"><div style="font-size:10px;color:rgba(255,255,255,0.45);">Entry</div><div style="font-size:13px;font-weight:700;">{{ $signal->entry_value }}</div></div>
              <div style="background:rgba(74,222,128,0.1);border-radius:8px;padding:6px;"><div style="font-size:10px;color:rgba(255,255,255,0.45);">TP</div><div style="font-size:13px;font-weight:700;color:#4ade80;">{{ $signal->target_value }}</div></div>
              <div style="background:rgba(248,113,113,0.1);border-radius:8px;padding:6px;"><div style="font-size:10px;color:rgba(255,255,255,0.45);">SL</div><div style="font-size:13px;font-weight:700;color:#f87171;">{{ $signal->stop_value }}</div></div>
            </div>
            <p style="font-size:11px;color:rgba(255,255,255,0.5);line-height:1.5;">{{ $signal->description }}</p>
          </div>
        @empty
          <div class="rounded-2xl border border-dashed border-[rgba(201,168,76,0.2)] px-4 py-8 text-center text-sm" style="color:rgba(255,255,255,0.55);">
            Belum ada sinyal forex.
          </div>
        @endforelse
      </div>
      <div id="signal-saham" class="hidden reveal space-y-3">
        @forelse ($stockSignals as $signal)
          <div class="signal-card p-4 rounded-xl">
            <div class="flex items-center justify-between mb-2 flex-wrap gap-2"><div class="flex items-center gap-2 flex-wrap"><span style="background:rgba(201,168,76,0.15);color:#c9a84c;font-weight:900;font-size:12px;padding:3px 10px;border-radius:8px;">{{ $signal->symbol }}</span><span style="color:rgba(255,255,255,0.45);font-size:11px;">{{ $signal->pair_name }}</span></div><span style="color:{{ $signal->position === 'buy' ? '#4ade80' : '#f87171' }};font-size:11px;font-weight:700;">● {{ strtoupper($signal->position) }}</span></div>
            @php($signalDateTime = $formatSignalDateTime($signal->signal_date, $signal->signal_time))
            @if ($signalDateTime)
              <div style="text-align:center;color:rgba(255,255,255,0.68);font-size:11px;font-weight:600;margin:-2px 0 10px;">
                {{ $signalDateTime }}
              </div>
            @endif
            <div class="grid grid-cols-3 gap-2 text-center mb-2">
              <div style="background:rgba(255,255,255,0.05);border-radius:8px;padding:6px;"><div style="font-size:10px;color:rgba(255,255,255,0.45);">Entry</div><div style="font-size:13px;font-weight:700;">{{ $signal->entry_value }}</div></div>
              <div style="background:rgba(74,222,128,0.1);border-radius:8px;padding:6px;"><div style="font-size:10px;color:rgba(255,255,255,0.45);">Target</div><div style="font-size:13px;font-weight:700;color:#4ade80;">{{ $signal->target_value }}</div></div>
              <div style="background:rgba(248,113,113,0.1);border-radius:8px;padding:6px;"><div style="font-size:10px;color:rgba(255,255,255,0.45);">Support</div><div style="font-size:13px;font-weight:700;color:#f87171;">{{ $signal->stop_value }}</div></div>
            </div>
            <p style="font-size:11px;color:rgba(255,255,255,0.5);">{{ $signal->description }}</p>
          </div>
        @empty
          <div class="rounded-2xl border border-dashed border-[rgba(201,168,76,0.2)] px-4 py-8 text-center text-sm" style="color:rgba(255,255,255,0.55);">
            Belum ada sinyal saham.
          </div>
        @endforelse
      </div>
      <div class="disclaimer reveal mt-5 p-3 rounded-xl">
        <p style="color:#fbbf24;font-size:11px;">⚠️ Sinyal ini hanya <strong>opini</strong>, kami harap analisa kembali sebelum beraksi.</p>
      </div>
    </div>
  </section>

  <!-- ===== BROKER CTA ===== -->
  <section id="broker" class="py-12 px-4 sec-div">
    <div class="max-w-4xl mx-auto reveal">
      <div class="overflow-hidden rounded-[32px]" style="position:relative;background:linear-gradient(160deg, rgba(10,22,40,0.98) 0%, rgba(13,31,60,0.96) 58%, rgba(26,48,96,0.92) 100%);border:1px solid rgba(201,168,76,0.24);box-shadow:0 24px 60px rgba(0,0,0,0.3);">
        <div style="position:absolute;inset:auto -80px -120px auto;width:260px;height:260px;border-radius:999px;background:radial-gradient(circle, rgba(201,168,76,0.2) 0%, transparent 72%);pointer-events:none;"></div>
        <div style="position:absolute;inset:-90px auto auto -70px;width:220px;height:220px;border-radius:999px;background:radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 72%);pointer-events:none;"></div>
        <div class="px-6 py-8 md:px-12 md:py-12">
          <div class="text-center">
            <span class="badge-new mb-4 inline-block">LIVE ACCOUNT</span>
            <h2 style="font-family:'Plus Jakarta Sans', sans-serif;font-size:clamp(2.5rem,8vw,5.4rem);line-height:0.95;letter-spacing:0.04em;font-weight:800;text-transform:uppercase;color:#ffffff;text-shadow:0 8px 28px rgba(0,0,0,0.25);">
              Buka Akun<br>Trading Sekarang
            </h2>
            <p style="max-width:780px;margin:26px auto 0;color:rgba(255,255,255,0.72);font-size:clamp(14px,2vw,19px);line-height:1.9;">
              Mulai perjalanan trading kamu dengan broker terpercaya. Daftar melalui link {{ $appName }} dan dapatkan support langsung dari tim kami.
            </p>
          </div>
          <div class="mt-8 md:mt-10 flex flex-col gap-4">
            <a href="{{ route('public.referral-links.index', ['type' => 'forex']) }}" class="block w-full rounded-full px-6 py-4 text-center text-base font-bold transition hover:-translate-y-0.5 md:text-2xl" style="background:linear-gradient(135deg, #c9a84c 0%, #f0c060 52%, #d8b25b 100%);color:#0a1628;box-shadow:0 14px 30px rgba(201,168,76,0.28);">
              Buka Akun Forex ->
            </a>
            <a href="{{ route('public.referral-links.index', ['type' => 'saham']) }}" class="block w-full rounded-full px-6 py-4 text-center text-base font-bold text-white transition hover:-translate-y-0.5 md:text-2xl" style="border:1px solid rgba(201,168,76,0.38);background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);box-shadow:inset 0 0 0 1px rgba(255,255,255,0.03);">
              Buka Akun Saham ->
            </a>
          </div>
          <div class="mt-6 text-center" style="font-size:12px;color:rgba(255,255,255,0.46);line-height:1.7;">
            *Link referral resmi Tim {{ $appName }}. Gratis konsultasi setelah daftar.
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== KOMUNITAS ===== -->
  <section id="komunitas" class="py-12 px-4 sec-div" style="background:#0d1f3c;">
    <div class="max-w-4xl mx-auto">
      <div class="reveal text-center mb-8">
        <span class="badge-new mb-3 inline-block">👥 KOMUNITAS</span>
        <h2 class="font-display font-black text-2xl">Bergabung Bersama Kami</h2>
        <p style="color:rgba(255,255,255,0.55);font-size:13px;margin-top:6px;">Ribuan trader aktif siap berdiskusi bersamamu</p>
      </div>
      <div class="reveal grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="card-glass p-5 rounded-2xl text-center">
          <div class="text-3xl mb-2">📱</div>
          <h3 class="font-bold mb-1 text-[#c9a84c]">Group Forex</h3>
          <p style="color:rgba(255,255,255,0.6);font-size:12px;margin-bottom:16px;">Diskusi analisa forex, sharing sinyal, tips trading harian</p>
          <div class="flex gap-3 justify-center">
            <a href="{{ $appSetting?->wa_group_link ?: '#' }}" target="_blank" rel="noreferrer" style="background:#16a34a;color:#fff;padding:8px 16px;border-radius:99px;font-size:12px;font-weight:700;">WhatsApp</a>
            <a href="{{ $appSetting?->telegram_group_link ?: '#' }}" target="_blank" rel="noreferrer" style="background:#2563eb;color:#fff;padding:8px 16px;border-radius:99px;font-size:12px;font-weight:700;">Telegram</a>
          </div>
        </div>
        <div class="card-glass p-5 rounded-2xl text-center">
          <div class="text-3xl mb-2">💹</div>
          <h3 class="font-bold mb-1 text-[#c9a84c]">Group Saham</h3>
          <p style="color:rgba(255,255,255,0.6);font-size:12px;margin-bottom:16px;">Diskusi saham, rekomendasi, dan strategi investasi</p>
          <div class="flex gap-3 justify-center">
            <a href="{{ $appSetting?->wa_group_link ?: '#' }}" target="_blank" rel="noreferrer" style="background:#16a34a;color:#fff;padding:8px 16px;border-radius:99px;font-size:12px;font-weight:700;">WhatsApp</a>
            <a href="{{ $appSetting?->telegram_group_link ?: '#' }}" target="_blank" rel="noreferrer" style="background:#2563eb;color:#fff;padding:8px 16px;border-radius:99px;font-size:12px;font-weight:700;">Telegram</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== JADWAL TERBARU ===== -->
  <section id="jadwal-terbaru" class="py-12 px-4 sec-div">
    <div class="max-w-5xl mx-auto">
      <div class="reveal text-center mb-8">
        <span class="badge-new mb-3 inline-block">JADWAL</span>
        <h2 class="font-display font-black text-2xl md:text-3xl">Jadwal Terbaru</h2>
        <p style="color:rgba(255,255,255,0.58);font-size:13px;margin-top:8px;">Pantau agenda edukasi dan sesi market update terbaru dari tim {{ $appName }}.</p>
      </div>
      <div class="grid gap-4 md:grid-cols-3">
        @forelse ($latestSchedules as $schedule)
          <div class="reveal rounded-[28px] border border-[rgba(201,168,76,0.18)] bg-white/5 p-5 shadow-lg shadow-black/10 backdrop-blur-sm">
            <div class="inline-flex rounded-full px-3 py-1 text-[11px] font-extrabold tracking-[0.18em]" style="background:rgba(201,168,76,0.16); color:#f3d27e;">
              {{ \Illuminate\Support\Str::of($schedule->event_time)->append(' WIB') }}
            </div>
            <div class="mt-4 text-sm font-semibold text-[#c9a84c]">
              {{ $schedule->event_date?->translatedFormat('l, j F Y') }}
            </div>
            <h3 class="mt-2 text-lg font-extrabold leading-snug text-white">{{ $schedule->title }}</h3>
            <div class="mt-3 text-sm leading-7 text-white/68 [&_p]:m-0 [&_ul]:my-0 [&_ol]:my-0 [&_li]:ml-4">
              {!! $schedule->description !!}
            </div>
          </div>
        @empty
          <div class="reveal rounded-[28px] border border-[rgba(201,168,76,0.18)] bg-white/5 p-6 text-center text-sm leading-7 text-white/68 shadow-lg shadow-black/10 backdrop-blur-sm md:col-span-3">
            Jadwal terbaru belum tersedia. Silakan cek lagi sebentar lagi.
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ===== LOKASI ===== -->
  <section id="lokasi" class="py-12 px-4 sec-div" style="background:#0d1f3c;">
    <div class="max-w-5xl mx-auto">
      <div class="reveal text-center mb-8">
        <span class="badge-new mb-3 inline-block">LOKASI</span>
        <h2 class="font-display font-black text-2xl md:text-3xl">Lokasi {{ $appName }}</h2>
        <p style="color:rgba(255,255,255,0.58);font-size:13px;margin-top:8px;">Kunjungi lokasi kami untuk diskusi trading, ngopi santai, dan ikut sesi komunitas secara langsung.</p>
      </div>
      <div class="grid gap-5 md:grid-cols-[0.95fr_1.05fr]">
        <div class="reveal card-glass rounded-[28px] p-6">
          <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl" style="background:rgba(201,168,76,0.16); color:#c9a84c;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-5.686-6-11a6 6 0 1112 0c0 5.314-6 11-6 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
          </div>
          <h3 class="text-xl font-extrabold text-white">Alamat Lengkap</h3>
          <p class="mt-4 text-sm leading-7 text-white/72">
            {{ $officeAddress }}
          </p>
          <div class="mt-6 space-y-3 text-sm text-white/70">
            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
              <div class="font-semibold text-[#c9a84c]">Jam Operasional</div>
              <div class="mt-1">{{ $operationalHours }}</div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
              <div class="font-semibold text-[#c9a84c]">Info Reservasi</div>
              <div class="mt-1">{{ $reservationInfo }}</div>
            </div>
          </div>
        </div>
        <div class="reveal overflow-hidden rounded-[28px] border border-[rgba(201,168,76,0.22)] bg-[#091426] shadow-2xl">
          <iframe
            src="{{ $googleMapsEmbedUrl }}"
            class="h-[360px] w-full md:h-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== SOCIAL MEDIA ===== -->
  <section id="sosmed" class="py-12 px-4 sec-div">
    <div class="max-w-4xl mx-auto text-center reveal">
      <span class="badge-new mb-3 inline-block">📱 SOSIAL MEDIA</span>
      <h2 class="font-display font-black text-2xl mb-2">Ikuti Kami</h2>
      <p style="color:rgba(255,255,255,0.55);font-size:13px;margin-bottom:32px;">Update analisa & tips trading setiap hari</p>
      <div class="flex justify-center gap-6 flex-wrap">
        <a href="{{ $appSetting?->instagram_link ?: '#' }}" target="_blank" rel="noreferrer" class="flex flex-col items-center gap-2 group">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110" style="background:linear-gradient(45deg,#833ab4,#fd1d1d,#fcb045);">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </div>
          <span style="font-size:11px;color:rgba(255,255,255,0.6);">Instagram</span>
        </a>
        <a href="{{ $appSetting?->facebook_link ?: '#' }}" target="_blank" rel="noreferrer" class="flex flex-col items-center gap-2 group">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110" style="background:#1877F2;">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.8c0-.9.2-1.6 1.5-1.6h1.7V4.3c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.4v2.2H7.5V14h2.5v8h3.5z"/></svg>
          </div>
          <span style="font-size:11px;color:rgba(255,255,255,0.6);">Facebook</span>
        </a>
        <a href="{{ $appSetting?->tiktok_link ?: '#' }}" target="_blank" rel="noreferrer" class="flex flex-col items-center gap-2 group">
          <div class="relative w-14 h-14 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110" style="background:#0f0f10; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.06);">
            <span aria-hidden="true" class="absolute text-[30px] font-black leading-none" style="color:#25F4EE; transform:translate(2px,1px);">♪</span>
            <span aria-hidden="true" class="absolute text-[30px] font-black leading-none" style="color:#FE2C55; transform:translate(-2px,-1px);">♪</span>
            <span aria-hidden="true" class="relative text-[30px] font-black leading-none text-white">♪</span>
          </div>
          <span style="font-size:11px;color:rgba(255,255,255,0.6);">TikTok</span>
        </a>
        <a href="{{ $appSetting?->youtube_link ?: '#' }}" target="_blank" rel="noreferrer" class="flex flex-col items-center gap-2 group">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110" style="background:#FF0000;">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.3 3.6-6.3 3.6Z"/></svg>
          </div>
          <span style="font-size:11px;color:rgba(255,255,255,0.6);">YouTube</span>
        </a>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="py-8 px-4" style="border-top:1px solid rgba(201,168,76,0.1);">
    <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <img src="{{ $logoUrl ?: 'https://placehold.co/160x56/0a1628/c9a84c?text=' . urlencode($appName) }}" alt="{{ $appName }}" class="h-10 w-auto">
        <div>
          <div style="font-weight:900;font-size:14px;">{{ $appName }}</div>
          <div style="color:rgba(255,255,255,0.4);font-size:11px;">Coffee & Trading Hub</div>
        </div>
      </div>
      <div style="text-align:center;font-size:11px;color:rgba(255,255,255,0.35);">
        © 2025 {{ $appName }} Coffee & Trading Hub.<br>
        <span style="color:rgba(202,138,4,0.7);">⚠️ Trading mengandung risiko. Pahami risiko sebelum berpartisipasi.</span>
      </div>
      <div class="flex gap-4" style="font-size:12px;color:rgba(255,255,255,0.4);">
        <a href="#" class="hover:text-[#c9a84c]">Disclaimer</a>
        <a href="{{ $waLink }}" target="_blank" rel="noreferrer" class="hover:text-[#c9a84c]">Kontak</a>
      </div>
    </div>
  </footer>

</div><!-- end page-content -->

<!-- FLOATING WA BUTTON -->
<a href="{{ $waLink }}" target="_blank" rel="noreferrer" class="wa-float">
  <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<!-- BOTTOM NAV (mobile-style, always visible) -->
<nav class="bottom-nav">
  <a href="#home" class="active" id="nav-home">
    <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    <span>Beranda</span>
  </a>
  <a href="#edukasi" id="nav-edukasi">
    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    <span>Edukasi</span>
  </a>
  <!-- Center big button -->
  <a href="#sinyal" id="nav-sinyal" style="color:#0a1628;">
    <div class="nav-center-btn">
      <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24"><path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/></svg>
    </div>
    <span style="color:rgba(255,255,255,0.45);font-size:10px;">Sinyal</span>
  </a>
  <a href="#komunitas" id="nav-komunitas">
    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    <span>Komunitas</span>
  </a>
  <a href="#sosmed" id="nav-sosmed">
    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
    <span>Sosmed</span>
  </a>
</nav>

<script>
// Scroll reveal
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.08 });
document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// Education tabs
function showEdu(type) {
  ['forex','saham','emas'].forEach(t => {
    document.getElementById('edu-' + t).classList.add('hidden');
    const btn = document.getElementById('tab-' + t);
    btn.className = 'top-tab text-xs';
    btn.style.fontSize = '12px';
  });
  document.getElementById('edu-' + type).classList.remove('hidden');
  const active = document.getElementById('tab-' + type);
  active.className = 'tab-active px-5 py-2 rounded-full text-xs font-bold';
}

// Signal tabs
function showSignal(type) {
  ['forex','saham'].forEach(t => {
    document.getElementById('signal-' + t).classList.add('hidden');
    const btn = document.getElementById('sig-' + t);
    btn.className = 'top-tab text-xs';
    btn.style.cssText = 'font-size:11px;padding:6px 14px;';
  });
  document.getElementById('signal-' + type).classList.remove('hidden');
  const active = document.getElementById('sig-' + type);
  active.className = 'tab-active px-4 py-1.5 rounded-full text-xs font-bold';
}

// Active bottom nav on scroll
const sections = ['home','lokasi','jadwal-terbaru','edukasi','sinyal','komunitas','sosmed'];
const navIds   = ['home','lokasi','jadwal-terbaru','edukasi','sinyal','komunitas','sosmed'];
const sectionEls = sections.map(id => document.getElementById(id)).filter(Boolean);

const navObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      navIds.forEach(id => {
        const el = document.getElementById('nav-' + id);
        if (el) el.classList.remove('active');
      });
      const active = document.getElementById('nav-' + e.target.id);
      if (active) active.classList.add('active');
    }
  });
}, { threshold: 0.4 });
sectionEls.forEach(el => navObserver.observe(el));
</script>
</body>
</html>
