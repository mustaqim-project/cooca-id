@extends('layouts.admin')

@section('title', 'Audit Logs & User Activity — COOCA.ID Admin')

@section('content')
<div class="page-header flex justify-between items-center" style="flex-wrap: wrap; gap: 16px;">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Security & Audit</span>
            <span>/</span>
            <span>Audit Logs</span>
        </div>
        <h1 class="page-title">Audit Trail & Realtime Activity Logs</h1>
        <p class="page-subtitle">Pelacakan realtime aktivitas seluruh pengguna (Admin, Customer, Affiliator), mutasi data, dan autentikasi.</p>
    </div>
    <div class="page-actions flex items-center gap-2">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate"></i> Refresh
        </button>
        <label class="flex items-center gap-2 text-xs font-semibold" style="cursor: pointer; background: var(--card); border: 1px solid var(--border); padding: 6px 12px; border-radius: var(--radius);">
            <input type="checkbox" id="auto-refresh-toggle" onchange="toggleAutoRefresh(this)">
            <span><i class="fa-solid fa-bolt" style="color: var(--primary);"></i> Live Auto-Refresh (15s)</span>
        </label>
    </div>
</div>

{{-- Stats Summary --}}
<div class="grid-4 mb-4">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(99, 102, 241, 0.12); color: var(--primary);">
            <i class="fa-solid fa-list-check"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">Total Log Tercatat</div>
            <div class="stat-value">{{ number_format($stats['total'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">{{ number_format($stats['today'] ?? 0) }} aktivitas hari ini</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(168, 85, 247, 0.12); color: #a855f7;">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">Aktivitas Guard Admin</div>
            <div class="stat-value" style="color: #a855f7;">{{ number_format($stats['admin_actions'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">Operasi & Manajemen Admin</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">Aktivitas Guard Customer</div>
            <div class="stat-value" style="color: #3b82f6;">{{ number_format($stats['customer_actions'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">Checkout, Bukti Bayar & Tiket</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.12); color: var(--danger);">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="stat-details">
            <div class="stat-label">High / Critical Risk</div>
            <div class="stat-value text-danger">{{ number_format($stats['high_risk'] ?? 0) }}</div>
            <div class="stat-sub text-xs text-muted">Penghapusan & Keamanan</div>
        </div>
    </div>
</div>

{{-- Filters Card --}}
<div class="card mb-4">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex items-center justify-between" style="flex-wrap: wrap; gap: 12px;">
            <div class="flex items-center gap-3" style="flex-wrap: wrap; flex: 1;">
                <div style="min-width: 220px; flex: 1;">
                    <input type="text" name="search" class="form-input" placeholder="Cari aksi, IP, entitas..." value="{{ request('search') }}">
                </div>

                <div style="min-width: 160px;">
                    <select name="user_type" class="form-input">
                        <option value="all">Semua Guard / User</option>
                        <option value="admin" {{ request('user_type') === 'admin' ? 'selected' : '' }}>🛡️ Guard Admin</option>
                        <option value="customer" {{ request('user_type') === 'customer' ? 'selected' : '' }}>👤 Guard Customer</option>
                        <option value="affiliator" {{ request('user_type') === 'affiliator' ? 'selected' : '' }}>💼 Guard Affiliator</option>
                        <option value="system" {{ request('user_type') === 'system' ? 'selected' : '' }}>⚙️ System / Guest</option>
                    </select>
                </div>

                <div style="min-width: 160px;">
                    <select name="risk_level" class="form-input">
                        <option value="all">Semua Level Risiko</option>
                        <option value="low" {{ request('risk_level') === 'low' ? 'selected' : '' }}>🟢 Low Risk</option>
                        <option value="medium" {{ request('risk_level') === 'medium' ? 'selected' : '' }}>🟡 Medium Risk</option>
                        <option value="high" {{ request('risk_level') === 'high' ? 'selected' : '' }}>🟠 High Risk</option>
                        <option value="critical" {{ request('risk_level') === 'critical' ? 'selected' : '' }}>🔴 Critical Risk</option>
                    </select>
                </div>

                <div style="min-width: 150px;">
                    <input type="date" name="date" class="form-input" value="{{ request('date') }}">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'user_type', 'risk_level', 'date']))
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Audit Logs Table --}}
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 170px;">Waktu & Tanggal</th>
                        <th style="width: 220px;">Pengguna & Guard</th>
                        <th>Aktivitas / Event</th>
                        <th>Entitas Model</th>
                        <th style="width: 140px;">IP & Jaringan</th>
                        <th style="width: 100px;">Risiko</th>
                        <th style="width: 80px; text-align: center;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                        <tr>
                            <td>
                                <div class="font-mono text-xs font-bold">{{ $log->created_at->format('H:i:s') }}</div>
                                <div class="text-xs text-muted">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-muted" style="font-size: 10px;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @if($log->user_type === 'admin')
                                        <span class="badge badge-purple" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-user-shield"></i> Admin</span>
                                    @elseif($log->user_type === 'customer')
                                        <span class="badge badge-primary" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-user"></i> Customer</span>
                                    @elseif($log->user_type === 'affiliator')
                                        <span class="badge badge-warning" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-handshake"></i> Affiliator</span>
                                    @else
                                        <span class="badge badge-muted" style="font-size: 10px; padding: 2px 6px;"><i class="fa-solid fa-server"></i> System / Guest</span>
                                    @endif
                                </div>
                                <div class="font-bold text-sm mt-1" style="color: var(--text);">
                                    {{ $log->user->name ?? ($log->user_type ? ucfirst($log->user_type) . ' #' . substr($log->user_id ?? '', 0, 8) : 'System / Guest') }}
                                </div>
                                @if($log->user?->email)
                                    <div class="text-xs text-muted">{{ $log->user->email }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold text-sm" style="color: var(--text);">{{ $log->action }}</div>
                                @if($log->new_values && is_array($log->new_values))
                                    @php
                                        $previewKeys = array_keys(array_slice($log->new_values, 0, 3));
                                    @endphp
                                    <div class="text-xs text-muted mt-1 font-mono" style="font-size: 11px;">
                                        Payload: {{ implode(', ', $previewKeys) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($log->model_type)
                                    <div class="text-xs font-bold" style="color: var(--primary);">
                                        {{ class_basename($log->model_type) }}
                                    </div>
                                    <div class="font-mono text-xs text-muted">
                                        #{{ substr((string) ($log->model_id ?? ''), 0, 13) }}
                                    </div>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-xs font-mono font-semibold"><code>{{ $log->ip_address ?? '127.0.0.1' }}</code></div>
                                @if($log->user_agent)
                                    <div class="text-xs text-muted truncate" style="max-width: 140px; font-size: 10px;" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 20) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($log->risk_level === 'critical')
                                    <span class="badge badge-danger" style="font-weight: 700;">CRITICAL</span>
                                @elseif($log->risk_level === 'high')
                                    <span class="badge badge-warning" style="font-weight: 700;">HIGH</span>
                                @elseif($log->risk_level === 'medium')
                                    <span class="badge badge-purple">MEDIUM</span>
                                @else
                                    <span class="badge badge-success">LOW</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="btn btn-outline btn-xs" title="Lihat Detail Log">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">
                                <i class="fa-solid fa-shield-halved" style="font-size: 32px; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                                Belum ada aktivitas yang tercatat untuk kriteria filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($auditLogs->hasPages())
        <div class="card-footer" style="padding: 16px; border-top: 1px solid var(--border);">
            {{ $auditLogs->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
let refreshTimer = null;

function toggleAutoRefresh(checkbox) {
    if (checkbox.checked) {
        localStorage.setItem('audit_log_auto_refresh', '1');
        refreshTimer = setInterval(function() {
            window.location.reload();
        }, 15000);
    } else {
        localStorage.removeItem('audit_log_auto_refresh');
        if (refreshTimer) {
            clearInterval(refreshTimer);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('auto-refresh-toggle');
    if (localStorage.getItem('audit_log_auto_refresh') === '1' && toggle) {
        toggle.checked = true;
        refreshTimer = setInterval(function() {
            window.location.reload();
        }, 15000);
    }
});
</script>
@endpush
