@extends('layouts.customer')
@section('title', 'Payment History')
@section('breadcrumb')
    <span class="crumb-current">Payments</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-credit-card" style="color:var(--primary);margin-right:10px;"></i>Payment Transactions</h1>
        <p class="page-subtitle">View your payment history and gateway status logs.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order / Tx ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td class="font-bold text-sm">{{ $payment->order_id ?? substr($payment->id, 0, 12) }}</td>
                        <td class="text-xs text-muted">{{ $payment->created_at->format('d M Y H:i') }}</td>
                        <td class="font-bold">Rp {{ number_format($payment->gross_amount ?? $payment->amount, 0, ',', '.') }}</td>
                        <td class="text-xs text-muted">{{ strtoupper($payment->payment_type ?? 'Midtrans') }}</td>
                        <td>
                            @if(in_array($payment->status, ['paid', 'settlement', 'success']))
                                <span class="badge badge-success">Paid</span>
                            @elseif(in_array($payment->status, ['pending']))
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('customer.payments.show', $payment->id) }}" class="btn btn-ghost btn-sm" title="Lihat Rincian Transaksi">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if(in_array($payment->status, ['pending', 'unpaid', 'issued']))
                                    @if($payment->invoice)
                                        <a href="{{ route('customer.invoices.show', $payment->invoice->id) }}" class="btn btn-warning btn-sm" title="Lanjutkan Pembayaran">
                                            <i class="fa-solid fa-credit-card"></i> Lanjutkan Bayar
                                        </a>
                                    @elseif($payment->subscription_id)
                                        <a href="{{ route('customer.subscriptions.checkout', $payment->subscription_id) }}" class="btn btn-warning btn-sm" title="Lanjutkan Pembayaran">
                                            <i class="fa-solid fa-credit-card"></i> Lanjutkan Bayar
                                        </a>
                                    @endif
                                @elseif(in_array($payment->status, ['paid', 'settlement', 'success']))
                                    @if($payment->invoice)
                                        <a href="{{ route('customer.invoices.show', $payment->invoice->id) }}" class="btn btn-outline btn-sm" title="Lihat Invoice & Lisensi">
                                            <i class="fa-solid fa-file-invoice"></i> Invoice
                                        </a>
                                    @endif
                                @elseif($payment->status === 'failed' && $payment->created_at->diffInHours(now()) <= 24 && $payment->subscription_id)
                                    <a href="{{ route('customer.subscriptions.checkout', $payment->subscription_id) }}" class="btn btn-primary btn-sm" title="Ulangi Pembayaran">
                                        <i class="fa-solid fa-rotate-right"></i> Ulangi
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">💳</div>
                                <div class="empty-state-title">No Payments Recorded</div>
                                <div class="empty-state-text">Transactions will appear here after paying invoices or subscriptions.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($payments, 'hasPages') && $payments->hasPages())
        <div class="card-footer">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
