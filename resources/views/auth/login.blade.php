@extends('layouts.auth')
@section('title', 'Login')
@section('content')

    @if($errors->any())
        <div class="auth-error">
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}"
                placeholder="admin@luminousjepara.id" required autofocus autocomplete="username">
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" placeholder="••••••••" required
                autocomplete="current-password">
        </div>
        <div class="form-check">
            <input id="remember" type="checkbox" name="remember">
            <label for="remember">Ingat saya</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link" style="margin-left:auto;font-size:0.78rem;">Lupa
                    password?</a>
            @endif
        </div>
        <button type="submit" class="btn-submit">🔐 Login ke Dashboard</button>
    </form>

    <div class="auth-footer">
        <a href="{{ route('home') }}" class="auth-link">← Kembali ke Beranda</a>
    </div>

    <div class="divider"></div>
    <div
        style="padding:0.75rem;background:rgba(245,158,11,0.05);border:1px solid rgba(245,158,11,0.1);border-radius:10px;font-size:0.75rem;color:var(--text-3);">
        <strong style="color:var(--accent);">Demo Akun:</strong><br>
        Super Admin: superadmin@luminousjepara.id<br>
        Admin Dishub: admin@luminousjepara.id<br>
        Verifikator: verifikator@luminousjepara.id<br>
        <span style="color:var(--text-3);">Password semua: <strong>password</strong></span>
    </div>
@endsection