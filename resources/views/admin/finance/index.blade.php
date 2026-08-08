@extends('layouts.admin')

@section('title', 'Finance & Reporting — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Finance & Reporting</span>
        </div>
        <h1 class="page-title">Finance & Reporting</h1>
        <p class="page-subtitle">Ringkasan pendapatan, potongan Midtrans, afiliasi, dan pajak perusahaan.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.finance.export') }}" class="btn btn-primary">
            <i class="fa-solid fa-file-csv" style="margin-right: 6px;"></i> Export to CSV
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 16px;">
        <div class="text-xs text-muted mb-1 font-bold uppercase tracking-wider">Total Revenue (Gross)</div>
        <div class="text-xl font-bold" style="color: var(--text);">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
    </div>
    <div class="card" style="padding: 16px;">
        <div class="text-xs text-muted mb-1 font-bold uppercase tracking-wider" style="color:var(--danger);">Total Pajak (PPN 11%)</div>
        <div class="text-xl font-bold" style="color: var(--danger);">-Rp {{ number_format($summary['total_tax'], 0, ',', '.') }}</div>
    </div>
    <div class="card" style="padding: 16px;">
        <div class="text-xs text-muted mb-1 font-bold uppercase tracking-wider" style="color:var(--warning);">Midtrans + Komisi</div>
        <div class="text-xl font-bold" style="color: var(--warning);">-Rp {{ number_format($summary['total_fees'] + $summary['total_commission'], 0, ',', '.') }}</div>
    </div>
    <div class="card" style="padding: 16px; background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.2);">
        <div class="text-xs mb-1 font-bold uppercase tracking-wider" style="color:var(--success);">Net Profit</div>
        <div class="text-xl font-bold" style="color: var(--success);">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h2 class="card-title">Riwayat Transaksi</h2>
        
        <form method="GET" action="{{ route('admin.finance.index') }}" style="display:flex; gap:8px;">
            <select name="month" class="form-select" style="padding:4px 8px; width:auto; font-size: 13px;">
                <option value="">Semua Bulan</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                @endfor
            </select>
            <select name="year" class="form-select" style="padding:4px 8px; width:auto; font-size: 13px;">
                <option value="">Semua Tahun</option>
                @for($y=date('Y'); $y>=date('Y')-3; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-outline btn-sm" style="padding:4px 12px;">Filter</button>
        </form>
    </div>
    
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table" style="font-size: 13px;">
            <thead>
                <tr>
                    <th>Invoice / Tgl</th>
                    <th>Customer</th>
                    <th>Payment</th>
                    <th style="text-align:right;">Net Amount</th>
                    <th style="text-align:right;">Tax 11%</th>
                    <th style="text-align:right;">Midtrans Fee</th>
                    <th style="text-align:right;">Komisi Aff</th>
                    <th style="text-align:right; font-weight:bold;">Net Profit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td>
                        <div class="font-bold">{{ $tx->invoice_number }}</div>
                        <div class="text-xs text-muted">{{ $tx->paid_at ? $tx->paid_at->format('d M Y, H:i') : '-' }}</div>
                    </td>
                    <td>
                        <div class="font-bold">{{ $tx->customer->name ?? 'Unknown' }}</div>
                    </td>
                    <td>
                        <span class="badge badge-outline">{{ strtoupper($tx->metrics['payment_type']) }}</span>
                    </td>
                    <td style="text-align:right; font-weight:600;">
                        Rp {{ number_format($tx->metrics['net_amount'], 0, ',', '.') }}
                    </td>
                    <td style="text-align:right; color:var(--danger);">
                        -Rp {{ number_format($tx->metrics['tax'], 0, ',', '.') }}
                    </td>
                    <td style="text-align:right; color:var(--danger);">
                        -Rp {{ number_format($tx->metrics['midtrans_fee'], 0, ',', '.') }}
                    </td>
                    <td style="text-align:right; color:var(--warning);">
                        -Rp {{ number_format($tx->metrics['affiliate_commission'], 0, ',', '.') }}
                    </td>
                    <td style="text-align:right; font-weight:800; color:var(--success);">
                        Rp {{ number_format($tx->metrics['net_profit'], 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-muted" style="padding: 40px;">Belum ada transaksi sukses.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    
    @if($transactions->hasPages())
    <div class="card-footer">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
