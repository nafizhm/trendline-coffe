<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Trendline Feedback' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --olive: #6b7a52;
            --olive-deep: #3d4a2e;
            --olive-mid: #8a9c6a;
            --olive-pale: #e8edd9;
            --olive-xpale: #f4f6ee;
            --cream: #faf9f5;
            --ink: #1e2416;
            --muted: #7a876a;
            --danger: #9c3e3e;
            --border: rgba(107, 122, 82, 0.2);
            --shadow-sm: 0 2px 12px rgba(61, 74, 46, 0.08);
            --shadow-md: 0 8px 32px rgba(61, 74, 46, 0.12);
            --shadow-lg: 0 20px 60px rgba(61, 74, 46, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Jost", sans-serif;
            background: var(--cream);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            color: var(--ink);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 100% 0%, rgba(107, 122, 82, 0.07) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 0% 100%, rgba(107, 122, 82, 0.05) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .wrap {
            position: relative;
            z-index: 1;
            width: min(100%, 720px);
            margin: 0 auto;
            padding: 40px 20px 80px;
        }

        .header {
            text-align: center;
            margin-bottom: 48px;
            animation: slideDown 0.7s cubic-bezier(0.23, 1, 0.32, 1) both;
        }

        .logo-card {
            display: inline-block;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 20px 40px;
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
        }

        .logo-card img {
            height: 80px;
            width: auto;
            display: block;
        }

        .header-tagline {
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(2rem, 4vw, 2.5rem);
            font-weight: 600;
            color: var(--olive-deep);
            letter-spacing: 0.3px;
            line-height: 1.15;
            margin-bottom: 8px;
        }

        .header-sub {
            font-size: 14px;
            font-weight: 300;
            color: var(--muted);
            letter-spacing: 0.5px;
            line-height: 1.6;
        }

        .progress-wrap {
            margin-bottom: 36px;
            animation: fadeIn 0.5s 0.3s ease both;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 8px;
        }

        .progress-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--olive);
        }

        .progress-count {
            font-size: 12px;
            color: var(--muted);
            font-weight: 300;
        }

        .progress-track {
            height: 3px;
            background: var(--olive-pale);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, var(--olive-mid), var(--olive-deep));
            border-radius: 999px;
            transition: width 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .q-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px 32px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) both;
            position: relative;
            overflow: hidden;
        }

        .q-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--olive-mid), var(--olive-deep));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .q-card:focus-within {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .q-card:focus-within::before {
            opacity: 1;
        }

        .q-number {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--olive);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .q-number::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .q-text {
            font-family: "Cormorant Garamond", serif;
            font-size: 19px;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.35;
            margin-bottom: 16px;
        }

        .field-textarea {
            width: 100%;
            background: var(--olive-xpale);
            border: 1.5px solid transparent;
            border-radius: 12px;
            padding: 13px 16px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 300;
            color: var(--ink);
            outline: none;
            transition: all 0.25s ease;
            appearance: none;
            min-height: 100px;
            resize: vertical;
            line-height: 1.6;
        }

        .field-textarea::placeholder {
            color: #a8b498;
            font-weight: 300;
        }

        .field-textarea:focus {
            background: #fff;
            border-color: var(--olive-mid);
            box-shadow: 0 0 0 4px rgba(107, 122, 82, 0.08);
        }

        .field-saran {
            min-height: 130px;
        }

        .field-error {
            border-color: rgba(156, 62, 62, 0.6);
            background: #fff6f6;
        }

        .error-text {
            margin-top: 10px;
            font-size: 13px;
            color: var(--danger);
            line-height: 1.5;
        }

        .helper-text {
            margin-top: 10px;
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        .submit-wrap {
            margin-top: 8px;
            animation: slideUp 0.6s 0.4s cubic-bezier(0.23, 1, 0.32, 1) both;
        }

        .submit-btn {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 17px 24px;
            background: var(--olive-deep);
            color: #fff;
            font-family: "Cormorant Garamond", serif;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 6px 24px rgba(61, 74, 46, 0.3);
        }

        .submit-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.12), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover {
            background: var(--olive);
            transform: translateY(-2px);
            box-shadow: 0 12px 36px rgba(61, 74, 46, 0.35);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .footer-note {
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            margin-top: 20px;
            letter-spacing: 0.3px;
            font-weight: 300;
            opacity: 0.86;
            animation: fadeIn 1s 0.8s ease both;
        }

        .thankyou {
            text-align: center;
            padding: 60px 24px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: var(--shadow-md);
            animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) both;
        }

        .ty-icon {
            display: block;
            font-size: 48px;
            margin-bottom: 24px;
            animation: pop 0.6s 0.1s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        .ty-title {
            font-family: "Cormorant Garamond", serif;
            font-size: 36px;
            font-weight: 700;
            color: var(--olive-deep);
            margin-bottom: 12px;
        }

        .ty-divider {
            width: 48px;
            height: 2px;
            background: var(--olive);
            margin: 0 auto 24px;
            border-radius: 999px;
        }

        .ty-sub {
            font-size: 16px;
            font-weight: 300;
            color: var(--muted);
            line-height: 1.8;
            margin-bottom: 36px;
        }

        .reset-btn {
            display: inline-block;
            padding: 12px 28px;
            border: 2px solid var(--olive);
            border-radius: 999px;
            color: var(--olive);
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .reset-btn:hover {
            background: var(--olive);
            color: #fff;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px);
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

        @keyframes pop {
            from {
                opacity: 0;
                transform: scale(0.4);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 640px) {
            .wrap {
                padding: 26px 16px 56px;
            }

            .logo-card {
                width: 100%;
                padding: 18px 24px;
            }

            .logo-card img {
                height: auto;
                width: min(100%, 220px);
                margin: 0 auto;
            }

            .q-card,
            .thankyou {
                padding: 22px 20px;
                border-radius: 18px;
            }

            .progress-info {
                display: grid;
            }

            .q-text {
                font-size: 18px;
            }

            .ty-title {
                font-size: 30px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrap">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
