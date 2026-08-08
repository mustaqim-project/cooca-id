@extends('layouts.customer')
@section('title', 'Payment Details')
@section('breadcrumb')
    <a href="{{ route('customer.payments.index') }}" class="crumb-link">Payments</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Details</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-receipt" style="color:var(--primary);margin-right:10px;"></i>Transaction Details</h1>
        <p class="page-subtitle">ID: {{ $payment->order_id ?? $payment->id }}</p>
    </div>
    <a href="{{ route('customer.payments.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="card" style="max-width:640px;margin:0 auto;">
    <div class="card-header">
        <div class="card-title">Transaction Summary</div>
        @if(in_array($payment->status, ['paid', 'settlement', 'success'])) <span class="badge badge-success">SUCCESS</span>
        @elseif($payment->status === 'pending') <span class="badge badge-warning">PENDING</span>
        @else <span class="badge badge-danger">{{ strtoupper($payment->status) }}</span>
        @endif
    </div>
    <div class="card-body">
        <div class="stats-row">
            <span class="text-sm text-muted">Order ID</span>
            <span class="font-mono text-sm font-bold">{{ $payment->order_id ?? $payment->id }}</span>
        </div>
        <div class="stats-row">
            <span class="text-sm text-muted">Gross Amount</span>
            <span class="font-bold text-base" style="color:var(--primary);">Rp {{ number_format($payment->gross_amount ?? $payment->amount, 0, ',', '.') }}</span>
        </div>
        <div class="stats-row">
            <span class="text-sm text-muted">Payment Type</span>
            <span class="text-sm font-semibold">{{ strtoupper($payment->payment_type ?? 'Bank Transfer / QRIS') }}</span>
        </div>
        <div class="stats-row">
            <span class="text-sm text-muted">Transaction Date</span>
            <span class="text-sm">{{ $payment->created_at->format('d M Y H:i:s') }}</span>
        </div>
    </div>
    @if($payment->status === 'failed' && $payment->created_at->diffInHours(now()) <= 24 && $payment->subscription_id)
    <div class="card-footer" style="padding:15px 20px; border-top:1px solid var(--border-color); text-align:right;">
        <p class="text-sm text-muted mb-3 text-left">Pembayaran gagal. Anda dapat mengulangi pembayaran dalam waktu 24 jam sejak transaksi dibuat.</p>
        <a href="{{ route('customer.subscriptions.checkout', $payment->subscription_id) }}" class="btn btn-primary">
            <i class="fa-solid fa-rotate-right"></i> Ulangi Pembayaran
        </a>
    </div>
    @endif
</div>
@endsection
