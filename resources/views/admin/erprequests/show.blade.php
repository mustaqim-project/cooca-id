@extends('layouts.admin')

@section('title', 'Detail Pengajuan ERP #' . substr($request->id, 0, 8) . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.erp-requests.index') }}">Pengajuan ERP</a>
            <span>/</span>
            <span>Detail #{{ substr($request->id, 0, 8) }}</span>
        </div>
        <h1 class="page-title">
            Pengajuan ERP — {{ $request->customer->name ?? 'Customer' }}
        </h1>
        <p class="page-subtitle">Review spesifikasi instansi SaaS, verifikasi domain, dan proses aktivasi lisensi deployment.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.erp-requests.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

<div class="grid-2" style="align-items: start; gap: 24px;">
    {{-- Left Main Detail --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        {{-- Card: Customer & Product Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informasi Instansi & Pemohon</div>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="grid-2" style="gap: 16px;">
                    <div>
                        <div class="text-xs text-muted font-bold uppercase" style="letter-spacing: 0.05em;">Nama Customer</div>
                        <div class="font-bold text-sm mt-1" style="color: var(--text);">{{ $request->customer->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase" style="letter-spacing: 0.05em;">Email Customer</div>
                        <div class="font-mono text-sm mt-1" style="color: var(--text);">{{ $request->customer->email ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase" style="letter-spacing: 0.05em;">Produk ERP</div>
                        <div class="font-bold text-sm mt-1" style="color: var(--primary);">{{ $request->product->name ?? 'Paket ERP SaaS' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase" style="letter-spacing: 0.05em;">Nomor WhatsApp</div>
                        <div class="font-semibold text-sm mt-1">
                            @if($request->customer->phone ?? false)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $request->customer->phone) }}" target="_blank" style="color: var(--success); text-decoration: none; font-weight: 700;">
                                    <i class="fa-brands fa-whatsapp"></i> +{{ $request->customer->phone }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase" style="letter-spacing: 0.05em;">Subdomain Yang Diminta</div>
                        <div class="mt-1">
                            @if($request->requested_subdomain)
                                <code style="background: var(--primary-soft); color: var(--primary); padding: 4px 8px; border-radius: var(--radius-sm); font-weight: 700; font-size: 12px;">
                                    https://{{ $request->requested_subdomain }}.cooca.id
                                </code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase" style="letter-spacing: 0.05em;">Custom Domain</div>
                        <div class="mt-1">
                            @if($request->requested_domain)
                                <code style="background: var(--warning-soft); color: var(--warning); padding: 4px 8px; border-radius: var(--radius-sm); font-weight: 700; font-size: 12px;">
                                    https://{{ $request->requested_domain }}
                                </code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($request->notes)
                    <div style="margin-top: 10px; padding: 14px; background: var(--bg-secondary); border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        <div class="text-xs text-muted font-bold uppercase mb-1" style="letter-spacing: 0.05em;">Catatan Pemohon:</div>
                        <div class="text-sm" style="color: var(--text); line-height: 1.5;">{{ $request->notes }}</div>
                    </div>
                @endif

                @if($request->admin_notes)
                    <div style="margin-top: 6px; padding: 14px; background: var(--warning-soft); border-radius: var(--radius-sm); border: 1px solid var(--warning);">
                        <div class="text-xs font-bold uppercase mb-1" style="color: var(--warning); letter-spacing: 0.05em;">Catatan Admin:</div>
                        <div class="text-sm font-semibold" style="color: var(--text); line-height: 1.5;">{{ $request->admin_notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card: License Info (if active) --}}
        @if($request->license)
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <div class="card-title">
                        <i class="fa-solid fa-key" style="color: var(--primary); margin-right: 6px;"></i> Informasi Lisensi ERP Terbit
                    </div>
                    <span class="badge badge-success" style="font-weight: 700;">AKTIF</span>
                </div>
                <div class="card-body" style="display: flex; flex-direction: column; gap: 14px;">
                    <div class="stats-row">
                        <span class="text-sm text-muted">License Code</span>
                        <code class="font-bold text-xs" style="background: var(--bg-secondary); color: var(--text); padding: 4px 8px; border-radius: var(--radius-sm);">
                            {{ $request->license->license_code }}
                        </code>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Token Code</span>
                        <code class="font-bold text-xs" style="background: var(--bg-secondary); color: var(--text); padding: 4px 8px; border-radius: var(--radius-sm);">
                            {{ $request->license->token_code }}
                        </code>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Domain Terdaftar</span>
                        <div>
                            <a href="https://{{ $request->license->domain }}" target="_blank" class="font-bold text-sm" style="color: var(--primary); text-decoration: none;">
                                {{ $request->license->domain }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                    <div class="stats-row">
                        <span class="text-sm text-muted">Masa Berlaku Lisensi</span>
                        <span class="font-semibold text-sm" style="color: var(--text);">
                            {{ $request->license->starts_at?->format('d M Y') ?? '—' }} s/d {{ $request->license->expires_at?->format('d M Y') ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Right Actions & Status Panel --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Status & Tindakan Manajemen</div>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
                @php
                    $labels = \App\Models\ErpRequest::getStatusLabels();
                    $status = $request->status;
                @endphp

                <div>
                    <div class="text-xs text-muted font-bold uppercase mb-1" style="letter-spacing: 0.05em;">Status Saat Ini:</div>
                    <div>
                        @if(in_array($status, ['active_trial', 'waiting_setup']))
                            <span class="badge badge-success" style="font-weight: 800; font-size: 13px; padding: 6px 12px;">
                                <i class="fa-solid fa-circle-check"></i> {{ $labels[$status] ?? strtoupper($status) }}
                            </span>
                        @elseif(in_array($status, ['submitted', 'waiting_approval']))
                            <span class="badge badge-warning" style="font-weight: 800; font-size: 13px; padding: 6px 12px;">
                                <i class="fa-solid fa-clock"></i> {{ $labels[$status] ?? strtoupper($status) }}
                            </span>
                        @elseif(in_array($status, ['in_setup', 'domain_setup', 'testing']))
                            <span class="badge badge-primary" style="font-weight: 800; font-size: 13px; padding: 6px 12px;">
                                <i class="fa-solid fa-gears"></i> {{ $labels[$status] ?? strtoupper($status) }}
                            </span>
                        @elseif(in_array($status, ['rejected', 'trial_expired']))
                            <span class="badge badge-danger" style="font-weight: 800; font-size: 13px; padding: 6px 12px;">
                                <i class="fa-solid fa-circle-xmark"></i> {{ $labels[$status] ?? strtoupper($status) }}
                            </span>
                        @else
                            <span class="badge badge-muted" style="font-size: 13px; padding: 6px 12px;">{{ $labels[$status] ?? strtoupper($status) }}</span>
                        @endif
                    </div>
                </div>

                @if($request->approvedBy)
                    <div class="stats-row" style="font-size: 12px;">
                        <span class="text-muted">Disetujui Oleh:</span>
                        <span class="font-bold" style="color: var(--text);">{{ $request->approvedBy->name ?? 'Admin' }}</span>
                    </div>
                    <div class="stats-row" style="font-size: 12px;">
                        <span class="text-muted">Waktu Persetujuan:</span>
                        <span class="font-semibold text-muted">{{ $request->approved_at ? $request->approved_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                @endif

                <div class="divider" style="height: 1px; background: var(--border); margin: 2px 0;"></div>

                {{-- Workflow Action Forms --}}
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @if(in_array($request->status, ['submitted', 'waiting_approval']))
                        <form action="{{ route('admin.erp-requests.approve', $request->id) }}" method="POST" style="background: var(--bg-secondary); padding: 16px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="admin_notes" class="form-label text-xs font-bold uppercase">
                                    Catatan Persetujuan (Wajib)
                                </label>
                                <textarea name="admin_notes" id="admin_notes" rows="3" required class="form-textarea" placeholder="Tulis instruksi atau catatan persetujuan untuk customer..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-full" style="justify-content: center; font-size: 14px; padding: 12px;">
                                <i class="fa-solid fa-check"></i> Setujui & Aktifkan ERP
                            </button>
                        </form>

                        <form action="{{ route('admin.erp-requests.reject', $request->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="rejection_reason" value="Tidak memenuhi syarat spesifikasi deployment">
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')" class="btn btn-danger w-full" style="justify-content: center; font-size: 13px; padding: 10px;">
                                <i class="fa-solid fa-xmark"></i> Tolak Pengajuan
                            </button>
                        </form>
                    @endif

                    @if($request->status === 'waiting_setup')
                        <form action="{{ route('admin.erp-requests.mark-in-setup', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full" style="justify-content: center; font-size: 14px; padding: 12px;">
                                <i class="fa-solid fa-gears"></i> Mulai Proses Setup Instansi
                            </button>
                        </form>
                    @endif

                    @if($request->status === 'in_setup')
                        <form action="{{ route('admin.erp-requests.mark-domain-setup', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full" style="justify-content: center; font-size: 14px; padding: 12px;">
                                <i class="fa-solid fa-globe"></i> Lanjut ke Konfigurasi Domain
                            </button>
                        </form>
                    @endif

                    @if($request->status === 'domain_setup')
                        <form action="{{ route('admin.erp-requests.mark-testing', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full" style="justify-content: center; font-size: 14px; padding: 12px;">
                                <i class="fa-solid fa-vial"></i> Lanjut ke Tahap Pengujian (Testing)
                            </button>
                        </form>
                    @endif

                    @if($request->status === 'testing')
                        <form action="{{ route('admin.erp-requests.confirm-ready', $request->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="trial_days" value="14">
                            <button type="submit" class="btn btn-success w-full" style="justify-content: center; font-size: 14px; padding: 12px;">
                                <i class="fa-solid fa-rocket"></i> Aktivasi Trial & Terbitkan Lisensi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
