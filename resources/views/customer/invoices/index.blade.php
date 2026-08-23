@extends('layouts.customer')
@section('title', 'Invoices')
@section('breadcrumb')
    <span class="crumb-current">Invoices</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-invoice" style="color:var(--primary);margin-right:10px;"></i>Invoices</h1>
        <p class="page-subtitle">Lihat, unduh, dan kelola tagihan langganan Anda.</p>
    </div>
    <div class="page-actions">
        @php
            $invItems = $invoices instanceof \Illuminate\Pagination\AbstractPaginator ? $invoices->getCollection() : collect($invoices);
            $outstanding = $invItems->filter(fn($i) => !$i->isPaid() && ($i->status === 'overdue' || ($i->due_at && $i->due_at->isPast())))->sum('amount');
        @endphp
        @if($outstanding > 0)
            <div class="alert alert-danger" style="margin-bottom:0;padding:10px 16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Outstanding: <strong>Rp {{ number_format($outstanding, 0, ',', '.') }}</strong>
            </div>
        @endif
    </div>
</div>

{{-- Summary KPI --}}
<div class="kpi-grid mb-6" style="grid-template-columns:repeat(4,1fr);">
    @php
        $paid    = $invItems->filter(fn($i) => $i->isPaid())->sum('amount');
        $pending = $invItems->filter(fn($i) => !$i->isPaid() && in_array($i->status, ['issued', 'pending']))->sum('amount');
        $overdue = $invItems->filter(fn($i) => !$i->isPaid() && ($i->status === 'overdue' || ($i->due_at && $i->due_at->isPast())))->sum('amount');
        $total   = $invoices->total() ?? $invItems->count();
    @endphp
    <div class="kpi-card kpi-success">
        <div class="kpi-icon success"><i class="fa-solid fa-check-circle"></i></div>
        <div class="kpi-value" style="font-size:18px;">Rp {{ number_format($paid, 0, ',', '.') }}</div>
        <div class="kpi-label">Total Paid</div>
    </div>
    <div class="kpi-card kpi-warning">
        <div class="kpi-icon warning"><i class="fa-solid fa-clock"></i></div>
        <div class="kpi-value" style="font-size:18px;">Rp {{ number_format($pending, 0, ',', '.') }}</div>
        <div class="kpi-label">Pending Payment</div>
    </div>
    <div class="kpi-card kpi-danger">
        <div class="kpi-icon danger"><i class="fa-solid fa-circle-xmark"></i></div>
        <div class="kpi-value" style="font-size:18px;">Rp {{ number_format($overdue, 0, ',', '.') }}</div>
        <div class="kpi-label">Overdue</div>
    </div>
    <div class="kpi-card kpi-primary">
        <div class="kpi-icon primary"><i class="fa-solid fa-file-invoice"></i></div>
        <div class="kpi-value">{{ $total }}</div>
        <div class="kpi-label">Total Invoices</div>
    </div>
</div>

<div class="card">
    {{-- Filter bar --}}
    <div class="card-header">
        <div class="card-title">Invoice History</div>
        <form method="GET" class="flex gap-2">
            <select name="status" class="form-select" style="min-width:140px;padding:7px 12px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="paid"    {{ request('status') === 'paid'    ? 'selected' : '' }}>Paid</option>
                <option value="issued"  {{ request('status') === 'issued'  ? 'selected' : '' }}>Pending</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </form>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Issued Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    @php
                        $isPaid = $invoice->isPaid();
                        $isVerifying = !$isPaid && $invoice->transaction && $invoice->transaction->hasPaymentProof() && $invoice->transaction->status === 'pending';
                    @endphp
                    <tr>
                        <td class="font-semibold">{{ $invoice->invoice_number }}</td>
                        <td class="text-sm text-muted">{{ $invoice->issued_at?->format('d M Y') ?? '—' }}</td>
                        <td class="text-sm text-muted">
                            {{ $invoice->due_at?->format('d M Y') ?? '—' }}
                            @if(!$isPaid && $invoice->due_at?->isPast())
                                <span class="badge badge-danger" style="font-size:10px;">Overdue</span>
                            @endif
                        </td>
                        <td><span class="font-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span></td>
                        <td>
                            @if($isPaid)
                                <span class="badge badge-success">Paid</span>
                            @elseif($isVerifying)
                                <span class="badge badge-warning" style="font-size: 11px;">
                                    <i class="fa-solid fa-clock"></i> Verifikasi Bukti
                                </span>
                            @elseif($invoice->status === 'overdue' || ($invoice->due_at && $invoice->due_at->isPast()))
                                <span class="badge badge-danger">Overdue</span>
                            @elseif($invoice->status === 'issued' || $invoice->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($invoice->status === 'cancelled')
                                <span class="badge badge-muted">Cancelled</span>
                            @else
                                <span class="badge badge-muted">{{ ucfirst($invoice->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1 items-center">
                                <a href="{{ route('customer.invoices.show', $invoice->id) }}" class="btn btn-ghost btn-sm" title="Lihat Rincian Tagihan">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('customer.invoices.download', $invoice->id) }}" class="btn btn-ghost btn-sm" title="Download PDF Invoice">
                                    <i class="fa-solid fa-file-pdf text-danger"></i>
                                </a>
                                @if(!$isPaid && !$isVerifying && in_array($invoice->status, ['issued', 'overdue', 'pending', 'unpaid']))
                                    <a href="{{ route('customer.invoices.show', $invoice->id) }}" class="btn btn-primary btn-sm" title="Bayar Tagihan">
                                        <i class="fa-solid fa-credit-card"></i> Bayar
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">📄</div>
                                <div class="empty-state-title">No Invoices Found</div>
                                <div class="empty-state-text">Your invoices will appear here after subscribing.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($invoices, 'hasPages') && $invoices->hasPages())
        <div class="card-footer">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
