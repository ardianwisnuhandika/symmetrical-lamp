@extends('layouts.admin')
@section('title', 'Edit User')
@section('page-title', '✏️ Edit User')
@section('content')
    <div style="max-width:600px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">✏️ Edit: {{ $user->name }}</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', ucwords($role->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div
                        style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--border);">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-accent">💾 Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection