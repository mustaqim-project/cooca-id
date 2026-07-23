@extends('customer.layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Welcome back, {{ auth()->user()->name ?? 'Customer' }}! 👋</h2>
                <p class="text-secondary mb-0">Here's an overview of your recent transactions and activities.</p>
            </div>
            <div class="d-flex gap-2">
            </div>
        </div>

        <!-- Stats Table / Filter Toolbar -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                
                <h5 class="mb-0 fw-semibold">Recent Transactions</h5>

                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search transactions...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Invoice #</th>
                            <th class="py-3 px-3 border-0">Product</th>
                            <th class="py-3 px-3 border-0">Amount</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Date</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($recentTransactions ?? [] as $transaction)
                            <tr>
                                <td class="py-3 px-4 fw-medium">
                                    {{ $transaction->invoice_number }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="fw-semibold">{{ $transaction->subscription->plan->product->name ?? 'Unknown Product' }}</span>
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusClass = match($transaction->status) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            'refunded' => 'info',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1 text-capitalize">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-secondary fs-7 text-end">
                                    {{ $transaction->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No Recent Transactions</h6>
                                    <p class="fs-7">You haven't made any transactions recently.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($recentTransactions) && method_exists($recentTransactions, 'hasPages') && $recentTransactions->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $recentTransactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
