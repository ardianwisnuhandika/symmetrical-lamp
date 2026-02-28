@extends('layouts.admin')
@section('title', 'Profil Saya')
@section('page-title', '👤 Profil Saya')
@section('content')
    <div style="max-width:560px;">
        <div class="card">
            <div class="card-header"><span class="card-title">👤 Update Profil</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-accent">💾 Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection