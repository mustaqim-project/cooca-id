@extends('layouts.customer')

@section('title', 'AI Token Wallet & Usage Tracking — COOCA.ID')

@push('styles')
<style>
.wallet-hero {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    border-radius: var(--radius-lg);
    padding: 24px 28px;
    color: #ffffff;
    margin-bottom: 24px;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}
.kpi-box {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-md);
    padding: 16px;
    backdrop-filter: blur(8px);
}
.kpi-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    font-weight: 700;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.kpi-value {
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
}
.breakdown-bar {
    height: 10px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    background: #334155;
    margin-top: 14px;
    margin-bottom: 12px;
}
.package-card {
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--card);
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
}
.package-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.package-card.selected {
    border-color: var(--primary);
    background: var(--primary-soft);
}
.tab-nav {
    display: flex;
    gap: 6px;
    border-bottom: 2px solid var(--border);
    margin-bottom: 20px;
    overflow-x: auto;
}
.tab-btn {
    background: none;
    border: none;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all .15s;
}
.tab-btn:hover { color: var(--text); }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-pane { display: none; }
.tab-pane.active { display: block; }
</style>
@endpush

@section('content')
{{-- Header --}}
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text); margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-wallet" style="color: var(--primary);"></i> AI Token Wallet & Analytics
        </h1>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 13px;">
            Kelola saldo token AI, batch/lot pembelian (30 hari masa aktif), mutasi transaksi, dan integrasi multi-model AI.
        </p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <button type="button" class="btn btn-primary" onclick="openTopUpModal()">
            <i class="fa-solid fa-bolt" style="color: #FACC15;"></i> + Top-Up AI Token
        </button>
        <button type="button" class="btn btn-outline" onclick="openNewKeyModal()">
            <i class="fa-solid fa-key"></i> Buat API Key
        </button>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- Expiration Warnings Banner --}}
@if(!empty($walletSummary['warnings']))
    @foreach($walletSummary['warnings'] as $warn)
        <div class="alert alert-{{ $warn['severity'] }} mb-3" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                <div>
                    <strong>Peringatan Kedaluwarsa Token:</strong> {{ $warn['message'] }}
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline" onclick="openTopUpModal()" style="font-size: 11px; padding: 4px 10px;">
                <i class="fa-solid fa-rotate"></i> Top-Up Sekarang
            </button>
        </div>
    @endforeach
@endif

{{-- ══════════════════════════════ HERO WALLET STATS ══════════════════════════════ --}}
<div class="wallet-hero">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.2); color: #60A5FA; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid rgba(96, 165, 250, 0.3);">
                <i class="fa-solid fa-brain"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Saldo AI Token Tersedia (Wallet)</div>
                <div style="font-size: 32px; font-weight: 900; color: #ffffff; line-height: 1.2;">
                    {{ number_format($walletSummary['total_available']) }} <span style="font-size: 16px; font-weight: 600; color: #94a3b8;">Tokens</span>
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34D399; border: 1px solid rgba(52, 211, 153, 0.3); font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 20px;">
                <i class="fa-solid fa-circle-check"></i> FEFO Consumption Active
            </span>
            <div style="font-size: 11px; color: #94a3b8; margin-top: 6px;">
                Top-Up berlaku <strong>30 Hari</strong> per batch
            </div>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-box">
            <div class="kpi-label">
                <span>Terpakai Bulan Ini</span>
                <i class="fa-solid fa-chart-line" style="color: #60A5FA;"></i>
            </div>
            <div class="kpi-value" style="color: #60A5FA;">
                {{ number_format($walletSummary['used_this_month']) }}
            </div>
            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Akumulasi pemakaian AI</div>
        </div>

        <div class="kpi-box">
            <div class="kpi-label">
                <span>Expiring Soon (H-7)</span>
                <i class="fa-solid fa-hourglass-half" style="color: #FBBF24;"></i>
            </div>
            <div class="kpi-value" style="color: {{ $walletSummary['expiring_soon'] > 0 ? '#FBBF24' : '#ffffff' }};">
                {{ number_format($walletSummary['expiring_soon']) }}
            </div>
            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Kedaluwarsa dlm 7 hari</div>
        </div>

        <div class="kpi-box">
            <div class="kpi-label">
                <span>Next Expiration</span>
                <i class="fa-solid fa-calendar-day" style="color: #A78BFA;"></i>
            </div>
            <div class="kpi-value" style="font-size: 18px; margin-top: 4px;">
                {{ $walletSummary['next_expiration_date'] ? $walletSummary['next_expiration_date']->translatedFormat('d M Y') : 'Tidak Ada' }}
            </div>
            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                {{ $walletSummary['next_expiration_date'] ? $walletSummary['next_expiration_date']->diffForHumans() : 'Semua lot aman' }}
            </div>
        </div>

        <div class="kpi-box">
            <div class="kpi-label">
                <span>Total Lot Aktif</span>
                <i class="fa-solid fa-layer-group" style="color: #34D399;"></i>
            </div>
            <div class="kpi-value" style="color: #34D399;">
                {{ $walletSummary['active_lots']->count() }} Lot
            </div>
            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Diproses otomatis FEFO</div>
        </div>
    </div>

    {{-- Breakdown Progress Bar --}}
    @php
        $totalAvail = max(1, $walletSummary['total_available']);
        $subPct = ($walletSummary['breakdown']['subscription'] / $totalAvail) * 100;
        $topupPct = ($walletSummary['breakdown']['topup'] / $totalAvail) * 100;
        $bonusPct = ($walletSummary['breakdown']['bonus'] / $totalAvail) * 100;
    @endphp
    <div class="breakdown-bar">
        @if($walletSummary['breakdown']['subscription'] > 0)
            <div style="width: {{ $subPct }}%; background: #3B82F6;" title="Subscription: {{ number_format($walletSummary['breakdown']['subscription']) }}"></div>
        @endif
        @if($walletSummary['breakdown']['topup'] > 0)
            <div style="width: {{ $topupPct }}%; background: #10B981;" title="Top Up: {{ number_format($walletSummary['breakdown']['topup']) }}"></div>
        @endif
        @if($walletSummary['breakdown']['bonus'] > 0)
            <div style="width: {{ $bonusPct }}%; background: #F59E0B;" title="Bonus/Promo: {{ number_format($walletSummary['breakdown']['bonus']) }}"></div>
        @endif
    </div>

    <div style="display: flex; gap: 16px; flex-wrap: wrap; font-size: 12px;">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="width: 10px; height: 10px; border-radius: 3px; background: #3B82F6; display: inline-block;"></span>
            <span>Subscription: <strong>{{ number_format($walletSummary['breakdown']['subscription']) }}</strong></span>
        </div>
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="width: 10px; height: 10px; border-radius: 3px; background: #10B981; display: inline-block;"></span>
            <span>Top Up: <strong>{{ number_format($walletSummary['breakdown']['topup']) }}</strong></span>
        </div>
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="width: 10px; height: 10px; border-radius: 3px; background: #F59E0B; display: inline-block;"></span>
            <span>Bonus & Promo: <strong>{{ number_format($walletSummary['breakdown']['bonus']) }}</strong></span>
        </div>
    </div>
</div>

{{-- ══════════════════════════════ TABS NAVIGATION ══════════════════════════════ --}}
<div class="tab-nav">
    <button type="button" class="tab-btn active" onclick="switchTab('lots')">
        <i class="fa-solid fa-layer-group"></i> Token Lots / Batch ({{ $tokenLots->total() }})
    </button>
    <button type="button" class="tab-btn" onclick="switchTab('transactions')">
        <i class="fa-solid fa-list-check"></i> Mutasi Transaksi Ledger ({{ $transactions->total() }})
    </button>
    <button type="button" class="tab-btn" onclick="switchTab('keys')">
        <i class="fa-solid fa-key"></i> AI API Keys ({{ $keys->count() }})
    </button>
    <button type="button" class="tab-btn" onclick="switchTab('telemetry')">
        <i class="fa-solid fa-microchip"></i> Riwayat Request AI ({{ $recentLogs->total() }})
    </button>
    <button type="button" class="tab-btn" onclick="switchTab('analytics')">
        <i class="fa-solid fa-chart-pie"></i> Multi-Model Analytics
    </button>
</div>

{{-- ══════════════════════════════ TAB 1: TOKEN LOTS ══════════════════════════════ --}}
<div class="tab-pane active" id="pane-lots">
    <div class="card mb-4">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold text-sm" style="color: var(--text);">
                <i class="fa-solid fa-boxes-stacked" style="color: var(--primary); margin-right: 6px;"></i> Daftar Token Lot / Batch Pembelian
            </div>
            <span class="text-xs text-muted">Setiap Top-Up memiliki masa berlaku 30 hari tersendiri</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="padding: 12px 18px;">Nomor Lot & Nama</th>
                            <th style="padding: 12px 18px;">Jenis Sumber</th>
                            <th style="padding: 12px 18px;">Sisa Token</th>
                            <th style="padding: 12px 18px;">Total Awal</th>
                            <th style="padding: 12px 18px;">Tgl Beli</th>
                            <th style="padding: 12px 18px;">Tgl Kedaluwarsa</th>
                            <th style="padding: 12px 18px; text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tokenLots as $lot)
                            @php
                                $daysLeft = $lot->daysUntilExpiration();
                                $pctLeft = $lot->original_tokens > 0 ? ($lot->remaining_tokens / $lot->original_tokens) * 100 : 0;
                            @endphp
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 14px 18px;">
                                    <strong style="color: var(--text); font-size: 13px;">{{ $lot->name }}</strong>
                                    <div class="font-mono text-xs text-muted">{{ $lot->lot_number }}</div>
                                </td>
                                <td style="padding: 14px 18px;">
                                    @if($lot->source_type === 'subscription')
                                        <span class="badge badge-primary" style="font-size: 10px;">SUBSCRIPTION</span>
                                    @elseif($lot->source_type === 'topup')
                                        <span class="badge badge-success" style="font-size: 10px;">TOP-UP (30 HARI)</span>
                                    @elseif($lot->source_type === 'bonus')
                                        <span class="badge badge-warning" style="font-size: 10px;">BONUS</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 10px;">{{ strtoupper($lot->source_type) }}</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;">
                                    <div class="font-mono font-bold text-sm" style="color: {{ $lot->remaining_tokens > 0 ? 'var(--primary)' : 'var(--text-muted)' }};">
                                        {{ number_format($lot->remaining_tokens) }}
                                    </div>
                                    <div style="font-size: 10px; color: var(--text-muted);">{{ number_format($pctLeft, 1) }}% tersisa</div>
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                    {{ number_format($lot->original_tokens) }}
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                    {{ $lot->purchased_at->translatedFormat('d M Y') }}
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs">
                                    <div style="color: {{ $daysLeft <= 3 && $lot->status === 'active' ? 'var(--danger)' : 'var(--text)' }}; font-weight: 700;">
                                        {{ $lot->expires_at->translatedFormat('d M Y, H:i') }}
                                    </div>
                                    @if($lot->status === 'active')
                                        <div style="font-size: 10px; color: {{ $daysLeft <= 3 ? 'var(--danger)' : 'var(--text-muted)' }};">
                                            {{ $daysLeft > 0 ? "Sisa {$daysLeft} hari" : 'Kedaluwarsa hari ini' }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px; text-align: right;">
                                    @if($lot->status === 'active')
                                        <span class="badge badge-success" style="font-size: 10px;">AKTIF</span>
                                    @elseif($lot->status === 'depleted')
                                        <span class="badge badge-secondary" style="font-size: 10px;">HABIS</span>
                                    @elseif($lot->status === 'expired')
                                        <span class="badge badge-danger" style="font-size: 10px;">EXPIRED</span>
                                    @else
                                        <span class="badge badge-dark" style="font-size: 10px;">{{ strtoupper($lot->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 40px;">
                                    Belum ada riwayat Token Lot. Lakukan Top-Up atau aktifkan paket SaaS untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tokenLots->hasPages())
            <div class="card-footer" style="padding: 14px 20px; border-top: 1px solid var(--border);">
                {{ $tokenLots->appends(['tab' => 'lots'])->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════ TAB 2: TRANSACTIONS LEDGER ══════════════════════════════ --}}
<div class="tab-pane" id="pane-transactions">
    <div class="card mb-4">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold text-sm" style="color: var(--text);">
                <i class="fa-solid fa-receipt" style="color: var(--primary); margin-right: 6px;"></i> Ledger Mutasi Saldo AI Token
            </div>
            <span class="text-xs text-muted">Audit trail perubahan saldo token transparan</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="padding: 12px 18px;">Waktu</th>
                            <th style="padding: 12px 18px;">Jenis Transaksi</th>
                            <th style="padding: 12px 18px;">Mutasi Token</th>
                            <th style="padding: 12px 18px;">Saldo Sebelum</th>
                            <th style="padding: 12px 18px;">Saldo Sesudah</th>
                            <th style="padding: 12px 18px;">Keterangan & Lot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                    {{ $tx->created_at?->translatedFormat('d M Y, H:i:s') }}
                                </td>
                                <td style="padding: 14px 18px;">
                                    @if($tx->type === 'purchase')
                                        <span class="badge badge-success" style="font-size: 10px;">TOP-UP PURCHASE</span>
                                    @elseif($tx->type === 'subscription')
                                        <span class="badge badge-primary" style="font-size: 10px;">SUBSCRIPTION</span>
                                    @elseif($tx->type === 'usage')
                                        <span class="badge badge-info" style="font-size: 10px;">USAGE (AI CHAT)</span>
                                    @elseif($tx->type === 'expiration')
                                        <span class="badge badge-danger" style="font-size: 10px;">EXPIRED (30 DAYS)</span>
                                    @elseif($tx->type === 'bonus')
                                        <span class="badge badge-warning" style="font-size: 10px;">BONUS</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 10px;">{{ strtoupper($tx->type) }}</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;">
                                    <div class="font-mono font-bold text-sm" style="color: {{ $tx->tokens > 0 ? 'var(--success)' : 'var(--danger)' }};">
                                        {{ $tx->tokens > 0 ? '+' . number_format($tx->tokens) : number_format($tx->tokens) }}
                                    </div>
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                    {{ number_format($tx->balance_before) }}
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs font-bold" style="color: var(--text);">
                                    {{ number_format($tx->balance_after) }}
                                </td>
                                <td style="padding: 14px 18px;">
                                    <div class="text-xs" style="color: var(--text);">{{ $tx->description }}</div>
                                    @if($tx->tokenLot)
                                        <div class="font-mono text-xs text-muted">Lot: {{ $tx->tokenLot->lot_number }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                    Belum ada riwayat mutasi transaksi token.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer" style="padding: 14px 20px; border-top: 1px solid var(--border);">
                {{ $transactions->appends(['tab' => 'transactions'])->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════ TAB 3: API KEYS ══════════════════════════════ --}}
<div class="tab-pane" id="pane-keys">
    <div class="card mb-4">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold text-sm" style="color: var(--text);">
                <i class="fa-solid fa-key" style="color: var(--primary); margin-right: 6px;"></i> Kunci API AI (API Keys)
            </div>
            @if($licenses->isNotEmpty())
                <button type="button" class="btn btn-primary btn-xs" onclick="openNewKeyModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Key Baru
                </button>
            @endif
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="padding: 12px 18px;">Nama Kunci</th>
                            <th style="padding: 12px 18px;">API Key Rahasia</th>
                            <th style="padding: 12px 18px;">Lisensi Terkait</th>
                            <th style="padding: 12px 18px;">Status</th>
                            <th style="padding: 12px 18px;">Terakhir Digunakan</th>
                            <th style="padding: 12px 18px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keys as $k)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 14px 18px;">
                                    <strong style="color: var(--text); font-size: 13px;">{{ $k->name }}</strong>
                                    <div class="text-xs text-muted font-mono">{{ $k->id }}</div>
                                </td>
                                <td style="padding: 14px 18px;">
                                    @php
                                        $hasPlain = !empty($k->plain_key);
                                        $masked = $k->key_prefix . '••••••••••••••••••••••••';
                                        $full = $hasPlain ? $k->plain_key : $masked;
                                    @endphp
                                    <div class="flex items-center gap-2" style="max-width: 320px;">
                                        <code id="key-text-{{ $k->id }}" 
                                              data-full="{{ $full }}" 
                                              data-masked="{{ $masked }}" 
                                              data-state="masked"
                                              class="font-mono text-xs font-bold" 
                                              style="background: var(--bg-secondary); color: var(--text); padding: 4px 8px; border-radius: 4px; border: 1px solid var(--border); word-break: break-all; flex: 1;">
                                            {{ $masked }}
                                        </code>
                                        @if($hasPlain)
                                            <button type="button" class="btn btn-ghost btn-xs" onclick="toggleKeyVisibility('{{ $k->id }}')" title="Tampilkan / Sembunyikan Kunci" style="padding: 4px 6px;">
                                                <i id="key-eye-{{ $k->id }}" class="fa-regular fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-ghost btn-xs" onclick="copyKeyText('{{ $k->id }}')" title="Salin API Key" style="padding: 4px 6px;">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 14px 18px;">
                                    <span class="font-bold text-xs" style="color: var(--text);">
                                        {{ $k->license->product->name ?? 'Paket SaaS' }}
                                    </span>
                                    <div class="text-xs text-muted font-mono">
                                        {{ $k->license->domain ?? 'Semua Domain' }}
                                    </div>
                                </td>
                                <td style="padding: 14px 18px;">
                                    @if($k->status === 'active')
                                        <span class="badge badge-success" style="font-size: 10px;">AKTIF</span>
                                    @else
                                        <span class="badge badge-danger" style="font-size: 10px;">REVOKED</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                    {{ $k->last_used_at ? $k->last_used_at->diffForHumans() : 'Belum pernah' }}
                                </td>
                                <td style="padding: 14px 18px; text-align: right;">
                                    @if($k->status === 'active')
                                        <form action="{{ route('customer.ai-usage.keys.revoke', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan API Key ini? Aksi ini tidak dapat dibatalkan.');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs" style="color: var(--danger);" title="Revoke Key">
                                                <i class="fa-solid fa-ban"></i> Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                    Belum ada AI API Key. Buat kunci pertama Anda untuk menghubungkan ERP dengan COOCA AI Gateway.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════ TAB 4: TELEMETRY ══════════════════════════════ --}}
<div class="tab-pane" id="pane-telemetry">
    <div class="card mb-4">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold text-sm" style="color: var(--text);">
                <i class="fa-solid fa-microchip" style="color: var(--primary); margin-right: 6px;"></i> Telemetri & Log Permintaan AI
            </div>
            <span class="text-xs text-muted">Pencatatan real-time per panggilan chat/completions</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="padding: 12px 18px;">Waktu</th>
                            <th style="padding: 12px 18px;">Model & Provider</th>
                            <th style="padding: 12px 18px;">Input Token</th>
                            <th style="padding: 12px 18px;">Output Token</th>
                            <th style="padding: 12px 18px;">Total Token</th>
                            <th style="padding: 12px 18px;">Lot Terpakai</th>
                            <th style="padding: 12px 18px;">Latensi</th>
                            <th style="padding: 12px 18px; text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">
                                    {{ $log->created_at?->translatedFormat('d M Y') }}
                                    <div style="font-size: 11px; color: var(--text);">{{ $log->created_at?->format('H:i:s') }} WIB</div>
                                </td>
                                <td style="padding: 14px 18px;">
                                    <code style="background: var(--primary-soft); color: var(--primary); padding: 3px 6px; border-radius: 4px; font-weight: 700; font-size: 11px;">
                                        {{ $log->model }}
                                    </code>
                                    <div class="text-xs text-muted" style="font-size: 10px; margin-top: 2px;">{{ strtoupper($log->provider) }}</div>
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs">{{ number_format($log->input_tokens ?: $log->prompt_tokens) }}</td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs">{{ number_format($log->output_tokens ?: $log->completion_tokens) }}</td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs font-bold">
                                    <span class="badge badge-primary font-mono text-xs">{{ number_format($log->total_tokens) }}</span>
                                </td>
                                <td style="padding: 14px 18px;">
                                    @if($log->tokenLot)
                                        <span class="badge badge-outline" style="font-size: 10px;" title="{{ $log->tokenLot->name }}">
                                            {{ $log->tokenLot->lot_number }}
                                        </span>
                                    @else
                                        <span class="text-xs text-muted">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-xs text-muted">{{ $log->duration_ms }} ms</td>
                                <td style="padding: 14px 18px; text-align: right;">
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
                                    Belum ada log penggunaan AI yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($recentLogs->hasPages())
            <div class="card-footer" style="padding: 14px 20px; border-top: 1px solid var(--border);">
                {{ $recentLogs->appends(['tab' => 'telemetry'])->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════ TAB 5: ANALYTICS ══════════════════════════════ --}}
<div class="tab-pane" id="pane-analytics">
    <div class="card mb-4">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold text-sm" style="color: var(--text);">
                <i class="fa-solid fa-chart-pie" style="color: var(--primary); margin-right: 6px;"></i> Distribusi Penggunaan Multi-Model AI (Bulan Ini)
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-secondary); border-bottom: 1px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);">
                            <th style="padding: 12px 18px;">Model LLM</th>
                            <th style="padding: 12px 18px;">Provider</th>
                            <th style="padding: 12px 18px;">Jumlah Request</th>
                            <th style="padding: 12px 18px;">Total Token Terpakai</th>
                            <th style="padding: 12px 18px;">Porsi (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grandTotalTokens = $modelBreakdown->sum('total_tokens');
                        @endphp
                        @forelse($modelBreakdown as $mb)
                            @php
                                $portion = $grandTotalTokens > 0 ? ($mb->total_tokens / $grandTotalTokens) * 100 : 0;
                            @endphp
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 14px 18px;">
                                    <code style="background: var(--primary-soft); color: var(--primary); padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 12px;">
                                        {{ $mb->model }}
                                    </code>
                                </td>
                                <td style="padding: 14px 18px;">
                                    <span class="badge badge-outline text-xs font-bold">{{ strtoupper($mb->provider) }}</span>
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-sm font-bold">
                                    {{ number_format($mb->total_requests) }}
                                </td>
                                <td style="padding: 14px 18px;" class="font-mono text-sm font-bold" style="color: var(--primary);">
                                    {{ number_format($mb->total_tokens) }}
                                </td>
                                <td style="padding: 14px 18px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="flex: 1; height: 8px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden;">
                                            <div style="width: {{ min(100, $portion) }}%; height: 100%; background: var(--primary); border-radius: 4px;"></div>
                                        </div>
                                        <span class="font-mono text-xs font-bold text-muted">{{ number_format($portion, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 40px;">
                                    Belum ada riwayat pemakaian model AI pada bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════ TOP-UP MODAL ══════════════════════════════ --}}
<div id="topup-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 680px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md); max-height: 90vh; overflow-y: auto;">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold" style="color: var(--text); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bolt" style="color: #FACC15;"></i> Top-Up Kuota Token AI (30 Hari Masa Aktif)
            </div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeTopUpModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('customer.ai-usage.packages.purchase') }}" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 18px; padding: 20px;">
                @if($licenses->isNotEmpty())
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">1. Hubungkan dengan Lisensi SaaS (Opsional)</label>
                        <select name="license_id" id="topup-license-select" class="form-select" style="width: 100%;">
                            <option value="">Semua Lisensi / Akun Utama Customer</option>
                            @foreach($licenses as $lic)
                                <option value="{{ $lic->id }}">
                                    {{ $lic->product->name ?? 'Paket SaaS' }} ({{ $lic->domain ?? 'Semua Domain' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="form-label text-xs font-bold uppercase mb-2" style="display: block;">Pilih Paket Kuota Token AI</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px;">
                        @forelse($tokenPackages as $idx => $pkg)
                            <label class="package-card {{ $idx === 0 ? 'selected' : '' }}" onclick="selectPackageRadio('pkg-{{ $pkg->id }}', this)">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-sm" style="color: var(--text);">{{ $pkg->name }}</div>
                                    @if($pkg->badge)
                                        <span class="badge badge-warning" style="font-size: 10px; font-weight: 700;">{{ $pkg->badge }}</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <div class="font-mono font-extrabold text-lg" style="color: var(--primary);">
                                        +{{ number_format($pkg->token_amount) }} Token
                                    </div>
                                    <div class="text-xs text-muted mt-1">
                                        <i class="fa-regular fa-clock"></i> Berlaku <strong>30 Hari</strong> sejak tanggal pembelian
                                    </div>
                                </div>
                                <div class="flex justify-between items-center pt-2" style="border-top: 1px solid var(--border); margin-top: auto;">
                                    <span class="font-extrabold text-sm" style="color: var(--text);">
                                        Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                    </span>
                                    <input type="radio" name="package_id" id="pkg-{{ $pkg->id }}" value="{{ $pkg->id }}" {{ $idx === 0 ? 'checked' : '' }} required style="accent-color: var(--primary);">
                                </div>
                            </label>
                        @empty
                            <div class="text-center text-muted" style="padding: 20px; grid-column: 1 / -1;">
                                Belum ada paket top-up yang aktif saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card" style="background: var(--bg-secondary); border: 1px solid var(--border); padding: 14px; border-radius: var(--radius-sm);">
                    <div class="flex items-center gap-2 text-xs font-semibold" style="color: var(--text);">
                        <i class="fa-solid fa-shield-halved" style="color: var(--success);"></i> Pembayaran Otomatis & Token Instan
                    </div>
                    <p class="text-xs text-muted mb-0 mt-1">
                        Token langsung ditambahkan ke <strong>Token Lot Baru</strong> segera setelah pembayaran berhasil (QRIS, VA BCA/Mandiri/BNI/BRI, atau Transfer Manual).
                    </p>
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeTopUpModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-cart-shopping"></i> Lanjut ke Pembayaran &rarr;</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════ MODAL CREATE API KEY ══════════════════════════════ --}}
<div id="new-key-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 460px; width: 90%; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        <div class="card-header flex justify-between items-center" style="padding: 16px 20px;">
            <div class="card-title font-bold" style="color: var(--text);">Buat AI API Key Baru</div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeNewKeyModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('customer.ai-usage.keys.store') }}" method="POST">
            @csrf
            <div class="card-body" style="display: flex; flex-direction: column; gap: 14px; padding: 20px;">
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Pilih Lisensi SaaS</label>
                    <select name="license_id" required class="form-select" style="width: 100%;">
                        @foreach($licenses as $lic)
                            <option value="{{ $lic->id }}">
                                {{ $lic->product->name ?? 'Paket SaaS' }} ({{ $lic->domain ?? 'Semua Domain' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase">Nama Identifikasi Kunci</label>
                    <input type="text" name="name" required class="form-input" placeholder="contoh: Production ERP Chatbot, Staging API..." style="width: 100%;">
                </div>
            </div>
            <div class="card-footer flex justify-end gap-2" style="padding: 14px 20px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeNewKeyModal()">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-key"></i> Generate API Key</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tabKey) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

    const btn = document.querySelector(`.tab-btn[onclick="switchTab('${tabKey}')"]`);
    const pane = document.getElementById('pane-' + tabKey);

    if (btn) btn.classList.add('active');
    if (pane) pane.classList.add('active');

    // Update URL hash
    window.location.hash = tabKey;
}

// Auto open tab from URL hash if present
document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#', '');
    if (hash && ['lots', 'transactions', 'keys', 'telemetry', 'analytics'].includes(hash)) {
        switchTab(hash);
    }
});

function openTopUpModal(licenseId = null) {
    if (licenseId) {
        const select = document.getElementById('topup-license-select');
        if (select) select.value = licenseId;
    }
    document.getElementById('topup-modal').style.display = 'flex';
}

function closeTopUpModal() {
    document.getElementById('topup-modal').style.display = 'none';
}

function selectPackageRadio(radioId, cardEl) {
    const radio = document.getElementById(radioId);
    if (radio) radio.checked = true;

    document.querySelectorAll('.package-card').forEach(c => c.classList.remove('selected'));
    cardEl.classList.add('selected');
}

function openNewKeyModal() {
    document.getElementById('new-key-modal').style.display = 'flex';
}

function closeNewKeyModal() {
    document.getElementById('new-key-modal').style.display = 'none';
}

function toggleKeyVisibility(id) {
    const codeEl = document.getElementById('key-text-' + id);
    const eyeEl = document.getElementById('key-eye-' + id);
    if (!codeEl || !eyeEl) return;

    const fullKey = codeEl.getAttribute('data-full');
    const maskedKey = codeEl.getAttribute('data-masked');
    const state = codeEl.getAttribute('data-state');

    if (state === 'masked') {
        codeEl.innerText = fullKey;
        codeEl.setAttribute('data-state', 'revealed');
        eyeEl.className = 'fa-regular fa-eye-slash';
    } else {
        codeEl.innerText = maskedKey;
        codeEl.setAttribute('data-state', 'masked');
        eyeEl.className = 'fa-regular fa-eye';
    }
}

function copyKeyText(id) {
    const codeEl = document.getElementById('key-text-' + id);
    if (!codeEl) return;
    const fullKey = codeEl.getAttribute('data-full');
    navigator.clipboard.writeText(fullKey).then(() => {
        alert('API Key berhasil disalin ke clipboard!');
    });
}
</script>
@endpush
