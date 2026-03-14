<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Luminous Jepara Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #071126;
            --bg-soft: #0b1731;
            --panel: #0f1f3f;
            --panel-soft: #13264b;
            --panel-hover: #18305e;
            --line: rgba(255, 255, 255, 0.10);
            --line-soft: rgba(255, 255, 255, 0.06);
            --text: #ecf2ff;
            --muted: #9fb2d8;
            --muted-2: #7f94be;
            --accent: #f59e0b;
            --accent-2: #ffbd45;
            --good: #10b981;
            --bad: #ef4444;
            --info: #3b82f6;
            --radius: 16px;
            --radius-sm: 12px;
            --shadow: 0 14px 30px rgba(2, 8, 22, 0.45);
            --sidebar-w: 274px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(980px 560px at -10% -20%, rgba(245, 158, 11, 0.12), transparent 58%),
                radial-gradient(920px 560px at 110% -25%, rgba(59, 130, 246, 0.14), transparent 62%),
                linear-gradient(180deg, #071126 0%, #060f23 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            line-height: 1.48;
        }

        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, #0a1630 0%, #0a1836 100%);
            border-right: 1px solid var(--line-soft);
            position: fixed;
            inset: 0 auto 0 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transition: transform 0.26s ease;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 18px;
            border-bottom: 1px solid var(--line-soft);
        }

        .logo-mark {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(145deg, var(--accent), #d97706);
            color: #1e1300;
            display: grid;
            place-items: center;
            font-size: 20px;
            font-weight: 800;
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.28);
            flex-shrink: 0;
        }

        .brand-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 28px;
            line-height: 0.95;
            letter-spacing: 0.01em;
        }

        .brand-sub {
            margin-top: 2px;
            color: var(--muted-2);
            font-size: 13px;
            font-weight: 500;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 14px 10px;
        }

        .nav-label {
            color: var(--muted-2);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 10px 10px 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            text-decoration: none;
            font-size: 19px;
            font-weight: 600;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .nav-item:hover {
            color: var(--text);
            background: rgba(255, 255, 255, 0.04);
            border-color: var(--line-soft);
        }

        .nav-item.active {
            color: #ffd48a;
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.20), rgba(245, 158, 11, 0.08));
            border-color: rgba(245, 158, 11, 0.24);
        }

        .nav-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: currentColor;
            opacity: 0.9;
            flex-shrink: 0;
        }

        .sidebar-footer {
            border-top: 1px solid var(--line-soft);
            padding: 14px;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--line-soft);
            border-radius: 14px;
            padding: 10px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(145deg, var(--accent), #d97706);
            color: #221503;
            font-weight: 800;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 14px;
            font-weight: 700;
        }

        .user-role {
            font-size: 12px;
            color: var(--muted-2);
            text-transform: capitalize;
        }

        .logout-btn {
            margin-left: auto;
            background: transparent;
            border: 0;
            color: var(--muted);
            cursor: pointer;
            font-size: 16px;
        }

        .logout-btn:hover {
            color: #ffd48a;
        }

        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            min-height: 72px;
            border-bottom: 1px solid var(--line-soft);
            background: rgba(7, 17, 38, 0.66);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 18px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .menu-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--line-soft);
            color: var(--muted);
            font-size: 17px;
            display: grid;
            place-items: center;
            cursor: pointer;
        }

        .menu-btn:hover {
            color: var(--text);
            background: rgba(255, 255, 255, 0.09);
        }

        .page-title {
            font-size: 29px;
            font-weight: 700;
            letter-spacing: 0.01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page {
            padding: 20px;
            flex: 1;
        }

        .shell {
            width: 100%;
            max-width: 1380px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .card {
            background: linear-gradient(180deg, rgba(19, 38, 75, 0.98) 0%, rgba(15, 31, 63, 0.98) 100%);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid var(--line-soft);
            padding: 15px 18px;
        }

        .card-title {
            font-size: 26px;
            font-weight: 700;
        }

        .card-body {
            padding: 16px 18px;
        }

        .btn {
            min-height: 40px;
            border-radius: 12px;
            padding: 8px 13px;
            font-size: 17px;
            font-weight: 700;
            border: 1px solid transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-sm {
            min-height: 32px;
            border-radius: 10px;
            padding: 5px 10px;
            font-size: 13px;
            font-weight: 700;
        }

        .btn-accent {
            background: linear-gradient(145deg, var(--accent), #db8600);
            color: #1d1200;
            border-color: rgba(255, 193, 90, 0.35);
        }

        .btn-accent:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(245, 158, 11, 0.28);
        }

        .btn-ghost {
            color: var(--text);
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--line);
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.09);
        }

        .btn-success {
            color: #ecfff9;
            background: rgba(16, 185, 129, 0.22);
            border-color: rgba(16, 185, 129, 0.35);
        }

        .btn-success:hover {
            background: rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            color: #ffeceb;
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.35);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .form-control,
        .form-select {
            width: 100%;
            min-height: 46px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            padding: 11px 14px;
            font-size: 15px;
            font-family: inherit;
            outline: 0;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(245, 158, 11, 0.55);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.20);
        }

        .form-control::placeholder {
            color: var(--muted-2);
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            width: auto;
            min-width: 180px;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-2);
        }

        .search-wrap .form-control {
            padding-left: 36px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            font-size: 17px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            text-align: left;
            color: var(--muted-2);
            padding: 13px 16px;
            border-bottom: 1px solid var(--line-soft);
            white-space: nowrap;
        }

        td {
            font-size: 15px;
            padding: 15px 16px;
            border-bottom: 1px solid var(--line-soft);
            vertical-align: middle;
            line-height: 1.45;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.03);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .badge-normal {
            color: #b4ffe6;
            background: rgba(16, 185, 129, 0.18);
            border-color: rgba(16, 185, 129, 0.32);
        }

        .badge-mati {
            color: #ffd1ce;
            background: rgba(239, 68, 68, 0.18);
            border-color: rgba(239, 68, 68, 0.30);
        }

        .badge-verified {
            color: #cfe2ff;
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .badge-unverified {
            color: #ffe4b3;
            background: rgba(245, 158, 11, 0.2);
            border-color: rgba(245, 158, 11, 0.35);
        }

        .badge-pju {
            color: #ffe1a7;
            background: rgba(245, 158, 11, 0.14);
            border-color: rgba(245, 158, 11, 0.24);
        }

        .badge-rambu {
            color: #d2e4ff;
            background: rgba(59, 130, 246, 0.14);
            border-color: rgba(59, 130, 246, 0.25);
        }

        .badge-rppj {
            color: #e5d8ff;
            background: rgba(139, 92, 246, 0.16);
            border-color: rgba(139, 92, 246, 0.25);
        }

        .badge-cermin {
            color: #ccf7ff;
            background: rgba(6, 182, 212, 0.15);
            border-color: rgba(6, 182, 212, 0.25);
        }

        .alert {
            border-radius: 12px;
            padding: 11px 14px;
            border: 1px solid transparent;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-success {
            color: #c8ffea;
            background: rgba(16, 185, 129, 0.18);
            border-color: rgba(16, 185, 129, 0.32);
        }

        .alert-error {
            color: #ffd3d0;
            background: rgba(239, 68, 68, 0.18);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 10px;
        }

        .pagination .page-link {
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.04);
            color: var(--muted);
            border-radius: 10px;
            min-width: 34px;
            min-height: 34px;
            display: inline-grid;
            place-items: center;
            text-decoration: none;
            font-size: 13px;
            padding: 4px 9px;
        }

        .pagination .page-item.active .page-link {
            background: var(--accent);
            color: #1f1300;
            border-color: rgba(245, 158, 11, 0.45);
        }

        @media (max-width: 1180px) {
            :root {
                --sidebar-w: 252px;
            }
        }

        @media (max-width: 920px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
            }

            .page {
                padding: 13px;
            }

            .topbar {
                padding: 8px 12px;
            }

            .page-title {
                font-size: 16px;
            }

            .topbar-right {
                gap: 6px;
            }

            .filter-bar .form-control,
            .filter-bar .form-select {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-mark">LJ</div>
            <div>
                <div class="brand-title">Luminous Jepara</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>

            @can('view_dashboard')
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-dot"></span> Dashboard
                </a>
            @endcan

            @can('view_pju')
                <a href="{{ route('admin.map') }}" class="nav-item {{ request()->routeIs('admin.map') ? 'active' : '' }}">
                    <span class="nav-dot"></span> Map View
                </a>
            @endcan

            @can('view_pju')
                <a href="{{ route('admin.pju.index') }}" class="nav-item {{ request()->routeIs('admin.pju.*') ? 'active' : '' }}">
                    <span class="nav-dot"></span> Data PJU
                </a>
            @endcan

            @can('verify_pju')
                <a href="{{ route('admin.verification.index') }}" class="nav-item {{ request()->routeIs('admin.verification.*') ? 'active' : '' }}">
                    <span class="nav-dot"></span> Verifikasi PJU
                </a>
            @endcan

            @can('manage_users')
                <div class="nav-label" style="margin-top:8px;">Administrasi</div>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="nav-dot"></span> User Management
                </a>
            @endcan

            @can('view_logs')
                <a href="{{ route('admin.logs.index') }}" class="nav-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                    <span class="nav-dot"></span> Audit Log
                </a>
            @endcan

            <div class="nav-label" style="margin-top:8px;">Lainnya</div>
            <a href="{{ route('map.index') }}" target="_blank" class="nav-item">
                <span class="nav-dot"></span> Peta Publik
            </a>
            <a href="{{ route('home') }}" class="nav-item">
                <span class="nav-dot"></span> Beranda
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-box">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-btn" onclick="toggleSidebar()">?</button>
                <span class="page-title">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                <a href="{{ route('map.index') }}" target="_blank" class="btn btn-ghost btn-sm">Peta Publik</a>
                @can('create_pju')
                    <a href="{{ route('admin.pju.create') }}" class="btn btn-accent btn-sm">Tambah PJU</a>
                @endcan
            </div>
        </header>

        <main class="page">
            <div class="shell">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
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
