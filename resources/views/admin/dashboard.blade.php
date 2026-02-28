@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', '📊 Dashboard Overview')

@section('content')
    <div class="stats-grid">
        <div class="stat-card" style="--accent-color:rgba(245,158,11,0.07)">
            <div class="stat-icon" style="background:rgba(245,158,11,0.1);font-size:1.5rem;">💡</div>
            <div class="stat-val">{{ $stats['total'] }}</div>
            <div class="stat-lbl">Total Titik PJU</div>
            <div class="stat-trend up">↑ Semua titik terdaftar</div>
        </div>
        <div class="stat-card" style="--accent-color:rgba(16,185,129,0.07)">
            <div class="stat-icon" style="background:rgba(16,185,129,0.1);font-size:1.5rem;">✅</div>
            <div class="stat-val" style="color:#10B981;">{{ $stats['normal'] }}</div>
            <div class="stat-lbl">Status Normal</div>
            <div class="stat-trend up" style="color:#10B981;">● Beroperasi</div>
        </div>
        <div class="stat-card" style="--accent-color:rgba(239,68,68,0.07)">
            <div class="stat-icon" style="background:rgba(239,68,68,0.1);font-size:1.5rem;">🔴</div>
            <div class="stat-val" style="color:#EF4444;">{{ $stats['mati'] }}</div>
            <div class="stat-lbl">Status Mati / Rusak</div>
            <div class="stat-trend down">⚠ Perlu perhatian</div>
        </div>
        <div class="stat-card" style="--accent-color:rgba(59,130,246,0.07)">
            <div class="stat-icon" style="background:rgba(59,130,246,0.1);font-size:1.5rem;">👥</div>
            <div class="stat-val" style="color:#3B82F6;">{{ $totalUsers }}</div>
            <div class="stat-lbl">Total User Admin</div>
            <div class="stat-trend">3 Role Aktif</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
        <!-- Quick Map -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">🗺️ Peta Distribusi PJU</span>
                <a href="{{ route('admin.map') }}" class="btn btn-ghost btn-sm">Buka Fullscreen</a>
            </div>
            <div style="height:320px;background:#0d1929;position:relative;overflow:hidden;" id="mini-map"></div>
        </div>

        <!-- Status Chart -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">📈 Status Overview</span>
            </div>
            <div class="card-body">
                <!-- Donut chart via CSS -->
                @php
                    $total = max($stats['total'], 1);
                    $normalPct = round(($stats['normal'] / $total) * 100);
                    $matiPct = round(($stats['mati'] / $total) * 100);
                    $verPct = round(($stats['verified'] / $total) * 100);
                @endphp
                <div style="text-align:center;margin-bottom:1.5rem;">
                    <div style="position:relative;width:140px;height:140px;margin:0 auto;">
                        <svg viewBox="0 0 36 36" style="transform:rotate(-90deg);width:100%;height:100%">
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="rgba(255,255,255,0.05)"
                                stroke-width="3" />
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#10B981" stroke-width="3"
                                stroke-dasharray="{{ $normalPct }} {{ 100 - $normalPct }}" stroke-linecap="round" />
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#EF4444" stroke-width="3"
                                stroke-dasharray="{{ $matiPct }} {{ 100 - $matiPct }}" stroke-dashoffset="{{ -$normalPct }}"
                                stroke-linecap="round" />
                        </svg>
                        <div
                            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                            <span
                                style="font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;">{{ $total }}</span>
                            <span style="font-size:0.65rem;color:var(--text-3);">Total PJU</span>
                        </div>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#10B981;"></div>
                            Normal
                        </div>
                        <div style="font-weight:700;color:#10B981;">{{ $stats['normal'] }} <span
                                style="color:var(--text-3);font-weight:400;">({{ $normalPct }}%)</span></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#EF4444;"></div>
                            Mati / Rusak
                        </div>
                        <div style="font-weight:700;color:#EF4444;">{{ $stats['mati'] }} <span
                                style="color:var(--text-3);font-weight:400;">({{ $matiPct }}%)</span></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#3B82F6;"></div>
                            Terverifikasi
                        </div>
                        <div style="font-weight:700;color:#3B82F6;">{{ $stats['verified'] }} <span
                                style="color:var(--text-3);font-weight:400;">({{ $verPct }}%)</span></div>
                    </div>
                </div>
                <div style="margin-top:1.5rem;">
                    <div
                        style="display:flex;justify-content:space-between;font-size:0.75rem;color:var(--text-3);margin-bottom:0.3rem;">
                        <span>Progress Verifikasi</span><span>{{ $verPct }}%</span>
                    </div>
                    <div style="width:100%;height:6px;background:rgba(255,255,255,0.06);border-radius:3px;overflow:hidden;">
                        <div
                            style="width:{{ $verPct }}%;height:100%;background:linear-gradient(90deg,#3B82F6,#60A5FA);border-radius:3px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1.5rem;">
        <a href="{{ route('admin.pju.create') }}" class="card"
            style="text-decoration:none;padding:1.25rem;display:flex;align-items:center;gap:1rem;transition:all 0.2s;">
            <div
                style="width:44px;height:44px;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                ➕</div>
            <div>
                <div style="font-weight:600;font-size:0.9rem;">Tambah PJU Baru</div>
                <div style="font-size:0.75rem;color:var(--text-3);">Input titik PJU</div>
            </div>
        </a>
        <a href="{{ route('admin.pju.index') }}?status=mati" class="card"
            style="text-decoration:none;padding:1.25rem;display:flex;align-items:center;gap:1rem;transition:all 0.2s;">
            <div
                style="width:44px;height:44px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                🔴</div>
            <div>
                <div style="font-weight:600;font-size:0.9rem;">PJU Mati</div>
                <div style="font-size:0.75rem;color:var(--red);">{{ $stats['mati'] }} perlu tindakan</div>
            </div>
        </a>
        <a href="{{ route('admin.pju.index') }}?is_verified=0" class="card"
            style="text-decoration:none;padding:1.25rem;display:flex;align-items:center;gap:1rem;transition:all 0.2s;">
            <div
                style="width:44px;height:44px;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                ⏳</div>
            <div>
                <div style="font-weight:600;font-size:0.9rem;">Pending Verifikasi</div>
                <div style="font-size:0.75rem;color:var(--accent);">Menunggu approval</div>
            </div>
        </a>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const miniMap = L.map('mini-map', { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false })
            .setView([-6.5886, 110.6679], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(miniMap);

        fetch('/api/markers').then(r => r.json()).then(data => {
            data.forEach(p => {
                const color = p.status === 'normal' ? '#F59E0B' : '#EF4444';
                const marker = L.circleMarker([p.lat, p.lng], {
                    radius: 5, fillColor: color, color: color,
                    weight: 1, opacity: 1, fillOpacity: 0.9,
                }).addTo(miniMap);
            });
        });
    </script>
@endpush