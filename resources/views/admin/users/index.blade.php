@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', '👥 User Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">👥 Daftar User & Role</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-accent btn-sm">+ Tambah User</a>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                        <tr>
                            <td style="color:var(--text-3);">{{ $users->firstItem() + $i }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.75rem;">
                                    <div
                                        style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#D97706);display:flex;align-items:center;justify-content:center;font-weight:700;color:#000;font-size:0.85rem;flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;">{{ $user->name }}</div>
                                        @if($user->id === auth()->id())
                                            <div style="font-size:0.7rem;color:var(--accent);">← Anda</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="color:var(--text-2);">{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-role">{{ str_replace('_', ' ', ucwords($role->name)) }}</span>
                                @endforeach
                            </td>
                            <td style="color:var(--text-3);font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display:flex;gap:0.3rem;">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Hapus user ini?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">🗑️</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:3rem;color:var(--text-3);">Tidak ada user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div style="padding:1rem;border-top:1px solid var(--border);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection