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
        <h1 class="page-title">Transaction Ledger</h1>
        <p class="page-subtitle">Real-time payment gateway logs, invoice details, Midtrans / Xendit webhooks, and refunds.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Reference / Invoice</th>
                        <th>Customer</th>
                        <th>Gross Amount</th>
                        <th>Gateway Fee</th>
                        <th>Net Revenue</th>
                        <th>Gateway</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td>
                                <code class="font-bold text-primary">{{ $tx->reference ?? ('INV-'.$tx->id) }}</code>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $tx->customer->name ?? 'Client' }}</div>
                                <div class="text-xs text-muted">{{ $tx->customer->email ?? '' }}</div>
                            </td>
                            <td class="font-bold text-sm">Rp {{ number_format($tx->amount ?? 0, 0, ',', '.') }}</td>
                            <td class="text-xs text-danger">Rp {{ number_format($tx->fee_amount ?? 0, 0, ',', '.') }}</td>
                            <td class="font-bold text-success text-sm">Rp {{ number_format($tx->net_amount ?? $tx->amount ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-purple">{{ strtoupper($tx->payment_gateway ?? 'Midtrans') }}</span>
                            </td>
                            <td>
                                @if(($tx->status ?? '') === 'paid')
                                    <span class="badge badge-success">PAID</span>
                                @elseif(($tx->status ?? '') === 'pending')
                                    <span class="badge badge-warning">PENDING</span>
                                @else
                                    <span class="badge badge-danger">{{ strtoupper($tx->status ?? 'FAILED') }}</span>
                                @endif
                            </td>
                            <td class="text-xs text-muted">{{ optional($tx->paid_at ?? $tx->created_at)->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.transactions.show', $tx->id) }}" class="btn btn-ghost btn-sm">👁️ Details</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted" style="padding: 40px;">No transaction records found.</td>
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
    @endif
</div>
@endsection
