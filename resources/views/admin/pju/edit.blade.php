@extends('layouts.admin')

@section('title', 'Edit PJU')
@section('page-title', '✏️ Edit Data PJU')

@section('content')
    <div style="max-width:900px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">📋 Edit: {{ $pju->nama }}</span>
                <a href="{{ route('admin.pju.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pju.update', $pju) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama / Kode PJU *</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $pju->nama) }}"
                                required>
                            @error('nama')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori *</label>
                            <select name="kategori" class="form-select" required>
                                @foreach(['pju' => 'PJU', 'rambu' => 'Rambu', 'rppj' => 'RPPJ', 'cermin' => 'Cermin Tikungan'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('kategori', $pju->kategori) == $val ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis *</label>
                            <select name="jenis" class="form-select" required>
                                @foreach(['sonte' => 'Sonte', 'led' => 'LED', 'kalipucang' => 'Kalipucang'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('jenis', $pju->jenis) == $val ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Daya</label>
                            <input type="text" name="daya" class="form-control" value="{{ old('daya', $pju->daya) }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Letak *</label>
                            <select name="letak" class="form-select" required>
                                <option value="kiri" {{ old('letak', $pju->letak) == 'kiri' ? 'selected' : '' }}>Kiri</option>
                                <option value="kanan" {{ old('letak', $pju->letak) == 'kanan' ? 'selected' : '' }}>Kanan
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Type / Tiang</label>
                            <input type="text" name="type" class="form-control" value="{{ old('type', $pju->type) }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Latitude *</label>
                            <input type="text" name="lat" id="lat-input" class="form-control"
                                value="{{ old('lat', $pju->lat) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude *</label>
                            <input type="text" name="long" id="long-input" class="form-control"
                                value="{{ old('long', $pju->long) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">📍 Posisi di Peta</label>
                        <div id="map-picker"
                            style="height:280px;border-radius:12px;overflow:hidden;border:1px solid var(--border);"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="normal" {{ old('status', $pju->status) == 'normal' ? 'selected' : '' }}>Normal
                                </option>
                                <option value="mati" {{ old('status', $pju->status) == 'mati' ? 'selected' : '' }}>Mati /
                                    Rusak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Foto Kondisi (opsional)</label>
                            @if($pju->foto)
                                <div style="margin-bottom:0.5rem;">
                                    <img src="{{ asset('storage/' . $pju->foto) }}" style="height:60px;border-radius:8px;">
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan / Catatan</label>
                        <textarea name="keterangan" class="form-control"
                            rows="3">{{ old('keterangan', $pju->keterangan) }}</textarea>
                    </div>

                    <div
                        style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--border);">
                        <a href="{{ route('admin.pju.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-accent">💾 Simpan Perubahan</button>
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
        const lat = {{ $pju->lat }};
        const lng = {{ $pju->long }};
        const map = L.map('map-picker').setView([lat, lng], 16);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(map);
        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        function updateInputs(la, ln) {
            document.getElementById('lat-input').value = la.toFixed(8);
            document.getElementById('long-input').value = ln.toFixed(8);
        }
        map.on('click', e => { marker.setLatLng(e.latlng); updateInputs(e.latlng.lat, e.latlng.lng); });
        marker.on('dragend', e => { const p = e.target.getLatLng(); updateInputs(p.lat, p.lng); });
    </script>
@endpush