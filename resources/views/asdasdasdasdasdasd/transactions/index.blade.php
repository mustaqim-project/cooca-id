@extends('layouts.admin')
@section('title', 'Transactions')
@section('subtitle', 'View all payment transactions')
@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search by invoice, customer...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <select class="form-saas-select" id="statusFilter" style="width:150px">
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>
    </div>
    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td><code style="font-size:.8rem">{{ $transaction->invoice_number }}</code></td>
                                <td>
                                    <div style="font-weight:500">{{ $transaction->customer->name ?? '-' }}</div>
                                    <div style="font-size:.75rem;color:var(--text-muted)">
                                        {{ $transaction->customer->email ?? '' }}</div>
                                </td>
                                <td>{{ $transaction->product->name ?? '-' }}</td>
                                <td style="font-weight:600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>{{ $transaction->payment_method ?? '-' }}</td>
                                <td>
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
                                </td>
                                <td style="color:var(--text-muted);white-space:nowrap">
                                    {{ $transaction->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $transaction) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-receipt"></i></div>
                                        <div class="empty-state-title">No transactions found</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (isset($transactions) && method_exists($transactions, 'links'))
            <div class="card-saas-footer">{{ $transactions->links() }}</div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', filter);
        document.getElementById('statusFilter').addEventListener('change', filter);

        function filter() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const s = document.getElementById('statusFilter').value.toLowerCase();
            document.querySelectorAll('#transactionsTable tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = (text.includes(q) && (s === '' || text.includes(s))) ? '' : 'none';
            });
        }
    </script>
@endpush
