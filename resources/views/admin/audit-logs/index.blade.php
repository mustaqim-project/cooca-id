@extends('layouts.admin')

@section('title', 'System Audit Logs — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Audit Logs</span>
        </div>
        <h1 class="page-title">Audit Trail & Security Logs</h1>
        <p class="page-subtitle">Detailed record of user actions, database mutations, setting changes, and administrative operations.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User / Admin</th>
                        <th>Event Action</th>
                        <th>Auditable Entity</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs ?? [] as $log)
                        <tr>
                            <td>
                                <div class="font-bold text-sm">{{ $log->user->name ?? 'System' }}</div>
                                <div class="text-xs text-muted">{{ $log->user->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-purple">{{ strtoupper($log->event ?? 'updated') }}</span>
                            </td>
                            <td>
                                <div class="text-sm font-semibold">{{ class_basename($log->auditable_type ?? '') }} #{{ $log->auditable_id ?? 1 }}</div>
                            </td>
                            <td class="text-xs text-muted"><code>{{ $log->ip_address ?? '127.0.0.1' }}</code></td>
                            <td class="text-xs text-muted">{{ optional($log->created_at)->format('d M Y, H:i:s') }}</td>
                            <td>
                                <a href="{{ route('admin.audit-logs.show', $log->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">No audit events recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
