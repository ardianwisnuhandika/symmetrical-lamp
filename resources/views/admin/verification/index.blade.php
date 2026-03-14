@extends('layouts.admin')

@section('title', 'Verifikasi PJU')
@section('page-title', 'Verifikasi Data PJU')

@section('content')
    <style>
        .verify-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .verify-kpi {
            background: linear-gradient(180deg, rgba(21, 40, 76, 0.95), rgba(16, 32, 64, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .verify-num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 33px;
            font-weight: 700;
            line-height: 1;
        }

        .verify-sub {
            font-size: 13px;
            color: var(--muted-2);
        }
    </style>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.verification.index') }}" class="filter-bar">
                <div class="search-wrap">
                    <span class="search-icon">??</span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / keterangan..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>

                <select name="state" class="form-select">
                    <option value="pending" {{ ($filters['state'] ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending Verifikasi</option>
                    <option value="verified" {{ ($filters['state'] ?? '') === 'verified' ? 'selected' : '' }}>Sudah Terverifikasi</option>
                    <option value="all" {{ ($filters['state'] ?? '') === 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>

                <button type="submit" class="btn btn-accent">Terapkan</button>
                <a href="{{ route('admin.verification.index') }}" class="btn btn-ghost">Reset</a>
            </form>
        </div>
    </div>

    <div class="verify-grid">
        <div class="verify-kpi">
            <div style="font-size:20px;">?</div>
            <div>
                <div class="verify-num" style="color:#f59e0b;">{{ $stats['pending'] }}</div>
                <div class="verify-sub">Pending Verifikasi</div>
            </div>
        </div>
        <div class="verify-kpi">
            <div style="font-size:20px;">?</div>
            <div>
                <div class="verify-num" style="color:#3b82f6;">{{ $stats['verified'] }}</div>
                <div class="verify-sub">Sudah Terverifikasi</div>
            </div>
        </div>
        <div class="verify-kpi">
            <div style="font-size:20px;">??</div>
            <div>
                <div class="verify-num">{{ $stats['total'] }}</div>
                <div class="verify-sub">Total Titik PJU</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Antrian Verifikasi</div>
            <div style="font-size:13px;color:var(--muted-2);">{{ $pjuPoints->total() }} data</div>
        </div>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Status Lampu</th>
                        <th>Lokasi</th>
                        <th>Status Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pjuPoints as $i => $pju)
                        <tr>
                            <td>{{ $pjuPoints->firstItem() + $i }}</td>
                            <td>
                                <div style="font-weight:700;">{{ $pju->nama }}</div>
                                <div style="font-size:13px;color:var(--muted-2);">Dibuat: {{ optional($pju->creator)->name ?? '-' }}</div>
                            </td>
                            <td><span class="badge badge-{{ $pju->kategori }}">{{ strtoupper($pju->kategori) }}</span></td>
                            <td>
                                @if($pju->status === 'normal')
                                    <span class="badge badge-normal">? Normal</span>
                                @else
                                    <span class="badge badge-mati">? Mati</span>
                                @endif
                            </td>
                            <td style="font-family:monospace;color:var(--muted);">{{ $pju->lat }}, {{ $pju->long }}</td>
                            <td>
                                @if($pju->is_verified)
                                    <span class="badge badge-verified">? Terverifikasi</span>
                                    <div style="font-size:12px;color:var(--muted-2);margin-top:4px;">oleh {{ optional($pju->verifier)->name ?? '-' }}</div>
                                @else
                                    <span class="badge badge-unverified">? Pending</span>
                                @endif
                            </td>
                            <td>
                                @if(!$pju->is_verified)
                                    <form method="POST" action="{{ route('admin.pju.verify', $pju) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-success btn-sm" type="submit">Approve</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.pju.verify', $pju) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-ghost btn-sm" type="submit">Batalkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:44px 20px;color:var(--muted-2);">Tidak ada data untuk diverifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pjuPoints->hasPages())
            <div style="padding:12px 14px;border-top:1px solid var(--line-soft);">
                {{ $pjuPoints->links('vendor.pagination.simple-default') }}
            </div>
        @endif
    </div>
@endsection
