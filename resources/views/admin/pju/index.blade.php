@extends('layouts.admin')

@section('title', 'Data PJU')
@section('page-title', '💡 Data PJU')

@section('content')
    <!-- FILTER BAR -->
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body" style="padding:0.75rem 1rem;">
            <form method="GET" action="{{ route('admin.pju.index') }}" class="filter-bar">
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama PJU..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="normal" {{ ($filters['status'] ?? '') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="mati" {{ ($filters['status'] ?? '') === 'mati' ? 'selected' : '' }}>Mati</option>
                </select>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="pju" {{ ($filters['kategori'] ?? '') === 'pju' ? 'selected' : '' }}>PJU</option>
                    <option value="rambu" {{ ($filters['kategori'] ?? '') === 'rambu' ? 'selected' : '' }}>Rambu</option>
                    <option value="rppj" {{ ($filters['kategori'] ?? '') === 'rppj' ? 'selected' : '' }}>RPPJ</option>
                    <option value="cermin" {{ ($filters['kategori'] ?? '') === 'cermin' ? 'selected' : '' }}>Cermin</option>
                </select>
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="sonte" {{ ($filters['jenis'] ?? '') === 'sonte' ? 'selected' : '' }}>Sonte</option>
                    <option value="led" {{ ($filters['jenis'] ?? '') === 'led' ? 'selected' : '' }}>LED</option>
                    <option value="kalipucang" {{ ($filters['jenis'] ?? '') === 'kalipucang' ? 'selected' : '' }}>Kalipucang
                    </option>
                </select>
                <button type="submit" class="btn btn-accent">Filter</button>
                <a href="{{ route('admin.pju.index') }}" class="btn btn-ghost">Reset</a>
                                @can('create_pju')
                <a href="{{ route('admin.pju.create') }}" class="btn btn-accent" style="margin-left:auto;">+ Tambah PJU</a>
                                @endcan
            </form>
        </div>
    </div>

    <!-- STATS ROW -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;margin-bottom:1rem;">
        <div
            style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:0.75rem 1rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="font-size:1.3rem;">💡</span>
            <div>
                <div style="font-size:1.3rem;font-weight:800;font-family:'Syne',sans-serif;">{{ $stats['total'] }}</div>
                <div style="font-size:0.72rem;color:var(--text-3);">Total Titik</div>
            </div>
        </div>
        <div
            style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:0.75rem 1rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="font-size:1.3rem;">🟢</span>
            <div>
                <div style="font-size:1.3rem;font-weight:800;font-family:'Syne',sans-serif;color:#10B981;">
                    {{ $stats['normal'] }}</div>
                <div style="font-size:0.72rem;color:var(--text-3);">Normal</div>
            </div>
        </div>
        <div
            style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:0.75rem 1rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="font-size:1.3rem;">🔴</span>
            <div>
                <div style="font-size:1.3rem;font-weight:800;font-family:'Syne',sans-serif;color:#EF4444;">
                    {{ $stats['mati'] }}</div>
                <div style="font-size:0.72rem;color:var(--text-3);">Mati</div>
            </div>
        </div>
        <div
            style="background:var(--card);border:1px solid var(--border);border-radius:12px;padding:0.75rem 1rem;display:flex;align-items:center;gap:0.75rem;">
            <span style="font-size:1.3rem;">✅</span>
            <div>
                <div style="font-size:1.3rem;font-weight:800;font-family:'Syne',sans-serif;color:#3B82F6;">
                    {{ $stats['verified'] }}</div>
                <div style="font-size:0.72rem;color:var(--text-3);">Terverifikasi</div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">💡 Daftar Titik PJU</span>
            <span style="font-size:0.8rem;color:var(--text-3);">{{ $pjuPoints->total() }} data ditemukan</span>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Daya</th>
                        <th>Letak</th>
                        <th>Type</th>
                        <th>Koordinat</th>
                        <th>Status</th>
                        <th>Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pjuPoints as $i => $pju)
                        <tr>
                            <td style="color:var(--text-3);">{{ $pjuPoints->firstItem() + $i }}</td>
                            <td>
                                <div style="font-weight:600;">{{ $pju->nama }}</div>
                                @if($pju->keterangan)
                                    <div style="font-size:0.72rem;color:var(--text-3);margin-top:2px;">
                                        {{ Str::limit($pju->keterangan, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $pju->kategori }}">{{ strtoupper($pju->kategori) }}</span>
                            </td>
                            <td>{{ ucfirst($pju->jenis) }}</td>
                            <td>{{ $pju->daya ?? '—' }}</td>
                            <td>{{ ucfirst($pju->letak) }}</td>
                            <td>{{ $pju->type ?? '—' }}</td>
                            <td>
                                <div style="font-size:0.75rem;color:var(--text-3);font-family:monospace;">
                                    {{ $pju->lat }},<br>{{ $pju->long }}
                                </div>
                            </td>
                            <td>
                                @if($pju->status === 'normal')
                                    <span class="badge badge-normal">● Normal</span>
                                @else
                                    <span class="badge badge-mati" style="animation:pulse-badge 1s infinite;">● Mati</span>
                                @endif
                            </td>
                            <td>
                                @if($pju->is_verified)
                                    <span class="badge badge-verified">✓ Terverifikasi</span>
                                @else
                                    <span class="badge badge-unverified">⏳ Pending</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:0.3rem;flex-wrap:wrap;">
                                    @can('edit_pju')
                                    <a href="{{ route('admin.pju.edit', $pju) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                    @endcan

                                    @can('verify_pju')
                                        @if(!$pju->is_verified)
                                            <form method="POST" action="{{ route('admin.pju.verify', $pju) }}" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button class="btn btn-success btn-sm" type="submit">✅</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.pju.verify', $pju) }}" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <button class="btn btn-ghost btn-sm" type="submit">↩️</button>
                                            </form>
                                        @endif
                                    @endcan

                                    @can('delete_pju')
                                        <form method="POST" action="{{ route('admin.pju.destroy', $pju) }}"
                                            onsubmit="return confirm('Hapus data PJU ini?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">🗑️</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align:center;padding:3rem;color:var(--text-3);">
                                <div style="font-size:3rem;margin-bottom:1rem;">💡</div>
                                <div>Tidak ada data PJU ditemukan</div>
                                @can('create_pju')
                                <a href="{{ route('admin.pju.create') }}" class="btn btn-accent" style="margin-top:1rem;">+
                                    Tambah PJU Pertama</a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pjuPoints->hasPages())
            <div style="padding:1rem;border-top:1px solid var(--border);">
                {{ $pjuPoints->links('vendor.pagination.simple-default') }}
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            @keyframes pulse-badge {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }
        </style>
    @endpush
@endsection
