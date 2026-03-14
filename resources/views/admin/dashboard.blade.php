@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Monitoring')

@section('content')
    <style>
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 12px;
        }

        .kpi-card {
            background: linear-gradient(180deg, rgba(21, 40, 76, 0.95), rgba(16, 32, 64, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 16px;
            padding: 16px;
        }

        .kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .kpi-title {
            font-size: 13px;
            color: var(--muted);
            font-weight: 600;
        }

        .kpi-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 32px;
            line-height: 1.05;
            font-weight: 700;
        }

        .kpi-note {
            font-size: 13px;
            color: var(--muted-2);
        }

        .dash-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(0, 1fr);
            gap: 12px;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .quick-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            text-decoration: none;
            color: var(--text);
        }

        .quick-ico {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-weight: 700;
            flex-shrink: 0;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.05);
        }

        .donut-wrap {
            width: 152px;
            height: 152px;
            margin: 0 auto 16px;
            position: relative;
        }

        .donut-center {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .donut-total {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 34px;
            font-weight: 700;
            line-height: 1;
        }

        .legend-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .legend-left {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
        }

        .dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            flex-shrink: 0;
        }

        @media (max-width: 1024px) {
            .dash-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        $total = max($stats['total'], 1);
        $normalPct = round(($stats['normal'] / $total) * 100);
        $matiPct = round(($stats['mati'] / $total) * 100);
        $verPct = round(($stats['verified'] / $total) * 100);
    @endphp

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-title">Total Titik PJU</div>
                <div>??</div>
            </div>
            <div class="kpi-value">{{ $stats['total'] }}</div>
            <div class="kpi-note">Seluruh data titik terdaftar</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-title">Status Normal</div>
                <div style="color:#10b981;">?</div>
            </div>
            <div class="kpi-value" style="color:#10b981;">{{ $stats['normal'] }}</div>
            <div class="kpi-note">Beroperasi normal</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-title">Status Mati / Rusak</div>
                <div style="color:#ef4444;">?</div>
            </div>
            <div class="kpi-value" style="color:#ef4444;">{{ $stats['mati'] }}</div>
            <div class="kpi-note">Butuh tindak lanjut</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-title">Total User Admin</div>
                <div>??</div>
            </div>
            <div class="kpi-value" style="color:#60a5fa;">{{ $totalUsers }}</div>
            <div class="kpi-note">Super admin, dishub, verifikator</div>
        </div>
    </div>

    <div class="dash-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Peta Distribusi PJU</div>
                <a href="{{ route('admin.map') }}" class="btn btn-ghost btn-sm">Buka Fullscreen</a>
            </div>
            <div id="mini-map" style="height:360px;"></div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Ringkasan Status</div>
            </div>
            <div class="card-body">
                <div class="donut-wrap">
                    <svg viewBox="0 0 36 36" style="transform:rotate(-90deg);width:100%;height:100%">
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="3"></circle>
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#10B981" stroke-width="3" stroke-dasharray="{{ $normalPct }} {{ 100 - $normalPct }}" stroke-linecap="round"></circle>
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#EF4444" stroke-width="3" stroke-dasharray="{{ $matiPct }} {{ 100 - $matiPct }}" stroke-dashoffset="{{ -$normalPct }}" stroke-linecap="round"></circle>
                    </svg>
                    <div class="donut-center">
                        <div class="donut-total">{{ $total }}</div>
                        <div style="font-size:12px;color:var(--muted-2);">Total Titik</div>
                    </div>
                </div>

                <div class="legend-row">
                    <div class="legend-left"><span class="dot" style="background:#10B981;"></span>Normal</div>
                    <div>{{ $stats['normal'] }} ({{ $normalPct }}%)</div>
                </div>
                <div class="legend-row">
                    <div class="legend-left"><span class="dot" style="background:#EF4444;"></span>Mati / Rusak</div>
                    <div>{{ $stats['mati'] }} ({{ $matiPct }}%)</div>
                </div>
                <div class="legend-row" style="margin-bottom:12px;">
                    <div class="legend-left"><span class="dot" style="background:#3B82F6;"></span>Terverifikasi</div>
                    <div>{{ $stats['verified'] }} ({{ $verPct }}%)</div>
                </div>

                <div style="font-size:12px;color:var(--muted-2);margin-bottom:6px;">Progress Verifikasi {{ $verPct }}%</div>
                <div style="height:8px;background:rgba(255,255,255,0.08);border-radius:999px;overflow:hidden;">
                    <div style="height:100%;width:{{ $verPct }}%;background:linear-gradient(90deg,#3B82F6,#60A5FA);"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="quick-grid">
        @can('create_pju')
            <a href="{{ route('admin.pju.create') }}" class="card quick-card">
                <div class="quick-ico">+</div>
                <div>
                    <div style="font-weight:700;">Tambah PJU Baru</div>
                    <div style="font-size:13px;color:var(--muted-2);">Input titik baru dari lapangan</div>
                </div>
            </a>
        @endcan

        <a href="{{ route('admin.pju.index') }}?status=mati" class="card quick-card">
            <div class="quick-ico" style="color:#ef4444;">!</div>
            <div>
                <div style="font-weight:700;">PJU Mati</div>
                <div style="font-size:13px;color:#fca5a5;">{{ $stats['mati'] }} perlu tindakan</div>
            </div>
        </a>

        <a href="{{ route('admin.pju.index') }}?is_verified=0" class="card quick-card">
            <div class="quick-ico" style="color:#f59e0b;">?</div>
            <div>
                <div style="font-weight:700;">Pending Verifikasi</div>
                <div style="font-size:13px;color:#fcd34d;">Menunggu persetujuan</div>
            </div>
        </a>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const miniMap = L.map('mini-map', {
            zoomControl: false,
            attributionControl: false,
            dragging: false,
            scrollWheelZoom: false
        }).setView([-6.5886, 110.6679], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(miniMap);

        fetch('/api/markers').then(r => r.json()).then(data => {
            data.forEach(p => {
                const color = p.status === 'normal' ? '#F59E0B' : '#EF4444';
                L.circleMarker([p.lat, p.lng], {
                    radius: 5,
                    fillColor: color,
                    color: color,
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.9,
                }).addTo(miniMap);
            });
        });
    </script>
@endpush
