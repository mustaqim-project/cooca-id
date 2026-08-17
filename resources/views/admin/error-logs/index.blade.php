@extends('layouts.admin')

@section('title', 'Application Error & Exception Logs — COOCA.ID Admin')

@section('content')
<div class="page-header flex justify-between items-center" style="flex-wrap: wrap; gap: 16px;">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Security & Audit</span>
            <span>/</span>
            <span>Error Logs</span>
        </div>
        <h1 class="page-title">Application Exception & Error Logs</h1>
        <p class="page-subtitle">Monitoring realtime runtime error, unhandled exceptions, and system failure stack traces.</p>
    </div>
    <div class="page-actions flex items-center gap-2" style="flex-wrap: wrap;">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate"></i> Refresh
        </button>

        <a href="{{ route('admin.error-logs.download', ['file' => $selectedFile]) }}" class="btn btn-outline btn-sm" title="Download Raw Log File">
            <i class="fa-solid fa-download"></i> Unduh Log ({{ $fileSize }} KB)
        </a>

        <form action="{{ route('admin.error-logs.clear', ['file' => $selectedFile]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan log file [{{ $selectedFile }}]?');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline btn-sm text-danger" style="border-color: rgba(239,68,68,0.4);">
                <i class="fa-solid fa-trash-can"></i> Bersihkan Log
            </button>
        </form>

        <label class="flex items-center gap-2 text-xs font-semibold" style="cursor: pointer; background: var(--card); border: 1px solid var(--border); padding: 6px 12px; border-radius: var(--radius);">
            <input type="checkbox" id="err-auto-refresh-toggle" onchange="toggleErrorAutoRefresh(this)">
            <span><i class="fa-solid fa-bolt" style="color: var(--primary);"></i> Live Auto-Refresh (10s)</span>
        </label>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

{{-- Stats Summary --}}
<div class="grid-4 mb-4">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(99, 102, 241, 0.12); color: var(--primary);">
            <i class="fa-solid fa-file-lines"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">Total Log di File</div>
            <div class="stat-value">{{ number_format($stats['total'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">File: <code>{{ $selectedFile }}</code></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.12); color: var(--danger);">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">Errors & Critical</div>
            <div class="stat-value text-danger">{{ number_format($stats['errors'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">Exception & Runtime Failures</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">Warnings</div>
            <div class="stat-value" style="color: #f59e0b;">{{ number_format($stats['warnings'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">Peringatan Sistem</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">Info & Debug</div>
            <div class="stat-value" style="color: #3b82f6;">{{ number_format($stats['info'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">Informasi Operasional</div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="card mb-4">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('admin.error-logs.index') }}" class="flex items-center justify-between" style="flex-wrap: wrap; gap: 12px;">
            <div class="flex items-center gap-3" style="flex-wrap: wrap; flex: 1;">
                <div style="min-width: 220px; flex: 1;">
                    <input type="text" name="search" class="form-input" placeholder="Cari pesan exception, class, atau stack trace..." value="{{ request('search') }}">
                </div>

                <div style="min-width: 200px;">
                    <select name="file" class="form-input" onchange="this.form.submit()">
                        @foreach($files as $f)
                            <option value="{{ $f }}" {{ $selectedFile === $f ? 'selected' : '' }}>
                                📁 {{ $f }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="min-width: 150px;">
                    <select name="level" class="form-input" onchange="this.form.submit()">
                        <option value="all">Semua Level Log</option>
                        <option value="error" {{ request('level') === 'error' ? 'selected' : '' }}>🔴 ERROR</option>
                        <option value="critical" {{ request('level') === 'critical' ? 'selected' : '' }}>🚨 CRITICAL</option>
                        <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>🟡 WARNING</option>
                        <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>🔵 INFO</option>
                        <option value="debug" {{ request('level') === 'debug' ? 'selected' : '' }}>🟣 DEBUG</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'level']))
                    <a href="{{ route('admin.error-logs.index', ['file' => $selectedFile]) }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Log Entries List --}}
<div style="display: flex; flex-direction: column; gap: 12px;">
    @forelse($logs as $index => $log)
        @php
            $isErr = in_array($log['level'], ['ERROR', 'CRITICAL', 'EMERGENCY', 'ALERT']);
            $isWarn = $log['level'] === 'WARNING';
            $borderColor = $isErr ? 'rgba(239, 68, 68, 0.4)' : ($isWarn ? 'rgba(245, 158, 11, 0.4)' : 'var(--border)');
        @endphp
        <div class="card" style="border: 1px solid {{ $borderColor }}; box-shadow: none;">
            <div class="card-body" style="padding: 16px;">
                <div class="flex items-center justify-between mb-2" style="flex-wrap: wrap; gap: 8px;">
                    <div class="flex items-center gap-2">
                        @if($isErr)
                            <span class="badge badge-danger" style="font-weight: 700; font-size: 11px;">
                                <i class="fa-solid fa-circle-xmark"></i> {{ $log['level'] }}
                            </span>
                        @elseif($isWarn)
                            <span class="badge badge-warning" style="font-weight: 700; font-size: 11px;">
                                <i class="fa-solid fa-triangle-exclamation"></i> {{ $log['level'] }}
                            </span>
                        @else
                            <span class="badge badge-primary" style="font-size: 11px;">
                                <i class="fa-solid fa-circle-info"></i> {{ $log['level'] }}
                            </span>
                        @endif

                        <span class="badge badge-muted" style="font-size: 10px; font-family: monospace;">
                            {{ $log['env'] }}
                        </span>

                        <span class="font-mono text-xs text-muted">
                            <i class="fa-regular fa-clock" style="margin-right: 2px;"></i> {{ $log['timestamp'] }}
                        </span>
                    </div>

                    @if(!empty($log['stack']))
                        <button type="button" class="btn btn-ghost btn-xs" onclick="toggleTrace('trace-{{ $index }}', this)">
                            <i class="fa-solid fa-code"></i> <span>Lihat Stack Trace</span>
                        </button>
                    @endif
                </div>

                <div class="font-mono text-sm font-semibold" style="color: var(--text); word-break: break-word; line-height: 1.5;">
                    {{ $log['message'] }}
                </div>

                @if(!empty($log['stack']))
                    <div id="trace-{{ $index }}" style="display: none; margin-top: 12px; position: relative;">
                        <div class="flex items-center justify-between text-xs text-muted mb-1 font-mono">
                            <span>Stack Trace Diagnostic:</span>
                            <button type="button" class="btn btn-ghost btn-xs" onclick="copyTrace('trace-content-{{ $index }}')">
                                <i class="fa-solid fa-copy"></i> Salin Trace
                            </button>
                        </div>
                        <pre id="trace-content-{{ $index }}" style="font-family: monospace; font-size: 11px; background: var(--bg); padding: 14px; border-radius: var(--radius); border: 1px solid var(--border); overflow: auto; max-height: 380px; margin: 0; color: var(--text); line-height: 1.6; white-space: pre-wrap; word-break: break-all;">{{ $log['stack'] }}</pre>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-muted" style="padding: 50px 20px;">
                <i class="fa-solid fa-circle-check text-success" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                <div class="font-bold text-base" style="color: var(--text);">Semua Berjalan Normal</div>
                <div class="text-xs text-muted mt-1">Tidak ada catatan exception / log error pada file <code>{{ $selectedFile }}</code>.</div>
            </div>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
let errRefreshTimer = null;

function toggleTrace(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;

    if (el.style.display === 'none' || el.style.display === '') {
        el.style.display = 'block';
        btn.querySelector('span').innerText = 'Sembunyikan Trace';
    } else {
        el.style.display = 'none';
        btn.querySelector('span').innerText = 'Lihat Stack Trace';
    }
}

function copyTrace(id) {
    const el = document.getElementById(id);
    if (!el) return;
    navigator.clipboard.writeText(el.innerText).then(() => {
        alert('Stack trace berhasil disalin ke clipboard!');
    });
}

function toggleErrorAutoRefresh(checkbox) {
    if (checkbox.checked) {
        localStorage.setItem('error_log_auto_refresh', '1');
        errRefreshTimer = setInterval(function() {
            window.location.reload();
        }, 10000);
    } else {
        localStorage.removeItem('error_log_auto_refresh');
        if (errRefreshTimer) {
            clearInterval(errRefreshTimer);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('err-auto-refresh-toggle');
    if (localStorage.getItem('error_log_auto_refresh') === '1' && toggle) {
        toggle.checked = true;
        errRefreshTimer = setInterval(function() {
            window.location.reload();
        }, 10000);
    }
});
</script>
@endpush
