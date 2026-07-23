@extends('customer.layouts.app')

@section('title', 'My Payments')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">My Payments</h2>
                <p class="text-secondary mb-0">History of all your transactions and payments.</p>
            </div>
            <div class="d-flex gap-2">
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search payments...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Transaction ID</th>
                            <th class="py-3 px-3 border-0">Date</th>
                            <th class="py-3 px-3 border-0">Amount</th>
                            <th class="py-3 px-3 border-0">Method</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($payments as $payment)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-credit-card"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $payment->invoice_number ?? $payment->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ $payment->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="fw-semibold">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    {{ strtoupper($payment->payment_type ?? '-') }}
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusClass = match($payment->status) {
                                            'paid', 'settlement', 'capture' => 'success',
                                            'pending' => 'warning',
                                            'failed', 'deny', 'cancel', 'expire' => 'danger',
                                            'refunded' => 'info',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('customer.payments.show', $payment->id) }}"
                                        class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                                        View <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No Payment Records Found</h6>
                                    <p class="fs-7">You don't have any payment history.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($payments, 'hasPages') && $payments->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
