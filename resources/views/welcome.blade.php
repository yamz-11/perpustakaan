<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                var dark = t ? t === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) {}
        })();
    </script>
    <style>
        :root {
            --bg: #fafaf8;
            --surface: #ffffff;
            --ink: #1d2a30;
            --muted: #62717a;
            --line: #e6e4de;
            --accent: #3e7c8c;
            --accent-dark: #2f6372;
        }
        :root.dark {
            color-scheme: dark;
            --bg: #14191c;
            --surface: #1b2227;
            --ink: #eef2f3;
            --muted: #98a6ad;
            --line: #2b353b;
            --accent: #5fa3b5;
            --accent-dark: #7bb9ca;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: Figtree, system-ui, -apple-system, "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--ink);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        .container { width: 100%; max-width: 1040px; margin: 0 auto; padding: 0 24px; }

        .nav { display: flex; align-items: center; justify-content: space-between; padding: 24px 0; }
        .brand { font-weight: 600; font-size: 1.05rem; letter-spacing: -0.01em; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .theme-toggle {
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 10px;
            border: 0; background: transparent; color: var(--muted); cursor: pointer;
            transition: background-color .15s, color .15s;
        }
        .theme-toggle:hover { background: var(--line); color: var(--ink); }
        .theme-toggle:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
        .theme-toggle svg { width: 20px; height: 20px; }
        .theme-toggle .icon-sun { display: none; }
        :root.dark .theme-toggle .icon-sun { display: block; }
        :root.dark .theme-toggle .icon-moon { display: none; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 10px 20px; border-radius: 10px;
            font-size: 0.95rem; font-weight: 500;
            border: 1px solid transparent;
            transition: background-color .15s, border-color .15s, color .15s;
        }
        .btn:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-dark); }
        .btn-ghost { color: var(--ink); }
        .btn-ghost:hover { background: var(--line); }
        .btn-outline { border-color: var(--line); color: var(--ink); background: var(--surface); }
        .btn-outline:hover { border-color: var(--muted); }

        .hero { display: grid; grid-template-columns: 1fr; gap: 48px; align-items: center; padding: 48px 0 72px; }
        .hero h1 {
            margin: 0 0 16px;
            font-size: clamp(2.1rem, 5vw, 3.25rem);
            line-height: 1.1;
            font-weight: 600;
            letter-spacing: -0.03em;
        }
        .hero p { margin: 0 0 32px; max-width: 46ch; color: var(--muted); font-size: 1.1rem; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .hero-image { border-radius: 20px; overflow: hidden; border: 1px solid var(--line); background: var(--surface); }
        .hero-image img { display: block; width: 100%; height: auto; }

        .features { display: grid; grid-template-columns: 1fr; gap: 32px; padding: 48px 0; border-top: 1px solid var(--line); }
        .feature h2 { margin: 0 0 8px; font-size: 1.05rem; font-weight: 600; }
        .feature p { margin: 0; color: var(--muted); font-size: 0.97rem; max-width: 36ch; }

        .about { padding: 48px 0; border-top: 1px solid var(--line); display: grid; grid-template-columns: 1fr; gap: 16px; }
        .about h2 { margin: 0; font-size: 1.4rem; font-weight: 600; letter-spacing: -0.02em; }
        .about p { margin: 0; color: var(--muted); max-width: 64ch; }

        .footer { padding: 32px 0 40px; border-top: 1px solid var(--line); color: var(--muted); font-size: 0.9rem; }

        @media (min-width: 820px) {
            .hero { grid-template-columns: 1fr 1.1fr; gap: 64px; padding: 72px 0 96px; }
            .features { grid-template-columns: repeat(3, 1fr); gap: 48px; }
            .about { grid-template-columns: 1fr 2fr; gap: 48px; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .btn { transition: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="nav">
            <a href="{{ url('/') }}" class="brand">Perpustakaan</a>
            <div class="nav-links">
                <button type="button" class="theme-toggle" aria-label="Ganti tema"
                onclick="var d = document.documentElement.classList.toggle('dark'); try { localStorage.setItem('theme', d ? 'dark' : 'light'); } catch (e) {}">
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" /></svg>
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" /></svg>
            </button>
            @if (Route::has('login'))
                <nav class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endif
                    @endauth
                </nav>
            @endif
            </div>
        </header>

        <main>
            <section class="hero">
                <div>
                    <h1>Aplikasi Perpustakaan</h1>
                    <p>Kelola koleksi buku, peminjaman, dan pengembalian dalam satu tempat.</p>
                    <div class="actions">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Buka dashboard</a>
                        @endauth
                    </div>
                </div>
                <div class="hero-image">
                    <img src="{{ asset('images/perpustakaan.svg') }}" alt="Ilustrasi ruang perpustakaan dengan rak buku dan meja baca">
                </div>
            </section>

            <section class="features">
                <div class="feature">
                    <h2>Koleksi buku</h2>
                    <p>Tambah, ubah, dan cari data buku dengan cepat.</p>
                </div>
                <div class="feature">
                    <h2>Peminjaman</h2>
                    <p>Catat siapa meminjam buku apa dan kapan.</p>
                </div>
                <div class="feature">
                    <h2>Pengembalian</h2>
                    <p>Pantau buku yang sudah kembali dan yang belum.</p>
                </div>
            </section>

            <section id="about" class="about">
                <h2>Tentang aplikasi</h2>
                <p>Aplikasi perpustakaan ini merupakan perangkat lunak yang dirancang untuk membantu manajemen dan operasional sebuah perpustakaan. Tujuan utama dari aplikasi perpustakaan adalah untuk mempermudah pelayanan, pengelolaan koleksi buku, dan pelacakan aktivitas peminjaman dan pengembalian buku.</p>
            </section>
        </main>

    </div>
</body>
</html>