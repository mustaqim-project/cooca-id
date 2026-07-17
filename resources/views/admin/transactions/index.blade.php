@extends('admin.layouts.app')

@section('title', 'Transactions')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Transactions</h2>
                <p class="text-secondary mb-0">Manage customer orders and payments.</p>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search transactions...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Invoice</th>
                            <th class="py-3 px-3 border-0">Customer</th>
                            <th class="py-3 px-3 border-0">Product</th>
                            <th class="py-3 px-3 border-0">Amount</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Date</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td class="py-3 px-4">
                                    <span class="text-primary fw-medium">{{ $transaction->invoice_number }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium">
                                        {{ $transaction->customer->name ?? ($transaction->user->name ?? 'N/A') }}</div>
                                    <div class="text-secondary fs-7">
                                        {{ $transaction->customer->email ?? ($transaction->user->email ?? '') }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    {{ $transaction->product->name ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-3 fw-medium">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3">
                                    @if ($transaction->status == 'paid')
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 text-capitalize">{{ $transaction->status }}</span>
                                    @elseif($transaction->status == 'pending')
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 text-capitalize">{{ $transaction->status }}</span>
                                    @elseif($transaction->status == 'failed' || $transaction->status == 'cancelled')
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 text-capitalize">{{ $transaction->status }}</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1 text-capitalize">{{ $transaction->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                        class="btn btn-sm btn-light border rounded-circle p-2 hover-lift">
                                        <i class="bi bi-chevron-right text-secondary"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-receipt fs-1"></i></div>
                                    <h6 class="fw-medium">No Transactions Found</h6>
                                    <p class="fs-7">There are no transactions recorded yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($transactions) && $transactions->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
