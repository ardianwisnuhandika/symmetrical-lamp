@extends('layouts.admin')

@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('content')
    <div class="card" style="margin-bottom:1rem;">
        <div class="card-body" style="padding:0.9rem 1rem;">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="filter-bar">
                <select name="level" class="form-select">
                    <option value="" {{ $level === '' ? 'selected' : '' }}>Semua Level</option>
                    <option value="ERROR" {{ $level === 'ERROR' ? 'selected' : '' }}>ERROR</option>
                    <option value="WARNING" {{ $level === 'WARNING' ? 'selected' : '' }}>WARNING</option>
                    <option value="INFO" {{ $level === 'INFO' ? 'selected' : '' }}>INFO</option>
                    <option value="DEBUG" {{ $level === 'DEBUG' ? 'selected' : '' }}>DEBUG</option>
                </select>
                <select name="lines" class="form-select">
                    <option value="100" {{ $lines === 100 ? 'selected' : '' }}>100 baris</option>
                    <option value="300" {{ $lines === 300 ? 'selected' : '' }}>300 baris</option>
                    <option value="500" {{ $lines === 500 ? 'selected' : '' }}>500 baris</option>
                    <option value="1000" {{ $lines === 1000 ? 'selected' : '' }}>1000 baris</option>
                </select>
                <button type="submit" class="btn btn-accent">Terapkan</button>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-ghost">Reset</a>
                <span style="margin-left:auto;color:var(--text-3);font-size:0.78rem;">{{ $logPath }}</span>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Log Viewer</span>
            <span style="font-size:0.78rem;color:var(--text-3);">{{ count($entries) }} baris</span>
        </div>
        <div class="card-body" style="padding:0;">
            @if(empty($entries))
                <div style="padding:1rem;color:var(--text-3);">Log tidak ditemukan atau kosong.</div>
            @else
                <pre style="margin:0;padding:1rem;max-height:70vh;overflow:auto;background:#0B1322;color:#DCE7F9;font-size:12px;line-height:1.5;">@foreach($entries as $line){{ $line }}
@endforeach</pre>
            @endif
        </div>
    </div>
@endsection
