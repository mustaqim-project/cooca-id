@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="stat-label">Total Customers</p>
                    <h3 class="stat-value">{{ $stats['totalCustomers'] ?? 0 }}</h3>
                    <p class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 12% from last month
                    </p>
                </div>
                <div class="stat-icon indigo">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="stat-label">Total Affiliators</p>
                    <h3 class="stat-value">{{ $stats['totalAffiliators'] ?? 0 }}</h3>
                    <p class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 8% from last month
                    </p>
                </div>
                <div class="stat-icon green">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="stat-label">Active Licenses</p>
                    <h3 class="stat-value">{{ $stats['activeLicenses'] ?? 0 }}</h3>
                    <p class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 15% from last month
                    </p>
                </div>
                <div class="stat-icon blue">
                    <i class="bi bi-key"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <p class="stat-label">Monthly Revenue</p>
                    <h3 class="stat-value">Rp {{ number_format($stats['monthlyRevenue'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="stat-change {{ ($stats['revenueChange'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                        <i class="bi bi-arrow-{{ ($stats['revenueChange'] ?? 0) >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['revenueChange'] ?? 0) }}% from last month
                    </p>
                </div>
                <div class="stat-icon yellow">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">Recent Transactions</h5>
        <a href="{{ route('admin.transactions.index') }}" class="text-decoration-none">View All →</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions ?? [] as $transaction)
                <tr>
                    <td class="fw-medium">{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->customer->name ?? '-' }}</td>
                    <td>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'pending' => 'badge-warning',
                                'paid' => 'badge-success',
                                'failed' => 'badge-danger',
                                'refunded' => 'badge-info'
                            ][$transaction->status] ?? 'badge-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($transaction->status) }}</span>
                    </td>
                    <td>{{ $transaction->paid_at ? \Carbon\Carbon::parse($transaction->paid_at)->format('d M Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No recent transactions</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
// You can add page-specific JavaScript here
</script>
@endpush
