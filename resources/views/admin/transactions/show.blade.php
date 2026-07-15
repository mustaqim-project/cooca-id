@extends('layouts.admin')
@section('title', 'Transaction #' . $transaction->invoice_number)
@section('subtitle', 'Transaction Details')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.transactions.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Transactions
        </a>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-receipt me-2"></i>Invoice Details</h5>
                </div>
                <div class="card-saas-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Invoice Number</div>
                            <div style="font-weight:600"><code>{{ $transaction->invoice_number }}</code></div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Status</div>
                            @php
                                $s = $transaction->status;
                                $cls = match ($s) {
                                    'paid' => 'badge-saas-success',
                                    'pending' => 'badge-saas-warning',
                                    'failed' => 'badge-saas-danger',
                                    'refunded' => 'badge-saas-info',
                                    default => 'badge-saas-neutral',
                                };
                            @endphp
                            <span class="badge-saas {{ $cls }}">{{ ucfirst($s) }}</span>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Customer</div>
                            <div style="font-weight:500">{{ $transaction->customer->name ?? '-' }}</div>
                            <div style="font-size:.8rem;color:var(--text-muted)">{{ $transaction->customer->email ?? '' }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Product</div>
                            <div style="font-weight:500">{{ $transaction->product->name ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Amount</div>
                            <div style="font-size:1.25rem;font-weight:700;color:var(--primary)">Rp
                                {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Payment Method</div>
                            <div style="font-weight:500">{{ $transaction->payment_method ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Transaction Date</div>
                            <div>{{ $transaction->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div
                                style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                Paid At</div>
                            <div>{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : '-' }}</div>
                        </div>
                        @if ($transaction->notes)
                            <div class="col-12">
                                <div
                                    style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em">
                                    Notes</div>
                                <div>{{ $transaction->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($transaction->voucher)
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-ticket-perforated me-2"></i>Voucher Applied</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <code class="fs-5">{{ $transaction->voucher->code }}</code>
                                <div style="font-size:.875rem;color:var(--text-muted)">
                                    {{ $transaction->voucher->description }}</div>
                            </div>
                            <div style="font-size:1.25rem;font-weight:700;color:var(--success)">
                                -Rp {{ number_format($transaction->discount_amount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    @if ($transaction->status === 'pending')
                        <button type="button" class="btn-saas btn-saas-primary w-100"
                            onclick="markAsPaid({{ $transaction->id }})">
                            <i class="bi bi-check-circle me-1"></i> Mark as Paid
                        </button>
                    @endif
                    @if ($transaction->status === 'paid')
                        <button type="button" class="btn-saas btn-saas-outline w-100"
                            onclick="processRefund({{ $transaction->id }})">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Process Refund
                        </button>
                    @endif
                    <a href="{{ route('admin.customers.show', $transaction->customer_id) }}"
                        class="btn-saas btn-saas-ghost w-100">
                        <i class="bi bi-person me-1"></i> View Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function markAsPaid(id) {
            if (!confirm('Mark this transaction as paid?')) return;
            fetch(`/admin/transactions/${id}/mark-paid`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(r => r.ok ? location.reload() : alert('Failed'));
        }

        function processRefund(id) {
            if (!confirm('Process refund for this transaction?')) return;
            fetch(`/admin/transactions/${id}/refund`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(r => r.ok ? location.reload() : alert('Failed'));
        }
    </script>
@endpush
