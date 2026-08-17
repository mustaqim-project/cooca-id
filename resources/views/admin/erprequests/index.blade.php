@extends('layouts.admin')

@section('title', 'Pengajuan Instansi & Lisensi ERP — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>SaaS Operations</span>
            <span>/</span>
            <span>Pengajuan ERP</span>
        </div>
        <h1 class="page-title">Pengajuan Instansi & Lisensi ERP</h1>
        <p class="page-subtitle">Kelola permohonan deploy instansi ERP, persetujuan domain/subdomain, dan aktivasi lisensi SaaS customer.</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate"></i> Refresh
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

{{-- KPI Stats Grid --}}
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color1: var(--primary); --kpi-color2: var(--accent);">
        <div class="kpi-header">
            <span class="kpi-label">Total Permohonan</span>
            <div class="kpi-icon" style="background: var(--primary-soft); color: var(--primary);">
                <i class="fa-solid fa-server"></i>
            </div>
        </div>
        <div class="kpi-value">{{ number_format($stats['total'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Seluruh pengajuan instansi ERP</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--warning); --kpi-color2: var(--orange);">
        <div class="kpi-header">
            <span class="kpi-label">Menunggu Persetujuan</span>
            <div class="kpi-icon" style="background: var(--warning-soft); color: var(--warning);">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--warning);">{{ number_format($stats['waiting_approval'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label text-warning">Perlu tindakan review admin</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--accent); --kpi-color2: var(--primary);">
        <div class="kpi-header">
            <span class="kpi-label">Dalam Setup & Testing</span>
            <div class="kpi-icon" style="background: var(--accent-soft); color: var(--accent);">
                <i class="fa-solid fa-gears"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--accent);">{{ number_format($stats['in_progress'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Tahap setup database & domain</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--success); --kpi-color2: #059669;">
        <div class="kpi-header">
            <span class="kpi-label">Trial Aktif (Live)</span>
            <div class="kpi-icon" style="background: var(--success-soft); color: var(--success);">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--success);">{{ number_format($stats['active_trial'] ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label text-success">Instansi berjalan & berlisensi</span>
        </div>
    </div>
</div>

{{-- Filters Card --}}
<div class="card mb-4">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('admin.erp-requests.index') }}" class="flex items-center justify-between" style="flex-wrap: wrap; gap: 12px;">
            <div class="flex items-center gap-3" style="flex-wrap: wrap; flex: 1;">
                <div style="min-width: 260px; flex: 1;">
                    <input type="text" name="search" class="form-input" placeholder="🔍 Cari customer, email, nomor WA, domain..." value="{{ request('search') }}">
                </div>

                <div style="min-width: 200px;">
                    <select name="status" class="form-select">
                        <option value="all">Semua Status Permohonan</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>🟡 Baru Diajukan (Submitted)</option>
                        <option value="waiting_approval" {{ request('status') === 'waiting_approval' ? 'selected' : '' }}>⏳ Menunggu Approval</option>
                        <option value="waiting_setup" {{ request('status') === 'waiting_setup' ? 'selected' : '' }}>⚙️ Menunggu Setup</option>
                        <option value="in_setup" {{ request('status') === 'in_setup' ? 'selected' : '' }}>🔧 Dalam Proses Setup</option>
                        <option value="domain_setup" {{ request('status') === 'domain_setup' ? 'selected' : '' }}>🌐 Konfigurasi Domain</option>
                        <option value="testing" {{ request('status') === 'testing' ? 'selected' : '' }}>🧪 Tahap Pengujian</option>
                        <option value="active_trial" {{ request('status') === 'active_trial' ? 'selected' : '' }}>🟢 Trial Aktif</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>🔴 Ditolak</option>
                        <option value="trial_expired" {{ request('status') === 'trial_expired' ? 'selected' : '' }}>⚪ Trial Berakhir</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.erp-requests.index') }}" class="btn btn-ghost btn-sm">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 240px;">Pemohon (Customer)</th>
                        <th>Produk ERP</th>
                        <th>Domain / Subdomain Instansi</th>
                        <th style="width: 170px;">Status</th>
                        <th style="width: 160px;">Waktu Pengajuan</th>
                        <th style="width: 80px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">
                                    {{ $req->customer->name ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-muted">{{ $req->customer->email ?? '' }}</div>
                                @if($req->customer->phone ?? false)
                                    <div class="text-xs mt-1" style="color: var(--success); font-weight: 600;">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $req->customer->phone) }}" target="_blank" style="color: var(--success); text-decoration: none;">
                                            <i class="fa-brands fa-whatsapp"></i> +{{ $req->customer->phone }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--primary);">
                                    {{ $req->product->name ?? 'Paket ERP SaaS' }}
                                </div>
                                @if($req->affiliator)
                                    <div class="text-xs text-muted mt-1">
                                        Ref: <span class="font-semibold">{{ $req->affiliator->name }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($req->requested_subdomain)
                                    <div class="flex items-center gap-2">
                                        <code style="background: var(--primary-soft); color: var(--primary); padding: 4px 8px; border-radius: var(--radius-sm); font-weight: 700; font-size: 12px;">
                                            https://{{ $req->requested_subdomain }}.cooca.id
                                        </code>
                                        <a href="https://{{ $req->requested_subdomain }}.cooca.id" target="_blank" class="text-xs text-muted" title="Buka URL Instansi">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                @elseif($req->requested_domain)
                                    <div class="flex items-center gap-2">
                                        <code style="background: var(--warning-soft); color: var(--warning); padding: 4px 8px; border-radius: var(--radius-sm); font-weight: 700; font-size: 12px;">
                                            https://{{ $req->requested_domain }}
                                        </code>
                                        <a href="https://{{ $req->requested_domain }}" target="_blank" class="text-xs text-muted" title="Buka Domain">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $labels = \App\Models\ErpRequest::getStatusLabels();
                                    $status = $req->status;
                                @endphp
                                @if(in_array($status, ['active_trial', 'waiting_setup']))
                                    <span class="badge badge-success" style="font-weight: 700;">
                                        <i class="fa-solid fa-circle-check"></i> {{ $labels[$status] ?? strtoupper($status) }}
                                    </span>
                                @elseif(in_array($status, ['submitted', 'waiting_approval']))
                                    <span class="badge badge-warning" style="font-weight: 700;">
                                        <i class="fa-solid fa-clock"></i> {{ $labels[$status] ?? strtoupper($status) }}
                                    </span>
                                @elseif(in_array($status, ['in_setup', 'domain_setup', 'testing']))
                                    <span class="badge badge-primary" style="font-weight: 700;">
                                        <i class="fa-solid fa-gears"></i> {{ $labels[$status] ?? strtoupper($status) }}
                                    </span>
                                @elseif(in_array($status, ['rejected', 'trial_expired']))
                                    <span class="badge badge-danger" style="font-weight: 700;">
                                        <i class="fa-solid fa-circle-xmark"></i> {{ $labels[$status] ?? strtoupper($status) }}
                                    </span>
                                @else
                                    <span class="badge badge-muted">{{ $labels[$status] ?? strtoupper($status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-mono text-xs font-bold" style="color: var(--text);">
                                    {{ $req->created_at ? $req->created_at->format('H:i:s') : '-' }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ $req->created_at ? $req->created_at->format('d M Y') : '-' }}
                                </div>
                                <div class="text-xs text-faint" style="font-size: 10px;">
                                    {{ $req->created_at ? $req->created_at->diffForHumans() : '' }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.erp-requests.show', $req->id) }}" class="btn btn-outline btn-sm" title="Kelola Pengajuan" style="padding: 6px 10px;">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 60px 20px;">
                                <i class="fa-solid fa-server" style="font-size: 44px; margin-bottom: 12px; display: block; opacity: 0.4; color: var(--primary);"></i>
                                <div class="font-bold text-base" style="color: var(--text);">Tidak Ada Permohonan ERP Ditemukan</div>
                                <div class="text-xs text-muted mt-1">Belum ada pengajuan instansi ERP untuk kriteria pencarian atau status ini.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($requests->hasPages())
        <div class="card-footer flex justify-between items-center" style="padding: 16px 24px;">
            <div class="text-xs text-muted">
                Menampilkan <strong>{{ $requests->firstItem() }}</strong> - <strong>{{ $requests->lastItem() }}</strong> dari <strong>{{ $requests->total() }}</strong> total pengajuan
            </div>
            <div>
                {{ $requests->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
