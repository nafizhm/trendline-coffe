<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>Trendline &mdash; Menu</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --green:#5C8F58;
    --green-deep:#3A5E38;
    --green-tint:#E4EFE1;
    --teal:#4C8C82;
    --teal-tint:#E1EEEC;
    --gold:#B4903A;
    --gold-tint:#F3E9D2;
    --ink:#20291F;
    --ink-soft:rgba(32,41,31,0.6);
    --paper:#FFFFFF;
    --cream:#F6F6F1;
    --line:rgba(32,41,31,0.10);
    --terminal-bg:#182018;
    --terminal-green:#7FDD84;
    --radius:16px;
  }
  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{
    background:var(--cream);
    color:var(--ink);
    font-family:'Inter', sans-serif;
    -webkit-font-smoothing:antialiased;
    min-height:100vh;
    padding-bottom:104px;
  }
  ::selection{background:var(--green-tint); color:var(--green-deep);}
  h2,p{margin:0;}

  header.top{
    position:sticky; top:0; z-index:40;
    background:var(--paper);
    border-bottom:1px solid var(--line);
    padding:8px 18px 0;
  }
  .top-row{ display:flex; align-items:center; justify-content:center; gap:12px; }
  .brand{display:flex; align-items:center; gap:10px;}
  .brand-logo{width:140px; height:auto; flex-shrink:0;}
  .brand-text .wordmark{
    font-family:'Poppins', sans-serif; font-weight:800; font-size:20px;
    letter-spacing:.01em; color:var(--green-deep); line-height:1;
  }
  .brand-text .tagline{
    font-family:'JetBrains Mono', monospace; font-size:9.5px;
    letter-spacing:.14em; text-transform:uppercase;
    color:var(--ink-soft); margin-top:4px;
  }
  .spark-wrap{padding:4px 0 0;}
  .spark-wrap svg{display:block; width:100%; height:20px;}

  .tabs{ display:flex; gap:8px; overflow-x:auto; padding:8px 18px 10px; scrollbar-width:none; }
  .tabs::-webkit-scrollbar{display:none;}
  .tab{
    flex:0 0 auto; font-family:'Inter', sans-serif; font-weight:600; font-size:12.5px;
    padding:8px 15px; border-radius:999px; border:1.3px solid var(--line);
    background:var(--paper); color:var(--ink-soft); cursor:pointer; transition:all .18s ease;
  }
  .tab:hover{border-color:var(--green); color:var(--green-deep);}
  .tab.active{ background:var(--green); border-color:var(--green); color:#fff; }

  main{padding:2px 16px 4px; max-width:640px; margin:0 auto;}
  .section-label{
    display:flex; align-items:center; gap:8px;
    font-family:'JetBrains Mono', monospace; font-size:11px; letter-spacing:.12em; text-transform:uppercase;
    color:var(--green-deep); margin:22px 4px 10px;
  }
  .section-label::after{content:''; flex:1; height:1px; background:var(--line);}
  .grid{display:flex; flex-direction:column; gap:12px;}

  .card{
    background:var(--paper); border:1px solid var(--line); border-radius:var(--radius);
    padding:10px; display:flex; gap:13px; align-items:center;
    transition:box-shadow .15s ease, transform .15s ease;
    cursor:pointer; text-align:left; width:100%;
  }
  .card:hover{box-shadow:0 8px 22px rgba(32,41,31,0.08); transform:translateY(-1px);}
  .card:focus-visible{outline:2px solid var(--green); outline-offset:2px;}

  .hero-tile{
    width:72px; height:72px; border-radius:13px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; overflow:hidden;
    color:#fff;
  }
  .hero-tile.kopi{background:var(--green);}
  .hero-tile.nonkopi{background:var(--teal);}
  .hero-tile.cemilan{background:var(--gold);}
  .hero-tile svg{width:78%; height:78%;}
  .hero-tile img{width:100%; height:100%; object-fit:cover;}

  .card-info{flex:1; min-width:0;}
  .card-title-row{display:flex; align-items:center; gap:8px; flex-wrap:wrap;}
  .card-title{ font-family:'Poppins', sans-serif; font-weight:600; font-size:15px; color:var(--ink); line-height:1.25; }
  .tag{
    font-family:'JetBrains Mono', monospace; font-size:9px; letter-spacing:.06em; text-transform:uppercase;
    color:var(--gold); border:1px solid var(--gold); border-radius:4px; padding:2px 6px;
  }
  .card-desc{ font-size:12px; color:var(--ink-soft); margin-top:3px; line-height:1.4;
    display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
  .price-row{
    display:flex; align-items:center; gap:5px; margin-top:7px;
    font-family:'JetBrains Mono', monospace; font-weight:700; font-size:13.5px; color:var(--green-deep);
  }
  .price-row .up{color:var(--green); font-size:10px;}

  .chevron{ flex-shrink:0; color:var(--ink-soft); opacity:.5; }
  .chevron svg{width:18px; height:18px;}

  footer{ max-width:640px; margin:30px auto 0; padding:20px 24px 0; text-align:center; }
  footer .note{ font-size:12.5px; color:var(--ink-soft); line-height:1.6; }
  footer .note b{color:var(--green-deep);}
  footer .foot-mark{width:26px; height:20px; margin:14px auto 6px; opacity:.5;}

  .bottom-menu{
    position:fixed; left:0; right:0; bottom:0; z-index:50;
    background:rgba(255,255,255,0.96);
    border-top:1px solid var(--line);
    box-shadow:0 -10px 30px rgba(32,41,31,0.08);
    backdrop-filter:blur(12px);
  }
  .bottom-menu-inner{
    max-width:640px; margin:0 auto;
    display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:10px;
    padding:10px 16px calc(10px + env(safe-area-inset-bottom));
  }
  .bottom-menu a{
    display:flex; align-items:center; justify-content:center; gap:8px;
    min-height:48px; border-radius:12px;
    font-family:'Inter', sans-serif; font-size:13px; font-weight:700;
    color:var(--green-deep); background:var(--green-tint);
    border:1px solid rgba(92,143,88,0.18);
    text-decoration:none; transition:background .15s ease, transform .15s ease;
  }
  .bottom-menu a:hover{background:#d9ead6; transform:translateY(-1px);}
  .bottom-menu a:focus-visible{outline:2px solid var(--green); outline-offset:2px;}
  .bottom-menu svg{width:18px; height:18px; flex-shrink:0;}

  .overlay{
    position:fixed; inset:0; background:rgba(20,26,19,0.5);
    opacity:0; pointer-events:none; transition:opacity .25s ease; z-index:60;
  }
  .overlay.show{opacity:1; pointer-events:auto;}

  .detail-sheet{
    position:fixed; left:0; right:0; bottom:0; z-index:70;
    max-width:640px; margin:0 auto;
    background:var(--paper);
    border-radius:24px 24px 0 0;
    max-height:90vh; overflow-y:auto;
    transform:translateY(100%);
    transition:transform .32s cubic-bezier(.32,.9,.35,1);
    box-shadow:0 -20px 50px rgba(0,0,0,0.25);
  }
  .detail-sheet.show{transform:translateY(0);}

  .sheet-hero{
    position:relative;
    height:200px;
    border-radius:24px 24px 0 0;
    display:flex; align-items:center; justify-content:center;
    color:#fff; overflow:hidden;
  }
  .sheet-hero.kopi{background:linear-gradient(155deg, var(--green), #476E43);}
  .sheet-hero.nonkopi{background:linear-gradient(155deg, var(--teal), #396A62);}
  .sheet-hero.cemilan{background:linear-gradient(155deg, var(--gold), #93712A);}
  .sheet-hero svg{width:44%; height:44%;}
  .sheet-hero.photo{background:#111;}
  .sheet-hero.photo #sheet-hero-icon{width:100%; height:100%;}
  .sheet-hero.photo img{width:100%; height:100%; object-fit:cover;}

  .sheet-handle{
    position:absolute; top:12px; left:50%; transform:translateX(-50%);
    width:38px; height:4px; border-radius:99px; background:rgba(255,255,255,0.55);
  }
  .sheet-close{
    position:absolute; top:14px; right:14px;
    width:32px; height:32px; border-radius:50%;
    background:rgba(255,255,255,0.92); color:var(--ink);
    border:none; display:flex; align-items:center; justify-content:center;
    cursor:pointer; box-shadow:0 3px 10px rgba(0,0,0,0.18);
  }
  .sheet-close svg{width:16px; height:16px;}

  .sheet-body{padding:20px 22px 26px;}
  .sheet-cat-pill{
    display:inline-block; font-family:'JetBrains Mono', monospace;
    font-size:10px; letter-spacing:.1em; text-transform:uppercase;
    color:var(--ink-soft); background:var(--cream); border-radius:6px;
    padding:4px 8px; margin-bottom:10px;
  }
  .sheet-title-row{ display:flex; align-items:center; gap:9px; flex-wrap:wrap; }
  .sheet-title{ font-family:'Poppins', sans-serif; font-weight:700; font-size:21px; color:var(--ink); }
  .sheet-desc{ font-size:14px; color:var(--ink-soft); line-height:1.65; margin-top:10px; }
  .sheet-price-row{
    display:flex; align-items:center; justify-content:space-between;
    margin-top:18px; padding-top:16px; border-top:1px solid var(--line);
  }
  .sheet-price{
    font-family:'JetBrains Mono', monospace; font-weight:700; font-size:20px; color:var(--green-deep);
    display:flex; align-items:center; gap:6px;
  }
  .sheet-price .up{color:var(--green); font-size:13px;}
  .sheet-hint{ font-size:11.5px; color:var(--ink-soft); }

  @media (prefers-reduced-motion: reduce){
    *{transition:none !important;}
  }
</style>
</head>
<body>

<header class="top">
  <div class="top-row">
    <div class="brand">
      <img src="{{ asset('images/trendline-logo.png') }}" alt="Trendline Logo" class="brand-logo">
    </div>
  </div>

  <div class="spark-wrap">
    <svg viewBox="0 0 400 26" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <polyline points="0,20 40,14 80,22 130,8 180,16 230,4 280,12 330,6 400,10"
        fill="none" stroke="#5C8F58" stroke-width="1.6" opacity="0.55" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>

  <nav class="tabs" id="tabs">
    <button class="tab active" data-cat="semua">Semua</button>
    @foreach ($categories as $category)
      <button class="tab" data-cat="{{ $category->slug }}">{{ $category->name }}</button>
    @endforeach
  </nav>
</header>

<main>
  <div id="menu-sections"></div>
</main>

<footer>
  <div class="note">Ketuk menu untuk lihat detail. Sampaikan pilihanmu ke <b>kasir / barista</b> untuk memesan.</div>
  <svg class="foot-mark" viewBox="0 0 220 170" xmlns="http://www.w3.org/2000/svg">
    <polyline points="12,145 58,78 92,115 135,55 185,15" fill="none" stroke="#5C8F58" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</footer>

<nav class="bottom-menu" aria-label="Menu bawah">
  <div class="bottom-menu-inner">
    <a href="https://trendlinecoffee.com/" target="_blank" rel="noopener noreferrer">
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3.5 10.5 12 3l8.5 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M5.5 9.5V20h13V9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M9.5 20v-6h5v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span>Edukasi Traiding</span>
    </a>
    <a href="https://kuesioner.trendlinecoffee.com/" target="_blank" rel="noopener noreferrer">
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M8 6h12M8 12h12M8 18h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M4 6h.01M4 12h.01M4 18h.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
      </svg>
      <span>Saran/Masukan</span>
    </a>
  </div>
</nav>

<div class="overlay" id="overlay"></div>
<div class="detail-sheet" id="detail-sheet" role="dialog" aria-modal="true" aria-labelledby="sheet-title">
  <div class="sheet-hero" id="sheet-hero">
    <div class="sheet-handle"></div>
    <button class="sheet-close" id="sheet-close" aria-label="Tutup">
      <svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
    </button>
    <div id="sheet-hero-icon"></div>
  </div>
  <div class="sheet-body">
    <div class="sheet-cat-pill" id="sheet-cat"></div>
    <div class="sheet-title-row">
      <h2 class="sheet-title" id="sheet-title"></h2>
      <div id="sheet-tags"></div>
    </div>
    <p class="sheet-desc" id="sheet-desc"></p>
    <div class="sheet-price-row">
      <div class="sheet-price"><span class="up">&#9650;</span><span id="sheet-price"></span></div>
      <div class="sheet-hint">Pesan lewat kasir</div>
    </div>
  </div>
</div>

<script>
  const S = (inner) => `<svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">${inner}</svg>`;

  const HERO = {
    kopiSusu: S(`<path d="M38 38 H82 L76 92 a6 6 0 0 1-6 5 H50 a6 6 0 0 1-6-5 Z"/><line x1="42" y1="58" x2="78" y2="58"/><circle cx="50" cy="70" r="4" fill="currentColor" stroke="none"/><circle cx="66" cy="76" r="4" fill="currentColor" stroke="none"/><path d="M58 18 q-6 8 0 14 q6 6 0 14"/><line x1="70" y1="34" x2="70" y2="88" stroke-width="3"/>`),
    americano: S(`<path d="M28 45h50v30a15 15 0 0 1-15 15H43a15 15 0 0 1-15-15Z"/><path d="M78 52a12 12 0 0 1 0 24"/><ellipse cx="53" cy="92" rx="30" ry="5"/><path d="M45 34q-5 6 0 12" stroke-width="4"/><path d="M62 34q-5 6 0 12" stroke-width="4"/>`),
    cappuccino: S(`<path d="M28 45h50v30a15 15 0 0 1-15 15H43a15 15 0 0 1-15-15Z"/><path d="M78 52a12 12 0 0 1 0 24"/><ellipse cx="53" cy="92" rx="30" ry="5"/><path d="M53 46a6 6 0 1 1 6 6a10 10 0 1 1-10-10" stroke-width="3.5"/>`),
    v60: S(`<path d="M33 28 H77 L58 55 Z"/><rect x="38" y="60" width="40" height="30" rx="8"/><line x1="55" y1="55" x2="55" y2="63" stroke-width="3"/><line x1="63" y1="55" x2="63" y2="63" stroke-width="3"/><path d="M47 16q-5 6 0 12" stroke-width="4"/>`),
    matcha: S(`<path d="M28 45h50v30a15 15 0 0 1-15 15H43a15 15 0 0 1-15-15Z"/><path d="M78 52a12 12 0 0 1 0 24"/><ellipse cx="53" cy="92" rx="30" ry="5"/><path d="M42 30 l6 8 l-6 8 l6 8" stroke-width="3.5"/><path d="M68 34 q8-6 14 0 q-8 6-14 0Z" fill="currentColor" stroke="none"/>`),
    taro: S(`<path d="M28 45h50v30a15 15 0 0 1-15 15H43a15 15 0 0 1-15-15Z"/><path d="M78 52a12 12 0 0 1 0 24"/><ellipse cx="53" cy="92" rx="30" ry="5"/><path d="M53 46a6 6 0 1 1 6 6a10 10 0 1 1-10-10" stroke-width="3.5"/><circle cx="36" cy="36" r="3" fill="currentColor" stroke="none"/><circle cx="70" cy="30" r="2.5" fill="currentColor" stroke="none"/>`),
    lemon: S(`<rect x="38" y="38" width="44" height="52" rx="7"/><circle cx="60" cy="26" r="11"/><line x1="60" y1="17" x2="60" y2="35" stroke-width="2.5"/><line x1="51" y1="20" x2="69" y2="32" stroke-width="2.5"/><line x1="69" y1="20" x2="51" y2="32" stroke-width="2.5"/><circle cx="50" cy="68" r="4" fill="currentColor" stroke="none"/><circle cx="68" cy="76" r="4" fill="currentColor" stroke="none"/>`),
    pisang: S(`<path d="M22 82 q38 22 76 0" stroke-width="4"/><ellipse cx="43" cy="70" rx="13" ry="7.5" transform="rotate(-18 43 70)"/><ellipse cx="60" cy="65" rx="13" ry="7.5" transform="rotate(4 60 65)"/><ellipse cx="77" cy="71" rx="13" ry="7.5" transform="rotate(18 77 71)"/><path d="M32 48 q10 8 20 0 q10-8 20 0 q10 8 20 0" stroke-width="3"/>`),
    croissant: S(`<path d="M22 74 q12-40 48-40 q38 0 48 40 q-18-12-48-12 q-30 0-48 12 Z"/><line x1="38" y1="52" x2="46" y2="38" stroke-width="3"/><line x1="55" y1="47" x2="60" y2="32" stroke-width="3"/><line x1="73" y1="50" x2="80" y2="37" stroke-width="3"/>`),
    fries: S(`<path d="M33 55 L87 55 L75 96 L45 96 Z"/><rect x="41" y="22" width="8" height="38" rx="2" transform="rotate(-9 45 41)"/><rect x="56" y="16" width="8" height="42" rx="2"/><rect x="70" y="22" width="8" height="38" rx="2" transform="rotate(9 74 41)"/>`)
  };

  const MENU = @json($menus);
  const CAT_LABEL = @json($categories->pluck('name', 'slug'));
  const CAT_STYLE = @json($categories->pluck('style_class', 'slug'));
  const CAT_ORDER = @json($categories->pluck('slug')->values());
  const fmt = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');
  const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (m) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));

  const sectionsEl = document.getElementById('menu-sections');

  function renderMenu(filter) {
    sectionsEl.innerHTML = '';
    const cats = filter === 'semua' ? CAT_ORDER : [filter];
    cats.forEach(cat => {
      const items = MENU.filter(m => m.cat === cat);
      if (!items.length) return;

      const label = document.createElement('div');
      label.className = 'section-label';
      label.textContent = CAT_LABEL[cat];
      sectionsEl.appendChild(label);

      const grid = document.createElement('div');
      grid.className = 'grid';

      items.forEach(item => {
        const style = CAT_STYLE[item.cat] || 'kopi';
        const visual = item.photoUrl
          ? `<img src="${esc(item.photoUrl)}" alt="${esc(item.name)}">`
          : (HERO[item.hero] || HERO.kopiSusu);
        const card = document.createElement('button');
        card.className = 'card';
        card.type = 'button';
        card.innerHTML = `
          <div class="hero-tile ${esc(style)}">${visual}</div>
          <div class="card-info">
            <div class="card-title-row">
              <div class="card-title">${esc(item.name)}</div>
              ${item.tag ? `<span class="tag">${esc(item.tag)}</span>` : ''}
            </div>
            <div class="card-desc">${esc(item.desc)}</div>
            <div class="price-row"><span class="up">&#9650;</span>${fmt(item.price)}</div>
          </div>
          <div class="chevron"><svg viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        `;
        card.addEventListener('click', () => openDetail(item.id));
        grid.appendChild(card);
      });

      sectionsEl.appendChild(grid);
    });
  }

  renderMenu('semua');

  document.getElementById('tabs').addEventListener('click', (e) => {
    const btn = e.target.closest('.tab');
    if (!btn) return;
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    renderMenu(btn.dataset.cat);
  });

  const overlay = document.getElementById('overlay');
  const sheet = document.getElementById('detail-sheet');
  const sheetHero = document.getElementById('sheet-hero');
  const sheetHeroIcon = document.getElementById('sheet-hero-icon');
  const sheetCat = document.getElementById('sheet-cat');
  const sheetTitle = document.getElementById('sheet-title');
  const sheetTags = document.getElementById('sheet-tags');
  const sheetDesc = document.getElementById('sheet-desc');
  const sheetPrice = document.getElementById('sheet-price');

  function openDetail(id) {
    const item = MENU.find(m => m.id === id);
    if (!item) return;
    const style = CAT_STYLE[item.cat] || 'kopi';
    sheetHero.className = 'sheet-hero ' + (item.photoUrl ? 'photo' : style);
    sheetHeroIcon.innerHTML = item.photoUrl
      ? `<img src="${esc(item.photoUrl)}" alt="${esc(item.name)}">`
      : (HERO[item.hero] || HERO.kopiSusu);
    sheetCat.textContent = CAT_LABEL[item.cat];
    sheetTitle.textContent = item.name;
    sheetTags.innerHTML = item.tag ? `<span class="tag">${esc(item.tag)}</span>` : '';
    sheetDesc.textContent = item.longDesc;
    sheetPrice.textContent = fmt(item.price);

    overlay.classList.add('show');
    sheet.classList.add('show');
  }
  function closeDetail() {
    overlay.classList.remove('show');
    sheet.classList.remove('show');
  }
  document.getElementById('sheet-close').addEventListener('click', closeDetail);
  overlay.addEventListener('click', closeDetail);
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeDetail(); });

</script>
</body>
</html>
