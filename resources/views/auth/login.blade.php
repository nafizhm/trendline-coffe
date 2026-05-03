<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trendline Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --sage: #6b7a5e;
            --sage-dark: #4e5a45;
            --sage-light: #8a9b7a;
            --cream: #f5f2ec;
            --cream-dark: #ede9e0;
            --text: #2c3021;
            --muted: #8a9070;
            --white: #ffffff;
            --danger: #b14a4a;
            --success-bg: #edf7ee;
            --success-text: #35613a;
            --radius: 14px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "DM Sans", sans-serif;
            background-color: var(--cream);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
            color: var(--text);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(107, 122, 94, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(107, 122, 94, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .card {
            position: relative;
            z-index: 1;
            background: var(--white);
            border-radius: 24px;
            padding: 40px 32px 36px;
            width: 100%;
            max-width: 400px;
            box-shadow:
                0 2px 4px rgba(44, 48, 33, 0.04),
                0 8px 24px rgba(44, 48, 33, 0.08),
                0 0 0 1px rgba(107, 122, 94, 0.1);
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .logo-img {
            width: 140px;
            height: auto;
            animation: fadeIn 0.6s ease 0.1s both;
        }

        .logo-tagline {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            text-align: center;
        }

        .heading {
            font-family: "DM Serif Display", serif;
            font-size: 22px;
            color: var(--text);
            text-align: center;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .subheading {
            text-align: center;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .status-error,
        .status-success {
            margin-bottom: 16px;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 13px;
            line-height: 1.6;
        }

        .status-error {
            background: #fff4f4;
            color: var(--danger);
        }

        .status-success {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--sage-dark);
            margin-bottom: 6px;
            letter-spacing: 0.02em;
        }

        input {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--cream-dark);
            border-radius: var(--radius);
            font: inherit;
            font-size: 15px;
            color: var(--text);
            background: var(--cream);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            appearance: none;
        }

        input::placeholder {
            color: var(--muted);
        }

        input:focus {
            border-color: var(--sage);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(107, 122, 94, 0.12);
        }

        .input-error {
            border-color: rgba(177, 74, 74, 0.45);
            background: #fff8f8;
        }

        .pw-wrap {
            position: relative;
        }

        .pw-wrap input {
            padding-right: 46px;
        }

        .pw-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--muted);
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .pw-toggle:hover {
            color: var(--sage);
        }

        .error-text {
            margin-top: 8px;
            color: var(--danger);
            font-size: 12.5px;
            line-height: 1.5;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 2px;
            color: var(--muted);
            font-size: 13px;
        }

        .remember-row input {
            width: 16px;
            height: 16px;
            padding: 0;
            border-radius: 5px;
            accent-color: var(--sage);
            box-shadow: none;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            margin-top: 24px;
            background: var(--sage);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            font: inherit;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.03em;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(107, 122, 94, 0.35);
        }

        .btn-login:hover {
            background: var(--sage-dark);
            box-shadow: 0 6px 18px rgba(107, 122, 94, 0.45);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.75;
        }

        .btn-login.loading::after {
            content: "";
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        .footer {
            position: relative;
            z-index: 1;
            margin-top: 24px;
            font-size: 11.5px;
            color: var(--muted);
            text-align: center;
            line-height: 1.6;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 32px 22px 28px;
                border-radius: 20px;
            }

            .logo-img {
                width: 124px;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-wrap">
            <img
                src="{{ asset('images/trendline-logo.png') }}"
                alt="Trendline Logo"
                class="logo-img"
            >
            <span class="logo-tagline">Trendline Coffee Admin Access</span>
        </div>

        <h1 class="heading">Selamat datang kembali</h1>
        <p class="subheading">Masuk dengan akun admin untuk mengelola dashboard Trendline Coffee dan data kuesioner.</p>

        @if (session('status'))
            <div class="status-success">{{ session('status') }}</div>
        @endif

        @if ($errors->has('username') && ! $errors->has('password'))
            <div class="status-error">{{ $errors->first('username') }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" id="loginForm" novalidate>
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required
                    class="@error('username') input-error @enderror"
                >
                @error('username')
                    @if ($message !== 'Username atau password tidak sesuai.')
                        <div class="error-text">{{ $message }}</div>
                    @endif
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="pw-wrap">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                        class="@error('password') input-error @enderror"
                    >
                    <button type="button" class="pw-toggle" aria-label="Tampilkan password" id="togglePw">
                        <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <label class="remember-row" for="remember">
                <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                Tetap masuk di perangkat ini
            </label>

            <button type="submit" class="btn-login" id="loginBtn">Masuk</button>
        </form>
    </div>

    <p class="footer">&copy; 2026 Trendline Coffee | Area admin internal</p>

    <script>
        (() => {
            const togglePw = document.getElementById('togglePw');
            const pwInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');

            if (togglePw && pwInput && eyeIcon) {
                const eyeOpen = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
                const eyeOff = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;

                togglePw.addEventListener('click', () => {
                    const show = pwInput.type === 'password';
                    pwInput.type = show ? 'text' : 'password';
                    eyeIcon.innerHTML = show ? eyeOff : eyeOpen;
                    togglePw.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                });
            }

            if (loginForm && loginBtn) {
                loginForm.addEventListener('submit', () => {
                    loginBtn.textContent = 'Memproses';
                    loginBtn.classList.add('loading');
                });
            }
        })();
    </script>
</body>
</html>
