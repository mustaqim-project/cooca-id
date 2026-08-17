@extends('layouts.admin')

@section('title', 'Audit Log Detail #' . substr($auditLog->id, 0, 8) . ' — COOCA.ID Admin')

@section('content')
<div class="page-header flex justify-between items-center" style="flex-wrap: wrap; gap: 16px;">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.audit-logs.index') }}">Audit Logs</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Audit Log Entry #{{ substr($auditLog->id, 0, 13) }}</h1>
        <p class="page-subtitle">Rincian parameter mutasi, pelaku aktivitas, dan timestamp rekaman audit.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Log
        </a>
    </div>
</div>

<div class="grid-2" style="align-items: start; gap: 20px;">
    {{-- Left Card: Metadata & Actor Details --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Pelaku & Peristiwa</div>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 14px;">
            <div class="stats-row">
                <span class="text-sm text-muted">Aksi / Event</span>
                <span class="font-bold text-base" style="color: var(--primary);">{{ $auditLog->action }}</span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Guard / Tipe Pengguna</span>
                <span>
                    @if($auditLog->user_type === 'admin')
                        <span class="badge badge-purple"><i class="fa-solid fa-user-shield"></i> Admin Guard</span>
                    @elseif($auditLog->user_type === 'customer')
                        <span class="badge badge-primary"><i class="fa-solid fa-user"></i> Customer Guard</span>
                    @elseif($auditLog->user_type === 'affiliator')
                        <span class="badge badge-warning"><i class="fa-solid fa-handshake"></i> Affiliator Guard</span>
                    @else
                        <span class="badge badge-muted"><i class="fa-solid fa-server"></i> System / Guest</span>
                    @endif
                </span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Nama Pengguna</span>
                <span class="font-semibold">{{ $auditLog->user->name ?? 'System / Anonymous' }}</span>
            </div>
            @if($auditLog->user?->email)
                <div class="stats-row">
                    <span class="text-sm text-muted">Email Pengguna</span>
                    <span class="font-mono text-sm">{{ $auditLog->user->email }}</span>
                </div>
            @endif
            @if($auditLog->user_id)
                <div class="stats-row">
                    <span class="text-sm text-muted">User ID</span>
                    <span class="font-mono text-xs">{{ $auditLog->user_id }}</span>
                </div>
            @endif
            <div class="divider"></div>
            <div class="stats-row">
                <span class="text-sm text-muted">Level Risiko</span>
                <span>
                    @if($auditLog->risk_level === 'critical')
                        <span class="badge badge-danger" style="font-weight: 700;">CRITICAL RISK</span>
                    @elseif($auditLog->risk_level === 'high')
                        <span class="badge badge-warning" style="font-weight: 700;">HIGH RISK</span>
                    @elseif($auditLog->risk_level === 'medium')
                        <span class="badge badge-purple">MEDIUM RISK</span>
                    @else
                        <span class="badge badge-success">LOW RISK</span>
                    @endif
                </span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Entitas Target</span>
                <span class="font-mono text-sm">
                    {{ $auditLog->model_type ?? 'None (Route Action)' }}
                    @if($auditLog->model_id)
                        (#{{ substr($auditLog->model_id, 0, 8) }})
                    @endif
                </span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">IP Address</span>
                <span class="font-mono text-sm"><code>{{ $auditLog->ip_address ?? '127.0.0.1' }}</code></span>
            </div>
            <div class="stats-row">
                <span class="text-sm text-muted">Waktu Pencatatan</span>
                <span class="text-sm font-semibold">{{ $auditLog->created_at->format('d M Y, H:i:s') }} ({{ $auditLog->created_at->diffForHumans() }})</span>
            </div>
            @if($auditLog->user_agent)
                <div style="margin-top: 6px;">
                    <div class="text-xs text-muted mb-1">User Agent:</div>
                    <div class="text-xs font-mono" style="background: var(--bg); padding: 8px 10px; border-radius: var(--radius); border: 1px solid var(--border); word-break: break-all;">
                        {{ $auditLog->user_agent }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Card: Payload & Values Diff --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        @if($auditLog->old_values)
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-sm"><i class="fa-solid fa-clock-rotate-left"></i> Nilai Sebelumnya (Old Values)</div>
                </div>
                <div class="card-body" style="padding: 12px;">
                    <pre style="font-family: monospace; font-size: 12px; background: var(--bg); padding: 12px; border-radius: var(--radius); border: 1px solid var(--border); overflow: auto; max-height: 240px; margin: 0; color: var(--text);">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="card-title text-sm"><i class="fa-solid fa-code"></i> Parameter & Payload (New Values)</div>
            </div>
            <div class="card-body" style="padding: 12px;">
                @if(!empty($auditLog->new_values))
                    <pre style="font-family: monospace; font-size: 12px; background: var(--bg); padding: 12px; border-radius: var(--radius); border: 1px solid var(--border); overflow: auto; max-height: 320px; margin: 0; color: var(--text);">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @else
                    <div class="text-xs text-muted" style="padding: 16px; text-align: center;">Tidak ada parameter payload spesifik yang terekam untuk aksi ini.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
