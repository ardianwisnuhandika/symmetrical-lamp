@extends('layouts.admin')
@section('title', 'Tambah User')
@section('page-title', '➕ Tambah User Baru')
@section('content')
    <div style="max-width:600px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">👤 Form User Baru</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', ucwords($role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div
                        style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--border);">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-accent">💾 Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection