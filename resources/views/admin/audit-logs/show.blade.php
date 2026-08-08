@extends('layouts.admin')

@section('title', 'Audit Log Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.audit-logs.index') }}">Audit Logs</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Audit Log Entry #{{ $auditLog->id ?? 1 }}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="grid-2 mb-4">
            <div>
                <div class="text-xs text-muted uppercase font-bold">Action Event</div>
                <div class="font-bold text-lg text-primary">{{ strtoupper($auditLog->event ?? 'UPDATED') }}</div>
            </div>
            <div>
                <div class="text-xs text-muted uppercase font-bold">IP Address</div>
                <div class="font-semibold text-sm"><code>{{ $auditLog->ip_address ?? '127.0.0.1' }}</code></div>
            </div>
        </div>

        <div class="card-title my-4">Old Values vs New Values</div>
        <div class="grid-2">
            <div>
                <div class="text-xs text-muted font-bold mb-2">Old Values</div>
                <pre class="form-textarea" style="font-family: monospace; font-size: 11px; background: var(--bg-secondary);">{{ json_encode($auditLog->old_values ?? [], JSON_PRETTY_PRINT) }}</pre>
            </div>
            <div>
                <div class="text-xs text-muted font-bold mb-2">New Values</div>
                <pre class="form-textarea" style="font-family: monospace; font-size: 11px; background: var(--bg-secondary);">{{ json_encode($auditLog->new_values ?? [], JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
