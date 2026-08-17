@extends('layouts.admin')

@section('title', 'Audit Log Detail #' . substr($auditLog->id, 0, 8) . ' — COOCA.ID Admin')

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
        <h1 class="page-title">Audit Log Entry #{{ substr($auditLog->id, 0, 13) }}</h1>
        <p class="page-subtitle">Rincian parameter mutasi data, pelaku aktivitas, payload, dan timestamp rekaman audit.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Log
        </a>
    </div>
</div>

<div class="grid-2" style="align-items: start; gap: 24px;">
    {{-- Left Card: Actor & Metadata --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Pelaku & Parameter Event</div>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
            <div class="stats-row">
                <span class="text-sm text-muted">Aksi / Event</span>
                <span class="font-bold text-base" style="color: var(--primary);">{{ $auditLog->action }}</span>
            </div>

            <div class="stats-row">
                <span class="text-sm text-muted">Guard / Tipe Pengguna</span>
                <span>
                    @if($auditLog->user_type === 'admin')
                        <span class="badge badge-purple" style="font-size: 11px;"><i class="fa-solid fa-user-shield"></i> Guard Admin</span>
                    @elseif($auditLog->user_type === 'customer')
                        <span class="badge badge-primary" style="font-size: 11px;"><i class="fa-solid fa-user"></i> Guard Customer</span>
                    @elseif($auditLog->user_type === 'affiliator')
                        <span class="badge badge-warning" style="font-size: 11px;"><i class="fa-solid fa-handshake"></i> Guard Affiliator</span>
                    @else
                        <span class="badge badge-muted" style="font-size: 11px;"><i class="fa-solid fa-server"></i> System / Guest</span>
                    @endif
                </span>
            </div>

            <div class="stats-row">
                <span class="text-sm text-muted">Nama Pengguna</span>
                <span class="font-bold" style="color: var(--text);">{{ $auditLog->user->name ?? 'System / Anonymous' }}</span>
            </div>

            @if($auditLog->user?->email)
                <div class="stats-row">
                    <span class="text-sm text-muted">Email Pengguna</span>
                    <span class="font-mono text-xs" style="color: var(--text);">{{ $auditLog->user->email }}</span>
                </div>
            @endif

            @if($auditLog->user_id)
                <div class="stats-row">
                    <span class="text-sm text-muted">User ID</span>
                    <span class="font-mono text-xs text-muted">{{ $auditLog->user_id }}</span>
                </div>
            @endif

            <div class="divider" style="height: 1px; background: var(--border); margin: 4px 0;"></div>

            <div class="stats-row">
                <span class="text-sm text-muted">Level Risiko</span>
                <span>
                    @if($auditLog->risk_level === 'critical')
                        <span class="badge badge-danger" style="font-weight: 800;"><i class="fa-solid fa-skull"></i> CRITICAL RISK</span>
                    @elseif($auditLog->risk_level === 'high')
                        <span class="badge badge-warning" style="font-weight: 800;"><i class="fa-solid fa-triangle-exclamation"></i> HIGH RISK</span>
                    @elseif($auditLog->risk_level === 'medium')
                        <span class="badge badge-purple"><i class="fa-solid fa-shield"></i> MEDIUM RISK</span>
                    @else
                        <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> LOW RISK</span>
                    @endif
                </span>
            </div>

            <div class="stats-row">
                <span class="text-sm text-muted">Entitas Target</span>
                <span class="font-mono text-xs font-semibold" style="color: var(--primary);">
                    {{ $auditLog->model_type ?? 'None (Route Action)' }}
                    @if($auditLog->model_id)
                        (#{{ substr($auditLog->model_id, 0, 8) }})
                    @endif
                </span>
            </div>

            <div class="stats-row">
                <span class="text-sm text-muted">IP Address</span>
                <span class="font-mono text-xs font-bold"><code>{{ $auditLog->ip_address ?? '127.0.0.1' }}</code></span>
            </div>

            <div class="stats-row">
                <span class="text-sm text-muted">Waktu Pencatatan</span>
                <span class="text-xs font-semibold" style="color: var(--text);">
                    {{ $auditLog->created_at->format('d M Y, H:i:s') }} 
                    <span class="text-muted">({{ $auditLog->created_at->diffForHumans() }})</span>
                </span>
            </div>

            @if($auditLog->user_agent)
                <div style="margin-top: 6px;">
                    <div class="text-xs text-muted font-bold uppercase mb-1" style="letter-spacing: 0.05em;">User Agent:</div>
                    <div class="text-xs font-mono" style="background: var(--bg-secondary); padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); word-break: break-all; color: var(--text-2); line-height: 1.5;">
                        {{ $auditLog->user_agent }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Card: Payload Diff & New Values --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        @if($auditLog->old_values)
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <div class="card-title text-sm"><i class="fa-solid fa-clock-rotate-left"></i> Nilai Sebelumnya (Old Values)</div>
                    <button type="button" class="btn btn-ghost btn-xs" onclick="copyJson('json-old')">
                        <i class="fa-solid fa-copy"></i> Salin
                    </button>
                </div>
                <div class="card-body" style="padding: 12px;">
                    <pre id="json-old" style="font-family: monospace; font-size: 12px; background: var(--bg-secondary); padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); overflow: auto; max-height: 240px; margin: 0; color: var(--text); line-height: 1.5;">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="card-title text-sm"><i class="fa-solid fa-code"></i> Parameter & Payload (New Values)</div>
                @if(!empty($auditLog->new_values))
                    <button type="button" class="btn btn-ghost btn-xs" onclick="copyJson('json-new')">
                        <i class="fa-solid fa-copy"></i> Salin JSON
                    </button>
                @endif
            </div>
            <div class="card-body" style="padding: 12px;">
                @if(!empty($auditLog->new_values))
                    <pre id="json-new" style="font-family: monospace; font-size: 12px; background: var(--bg-secondary); padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); overflow: auto; max-height: 380px; margin: 0; color: var(--text); line-height: 1.5;">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @else
                    <div class="text-xs text-muted" style="padding: 24px; text-align: center;">Tidak ada parameter payload spesifik yang terekam untuk aksi ini.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyJson(elementId) {
    const el = document.getElementById(elementId);
    if (!el) return;
    navigator.clipboard.writeText(el.innerText).then(() => {
        alert('JSON payload berhasil disalin ke clipboard!');
    });
}
</script>
@endpush
