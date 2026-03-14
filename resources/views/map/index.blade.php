<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Map - Dishub Jepara</title>
    <meta name="description" content="Peta monitoring interaktif Penerangan Jalan Umum (PJU) Kabupaten Jepara.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --navy: #0B1120;
            --card: #141E32;
            --card-2: #1a2540;
            --accent: #F59E0B;
            --green: #10B981;
            --red: #EF4444;
            --blue: #3B82F6;
            --text-1: #F1F5F9;
            --text-2: #94A3B8;
            --text-3: #64748B;
            --border: rgba(255, 255, 255, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            color: var(--text-1);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* === TOPBAR === */
        .topbar {
            height: 56px;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(13, 21, 38, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            z-index: 1000;
            flex-shrink: 0;
        }

        .tb-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .tb-logo-icon {
            width: 32px;
            height: 32px;
        }
        
        .tb-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .tb-logo span {
            font-family: 'Syne', sans-serif;
            font-size: 0.9rem;
            font-weight: 800;
        }

        .tb-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .tb-btn {
            padding: 0.35rem 0.85rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tb-accent {
            background: linear-gradient(135deg, var(--accent), #D97706);
            color: #000;
        }

        .tb-ghost {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-1);
            border: 1px solid var(--border);
        }

        .tb-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* === LAYOUT === */
        .map-layout {
            display: flex;
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        /* === SIDEBAR PANEL === */
        .map-sidebar {
            width: 320px;
            background: rgba(13, 21, 38, 0.96);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 500;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-section {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-title {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-3);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.75rem;
        }

        /* Stats */
        .stat-pills {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .stat-pill {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 0.75rem;
        }

        .stat-pill .num {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-pill .lbl {
            font-size: 0.68rem;
            color: var(--text-3);
            margin-top: 2px;
        }

        .stat-pill.total .num {
            color: var(--accent);
        }

        .stat-pill.normal .num {
            color: var(--green);
        }

        .stat-pill.mati .num {
            color: var(--red);
        }

        .stat-pill.ver .num {
            color: var(--blue);
        }

        /* Filters */
        .filter-group {
            margin-bottom: 0.5rem;
        }

        .filter-label {
            font-size: 0.72rem;
            color: var(--text-2);
            margin-bottom: 0.3rem;
            display: block;
        }

        .filter-select {
            width: 100%;
            padding: 0.45rem 0.7rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-1);
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
            outline: none;
        }

        .filter-select:focus {
            border-color: var(--accent);
        }

        .filter-select option {
            background: var(--navy);
        }

        /* Legend */
        .legend-items {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8rem;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .legend-dot.normal {
            background: var(--accent);
            box-shadow: 0 0 8px var(--accent);
        }

        .legend-dot.mati {
            background: var(--red);
            animation: blink 1s infinite;
        }

        .legend-dot.rambu {
            background: var(--blue);
        }

        .legend-dot.rppj {
            background: #A78BFA;
        }

        .legend-dot.cermin {
            background: #22D3EE;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        /* Popup list */
        .popup-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .popup-item {
            padding: 0.7rem 0.75rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            margin-bottom: 0.4rem;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(255, 255, 255, 0.02);
        }

        .popup-item:hover {
            background: rgba(245, 158, 11, 0.06);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .popup-item .pi-name {
            font-size: 0.82rem;
            font-weight: 600;
        }

        .popup-item .pi-meta {
            font-size: 0.72rem;
            color: var(--text-3);
            margin-top: 2px;
        }

        .pi-status {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
        }

        /* === MAP === */
        #map {
            flex: 1;
            z-index: 1;
        }

        /* === LEAFLET CUSTOM === */
        .leaflet-container {
            background: #0d1929;
        }

        .leaflet-popup-content-wrapper {
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6) !important;
            color: var(--text-1) !important;
            padding: 0 !important;
            overflow: hidden;
            min-width: 300px;
            max-width: 360px;
        }

        .leaflet-popup-tip {
            background: var(--card) !important;
        }

        .leaflet-popup-close-button {
            color: var(--text-2) !important;
            right: 12px !important;
            top: 12px !important;
            font-size: 18px !important;
        }

        .popup-header {
            padding: 1rem 1rem 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .popup-title {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
        }

        .popup-badges {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .pop-badge {
            padding: 0.15rem 0.55rem;
            border-radius: 50px;
            font-size: 0.68rem;
            font-weight: 600;
        }

        .pop-badge.normal {
            background: rgba(16, 185, 129, 0.15);
            color: var(--green);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .pop-badge.mati {
            background: rgba(239, 68, 68, 0.15);
            color: var(--red);
            border: 1px solid rgba(239, 68, 68, 0.3);
            animation: badge-pulse 1s infinite;
        }

        @keyframes badge-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .pop-badge.ver {
            background: rgba(59, 130, 246, 0.15);
            color: var(--blue);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .popup-body {
            padding: 0.75rem 1rem;
        }

        .popup-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.3rem 0;
            font-size: 0.8rem;
        }

        .popup-row:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .popup-row .key {
            color: var(--text-3);
        }

        .popup-row .val {
            font-weight: 600;
        }

        .street-view-wrap {
            margin: 0.75rem 1rem;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
            position: relative;
        }

        .street-view-wrap iframe {
            display: block;
            width: 100%;
            height: 160px;
            border: none;
        }

        .sv-label {
            position: absolute;
            top: 6px;
            left: 6px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
        }

        .popup-actions {
            padding: 0.75rem 1rem;
            display: flex;
            gap: 0.5rem;
            border-top: 1px solid var(--border);
        }

        .pop-btn {
            flex: 1;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .pop-btn.maps {
            background: linear-gradient(135deg, #4285F4, #34A853);
            color: #fff;
        }

        .pop-btn.maps:hover {
            opacity: 0.9;
        }

        .pop-btn.share {
            background: rgba(255, 255, 255, 0.07);
            color: var(--text-1);
            border: 1px solid var(--border);
        }

        .pop-btn.share:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        /* Pulse marker CSS */
        .pulse-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--red);
            opacity: 0;
            animation: pulse-ring 1.5s ease-out infinite;
        }

        .pulse-ring:nth-child(2) {
            animation-delay: 0.5s;
        }

        @keyframes pulse-ring {
            0% {
                transform: translate(-50%, -50%) scale(0.5);
                opacity: 0.8;
            }

            100% {
                transform: translate(-50%, -50%) scale(2.5);
                opacity: 0;
            }
        }

        /* Loading */
        .map-loading {
            position: absolute;
            inset: 0;
            background: rgba(11, 17, 32, 0.8);
            backdrop-filter: blur(5px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 999;
            gap: 1rem;
        }

        .loading-spinner {
            width: 48px;
            height: 48px;
            border: 3px solid rgba(245, 158, 11, 0.2);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Bottom bar */
        .bottom-bar {
            position: absolute;
            bottom: 0;
            left: 320px;
            right: 0;
            padding: 0.6rem 1rem;
            background: rgba(13, 21, 38, 0.9);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--border);
            z-index: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--text-3);
        }

        .coords-display {
            font-family: monospace;
            color: var(--text-2);
        }

        @media (max-width:768px) {
            .map-sidebar {
                width: 100%;
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 999;
                max-height: 50vh;
                border-right: none;
                border-top: 1px solid var(--border);
            }

            .bottom-bar {
                left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- TOPBAR -->
    <header class="topbar">
        <a href="{{ route('home') }}" class="tb-logo">
            <div class="tb-logo-icon">
                <img src="{{ asset('images/logo-dishub.svg') }}" alt="Logo Dishub Jepara">
            </div>
            <span>Dishub Jepara</span>
        </a>
        <div class="tb-right">
            <span style="font-size:0.78rem;color:var(--text-3);" id="update-time">Memuat data...</span>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="tb-btn tb-ghost">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="tb-btn tb-accent">Login Admin</a>
            @endauth
        </div>
    </header>

    <!-- MAP LAYOUT -->
    <div class="map-layout">

        <!-- SIDEBAR -->
        <aside class="map-sidebar">
            <!-- Stats -->
            <div class="sidebar-section">
                <div class="sidebar-title">📊 Statistik Titik</div>
                <div class="stat-pills">
                    <div class="stat-pill total">
                        <div class="num" id="s-total">—</div>
                        <div class="lbl">Total PJU</div>
                    </div>
                    <div class="stat-pill normal">
                        <div class="num" id="s-normal">—</div>
                        <div class="lbl">Normal</div>
                    </div>
                    <div class="stat-pill mati">
                        <div class="num" id="s-mati">—</div>
                        <div class="lbl">Mati</div>
                    </div>
                    <div class="stat-pill ver">
                        <div class="num" id="s-ver">—</div>
                        <div class="lbl">Terverifikasi</div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="sidebar-section">
                <div class="sidebar-title">🔧 Filter Tampilan</div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select" id="filter-status" onchange="applyFilters()">
                        <option value="">Semua Status</option>
                        <option value="normal">● Normal</option>
                        <option value="mati">● Mati</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Kategori</label>
                    <select class="filter-select" id="filter-kategori" onchange="applyFilters()">
                        <option value="">Semua Kategori</option>
                        <option value="pju">PJU</option>
                        <option value="rambu">Rambu</option>
                        <option value="rppj">RPPJ</option>
                        <option value="cermin">Cermin</option>
                    </select>
                </div>
            </div>

            <!-- Legend -->
            <div class="sidebar-section">
                <div class="sidebar-title">🗺️ Legenda</div>
                <div class="legend-items">
                    <div class="legend-item">
                        <div class="legend-dot normal"></div> PJU Normal (Menyala)
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot mati"></div> PJU Mati / Rusak (Berkedip)
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot rambu"></div> Rambu Lalu Lintas
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot rppj"></div> RPPJ
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot cermin"></div> Cermin Tikungan
                    </div>
                </div>
            </div>

            <!-- PJU List -->
            <div class="sidebar-section" style="border-bottom:none;">
                <div class="sidebar-title">📋 Daftar Titik (<span id="list-count">0</span>)</div>
            </div>
            <div class="popup-list" id="pju-list"></div>
        </aside>

        <!-- MAP -->
        <div id="map"></div>

        <!-- LOADING -->
        <div class="map-loading" id="loading">
            <div class="loading-spinner"></div>
            <span style="color:var(--text-2);font-size:0.85rem;">Memuat data titik PJU...</span>
        </div>

        <!-- BOTTOM BAR -->
        <div class="bottom-bar">
            <span>📍 Koordinat: <span class="coords-display" id="coords">Arahkan kursor ke peta</span></span>
            <span style="margin-left:auto;">© Dishub Kab. Jepara {{ date('Y') }}</span>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ===== MAP INIT =====
        const map = L.map('map', {
            center: [-6.5886, 110.6679],
            zoom: 14,
            zoomControl: false,
        });

        // Dark matter tile
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
            maxZoom: 20,
        }).addTo(map);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Track mouse coords
        map.on('mousemove', e => {
            document.getElementById('coords').textContent =
                e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6);
        });

        // ===== MARKER CREATION =====
        let allMarkers = [];
        let allData = [];

        function getMarkerColor(point) {
            const colors = { pju: point.status === 'normal' ? '#F59E0B' : '#EF4444', rambu: '#3B82F6', rppj: '#A78BFA', cermin: '#22D3EE' };
            return colors[point.kategori] || '#F59E0B';
        }

        function createMarker(point) {
            const color = getMarkerColor(point);
            const isMati = point.status === 'mati';

            // Custom div icon
            const iconHtml = isMati
                ? `<div style="position:relative;width:20px;height:20px;">
               <div style="position:absolute;inset:0;border-radius:50%;background:${color};box-shadow:0 0 10px ${color};"></div>
               <div class="pulse-ring"></div>
               <div class="pulse-ring"></div>
           </div>`
                : `<div style="width:14px;height:14px;border-radius:50%;background:${color};
                box-shadow:0 0 12px ${color},0 0 24px ${color}55;
                border:2px solid rgba(255,255,255,0.3);">
           </div>`;

            const icon = L.divIcon({
                html: iconHtml,
                className: '',
                iconSize: isMati ? [20, 20] : [14, 14],
                iconAnchor: isMati ? [10, 10] : [7, 7],
            });

            const marker = L.marker([point.lat, point.lng], { icon });
            marker.pointData = point;

            marker.bindPopup(buildPopup(point), { maxWidth: 360 });
            marker.on('click', () => highlightListItem(point.id));

            return marker;
        }

        function buildPopup(p) {
            const statusClass = p.status === 'normal' ? 'normal' : 'mati';
            const statusLabel = p.status === 'normal' ? '● Normal' : '● Mati';
            const mapsUrl = `https://www.google.com/maps?q=${p.lat},${p.lng}`;
            const svUrl = `https://www.google.com/maps/embed/v1/streetview?key=AIzaSyBFw0Qbyf5RoeTq9wJLsKZKXJlPrCRsJ80&location=${p.lat},${p.lng}&heading=0&pitch=0&fov=90`;

            return `
    <div class="popup-header">
        <div class="popup-title">${p.nama}</div>
        <div class="popup-badges">
            <span class="pop-badge ${statusClass}">${statusLabel}</span>
            ${p.is_verified ? '<span class="pop-badge ver">✓ Terverifikasi</span>' : '<span class="pop-badge" style="background:rgba(245,158,11,0.1);color:#F59E0B;border:1px solid rgba(245,158,11,0.2);">⏳ Pending</span>'}
        </div>
    </div>
    <div class="popup-body">
        <div class="popup-row"><span class="key">Kategori</span><span class="val">${p.kategori?.toUpperCase()}</span></div>
        <div class="popup-row"><span class="key">Jenis</span><span class="val">${p.jenis ? p.jenis.charAt(0).toUpperCase() + p.jenis.slice(1) : '—'}</span></div>
        <div class="popup-row"><span class="key">Daya</span><span class="val">${p.daya || '—'}</span></div>
        <div class="popup-row"><span class="key">Letak</span><span class="val">${p.letak ? p.letak.charAt(0).toUpperCase() + p.letak.slice(1) + ' Jalan' : '—'}</span></div>
        <div class="popup-row"><span class="key">Type</span><span class="val">${p.type || '—'}</span></div>
        <div class="popup-row"><span class="key">Koordinat</span><span class="val" style="font-family:monospace;font-size:0.75rem;">${p.lat.toFixed(6)}, ${p.lng.toFixed(6)}</span></div>
    </div>
    <div class="street-view-wrap">
        <div class="sv-label">📷 Street View</div>
        <iframe src="${svUrl}" allowfullscreen loading="lazy"></iframe>
    </div>
    <div class="popup-actions">
        <a href="${mapsUrl}" target="_blank" class="pop-btn maps">🧭 Navigasi Google Maps</a>
        <button class="pop-btn share" onclick="navigator.clipboard.writeText('${p.lat},${p.lng}').then(()=>alert('Koordinat disalin!'))">📋 Salin</button>
    </div>`;
        }

        function buildListItem(p) {
            const color = getMarkerColor(p);
            return `
    <div class="popup-item" id="list-${p.id}" onclick="flyToPoint(${p.id})">
        <div class="pi-name">
            <span class="pi-status" style="background:${color};${p.status === 'mati' ? 'animation:blink 1s infinite;' : ''}"></span>
            ${p.nama}
        </div>
        <div class="pi-meta">${p.kategori?.toUpperCase()} • ${p.jenis} • ${p.daya || '—'}</div>
    </div>`;
        }

        function highlightListItem(id) {
            document.querySelectorAll('.popup-item').forEach(el => el.style.borderColor = 'var(--border)');
            const el = document.getElementById('list-' + id);
            if (el) { el.style.borderColor = 'var(--accent)'; el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
        }

        function flyToPoint(id) {
            const mData = allMarkers.find(m => m.pointData.id === id);
            if (!mData) return;
            map.flyTo([mData.pointData.lat, mData.pointData.lng], 17, { duration: 1.5 });
            setTimeout(() => mData.openPopup(), 1600);
            highlightListItem(id);
        }

        // Layer group
        const markerGroup = L.layerGroup().addTo(map);

        function applyFilters() {
            const status = document.getElementById('filter-status').value;
            const kategori = document.getElementById('filter-kategori').value;

            markerGroup.clearLayers();
            document.getElementById('pju-list').innerHTML = '';

            let filtered = allData.filter(p => {
                if (status && p.status !== status) return false;
                if (kategori && p.kategori !== kategori) return false;
                return true;
            });

            filtered.forEach(p => {
                const mk = allMarkers.find(m => m.pointData.id === p.id);
                if (mk) markerGroup.addLayer(mk);
                document.getElementById('pju-list').insertAdjacentHTML('beforeend', buildListItem(p));
            });

            document.getElementById('list-count').textContent = filtered.length;
            updateStats(filtered);
        }

        function updateStats(data) {
            document.getElementById('s-total').textContent = data.length;
            document.getElementById('s-normal').textContent = data.filter(d => d.status === 'normal').length;
            document.getElementById('s-mati').textContent = data.filter(d => d.status === 'mati').length;
            document.getElementById('s-ver').textContent = data.filter(d => d.is_verified).length;
        }

        // ===== FETCH DATA =====
        fetch('/api/markers')
            .then(r => r.json())
            .then(data => {
                allData = data;
                allMarkers = data.map(p => createMarker(p));

                applyFilters();

                // Update time
                document.getElementById('update-time').textContent =
                    '🟢 Data live • ' + new Date().toLocaleTimeString('id-ID');

                document.getElementById('loading').style.display = 'none';

                // Fit bounds
                if (allMarkers.length > 0) {
                    const bounds = L.latLngBounds(allMarkers.map(m => [m.pointData.lat, m.pointData.lng]));
                    map.fitBounds(bounds, { padding: [60, 60] });
                }
            })
            .catch(err => {
                document.getElementById('loading').innerHTML = '<div style="color:#EF4444;text-align:center;"><div style="font-size:2rem;margin-bottom:0.5rem;">⚠️</div><div>Gagal memuat data</div></div>';
                console.error(err);
            });
    </script>
</body>

</html>