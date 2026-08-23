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
        @if($payment->status === 'pending')
            <div class="alert alert-warning mb-3" style="font-size:12px; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-clock"></i>
                <div>
                    <strong>Batas Waktu Pembayaran:</strong> Harap selesaikan pembayaran dalam <strong>1 jam</strong> sejak pemesanan dibuat (sebelum <strong>{{ $payment->created_at->addHours(1)->format('H:i') }} WIB</strong>).
                </div>
            </div>
        @elseif(in_array($payment->status, ['failed', 'expire', 'cancelled']))
            <div class="alert alert-danger mb-3" style="font-size:12px; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-circle-xmark"></i>
                <div>
                    <strong>Transaksi Kedaluwarsa:</strong> {{ $payment->rejection_reason ?? 'Batas waktu pembayaran 1 jam telah berakhir.' }} Domain Anda telah dibebaskan dan dapat dipesan ulang.
                </div>
            </div>
        @endif

        <div class="stats-row">
            <span class="text-sm text-muted">Order ID</span>
            <span class="font-mono text-sm font-bold">{{ $payment->order_id ?? $payment->id }}</span>
        </div>
        @if($payment->invoice)
        <div class="stats-row">
            <span class="text-sm text-muted">Invoice No.</span>
            <a href="{{ route('customer.invoices.show', $payment->invoice->id) }}" class="font-mono text-sm font-bold text-primary">
                {{ $payment->invoice->invoice_number }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
            </a>
        </div>
        @endif
        @if($payment->subscription?->product)
        <div class="stats-row">
            <span class="text-sm text-muted">Product / Service</span>
            <span class="font-bold text-sm">{{ $payment->subscription->product->name }} ({{ $payment->subscription->subscriptionPlan->name ?? 'Standard' }})</span>
        </div>
        @endif
        @if($payment->subscription?->license?->domain)
        <div class="stats-row">
            <span class="text-sm text-muted">Domain / Subdomain</span>
            <span class="font-bold text-sm text-primary">{{ $payment->subscription->license->domain }}</span>
        </div>
        @endif
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
        @if($payment->paid_at)
        <div class="stats-row">
            <span class="text-sm text-muted">Paid At</span>
            <span class="text-sm text-success font-bold">{{ $payment->paid_at->format('d M Y H:i:s') }}</span>
        </div>
        @endif
    </div>

    <div class="card-footer" style="padding:15px 20px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <a href="{{ route('customer.payments.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>

        <div class="flex gap-2">
            @if(in_array($payment->status, ['pending', 'unpaid', 'issued']))
                @if($payment->invoice)
                    <a href="{{ route('customer.invoices.show', $payment->invoice->id) }}" class="btn btn-primary">
                        <i class="fa-solid fa-credit-card"></i> Lanjutkan Pembayaran
                    </a>
                @elseif($payment->subscription_id)
                    <a href="{{ route('customer.subscriptions.checkout', $payment->subscription_id) }}" class="btn btn-primary">
                        <i class="fa-solid fa-credit-card"></i> Lanjutkan Pembayaran
                    </a>
                @endif
            @elseif(in_array($payment->status, ['paid', 'settlement', 'success']))
                @if($payment->invoice)
                    <a href="{{ route('customer.invoices.show', $payment->invoice->id) }}" class="btn btn-outline">
                        <i class="fa-solid fa-file-invoice"></i> Buka Invoice
                    </a>
                @endif
                @if($payment->subscription?->license)
                    <a href="{{ route('customer.licenses.credentials', $payment->subscription->license->id) }}" class="btn btn-primary">
                        <i class="fa-solid fa-key"></i> Kredensial Lisensi
                    </a>
                @endif
            @elseif(in_array($payment->status, ['failed', 'expire', 'cancelled']))
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-cart-plus"></i> Order Ulang Sekarang
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
