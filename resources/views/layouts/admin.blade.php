<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Luminous Jepara Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --navy: #0B1120;
            --navy-2: #111827;
            --sidebar: #0D1526;
            --card: #141E32;
            --card-2: #1a2540;
            --accent: #F59E0B;
            --accent2: #FBBF24;
            --accent-glow: rgba(245, 158, 11, 0.25);
            --green: #10B981;
            --red: #EF4444;
            --blue: #3B82F6;
            --text-1: #F1F5F9;
            --text-2: #94A3B8;
            --text-3: #64748B;
            --border: rgba(255, 255, 255, 0.07);
            --border2: rgba(255, 255, 255, 0.12);
            --sidebar-w: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            color: var(--text-1);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), #D97706);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 0 20px var(--accent-glow);
            flex-shrink: 0;
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .logo-sub {
            font-size: 0.7rem;
            color: var(--text-3);
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section-title {
            padding: 0.4rem 1.2rem 0.2rem;
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--text-3);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.2rem;
            margin: 0.1rem 0.5rem;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-2);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-1);
        }

        .nav-item.active {
            background: rgba(245, 158, 11, 0.12);
            color: var(--accent);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: #000;
            padding: 0.1rem 0.5rem;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 1rem 1.2rem;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #D97706);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #000;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 0.82rem;
            font-weight: 600;
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--text-3);
        }

        .logout-btn {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-3);
            font-size: 1rem;
            transition: color 0.2s;
            padding: 0.2rem;
        }

        .logout-btn:hover {
            color: var(--red);
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            height: 64px;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(13, 21, 38, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-2);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .topbar-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-1);
        }

        /* ===== PAGE CONTENT ===== */
        .page-content {
            padding: 1.5rem;
            flex: 1;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* ===== STAT CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--border2);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--accent-color, rgba(245, 158, 11, 0.05));
            transform: translate(30px, -30px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-val {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-lbl {
            font-size: 0.8rem;
            color: var(--text-2);
            margin-top: 0.3rem;
        }

        .stat-trend {
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        .stat-trend.up {
            color: var(--green);
        }

        .stat-trend.down {
            color: var(--red);
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            color: var(--text-3);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        td {
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.65rem;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-normal {
            background: rgba(16, 185, 129, 0.1);
            color: var(--green);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-mati {
            background: rgba(239, 68, 68, 0.1);
            color: var(--red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-verified {
            background: rgba(59, 130, 246, 0.1);
            color: var(--blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-unverified {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-role {
            background: rgba(139, 92, 246, 0.1);
            color: #A78BFA;
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .badge-pju {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-rambu {
            background: rgba(59, 130, 246, 0.1);
            color: var(--blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-rppj {
            background: rgba(139, 92, 246, 0.1);
            color: #A78BFA;
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .badge-cermin {
            background: rgba(6, 182, 212, 0.1);
            color: #22D3EE;
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #D97706);
            color: #000;
        }

        .btn-accent:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        .btn-ghost {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-1);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .btn-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--green);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .btn-success:hover {
            background: rgba(16, 185, 129, 0.2);
        }

        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        /* ===== FORMS ===== */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 0.6rem 0.9rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-1);
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .form-control::placeholder {
            color: var(--text-3);
        }

        .form-select option {
            background: var(--card);
        }

        .form-error {
            color: var(--red);
            font-size: 0.75rem;
            margin-top: 0.3rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 0.8rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--green);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--red);
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            gap: 0.3rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination .page-link {
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-size: 0.8rem;
            text-decoration: none;
            color: var(--text-2);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .pagination .page-item.active .page-link {
            background: var(--accent);
            color: #000;
            border-color: var(--accent);
        }

        .pagination .page-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-1);
        }

        /* ===== SEARCH/FILTER BAR ===== */
        .filter-bar {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            width: auto;
            min-width: 160px;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-wrap .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-3);
        }

        .search-wrap .form-control {
            padding-left: 2.2rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width:1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-wrap {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">💡</div>
            <div>
                <div class="logo-text">Luminous Jepara</div>
                <div class="logo-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>

            <a href="{{ route('admin.map') }}" class="nav-item {{ request()->routeIs('admin.map') ? 'active' : '' }}">
                <span class="nav-icon">🗺️</span> Map View
            </a>

            <a href="{{ route('admin.pju.index') }}"
                class="nav-item {{ request()->routeIs('admin.pju.*') ? 'active' : '' }}">
                <span class="nav-icon">💡</span> Data PJU
            </a>

            @hasrole('super_admin')
            <div class="nav-section-title" style="margin-top:0.5rem;">Administrasi</div>

            <a href="{{ route('admin.users.index') }}"
                class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> User Management
            </a>
            @endhasrole

            <div class="nav-section-title" style="margin-top:0.5rem;">Lainnya</div>

            <a href="{{ route('map.index') }}" target="_blank" class="nav-item">
                <span class="nav-icon">🌍</span> Peta Publik
                <span style="margin-left:auto;font-size:0.7rem;color:var(--text-3);">↗</span>
            </a>

            <a href="{{ route('home') }}" class="nav-item">
                <span class="nav-icon">🏠</span> Beranda
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">⏻</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-wrap">
        <!-- TOPBAR -->
        <header class="topbar">
            <div style="display:flex;align-items:center;gap:1rem;">
                <button class="topbar-btn" id="sidebar-toggle" onclick="toggleSidebar()">☰</button>
                <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                <a href="{{ route('map.index') }}" target="_blank" class="btn btn-ghost btn-sm">🌍 Peta Publik</a>
                <a href="{{ route('admin.pju.create') }}" class="btn btn-accent btn-sm">+ Tambah PJU</a>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
    </script>
    @stack('scripts')
</body>

</html>