<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Luminous Jepara - Sistem Pemetaan & Monitoring Penerangan Jalan Umum (PJU) Kota Jepara yang Transparan dan Modern.">
    <title>Luminous Jepara - Sistem Monitoring PJU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --navy: #0B1120;
            --navy-2: #111827;
            --navy-3: #1a2540;
            --graphite: #1E293B;
            --accent: #F59E0B;
            --accent-2: #FBBF24;
            --accent-glow: rgba(245, 158, 11, 0.3);
            --green: #10B981;
            --red: #EF4444;
            --text-primary: #F1F5F9;
            --text-secondary: #94A3B8;
            --border: rgba(255, 255, 255, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* --- NAVBAR --- */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 1.2rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: transparent;
            transition: background 0.4s ease, backdrop-filter 0.4s;
        }

        nav.scrolled {
            background: rgba(11, 17, 32, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .nav-logo .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent), #D97706);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .nav-logo span {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s;
        }

        .nav-links a:hover {
            color: var(--text-primary);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn-nav {
            padding: 0.5rem 1.4rem;
            background: linear-gradient(135deg, var(--accent), #D97706);
            color: #000 !important;
            font-weight: 700 !important;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.85rem;
            box-shadow: 0 4px 15px var(--accent-glow);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--accent-glow);
        }

        .btn-nav::after {
            display: none !important;
        }

        /* --- HERO --- */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 8rem 5% 4rem;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 60% 50%, rgba(245, 158, 11, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
                linear-gradient(165deg, #0B1120 0%, #111827 100%);
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background-image: linear-gradient(rgba(245, 158, 11, 0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245, 158, 11, 0.4) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .hero-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--accent);
            border-radius: 50%;
            animation: float-particle linear infinite;
            opacity: 0;
        }

        @keyframes float-particle {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-10vh) scale(1);
                opacity: 0;
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 650px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            margin-bottom: 1.5rem;
            font-size: 0.8rem;
            color: var(--accent);
            font-weight: 600;
        }

        .hero-badge .dot {
            width: 8px;
            height: 8px;
            background: var(--green);
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5);
            }

            50% {
                opacity: 0.8;
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }

        .hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(3rem, 7vw, 5.5rem);
            font-weight: 800;
            line-height: 1.05;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #fff 30%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--text-secondary);
            line-height: 1.8;
            margin-bottom: 2.5rem;
            max-width: 520px;
        }

        .hero-btns {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2rem;
            background: linear-gradient(135deg, var(--accent), #D97706);
            color: #000;
            font-weight: 700;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-shadow: 0 6px 30px var(--accent-glow);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px rgba(245, 158, 11, 0.4);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            color: var(--text-primary);
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Hero Map Visual */
        .hero-map {
            position: absolute;
            right: -5%;
            top: 50%;
            transform: translateY(-50%);
            width: 50%;
            max-width: 600px;
            z-index: 1;
        }

        .map-frame {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5), 0 0 60px rgba(245, 158, 11, 0.1);
            position: relative;
            padding-bottom: 62%;
            background: #0d1929;
        }

        .map-frame img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
        }

        .map-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(11, 17, 32, 0.4), transparent);
        }

        /* Animated dots on map */
        .map-dot {
            position: absolute;
            border-radius: 50%;
            animation: map-pulse 2s ease-in-out infinite;
        }

        .map-dot.yellow {
            background: var(--accent);
            box-shadow: 0 0 15px var(--accent), 0 0 30px var(--accent-glow);
            width: 12px;
            height: 12px;
        }

        .map-dot.red {
            background: var(--red);
            box-shadow: 0 0 15px var(--red);
            width: 10px;
            height: 10px;
            animation: map-blink 1s ease-in-out infinite;
        }

        @keyframes map-pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.3);
                opacity: 0.8;
            }
        }

        @keyframes map-blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        /* --- STATS BAR --- */
        .stats-bar {
            padding: 2rem 5%;
            background: rgba(255, 255, 255, 0.02);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .stats-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.3rem;
        }

        /* --- SECTION HEADERS --- */
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.2);
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .section-sub {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 500px;
        }

        /* --- FEATURES --- */
        .features {
            padding: 6rem 5%;
            max-width: 1300px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header .section-sub {
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            cursor: default;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            transform: scaleX(0);
            transition: transform 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(245, 158, 11, 0.2);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3), 0 0 30px rgba(245, 158, 11, 0.05);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .feature-icon.amber {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .feature-icon.green {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .feature-icon.blue {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .feature-icon.purple {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .feature-icon.red {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .feature-icon.cyan {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        .feature-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* --- MAP PREVIEW SECTION --- */
        .map-section {
            padding: 6rem 5%;
            background: linear-gradient(180deg, var(--navy) 0%, var(--navy-2) 100%);
        }

        .map-content {
            max-width: 1300px;
            margin: 0 auto;
        }

        .map-preview-frame {
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.6), 0 0 80px rgba(245, 158, 11, 0.06);
            position: relative;
        }

        .map-legend {
            display: flex;
            gap: 1.5rem;
            padding: 1rem 1.5rem;
            background: rgba(11, 17, 32, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .legend-dot.normal {
            background: var(--accent);
            box-shadow: 0 0 8px var(--accent);
        }

        .legend-dot.mati {
            background: var(--red);
            animation: map-blink 1s infinite;
        }

        .map-placeholder {
            height: 400px;
            background: linear-gradient(135deg, #0d1929, #111827);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
            color: var(--text-secondary);
            position: relative;
            overflow: hidden;
        }

        .map-grid-bg {
            position: absolute;
            inset: 0;
            opacity: 0.05;
            background: repeating-linear-gradient(0deg, #fff 0, #fff 1px, transparent 1px, transparent 40px),
                repeating-linear-gradient(90deg, #fff 0, #fff 1px, transparent 1px, transparent 40px);
        }

        .map-cta {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .map-cta h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        /* --- HOW IT WORKS --- */
        .how-section {
            padding: 6rem 5%;
        }

        .how-content {
            max-width: 1100px;
            margin: 0 auto;
        }

        .how-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 4rem;
            position: relative;
        }

        .how-steps::before {
            content: '';
            position: absolute;
            top: 40px;
            left: calc(16.66% + 20px);
            right: calc(16.66% + 20px);
            height: 2px;
            background: linear-gradient(90deg, var(--accent), transparent 50%, var(--accent));
        }

        .step-card {
            text-align: center;
            padding: 2rem 1rem;
        }

        .step-num {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--graphite);
            border: 2px solid var(--border);
            position: relative;
            transition: all 0.3s;
        }

        .step-num.active {
            background: linear-gradient(135deg, var(--accent), #D97706);
            color: #000;
            border-color: var(--accent);
            box-shadow: 0 0 30px var(--accent-glow);
        }

        .step-card h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .step-card p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* --- CTA SECTION --- */
        .cta-section {
            padding: 6rem 5%;
            text-align: center;
            background: radial-gradient(ellipse at center, rgba(245, 158, 11, 0.07) 0%, transparent 60%);
        }

        .cta-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .cta-content p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .cta-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* --- FOOTER --- */
        footer {
            padding: 3rem 5%;
            border-top: 1px solid var(--border);
            background: var(--navy-2);
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1rem;
        }

        .footer-copy {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* --- RESPONSIVE --- */
        @media (max-width:1024px) {
            .hero-map {
                display: none;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:768px) {
            .nav-links {
                display: none;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .how-steps {
                grid-template-columns: 1fr;
            }

            .how-steps::before {
                display: none;
            }

            .stats-inner {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav id="navbar">
        <a href="{{ route('home') }}" class="nav-logo">
            <div class="logo-icon">💡</div>
            <span>Luminous Jepara</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('map.index') }}">Monitoring Map</a>
            <a href="#">Blogs</a>
            @auth
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
            <a href="{{ route('map.index') }}" class="btn-nav">Get Started</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-grid"></div>
        <div class="hero-particles" id="particles"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <span class="dot"></span>
                Sistem Aktif & Realtime
            </div>
            <h1>Luminous<br>Jepara</h1>
            <p>
                Sistem pemetaan dan monitoring Penerangan Jalan Umum (PJU) Kota Jepara yang transparan, modern, dan
                dapat diakses publik maupun instansi terkait.
            </p>
            <div class="hero-btns">
                <a href="{{ route('map.index') }}" class="btn-primary">
                    🗺️ Lihat Peta Monitoring
                </a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                        📊 Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary">
                        🔐 Login Admin
                    </a>
                @endauth
            </div>
        </div>

        <!-- Hero Map Visual -->
        <div class="hero-map">
            <div class="map-frame">
                <div class="map-overlay"></div>
                <!-- Animated marker dots -->
                <div class="map-dot yellow" style="top:40%; left:45%;"></div>
                <div class="map-dot yellow" style="top:55%; left:60%;"></div>
                <div class="map-dot yellow" style="top:30%; left:55%;"></div>
                <div class="map-dot red" style="top:50%; left:40%;"></div>
                <div class="map-dot yellow" style="top:65%; left:55%;"></div>
                <div class="map-dot red" style="top:35%; left:35%;"></div>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <div class="stats-bar">
        <div class="stats-inner">
            <div class="stat-item">
                <div class="stat-num" id="stat-total">—</div>
                <div class="stat-label">Total Titik PJU</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" id="stat-normal"
                    style="background:linear-gradient(135deg,#10B981,#34D399); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">
                    —</div>
                <div class="stat-label">Status Normal</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" id="stat-mati"
                    style="background:linear-gradient(135deg,#EF4444,#F87171); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">
                    —</div>
                <div class="stat-label">Status Mati / Rusak</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">{{ date('Y') }}</div>
                <div class="stat-label">Kecamatan Terpantau</div>
            </div>
        </div>
    </div>

    <!-- FEATURES -->
    <section class="features">
        <div class="features-header">
            <div class="section-tag">✨ Fitur Unggulan</div>
            <h2 class="section-title">Solusi Lengkap untuk<br>Manajemen PJU Modern</h2>
            <p class="section-sub">Platform terintegrasi untuk memantau, mengelola, dan memverifikasi seluruh titik
                penerangan jalan di Kabupaten Jepara.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon amber">🗺️</div>
                <h3>Peta Interaktif Real-time</h3>
                <p>Visualisasikan seluruh titik PJU pada peta gelap yang elegan dengan marker khusus untuk status normal
                    dan mati.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon green">✅</div>
                <h3>Sistem Verifikasi Multi-level</h3>
                <p>Alur verifikasi terstruktur: Admin input data → Verifikator validasi → Data terpublikasi dengan badge
                    terverifikasi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon blue">📷</div>
                <h3>Street View Integration</h3>
                <p>Langsung lihat kondisi lapangan dengan embed Google Street View dinamis berdasarkan koordinat setiap
                    titik PJU.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon purple">👥</div>
                <h3>Manajemen Multi-Role</h3>
                <p>Tiga level akses: Super Admin, Admin Dishub, dan Verifikator dengan hak akses yang terdefinisi jelas.
                </p>
            </div>
            <div class="feature-card">
                <div class="feature-icon red">🚨</div>
                <h3>Notifikasi Status PJU</h3>
                <p>Marker yang berkedip (pulse animation) untuk PJU mati memudahkan identifikasi masalah secara visual
                    dengan cepat.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon cyan">📊</div>
                <h3>Dashboard Analitik</h3>
                <p>Ringkasan statistik komprehensif: total PJU, persentase normal/mati, dan progress verifikasi data.
                </p>
            </div>
        </div>
    </section>

    <!-- MAP PREVIEW -->
    <section class="map-section">
        <div class="map-content">
            <div
                style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:2rem;margin-bottom:3rem;">
                <div>
                    <div class="section-tag">🌍 Peta Publik</div>
                    <h2 class="section-title">Transparansi Penuh<br>untuk Masyarakat</h2>
                </div>
                <p class="section-sub">Siapapun dapat mengakses peta monitoring PJU tanpa perlu login. Transparansi
                    adalah kunci kepercayaan publik.</p>
            </div>
            <div class="map-preview-frame">
                <div class="map-legend">
                    <div class="legend-item">
                        <div class="legend-dot normal"></div> PJU Normal (Menyala)
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot mati"></div> PJU Mati / Rusak
                    </div>
                    <div class="legend-item">🔵 Rambu Lalu Lintas</div>
                    <div class="legend-item">🟣 RPPJ / Cermin</div>
                </div>
                <div class="map-placeholder">
                    <div class="map-grid-bg"></div>
                    <div class="map-cta">
                        <h3>🗺️ Peta Interaktif Jepara</h3>
                        <p style="color:var(--text-secondary);margin-bottom:1.5rem;">Klik tombol di bawah untuk membuka
                            peta monitoring live</p>
                        <a href="{{ route('map.index') }}" class="btn-primary">Buka Monitoring Map</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="how-section">
        <div class="how-content">
            <div style="text-align:center;">
                <div class="section-tag">⚙️ Cara Kerja</div>
                <h2 class="section-title">Sederhana, Terstruktur,<br>dan Terpercaya</h2>
            </div>
            <div class="how-steps">
                <div class="step-card">
                    <div class="step-num active">1</div>
                    <h3>Input Data Lapangan</h3>
                    <p>Admin Dishub menginput data PJU dari lapangan lengkap dengan koordinat GPS, foto, dan spesifikasi
                        teknis.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <h3>Validasi Verifikator</h3>
                    <p>Tim Verifikator memvalidasi keakuratan data. Hanya data yang disetujui yang ditampilkan di peta
                        publik.</p>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <h3>Publik & Transparan</h3>
                    <p>Masyarakat dapat memantau kondisi PJU di lingkungan mereka secara realtime melalui peta
                        interaktif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>Mulai Monitoring Sekarang</h2>
            <p>Bergabunglah dalam mewujudkan tata kelola infrastruktur lampu jalan yang lebih transparan dan efisien
                untuk Kota Jepara.</p>
            <div class="cta-btns">
                <a href="{{ route('map.index') }}" class="btn-primary">🗺️ Lihat Peta Publik</a>
                <a href="{{ route('login') }}" class="btn-secondary">🔐 Login Admin</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-inner">
            <div class="footer-logo">💡 Luminous Jepara</div>
            <div class="footer-copy">© {{ date('Y') }} Dinas Perhubungan Kabupaten Jepara. Sistem Informasi PJU.</div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Generate floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 25; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.width = p.style.height = (Math.random() * 3 + 1) + 'px';
            p.style.animationDuration = (Math.random() * 15 + 10) + 's';
            p.style.animationDelay = (Math.random() * 10) + 's';
            container.appendChild(p);
        }

        // Fetch live stats
        fetch('/api/markers')
            .then(r => r.json())
            .then(data => {
                const total = data.length;
                const normal = data.filter(d => d.status === 'normal').length;
                const mati = data.filter(d => d.status === 'mati').length;
                animateCount('stat-total', total);
                animateCount('stat-normal', normal);
                animateCount('stat-mati', mati);
            }).catch(() => { });

        function animateCount(id, end) {
            let start = 0;
            const el = document.getElementById(id);
            if (!el) return;
            const step = () => {
                start += Math.ceil(end / 30);
                if (start > end) start = end;
                el.textContent = start;
                if (start < end) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        }
    </script>
</body>

</html>