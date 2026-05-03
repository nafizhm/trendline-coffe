<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Trendline Coffee Admin' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f8fc;
            --panel: #ffffff;
            --line: #e5e7eb;
            --text: #0f172a;
            --muted: #64748b;
            --brand: #0f172a;
            --brand-soft: #1e293b;
            --accent: #d4a74a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(212, 167, 74, 0.08), transparent 20%),
                linear-gradient(180deg, #f8fafc 0%, #f3f6fb 100%);
            color: var(--text);
        }
        .sidebar-shell {
            transition: width 0.25s ease, transform 0.25s ease;
        }
        .sidebar-link {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
        .sidebar-link.active,
        .sidebar-link:hover {
            background: #eff6ff;
            color: #0f172a;
            border-color: #cbd5e1;
        }
        .sidebar-collapsed .sidebar-shell {
            width: 5.5rem;
        }
        .sidebar-collapsed .sidebar-label,
        .sidebar-collapsed .sidebar-heading,
        .sidebar-collapsed .sidebar-user-text,
        .sidebar-collapsed .sidebar-brand-copy,
        .sidebar-collapsed .sidebar-logout-label {
            display: none;
        }
        .sidebar-collapsed .sidebar-link,
        .sidebar-collapsed .sidebar-logout-btn {
            justify-content: center;
        }
        .sidebar-collapsed .content-shell {
            margin-left: 0;
        }
        @media (max-width: 1023px) {
            .sidebar-collapsed .sidebar-shell {
                width: 18rem;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="relative min-h-screen lg:flex" id="adminLayout">
        <div id="sidebarBackdrop" class="fixed inset-0 z-30 hidden bg-slate-900/40 lg:hidden"></div>

        <aside id="sidebar"
            class="sidebar-shell fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200 bg-white lg:translate-x-0">
            <div class="flex h-full flex-col">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-extrabold text-white">
                        TC
                    </div>
                    <div class="sidebar-brand-copy min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-amber-700">Trendline</p>
                        <h1 class="truncate text-lg font-extrabold text-slate-900">Admin Panel</h1>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-4">
                    <p class="sidebar-heading mb-2 px-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">Menu</p>
                    <nav class="space-y-1.5">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l7-7 7 7M5 10v9h14v-9"/></svg>
                            </span>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                        <a href="{{ route('contents.index') }}" class="sidebar-link {{ request()->routeIs('contents.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 4.5h9A1.5 1.5 0 0118 6v12a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 016 18V6a1.5 1.5 0 011.5-1.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6M9 12h6M9 15.75h3"/></svg>
                            </span>
                            <span class="sidebar-label">Konten</span>
                        </a>
                        <a href="{{ route('articles.index') }}" class="sidebar-link {{ request()->routeIs('articles.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 5.25A2.25 2.25 0 018.25 3h8.129a2.25 2.25 0 011.591.659l1.371 1.371A2.25 2.25 0 0120 6.621V18.75A2.25 2.25 0 0117.75 21h-9.5A2.25 2.25 0 016 18.75V5.25z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6M9 12h6M9 15.75h4.5"/></svg>
                            </span>
                            <span class="sidebar-label">Artikel</span>
                        </a>
                        <a href="{{ route('videos.index') }}" class="sidebar-link {{ request()->routeIs('videos.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6.75a2.25 2.25 0 00-2.25-2.25h-6A2.25 2.25 0 005.25 6.75v10.5A2.25 2.25 0 007.5 19.5h6a2.25 2.25 0 002.25-2.25V13.5l3.75 2.25v-7.5l-3.75 2.25z"/></svg>
                            </span>
                            <span class="sidebar-label">Video</span>
                        </a>
                        <a href="{{ route('referral-links.index') }}" class="sidebar-link {{ request()->routeIs('referral-links.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a2.25 2.25 0 00-4.5 0v3.75m-3 0h10.5a1.5 1.5 0 011.5 1.5v5.25a1.5 1.5 0 01-1.5 1.5H6a1.5 1.5 0 01-1.5-1.5V12a1.5 1.5 0 011.5-1.5zm4.5 3.75h.008v.008H10.5v-.008z"/></svg>
                            </span>
                            <span class="sidebar-label">Link Referal</span>
                        </a>
                        <a href="{{ route('latest-schedules.index') }}" class="sidebar-link {{ request()->routeIs('latest-schedules.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5M8.25 12h7.5M8.25 17.25h4.5M6 3.75h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75z"/></svg>
                            </span>
                            <span class="sidebar-label">Jadwal Terbaru</span>
                        </a>
                        <a href="{{ route('questionnaires.index') }}" class="sidebar-link {{ request()->routeIs('questionnaires.*') || request()->routeIs('questionnaire-questions.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="sidebar-label">Quesioner</span>
                        </a>
                        <a href="{{ route('daily-signals.index', ['type' => 'forex']) }}" class="sidebar-link {{ request()->routeIs('daily-signals.*') && request()->route('type') === 'forex' ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm14.71-9.04a1.003 1.003 0 000-1.42l-2.5-2.5a1.003 1.003 0 00-1.42 0l-1.96 1.96 3.75 3.75 2.13-1.79z"/></svg>
                            </span>
                            <span class="sidebar-label">Sinyal Harian Forex</span>
                        </a>
                        <a href="{{ route('daily-signals.index', ['type' => 'saham']) }}" class="sidebar-link {{ request()->routeIs('daily-signals.*') && request()->route('type') === 'saham' ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5h16M6.75 16.5V9.75M12 16.5V6.75M17.25 16.5V12"/></svg>
                            </span>
                            <span class="sidebar-label">Sinyal Harian Saham</span>
                        </a>
                        <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 6h12M4.5 6h.008v.008H4.5V6zm3 6h12M4.5 12h.008v.008H4.5V12zm3 6h12M4.5 18h.008v.008H4.5V18z"/></svg>
                            </span>
                            <span class="sidebar-label">Kategori</span>
                        </a>
                        <a href="{{ route('settings.edit') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0012.91 5.303l1.996.72a.75.75 0 00.961-.96l-.72-1.997A7.5 7.5 0 104.5 12z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 12a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0z"/></svg>
                            </span>
                            <span class="sidebar-label">Pengaturan</span>
                        </a>
                        <a href="{{ route('accounts.index') }}" class="sidebar-link {{ request()->routeIs('accounts.*') ? 'active' : '' }} flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2.5 text-sm font-semibold text-slate-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20h10M12 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
                            </span>
                            <span class="sidebar-label">Pengaturan Akun</span>
                        </a>
                    </nav>
                </div>

                <div class="border-t border-slate-200 p-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-logout-btn flex w-full items-center gap-2.5 rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H6a2 2 0 01-2-2V6a2 2 0 012-2h7"/></svg>
                            </span>
                            <span class="sidebar-logout-label">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="content-shell flex min-h-screen flex-1 flex-col lg:ml-72">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button id="sidebarToggle" type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Trendline Coffee</p>
                            <h2 class="text-lg font-bold text-slate-900">{{ $headerTitle ?? 'Admin Dashboard' }}</h2>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Lihat Frontend
                    </a>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    @if (session('status'))
        <div id="appToast" class="fixed bottom-5 right-5 z-50 max-w-sm rounded-2xl bg-slate-900 px-4 py-3 text-sm font-medium text-white shadow-2xl shadow-slate-900/20">
            {{ session('status') }}
        </div>
    @endif

    <script>
        const layout = document.getElementById('adminLayout');
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const desktopQuery = window.matchMedia('(min-width: 1024px)');

        function openMobileSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
        }

        function closeMobileSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
        }

        function applyDesktopSidebarState() {
            if (!desktopQuery.matches) {
                layout.classList.remove('sidebar-collapsed');
                closeMobileSidebar();
                return;
            }

            closeMobileSidebar();

            if (localStorage.getItem('trendline-admin-sidebar') === 'collapsed') {
                layout.classList.add('sidebar-collapsed');
            } else {
                layout.classList.remove('sidebar-collapsed');
            }
        }

        sidebarToggle.addEventListener('click', () => {
            if (desktopQuery.matches) {
                const collapsed = layout.classList.toggle('sidebar-collapsed');
                localStorage.setItem('trendline-admin-sidebar', collapsed ? 'collapsed' : 'expanded');
                return;
            }

            const hidden = sidebar.classList.contains('-translate-x-full');
            if (hidden) {
                openMobileSidebar();
            } else {
                closeMobileSidebar();
            }
        });

        sidebarBackdrop.addEventListener('click', closeMobileSidebar);
        window.addEventListener('resize', applyDesktopSidebarState);
        applyDesktopSidebarState();

        const appToast = document.getElementById('appToast');
        if (appToast) {
            setTimeout(() => {
                appToast.classList.add('opacity-0', 'translate-y-2', 'transition', 'duration-300');
                setTimeout(() => appToast.remove(), 300);
            }, 2000);
        }
    </script>
</body>
</html>
