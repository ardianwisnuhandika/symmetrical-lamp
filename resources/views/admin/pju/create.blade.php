@extends('layouts.admin')

@section('title', 'Tambah PJU')
@section('page-title', '➕ Tambah Data PJU Baru')

@section('content')
    <div style="max-width:900px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">📋 Form Input Data PJU</span>
                <a href="{{ route('admin.pju.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pju.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama / Kode PJU *</label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: PJU-001 - Jl. Pemuda"
                                value="{{ old('nama') }}" required>
                            @error('nama')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori *</label>
                            <select name="kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(['pju' => 'PJU', 'rambu' => 'Rambu', 'rppj' => 'RPPJ', 'cermin' => 'Cermin Tikungan'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('kategori') == $val ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis *</label>
                            <select name="jenis" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach(['sonte' => 'Sonte', 'led' => 'LED', 'kalipucang' => 'Kalipucang'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('jenis') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('jenis')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Daya</label>
                            <input type="text" name="daya" class="form-control" placeholder="Contoh: 150w, 250w"
                                value="{{ old('daya') }}">
                            @error('daya')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Letak *</label>
                            <select name="letak" class="form-select" required>
                                <option value="kiri" {{ old('letak') == 'kiri' ? 'selected' : '' }}>Kiri Jalan</option>
                                <option value="kanan" {{ old('letak') == 'kanan' ? 'selected' : '' }}>Kanan Jalan</option>
                            </select>
                            @error('letak')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Type / Tiang</label>
                            <input type="text" name="type" class="form-control" placeholder="Contoh: Stang 4m, Tiang 6m"
                                value="{{ old('type') }}">
                            @error('type')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Latitude *</label>
                            <input type="text" name="lat" id="lat-input" class="form-control" placeholder="-6.587920"
                                value="{{ old('lat') }}" required>
                            @error('lat')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude *</label>
                            <input type="text" name="long" id="long-input" class="form-control" placeholder="110.668410"
                                value="{{ old('long') }}" required>
                            @error('long')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- MAP PICKER -->
                    <div class="form-group">
                        <label class="form-label">📍 Pilih Lokasi di Peta <small
                                style="color:var(--text-3);font-weight:400;">(klik untuk menentukan titik)</small></label>
                        <div id="map-picker"
                            style="height:280px;border-radius:12px;overflow:hidden;border:1px solid var(--border);"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="normal" {{ old('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="mati" {{ old('status') == 'mati' ? 'selected' : '' }}>Mati / Rusak</option>
                            </select>
                            @error('status')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Foto Kondisi</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            @error('foto')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan / Catatan</label>
                        <textarea name="keterangan" class="form-control" rows="3"
                            placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div
                        style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--border);">
                        <a href="{{ route('admin.pju.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-accent">💾 Simpan Data PJU</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const defaultLat = {{ old('lat', -6.5886) }};
        const defaultLng = {{ old('long', 110.6679) }};
        const map = L.map('map-picker').setView([defaultLat, defaultLng], 14);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('lat-input').value = lat.toFixed(8);
            document.getElementById('long-input').value = lng.toFixed(8);
        }

        map.on('click', e => {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });
        marker.on('dragend', e => {
            const pos = e.target.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });
    </script>
@endpush