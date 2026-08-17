@extends('layouts.admin')

@section('title', 'Payment Transactions — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Transactions</span>
        </div>
        <h1 class="page-title">Transaction Ledger & Verification</h1>
        <p class="page-subtitle">Midtrans payment gateway logs, manual bank transfer verification, invoice records, and refunds.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

<div class="card mb-6">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('admin.transactions.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
            <div style="flex:1;min-width:200px;">
                <input type="text" name="search" class="form-input" placeholder="Cari No. Invoice, Customer, Pengirim..." value="{{ request('search') }}">
            </div>
            <div style="min-width:160px;">
                <select name="gateway" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Metode</option>
                    <option value="midtrans" {{ request('gateway') === 'midtrans' ? 'selected' : '' }}>Midtrans Gateway</option>
                    <option value="manual" {{ request('gateway') === 'manual' ? 'selected' : '' }}>Transfer Bank Manual</option>
                </select>
            </div>
            <div style="min-width:140px;">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed / Ditolak</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                @if(request()->hasAny(['search', 'gateway', 'status']))
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Metode Pembayaran</th>
                        <th>Bukti Bayar</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td>
                                <code class="font-bold text-primary">{{ $tx->invoice_number ?? ('INV-'.$tx->id) }}</code>
                                @if($tx->type)
                                    <div class="text-xs text-muted">{{ ucfirst($tx->type) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $tx->customer->name ?? 'Client' }}</div>
                                <div class="text-xs text-muted">{{ $tx->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-bold text-sm">Rp {{ number_format($tx->net_amount ?? $tx->gross_amount ?? $tx->amount ?? 0, 0, ',', '.') }}</div>
                                @if(($tx->voucher_discount ?? 0) > 0)
                                    <div class="text-xs text-success">Disc: Rp {{ number_format($tx->voucher_discount, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td>
                                @if($tx->isManualTransfer())
                                    <span class="badge badge-warning" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;">
                                        <i class="fa-solid fa-building-columns"></i> Transfer Manual
                                    </span>
                                    @if($tx->sender_name)
                                        <div class="text-xs text-muted mt-1">a/n {{ $tx->sender_name }}</div>
                                    @endif
                                @else
                                    <span class="badge badge-purple">
                                        <i class="fa-solid fa-bolt"></i> Midtrans
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($tx->payment_proof)
                                    <a href="{{ $tx->payment_proof_url }}" target="_blank" class="btn btn-ghost btn-sm" style="color:var(--primary);font-size:12px;">
                                        <i class="fa-solid fa-image"></i> Lihat Bukti
                                    </a>
                                @elseif($tx->isManualTransfer())
                                    <span class="text-xs text-danger"><i class="fa-solid fa-circle-exclamation"></i> Belum Upload</span>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($tx->status === 'paid')
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> PAID</span>
                                @elseif($tx->status === 'pending')
                                    @if($tx->isManualTransfer() && $tx->payment_proof)
                                        <span class="badge badge-warning" style="background:#ffedd5;color:#9a3412;border:1px solid #fed7aa;">
                                            <i class="fa-solid fa-clock"></i> Butuh Verifikasi
                                        </span>
                                    @else
                                        <span class="badge badge-warning">PENDING</span>
                                    @endif
                                @elseif($tx->status === 'failed')
                                    <span class="badge badge-danger">FAILED</span>
                                @else
                                    <span class="badge badge-muted">{{ strtoupper($tx->status) }}</span>
                                @endif
                            </td>
                            <td class="text-xs text-muted">{{ optional($tx->paid_at ?? $tx->created_at)->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.transactions.show', $tx->id) }}" class="btn btn-ghost btn-sm">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 40px;">Tidak ada catatan transaksi ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
