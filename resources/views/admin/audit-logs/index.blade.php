@extends('layouts.admin')

@section('title', 'Audit Trail & Realtime Activity Logs — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Security & Compliance</span>
            <span>/</span>
            <span>Audit Trail</span>
        </div>
        <h1 class="page-title">Audit Trail & Realtime Activity Logs</h1>
        <p class="page-subtitle">Pelacakan realtime aktivitas seluruh pengguna (Admin, Customer, Affiliator), mutasi data, dan keamanan.</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate"></i> Refresh
        </button>
        <label class="flex items-center gap-2 text-xs font-semibold" style="cursor: pointer; background: var(--card); border: 1px solid var(--border); padding: 7px 14px; border-radius: var(--radius-sm); color: var(--text-2);">
            <input type="checkbox" id="auto-refresh-toggle" onchange="toggleAutoRefresh(this)" style="cursor: pointer;">
            <span><i class="fa-solid fa-bolt" style="color: var(--primary);"></i> Live Auto-Refresh (15s)</span>
        </label>
    </div>
</div>

{{-- KPI Stats Grid --}}
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color1: var(--primary); --kpi-color2: var(--accent);">
        <div class="kpi-header">
            <span class="kpi-label">Total Aktivitas</span>
            <div class="kpi-icon" style="background: var(--primary-soft); color: var(--primary);">
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>
        <div class="kpi-value">{{ number_format($stats['total'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label"><strong style="color: var(--text);">{{ number_format($stats['today'] ?? 0) }}</strong> terekam hari ini</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--purple); --kpi-color2: var(--primary);">
        <div class="kpi-header">
            <span class="kpi-label">Guard Admin</span>
            <div class="kpi-icon" style="background: var(--purple-soft); color: var(--purple);">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--purple);">{{ number_format($stats['admin_actions'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Operasi & Manajemen Admin</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--accent); --kpi-color2: var(--success);">
        <div class="kpi-header">
            <span class="kpi-label">Guard Customer</span>
            <div class="kpi-icon" style="background: var(--accent-soft); color: var(--accent);">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--accent);">{{ number_format($stats['customer_actions'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Checkout, Bukti Bayar & Tiket</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--danger); --kpi-color2: var(--warning);">
        <div class="kpi-header">
            <span class="kpi-label">High / Critical Risk</span>
            <div class="kpi-icon" style="background: var(--danger-soft); color: var(--danger);">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--danger);">{{ number_format($stats['high_risk'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label text-danger">Penghapusan & Keamanan</span>
        </div>
    </div>
</div>

{{-- Filters Card --}}
<div class="card mb-4">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex items-center justify-between" style="flex-wrap: wrap; gap: 12px;">
            <div class="flex items-center gap-3" style="flex-wrap: wrap; flex: 1;">
                <div style="min-width: 240px; flex: 1;">
                    <input type="text" name="search" class="form-input" placeholder="🔍 Cari aksi, IP, model entitas..." value="{{ request('search') }}">
                </div>

                <div style="min-width: 170px;">
                    <select name="user_type" class="form-select">
                        <option value="all">Semua Guard / User</option>
                        <option value="admin" {{ request('user_type') === 'admin' ? 'selected' : '' }}>🛡️ Guard Admin</option>
                        <option value="customer" {{ request('user_type') === 'customer' ? 'selected' : '' }}>👤 Guard Customer</option>
                        <option value="affiliator" {{ request('user_type') === 'affiliator' ? 'selected' : '' }}>💼 Guard Affiliator</option>
                        <option value="system" {{ request('user_type') === 'system' ? 'selected' : '' }}>⚙️ System / Guest</option>
                    </select>
                </div>

                <div style="min-width: 170px;">
                    <select name="risk_level" class="form-select">
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
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
                @if(request()->hasAny(['search', 'user_type', 'risk_level', 'date']))
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-ghost btn-sm">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Audit Logs Table --}}
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 180px;">Waktu & Tanggal</th>
                        <th style="width: 240px;">Pengguna & Guard</th>
                        <th>Aktivitas / Event</th>
                        <th>Entitas Model</th>
                        <th style="width: 150px;">IP Address</th>
                        <th style="width: 120px;">Level Risiko</th>
                        <th style="width: 70px; text-align: center;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                        <tr>
                            <td>
                                <div class="font-mono text-xs font-bold" style="color: var(--text);">{{ $log->created_at->format('H:i:s') }}</div>
                                <div class="text-xs text-muted">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-faint" style="font-size: 10px; margin-top: 2px;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @if($log->user_type === 'admin')
                                        <span class="badge badge-purple" style="font-size: 10px;"><i class="fa-solid fa-user-shield"></i> Admin</span>
                                    @elseif($log->user_type === 'customer')
                                        <span class="badge badge-primary" style="font-size: 10px;"><i class="fa-solid fa-user"></i> Customer</span>
                                    @elseif($log->user_type === 'affiliator')
                                        <span class="badge badge-warning" style="font-size: 10px;"><i class="fa-solid fa-handshake"></i> Affiliator</span>
                                    @else
                                        <span class="badge badge-muted" style="font-size: 10px;"><i class="fa-solid fa-server"></i> System</span>
                                    @endif
                                </div>
                                <div class="font-bold text-sm mt-1" style="color: var(--text);">
                                    {{ $log->user->name ?? ($log->user_type ? ucfirst($log->user_type) . ' #' . substr($log->user_id ?? '', 0, 8) : 'System / Guest') }}
                                </div>
                                @if($log->user?->email)
                                    <div class="text-xs text-muted font-mono" style="font-size: 11px;">{{ $log->user->email }}</div>
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
                                    <span class="text-xs text-faint">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-xs font-mono font-semibold" style="color: var(--text);">
                                    <code>{{ $log->ip_address ?? '127.0.0.1' }}</code>
                                </div>
                                @if($log->user_agent)
                                    <div class="text-xs text-faint truncate" style="max-width: 140px; font-size: 10px;" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 20) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($log->risk_level === 'critical')
                                    <span class="badge badge-danger" style="font-weight: 800;"><i class="fa-solid fa-skull"></i> CRITICAL</span>
                                @elseif($log->risk_level === 'high')
                                    <span class="badge badge-warning" style="font-weight: 800;"><i class="fa-solid fa-triangle-exclamation"></i> HIGH</span>
                                @elseif($log->risk_level === 'medium')
                                    <span class="badge badge-purple"><i class="fa-solid fa-shield"></i> MEDIUM</span>
                                @else
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> LOW</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="btn btn-outline btn-sm" title="Lihat Rincian Log" style="padding: 5px 8px;">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 60px 20px;">
                                <i class="fa-solid fa-shield-halved" style="font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.4; color: var(--primary);"></i>
                                <div class="font-bold text-base" style="color: var(--text);">Tidak Ada Aktivitas Ditemukan</div>
                                <div class="text-xs text-muted mt-1">Belum ada catatan aktivitas audit untuk kriteria filter yang dipilih.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($auditLogs->hasPages())
        <div class="card-footer flex justify-between items-center" style="padding: 16px 24px;">
            <div class="text-xs text-muted">
                Menampilkan <strong>{{ $auditLogs->firstItem() }}</strong> - <strong>{{ $auditLogs->lastItem() }}</strong> dari <strong>{{ $auditLogs->total() }}</strong> total log
            </div>
            <div>
                {{ $auditLogs->links() }}
            </div>
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
