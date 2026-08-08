@extends('layouts.customer')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('breadcrumb')
    <a href="{{ route('customer.invoices.index') }}" class="crumb-link">Invoices</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">{{ $invoice->invoice_number }}</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-invoice" style="color:var(--primary);margin-right:10px;"></i>Invoice #{{ $invoice->invoice_number }}</h1>
        <p class="page-subtitle">Issued on {{ $invoice->issued_at?->format('d M Y') ?? $invoice->created_at->format('d M Y') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.invoices.download', $invoice->id) }}" class="btn btn-outline">
            <i class="fa-solid fa-download"></i> Download PDF
        </a>
        <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card" style="max-width:800px;margin:0 auto;">
    <div class="card-body" style="padding:32px;">
        {{-- Invoice Header --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <div class="font-bold text-2xl" style="color:var(--primary);">COOCA.ID</div>
                <div class="text-xs text-muted">PT COOCA TECHNOLOGIES INDONESIA</div>
                <div class="text-xs text-muted">Jakarta, Indonesia</div>
            </div>
            <div class="text-right">
                <div class="font-bold text-xl">{{ $invoice->invoice_number }}</div>
                <div class="mt-2">
                    @if($invoice->status === 'paid')    <span class="badge badge-success" style="font-size:14px;padding:6px 14px;">PAID</span>
                    @elseif($invoice->status === 'overdue') <span class="badge badge-danger" style="font-size:14px;padding:6px 14px;">OVERDUE</span>
                    @else <span class="badge badge-warning" style="font-size:14px;padding:6px 14px;">PENDING PAYMENT</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid-2 mb-6" style="background:var(--bg);border-radius:var(--radius);padding:18px;">
            <div>
                <div class="text-xs text-muted font-bold uppercase mb-1">Billed To</div>
                <div class="font-bold text-sm">{{ auth('customer')->user()->business_name ?? auth('customer')->user()->name }}</div>
                <div class="text-xs text-muted">{{ auth('customer')->user()->email }}</div>
            </div>
            <div class="text-right">
                <div class="text-xs text-muted font-bold uppercase mb-1">Invoice Dates</div>
                <div class="text-xs">Issued: <strong>{{ $invoice->issued_at?->format('d M Y') ?? '—' }}</strong></div>
                <div class="text-xs">Due: <strong style="color:var(--danger);">{{ $invoice->due_at?->format('d M Y') ?? '—' }}</strong></div>
            </div>
        </div>

        {{-- Table --}}
        <table class="data-table mb-6">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="font-bold text-sm">{{ $invoice->subscription?->product?->name ?? 'COOCA SaaS Subscription' }}</div>
                        <div class="text-xs text-muted">{{ $invoice->subscription?->subscriptionPlan?->name ?? 'Service Plan' }}</div>
                    </td>
                    <td class="text-right font-bold text-base">
                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-between items-center pt-4" style="border-top:2px solid var(--border);">
            <div class="text-sm font-bold">Total Amount Due:</div>
            <div class="text-2xl font-bold" style="color:var(--primary);">
                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
            </div>
        </div>

        @if(in_array($invoice->status, ['issued', 'overdue', 'pending', 'unpaid']))
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('customer.payments.store') }}">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                <input type="hidden" name="subscription_id" value="{{ $invoice->subscription_id }}">
                <input type="hidden" name="gross_amount" value="{{ $invoice->amount }}">
                <button type="submit" class="btn btn-primary btn-lg w-full" style="justify-content:center;">
                    <i class="fa-solid fa-credit-card"></i> Pay Invoice Now via Midtrans
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
