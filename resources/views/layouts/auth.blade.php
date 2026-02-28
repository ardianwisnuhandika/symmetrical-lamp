<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Luminous Jepara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Syne:wght@800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --navy: #0B1120;
            --accent: #F59E0B;
            --accent-glow: rgba(245, 158, 11, 0.3);
            --text-1: #F1F5F9;
            --text-2: #94A3B8;
            --text-3: #64748B;
            --card: #141E32;
            --border: rgba(255, 255, 255, 0.08);
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .bg {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(245, 158, 11, 0.06) 0%, transparent 60%), radial-gradient(ellipse at 80% 20%, rgba(16, 185, 129, 0.04) 0%, transparent 50%);
        }

        .bg-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(245, 158, 11, 0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(245, 158, 11, 0.04) 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.5;
        }

        .auth-box {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .auth-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), #D97706);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 0.75rem;
            box-shadow: 0 10px 30px var(--accent-glow);
        }

        .auth-logo h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .auth-logo p {
            color: var(--text-3);
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.7rem 1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-1);
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .form-control::placeholder {
            color: var(--text-3);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .form-check input {
            accent-color: var(--accent);
        }

        .form-check label {
            font-size: 0.82rem;
            color: var(--text-2);
        }

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--accent), #D97706);
            color: #000;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--accent-glow);
        }

        .auth-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #EF4444;
            padding: 0.7rem 0.9rem;
            border-radius: 8px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }

        .auth-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10B981;
            padding: 0.7rem 0.9rem;
            border-radius: 8px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }

        .auth-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: var(--text-3);
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.25rem 0;
        }
    </style>
</head>

<body>
    <div class="bg"></div>
    <div class="bg-grid"></div>
    <div class="auth-box">
        <div class="auth-card">
            <div class="auth-logo">
                <div class="auth-logo-icon">💡</div>
                <h1>Luminous Jepara</h1>
                <p>Sistem Monitoring PJU Kabupaten Jepara</p>
            </div>
            @yield('content')
        </div>
    </div>
</body>

</html>