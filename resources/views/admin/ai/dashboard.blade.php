@extends('layouts.admin')

@section('title', 'AI Gateway & Model Configuration — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>AI Platform</span>
            <span>/</span>
            <span>Gateway & Models</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-brain text-primary" style="margin-right: 6px;"></i> AI Gateway & Model Configuration
        </h1>
        <p class="page-subtitle">Pusat konfigurasi Base URL, Master API Key, kuota token master (tracking), daftar model AI, dan paket top-up.</p>
    </div>
    <div class="page-actions flex items-center gap-2" style="flex-wrap: wrap;">
        <button type="button" class="btn btn-primary btn-sm" onclick="openGatewayModal()">
            <i class="fa-solid fa-sliders"></i> Konfigurasi AI Gateway
        </button>
        @if($providerConfig->is_active && $hasKey)
            <div class="badge badge-success flex items-center gap-2" style="padding: 6px 12px; font-weight: 700;">
                <span style="width: 8px; height: 8px; background: currentColor; border-radius: 50%; display: inline-block;"></span>
                AI Gateway Active
            </div>
        @else
            <div class="badge badge-warning flex items-center gap-2" style="padding: 6px 12px; font-weight: 700;">
                <span style="width: 8px; height: 8px; background: currentColor; border-radius: 50%; display: inline-block;"></span>
                AI Gateway Inactive / Unset
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- Monthly & Master Token KPI Cards --}}
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color1: var(--primary); --kpi-color2: var(--accent);">
        <div class="kpi-header">
            <span class="kpi-label">Token Terpakai (Bulan Ini)</span>
            <div class="kpi-icon" style="background: var(--primary-soft); color: var(--primary);">
                <i class="fa-solid fa-microchip"></i>
            </div>
        </div>
        <div class="kpi-value">{{ number_format($monthlyUsage->total_tokens ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Akumulasi bulan berjalan</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--accent); --kpi-color2: var(--primary);">
        <div class="kpi-header">
            <span class="kpi-label">Sisa Kuota Master AI</span>
            <div class="kpi-icon" style="background: var(--accent-soft); color: var(--accent);">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
        </div>
        @if($masterQuota > 0)
            <div class="kpi-value" style="color: var(--accent);">{{ number_format($masterRemaining) }}</div>
            <div class="kpi-trend">
                <span class="trend-label">Dari total kuota {{ number_format($masterQuota) }}</span>
            </div>
        @else
            <div class="kpi-value" style="color: var(--accent);">Unlimited</div>
            <div class="kpi-trend">
                <span class="trend-label">Kuota master belum dibatasi</span>
            </div>
        @endif
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--success); --kpi-color2: #059669;">
        <div class="kpi-header">
            <span class="kpi-label">Total Permintaan API</span>
            <div class="kpi-icon" style="background: var(--success-soft); color: var(--success);">
                <i class="fa-solid fa-bolt"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--success);">{{ number_format($monthlyUsage->total_requests ?? 0) }}</div>
        <div class="kpi-trend">
            <span class="trend-label text-success">API Calls ke Gateway</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--warning); --kpi-color2: #d97706;">
        <div class="kpi-header">
            <span class="kpi-label">Rata-rata Latensi</span>
            <div class="kpi-icon" style="background: var(--warning-soft); color: var(--warning);">
                <i class="fa-solid fa-stopwatch"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--warning);">{{ number_format($monthlyUsage->avg_latency_ms ?? 0) }} ms</div>
        <div class="kpi-trend">
            <span class="trend-label">Waktu respon gateway</span>
        </div>
    </div>
</div>

{{-- Section: Master AI Gateway Settings & Tracking --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-server" style="color: var(--primary); margin-right: 6px;"></i> Master AI Gateway & Token Tracking
        </div>
        <span class="text-xs text-muted">OpenAI-Compatible Standard • Terenkripsi AES-256</span>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            {{-- Left: Endpoint & Authentication Info --}}
            <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div style="width: 42px; height: 42px; border-radius: 10px; background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid var(--border);">
                            <i class="fa-solid fa-network-wired"></i>
                        </div>
                        <div>
                            <div class="font-bold text-sm" style="color: var(--text);">AI Gateway Endpoint</div>
                            <div class="text-xs text-muted">Universal Chat Completion Routing</div>
                        </div>
                    </div>

                    @if($providerConfig->is_active && $hasKey)
                        <span class="badge badge-success" style="font-weight: 700;">AKTIF</span>
                    @elseif($providerConfig->is_active)
                        <span class="badge badge-warning" style="font-weight: 700;">KEY BELUM DISET</span>
                    @else
                        <span class="badge badge-muted" style="font-weight: 700;">NONAKTIF</span>
                    @endif
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-muted" style="display: block; margin-bottom: 4px;">Base URL Endpoint:</label>
                    <div style="background: var(--bg); padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); font-family: monospace; font-size: 13px; color: var(--text); word-break: break-all;">
                        {{ $providerConfig->base_url ?: 'Belum dikonfigurasi' }}
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-muted" style="display: block; margin-bottom: 4px;">API Key Status:</label>
                    <div class="flex items-center justify-between" style="background: var(--bg); padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        <span class="font-mono text-xs" style="color: var(--text-2);">
                            @if($hasKey)
                                ●●●●●●●●●●●●●●●● (Tersimpan aman)
                            @else
                                <span class="text-danger font-semibold"><i class="fa-solid fa-circle-exclamation"></i> API Key belum diisi</span>
                            @endif
                        </span>
                        @if($hasKey)
                            <span class="badge badge-success" style="font-size: 10px;"><i class="fa-solid fa-lock"></i> Encrypted</span>
                        @endif
                    </div>
                </div>

                {{-- Master Token Tracking Bar --}}
                <div style="background: var(--bg); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <div class="flex justify-between items-center text-xs font-semibold mb-1">
                        <span style="color: var(--text);">
                            <i class="fa-solid fa-chart-pie text-primary" style="margin-right: 4px;"></i> Tracking Kuota Token Master
                        </span>
                        @if($masterQuota > 0)
                            <span class="font-mono text-muted">{{ number_format($allTimeTokensUsed) }} / {{ number_format($masterQuota) }} ({{ $masterPercentUsed }}%)</span>
                        @else
                            <span class="font-mono text-muted">{{ number_format($allTimeTokensUsed) }} Terpakai (Unlimited)</span>
                        @endif
                    </div>
                    @if($masterQuota > 0)
                        @php
                            $barColor = $masterPercentUsed > 90 ? 'var(--danger)' : ($masterPercentUsed > 75 ? 'var(--warning)' : 'var(--success)');
                        @endphp
                        <div style="height: 8px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden; border: 1px solid var(--border); margin-top: 6px;">
                            <div style="height: 100%; width: {{ $masterPercentUsed }}%; background: {{ $barColor }}; border-radius: 4px; transition: width 0.4s ease;"></div>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-1" style="font-size: 11px;">
                            <span class="text-muted">Sisa: <strong style="color: {{ $barColor }};">{{ number_format($masterRemaining) }} Token</strong></span>
                            <span class="text-muted">Terpakai: {{ $masterPercentUsed }}%</span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 mt-auto" style="padding-top: 8px; border-top: 1px solid var(--border);">
                    <button type="button" class="btn btn-primary btn-xs" onclick="openGatewayModal()">
                        <i class="fa-solid fa-gear"></i> Konfigurasi Gateway & Kuota
                    </button>

                    <form action="{{ route('admin.ai.providers.toggle') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-xs" title="Toggle Status Gateway">
                            <i class="fa-solid {{ $providerConfig->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}" style="font-size: 14px; margin-right: 4px;"></i>
                            {{ $providerConfig->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    <button type="button" class="btn btn-outline btn-xs" onclick="openTestModal()">
                        <i class="fa-solid fa-vial" style="color: var(--accent);"></i> Test Ping
                    </button>
                </div>
            </div>

            {{-- Right: Available Models List --}}
            <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 20px; display: flex; flex-direction: column; gap: 14px;">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold text-sm" style="color: var(--text);">
                            <i class="fa-solid fa-cubes" style="color: var(--primary); margin-right: 4px;"></i> Model yang Disediakan (Endpoint Models)
                        </div>
                        <div class="text-xs text-muted">Model AI aktif yang dapat diakses melalui API Cooca</div>
                    </div>
                    <span class="badge badge-primary font-mono text-xs">{{ count($availableModels) }} Model</span>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 120px; align-content: flex-start; background: var(--bg); padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    @forelse($availableModels as $model)
                        <div style="background: var(--primary-soft); color: var(--primary); border: 1px solid var(--primary); padding: 6px 12px; border-radius: 6px; font-family: monospace; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-robot"></i> {{ $model }}
                        </div>
                    @empty
                        <div class="text-muted text-xs" style="padding: 10px;">
                            Belum ada model yang didaftarkan. Klik tombol "Konfigurasi AI Gateway" untuk mendaftarkan nama model.
                        </div>
                    @endforelse
                </div>

                <div class="text-xs text-muted">
                    <i class="fa-solid fa-circle-info text-primary" style="margin-right: 4px;"></i>
                    Model-model ini diteruskan langsung ke Base URL upstream Anda sesuai input admin.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Subscription Plans AI Quotas --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-layer-group" style="color: var(--primary); margin-right: 6px;"></i> Konfigurasi Kuota AI Per Paket Langganan (SaaS Plans)
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Paket Langganan</th>
                        <th>Kuota Token Bulanan</th>
                        <th>Rate Limit (RPM)</th>
                        <th>Model yang Diizinkan</th>
                        <th>Kebijakan Overage</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        @php
                            $cfg = $plan->aiPlanConfig;
                            $allowed = $cfg ? ($cfg->allowed_models ?? []) : $availableModels;
                            $monthlyQuota = $cfg ? $cfg->monthly_token_quota : 100000;
                            $rpm = $cfg ? $cfg->requests_per_minute : 60;
                            $overage = $cfg ? $cfg->overage_policy : 'hard_stop';
                        @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">{{ $plan->name }}</div>
                                <div class="text-xs text-muted">Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->billing_cycle ?? 'bulan' }}</div>
                            </td>
                            <td>
                                <span class="font-mono font-bold text-sm" style="color: var(--primary);">
                                    {{ number_format($monthlyQuota) }} Token
                                </span>
                                <div class="text-xs text-muted">per siklus billing</div>
                            </td>
                            <td>
                                <span class="font-mono text-xs font-bold">{{ $rpm }} Req/Menit</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1" style="flex-wrap: wrap; max-width: 320px;">
                                    @foreach($allowed as $am)
                                        <span class="badge badge-primary font-mono" style="font-size: 10px;">{{ $am }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                @if($overage === 'hard_stop')
                                    <span class="badge badge-danger" style="font-size: 10px;">HARD STOP (Tolak)</span>
                                @else
                                    <span class="badge badge-warning" style="font-size: 10px;">SOFT STOP (Notifikasi)</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-outline btn-xs" onclick='openPlanModal(@json($plan), @json($cfg), @json($availableModels))'>
                                    <i class="fa-solid fa-sliders"></i> Atur Kuota
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                Belum ada data Subscription Plan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Section: AI Token Top-Up Packages Management --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-cubes-stacked" style="color: var(--primary); margin-right: 6px;"></i> Paket Top-Up Kuota Token AI
        </div>
        <button type="button" class="btn btn-primary btn-xs" onclick="openPackageModal()">
            <i class="fa-solid fa-plus"></i> Tambah Paket
        </button>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Jumlah Kuota Token</th>
                        <th>Harga (IDR)</th>
                        <th>Badge Promo</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokenPackages as $pkg)
                        <tr>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">{{ $pkg->name }}</div>
                                <div class="text-xs text-muted">{{ $pkg->description ?? 'Tidak ada deskripsi' }}</div>
                            </td>
                            <td>
                                <span class="font-mono font-bold text-sm" style="color: var(--primary);">
                                    +{{ number_format($pkg->token_amount) }} Token
                                </span>
                            </td>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">
                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                @if($pkg->badge)
                                    <span class="badge badge-warning" style="font-size: 11px;">{{ $pkg->badge }}</span>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($pkg->is_active)
                                    <span class="badge badge-success" style="font-size: 10px;">AKTIF</span>
                                @else
                                    <span class="badge badge-muted" style="font-size: 10px;">NONAKTIF</span>
                                @endif
                            </td>
                            <td class="font-mono text-xs text-muted">#{{ $pkg->sort_order }}</td>
                            <td style="text-align: right;">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" class="btn btn-ghost btn-xs" onclick='editPackageModal(@json($pkg))' title="Edit Paket">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.ai.packages.toggle', $pkg->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-xs" title="Toggle Aktif/Nonaktif">
                                            <i class="fa-solid {{ $pkg->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.ai.packages.delete', $pkg->id) }}" method="POST" onsubmit="return confirm('Hapus paket token ini?')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs" style="color: var(--danger);" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 40px;">
                                Belum ada paket top-up token AI yang dikonfigurasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Section: Recent Top-Up Purchases by Customers --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-cart-shopping" style="color: var(--primary); margin-right: 6px;"></i> Riwayat Pembelian Top-Up Token Customer
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu Order</th>
                        <th>Invoice / Transaksi</th>
                        <th>Customer</th>
                        <th>Paket / Tambahan Token</th>
                        <th>Harga Dibayar</th>
                        <th>Metode & Bukti</th>
                        <th>Status</th>
                        <th>Waktu Kredit</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPurchases as $pur)
                        <tr>
                            <td class="font-mono text-xs text-muted">
                                {{ $pur->created_at?->format('d M Y, H:i') }}
                            </td>
                            <td>
                                @if($pur->transaction)
                                    <a href="{{ route('admin.transactions.show', $pur->transaction_id) }}" class="font-mono font-bold text-xs text-primary" title="Buka Detail Transaksi">
                                        {{ $pur->transaction->invoice_number ?? ('INV-'.$pur->transaction_id) }}
                                    </a>
                                @else
                                    <span class="font-mono text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-bold text-xs" style="color: var(--text);">{{ $pur->customer->name ?? 'Customer' }}</div>
                                <div class="text-xs text-muted">{{ $pur->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-xs" style="color: var(--text);">{{ $pur->package->name ?? 'Custom AI Top-up' }}</div>
                                <span class="badge badge-primary font-mono text-xs">+{{ number_format($pur->tokens_amount) }} Token</span>
                            </td>
                            <td>
                                <strong style="color: var(--text);">Rp {{ number_format($pur->price_paid, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($pur->transaction?->isManualTransfer())
                                    <span class="badge badge-warning" style="font-size: 10px;">
                                        <i class="fa-solid fa-building-columns"></i> Transfer Manual
                                    </span>
                                    @if($pur->transaction->payment_proof)
                                        <div class="mt-1">
                                            <a href="{{ $pur->transaction->payment_proof_url }}" target="_blank" class="btn btn-ghost btn-xs text-primary" style="padding: 2px 6px; font-size: 11px;">
                                                <i class="fa-solid fa-image"></i> Lihat Bukti
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-xs text-danger mt-1">Belum Upload</div>
                                    @endif
                                @elseif($pur->transaction)
                                    <span class="badge badge-purple" style="font-size: 10px;">
                                        <i class="fa-solid fa-bolt"></i> Midtrans
                                    </span>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($pur->status === 'completed')
                                    <span class="badge badge-success" style="font-size: 10px;"><i class="fa-solid fa-circle-check"></i> LUNAS</span>
                                @elseif($pur->status === 'cancelled')
                                    <span class="badge badge-danger" style="font-size: 10px;">DIBATALKAN</span>
                                @else
                                    @if($pur->transaction?->isManualTransfer() && $pur->transaction?->payment_proof)
                                        <span class="badge badge-warning" style="font-size: 10px; background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa;">
                                            <i class="fa-solid fa-clock"></i> BUTUH VERIFIKASI
                                        </span>
                                    @else
                                        <span class="badge badge-warning" style="font-size: 10px;">PENDING</span>
                                    @endif
                                @endif
                            </td>
                            <td class="font-mono text-xs text-muted">
                                {{ $pur->credited_at ? $pur->credited_at->format('d M Y, H:i') : '—' }}
                            </td>
                            <td style="text-align: right;">
                                <div class="flex items-center justify-end gap-1">
                                    @if($pur->status !== 'completed' && $pur->transaction_id)
                                        <form action="{{ route('admin.transactions.verify', $pur->transaction_id) }}" method="POST" onsubmit="return confirm('Verifikasi pembayaran Top-Up AI sebesar Rp {{ number_format($pur->price_paid, 0, ',', '.') }} dan kreditkan +{{ number_format($pur->tokens_amount) }} token ke akun pelanggan?');" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs" title="Setujui Pembayaran">
                                                <i class="fa-solid fa-check"></i> Approve
                                            </button>
                                        </form>
                                    @endif
                                    @if($pur->transaction_id)
                                        <a href="{{ route('admin.transactions.show', $pur->transaction_id) }}" class="btn btn-ghost btn-xs" title="Lihat Rincian Transaksi">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 40px;">
                                Belum ada riwayat pembelian top-up token AI dari customer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($recentPurchases->hasPages())
        <div class="card-footer" style="padding: 14px 20px;">
            {{ $recentPurchases->links() }}
        </div>
    @endif
</div>

{{-- Section: Active Usage Cycles & Quotas --}}
<div class="card mb-4">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-chart-pie" style="color: var(--primary); margin-right: 6px;"></i> Siklus Penggunaan Token Aktif Customer
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer / Instansi</th>
                        <th>Paket / Lisensi</th>
                        <th>Periode Siklus</th>
                        <th style="min-width: 220px;">Pemakaian Token</th>
                        <th style="text-align: right; width: 140px;">Aksi Bonus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeCycles as $cycle)
                        @php
                            $pct = $cycle->token_quota > 0 ? ($cycle->tokens_used / $cycle->token_quota) * 100 : 0;
                            $barColor = $pct > 90 ? 'var(--danger)' : ($pct > 75 ? 'var(--warning)' : 'var(--success)');
                        @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-sm" style="color: var(--text);">
                                    {{ $cycle->license->customer->name ?? 'Customer' }}
                                </div>
                                <div class="text-xs text-muted">{{ $cycle->license->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                <span class="font-bold text-xs" style="color: var(--primary);">
                                    {{ $cycle->license->product->name ?? 'Paket SaaS' }}
                                </span>
                                <div class="text-xs font-mono text-muted">Lic: {{ substr($cycle->license_id, 0, 8) }}...</div>
                            </td>
                            <td>
                                <div class="text-xs font-semibold" style="color: var(--text);">
                                    {{ $cycle->cycle_start?->format('d M Y') }} — {{ $cycle->cycle_end?->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span style="color: var(--text);">{{ number_format($cycle->tokens_used) }} terpakai</span>
                                    <span class="text-muted">Quota: {{ number_format($cycle->token_quota) }}</span>
                                </div>
                                <div style="height: 6px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden; border: 1px solid var(--border);">
                                    <div style="height: 100%; width: {{ min(100, $pct) }}%; background: {{ $barColor }}; border-radius: 4px; transition: width 0.3s ease;"></div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-outline btn-xs" onclick="openBonusModal('{{ $cycle->id }}', '{{ $cycle->license->customer->name ?? 'Customer' }}')">
                                    <i class="fa-solid fa-gift" style="color: var(--primary);"></i> +Bonus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">
                                Belum ada siklus AI aktif saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($activeCycles->hasPages())
        <div class="card-footer" style="padding: 14px 20px;">
            {{ $activeCycles->links() }}
        </div>
    @endif
</div>

{{-- Section: Recent AI Telemetry Logs --}}
<div class="card">
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <i class="fa-solid fa-list-check" style="color: var(--primary); margin-right: 6px;"></i> Telemetri Permintaan AI Terbaru
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Customer / Key</th>
                        <th>Model AI</th>
                        <th>Prompt Tokens</th>
                        <th>Completion</th>
                        <th>Total Tokens</th>
                        <th>Durasi (ms)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr>
                            <td class="font-mono text-xs text-muted">
                                {{ $log->created_at?->format('H:i:s') }}
                                <div style="font-size: 10px;">{{ $log->created_at?->format('d/m/y') }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-xs" style="color: var(--text);">
                                    {{ $log->apiKey->customer->name ?? 'Customer' }}
                                </div>
                                <div class="font-mono text-xs text-muted">{{ $log->apiKey->name ?? 'API Key' }}</div>
                            </td>
                            <td>
                                <code style="background: var(--primary-soft); color: var(--primary); padding: 3px 6px; border-radius: 4px; font-weight: 700; font-size: 11px;">
                                    {{ $log->model }}
                                </code>
                            </td>
                            <td class="font-mono text-xs">{{ number_format($log->prompt_tokens) }}</td>
                            <td class="font-mono text-xs">{{ number_format($log->completion_tokens) }}</td>
                            <td class="font-mono text-xs font-bold" style="color: var(--text);">{{ number_format($log->total_tokens) }}</td>
                            <td class="font-mono text-xs text-muted">{{ $log->duration_ms }} ms</td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge badge-success" style="font-size: 10px;">200 OK</span>
                                @elseif($log->status === 'quota_exceeded')
                                    <span class="badge badge-warning" style="font-size: 10px;">429 QUOTA</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 10px;">{{ strtoupper($log->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                                Belum ada telemetri log AI yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Unified AI Gateway Configuration & Master Quota --}}
<div id="gateway-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 560px; width: 92%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md); max-height: 90vh; overflow-y: auto;">
        <div class="card-header flex justify-between items-center">
            <div class="card-title">
                <i class="fa-solid fa-sliders text-primary" style="margin-right: 6px;"></i> Konfigurasi Master AI Gateway & Kuota
            </div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeGatewayModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.ai.providers.save') }}" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 16px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Base URL Endpoint *</label>
                    <input type="url" name="base_url" id="modal-gateway-url" required value="{{ $providerConfig->base_url ?? 'https://r4g77gv.abc-tunnel.us/v1' }}" placeholder="https://r4g77gv.abc-tunnel.us/v1" class="form-input font-mono">
                    <small class="text-muted text-xs" style="margin-top: 4px; display: block;">
                        Base URL OpenAI-compatible master AI provider Anda.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Master API Key</label>
                    <div style="position: relative;">
                        <input type="password" name="api_key" id="modal-gateway-key" class="form-input font-mono" placeholder="{{ $hasKey ? '●●●●●●●● (Kosongkan bila tidak ingin mengubah)' : 'sk-...' }}">
                        <button type="button" onclick="togglePasswordVisibility('modal-gateway-key')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted); cursor: pointer;">
                            <i class="fa-solid fa-eye" id="eye-icon-modal-gateway-key"></i>
                        </button>
                    </div>
                    <small class="text-muted text-xs" style="margin-top: 4px; display: block;">
                        Kunci API disimpan dengan enkripsi AES-256 di database.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Total Kuota Token Master (Tracking Limit)</label>
                    <input type="number" name="total_token_quota" id="modal-gateway-quota" step="100000" min="0" value="{{ $providerConfig->total_token_quota ?? 0 }}" placeholder="Contoh: 10000000 (Isi 0 untuk Unlimited)" class="form-input font-mono">
                    <small class="text-muted text-xs" style="margin-top: 4px; display: block;">
                        Total kuota token yang Anda miliki pada akun provider master untuk memantau sisa token (isi 0 jika unlimited).
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Daftar Model yang Disediakan *</label>
                    <textarea name="models" id="modal-gateway-models" rows="5" class="form-textarea font-mono" placeholder="cx/gpt-5.5-xhigh&#10;cx/gpt-5.5&#10;ag/claude-sonnet-4-6&#10;ag/claude-opus-4-6-thinking&#10;ag/gemini-pro-agent">{{ implode("\n", $availableModels) }}</textarea>
                    <small class="text-muted text-xs" style="margin-top: 4px; display: block;">
                        Tulis 1 nama model per baris (atau dipisahkan koma). Model-model ini yang akan tersedia untuk paket dan API request customer.
                    </small>
                </div>

                <div class="form-group flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="modal-gateway-active" value="1" {{ $providerConfig->is_active ? 'checked' : '' }} style="cursor: pointer;">
                    <label for="modal-gateway-active" class="text-sm font-semibold" style="cursor: pointer; color: var(--text);">Aktifkan AI Gateway Service</label>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeGatewayModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Test Ping Connection --}}
<div id="test-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 440px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center">
            <div class="card-title"><i class="fa-solid fa-vial text-accent" style="margin-right: 6px;"></i> Test Ping Koneksi Gateway</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeTestModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.ai.providers.test') }}" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <p class="text-xs text-muted">
                    Sistem akan mengirimkan request ringan (ping) ke Base URL dengan model yang dipilih untuk memvalidasi otentikasi API Key dan respon endpoint.
                </p>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Pilih Model untuk Test</label>
                    <select name="model" class="form-select font-mono text-sm" required>
                        @foreach($availableModels as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeTestModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Kirim Test Ping</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Plan AI Config --}}
<div id="plan-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 500px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md); max-height: 90vh; overflow-y: auto;">
        <div class="card-header flex justify-between items-center">
            <div class="card-title" id="modal-plan-title">Atur Kuota AI Plan</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closePlanModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.ai.plans.save') }}" method="POST">
            @csrf
            <input type="hidden" name="subscription_plan_id" id="modal-plan-id" value="">
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Kuota Token Bulanan *</label>
                    <input type="number" name="monthly_token_quota" id="modal-plan-quota" required step="5000" min="1000" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Rate Limit (Requests per Minute) *</label>
                    <input type="number" name="requests_per_minute" id="modal-plan-rpm" required min="5" max="600" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Kebijakan Jika Kuota Habis (Overage Policy)</label>
                    <select name="overage_policy" id="modal-plan-overage" class="form-select">
                        <option value="hard_stop">Hard Stop (Tolak request dengan status 429)</option>
                        <option value="soft_stop">Soft Stop (Hanya beri notifikasi / toleransi)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Model yang Diizinkan untuk Paket Ini *</label>
                    <div id="plan-models-checkboxes" style="display: flex; flex-direction: column; gap: 6px; background: var(--bg-secondary); padding: 12px; border-radius: var(--radius-sm); max-height: 160px; overflow-y: auto; border: 1px solid var(--border);">
                        <!-- Dynamic checkboxes injected by JS -->
                    </div>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closePlanModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Kuota Plan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Package Config (Create / Edit) --}}
<div id="package-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 520px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center">
            <div class="card-title" id="modal-package-title">Tambah Paket Top-Up Token AI</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closePackageModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.ai.packages.save') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="modal-package-id" value="">
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Nama Paket *</label>
                    <input type="text" name="name" id="modal-package-name" required placeholder="Contoh: Pro AI Booster 500K" class="form-input">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Jumlah Token *</label>
                        <input type="number" name="token_amount" id="modal-package-tokens" required step="10000" min="1000" placeholder="500000" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Harga (IDR) *</label>
                        <input type="number" name="price" id="modal-package-price" required step="5000" min="0" placeholder="100000" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Deskripsi / Peruntukan</label>
                    <textarea name="description" id="modal-package-desc" rows="2" class="form-textarea" placeholder="Ideal untuk traffic bot WhatsApp & integrasi CRM..."></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Badge Promo (Opsional)</label>
                        <input type="text" name="badge" id="modal-package-badge" placeholder="Contoh: Paling Populer / Hemat 25%" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Urutan Tampil</label>
                        <input type="number" name="sort_order" id="modal-package-order" value="1" min="0" class="form-input">
                    </div>
                </div>

                <div class="form-group flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="modal-package-active" value="1" checked style="cursor: pointer;">
                    <label for="modal-package-active" class="text-sm font-semibold" style="cursor: pointer; color: var(--text);">Tampilkan Paket ke Customer</label>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closePackageModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Paket</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Bonus Tokens --}}
<div id="bonus-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 440px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center">
            <div class="card-title">Tambah Bonus Kuota Token</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeBonusModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="bonus-form" action="" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="text-sm" style="color: var(--text);">
                    Penerima: <strong id="bonus-customer-name"></strong>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Jumlah Token Bonus</label>
                    <input type="number" name="bonus_tokens" value="50000" step="5000" min="1000" required class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Alasan Pemberian Bonus</label>
                    <input type="text" name="reason" value="Promosi & Loyalty Reward" required class="form-input">
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px;">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeBonusModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-gift"></i> Berikan Bonus</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openGatewayModal() {
    document.getElementById('gateway-modal').style.display = 'flex';
}

function closeGatewayModal() {
    document.getElementById('gateway-modal').style.display = 'none';
}

function openTestModal() {
    document.getElementById('test-modal').style.display = 'flex';
}

function closeTestModal() {
    document.getElementById('test-modal').style.display = 'none';
}

function togglePasswordVisibility(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('eye-icon-' + id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function openPlanModal(plan, cfg, allModels) {
    document.getElementById('modal-plan-title').innerText = 'Atur Kuota AI: ' + plan.name;
    document.getElementById('modal-plan-id').value = plan.id;
    document.getElementById('modal-plan-quota').value = cfg ? cfg.monthly_token_quota : 100000;
    document.getElementById('modal-plan-rpm').value = cfg ? cfg.requests_per_minute : 60;
    document.getElementById('modal-plan-overage').value = cfg ? cfg.overage_policy : 'hard_stop';

    const currentAllowed = cfg && cfg.allowed_models ? cfg.allowed_models : (allModels || []);
    const container = document.getElementById('plan-models-checkboxes');
    container.innerHTML = '';

    (allModels || []).forEach(m => {
        const isChecked = currentAllowed.includes(m);
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="checkbox" name="allowed_models[]" value="${m}" id="model_chk_${m}" ${isChecked ? 'checked' : ''} style="cursor: pointer;">
            <label for="model_chk_${m}" class="font-mono text-xs" style="cursor: pointer; color: var(--text);">${m}</label>
        `;
        container.appendChild(div);
    });

    document.getElementById('plan-modal').style.display = 'flex';
}

function closePlanModal() {
    document.getElementById('plan-modal').style.display = 'none';
}

function openBonusModal(cycleId, customerName) {
    document.getElementById('bonus-form').action = '/admin/ai/cycles/' + cycleId + '/bonus';
    document.getElementById('bonus-customer-name').innerText = customerName;
    document.getElementById('bonus-modal').style.display = 'flex';
}

function closeBonusModal() {
    document.getElementById('bonus-modal').style.display = 'none';
}

function openPackageModal() {
    document.getElementById('modal-package-title').innerText = 'Tambah Paket Top-Up Token AI';
    document.getElementById('modal-package-id').value = '';
    document.getElementById('modal-package-name').value = '';
    document.getElementById('modal-package-tokens').value = '';
    document.getElementById('modal-package-price').value = '';
    document.getElementById('modal-package-desc').value = '';
    document.getElementById('modal-package-badge').value = '';
    document.getElementById('modal-package-order').value = '1';
    document.getElementById('modal-package-active').checked = true;

    document.getElementById('package-modal').style.display = 'flex';
}

function editPackageModal(pkg) {
    document.getElementById('modal-package-title').innerText = 'Edit Paket: ' + pkg.name;
    document.getElementById('modal-package-id').value = pkg.id;
    document.getElementById('modal-package-name').value = pkg.name;
    document.getElementById('modal-package-tokens').value = pkg.token_amount;
    document.getElementById('modal-package-price').value = Math.round(pkg.price);
    document.getElementById('modal-package-desc').value = pkg.description || '';
    document.getElementById('modal-package-badge').value = pkg.badge || '';
    document.getElementById('modal-package-order').value = pkg.sort_order;
    document.getElementById('modal-package-active').checked = Boolean(pkg.is_active);

    document.getElementById('package-modal').style.display = 'flex';
}

function closePackageModal() {
    document.getElementById('package-modal').style.display = 'none';
}
</script>
@endpush
