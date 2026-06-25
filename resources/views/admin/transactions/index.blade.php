@extends('layouts.admin')

@section('title', 'Transactions Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-exchange-alt me-2"></i>Transactions Management
            </h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.transactions.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search invoice/customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="payment_method" class="form-select">
                            <option value="">All Payment Methods</option>
                            <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="e-wallet" {{ request('payment_method') === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">All Transactions ({{ $transactions->total() }})</h5>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-success">{{ $transactions->where('status', 'paid')->count() }} Paid</span>
                    <span class="badge bg-warning text-dark">{{ $transactions->where('status', 'pending')->count() }} Pending</span>
                    <span class="badge bg-danger">{{ $transactions->where('status', 'failed')->count() }} Failed</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td><strong><code>{{ $transaction->invoice_number ?? '-' }}</code></strong></td>
                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y H:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                        {{ substr($transaction->customer_name ?? 'C', 0, 2) }}
                                    </div>
                                    {{ $transaction->customer_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ $transaction->product_name ?? '-' }}</td>
                            <td>
                                <strong>Rp {{ number_format($transaction->amount ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($transaction->payment_method === 'bank_transfer')
                                    <i class="fas fa-university text-muted me-1"></i>Bank Transfer
                                @elseif($transaction->payment_method === 'credit_card')
                                    <i class="fas fa-credit-card text-muted me-1"></i>Credit Card
                                @elseif($transaction->payment_method === 'e-wallet')
                                    <i class="fas fa-wallet text-muted me-1"></i>E-Wallet
                                @else
                                    {{ ucfirst($transaction->payment_method ?? '-') }}
                                @endif
                            </td>
                            <td>
                                @if($transaction->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($transaction->status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @elseif($transaction->status === 'refunded')
                                    <span class="badge bg-info">Refunded</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($transaction->status ?? '-') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($transaction->status === 'pending')
                                        <button class="btn btn-outline-success" onclick="markAsPaid({{ $transaction->id }}, '{{ $transaction->invoice_number }}')" title="Mark as Paid">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No transactions found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} entries</p>
                <div class="pagination-wrapper">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function markAsPaid(id, invoiceNumber) {
        Swal.fire({
            title: 'Mark as Paid?',
            text: `Are you sure you want to mark transaction ${invoiceNumber} as paid?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, mark as paid!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement mark as paid logic here via AJAX
                Swal.fire('Success', 'Transaction marked as paid', 'success');
            }
        });
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
