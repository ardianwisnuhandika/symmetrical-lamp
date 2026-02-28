@extends('layouts.auth')
@section('title', 'Lupa Password')
@section('content')
    @if(session('status'))
        <div class="auth-success">{{ session('status') }}</div>
    @endif
    <p style="color:var(--text-2);font-size:0.85rem;margin-bottom:1.5rem;line-height:1.6;">
        Masukkan email Anda dan kami akan mengirimkan link reset password.
    </p>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        @error('email')<div class="auth-error">{{ $message }}</div>@enderror
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@example.com"
                required>
        </div>
        <button type="submit" class="btn-submit">📧 Kirim Link Reset</button>
    </form>
    <div class="auth-footer">
        <a href="{{ route('login') }}" class="auth-link">← Kembali ke Login</a>
    </div>
@endsection