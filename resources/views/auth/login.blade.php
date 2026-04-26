<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Trendline Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(201, 168, 76, 0.22), transparent 28%),
                linear-gradient(160deg, #0a1628 0%, #0d2248 58%, #1a3060 100%);
            color: #fff;
        }
        .font-display {
            font-family: 'Playfair Display', serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(201, 168, 76, 0.22);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="mx-auto flex min-h-screen max-w-7xl items-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid w-full gap-8 lg:grid-cols-[1.15fr_0.85fr]">
            <section class="hidden rounded-[2rem] border border-amber-300/15 bg-white/5 p-10 lg:block">
                <p class="text-sm font-semibold uppercase tracking-[0.45em] text-amber-200/70">Trendline</p>
                <h1 class="font-display mt-4 max-w-xl text-5xl font-black leading-tight text-white">
                    Coffee, Trading, dan Dashboard internal dalam satu panel.
                </h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-200/80">
                    Tampilan login ini mengadopsi nuansa frontend terlampir: dominan navy, aksen gold, dan glass card modern.
                </p>

                <div class="mt-10 grid gap-4 md:grid-cols-3">
                    <div class="glass rounded-3xl p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-amber-200/60">Akses</p>
                        <p class="mt-3 text-lg font-bold">Login pakai username</p>
                    </div>
                    <div class="glass rounded-3xl p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-amber-200/60">Panel</p>
                        <p class="mt-3 text-lg font-bold">Dashboard & akun</p>
                    </div>
                    <div class="glass rounded-3xl p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-amber-200/60">Style</p>
                        <p class="mt-3 text-lg font-bold">Tailwind sidebar kiri</p>
                    </div>
                </div>
            </section>

            <section class="glass rounded-[2rem] p-6 shadow-2xl shadow-slate-950/40 sm:p-8 lg:p-10">
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-amber-200/70">Masuk Ke Sistem</p>
                <h2 class="font-display mt-3 text-4xl font-black text-white">Login</h2>
                <p class="mt-3 text-sm leading-7 text-slate-200/75">Gunakan username yang sudah didaftarkan untuk masuk ke dashboard.</p>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="username" class="mb-2 block text-sm font-semibold text-slate-100">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/25 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-400 focus:border-amber-300" placeholder="Masukkan username">
                        @error('username')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-100">Password</label>
                        <input id="password" name="password" type="password" class="w-full rounded-2xl border border-white/10 bg-slate-950/25 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-400 focus:border-amber-300" placeholder="Masukkan password">
                        @error('password')
                            <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-200/80">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-slate-950/25 text-amber-400 focus:ring-amber-300">
                        Ingat saya
                    </label>

                    <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-400 px-4 py-3 text-sm font-extrabold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5">
                        Masuk
                    </button>
                </form>
            </section>
        </div>
    </div>
</body>
</html>
