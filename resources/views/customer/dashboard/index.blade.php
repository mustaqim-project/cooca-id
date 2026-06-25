@extends('layouts.customer')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Dashboard</h1>
    <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
        <i class="bi bi-cart-plus me-2"></i>Beli Produk Baru
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Subscripsi Aktif</p>
                    <h3 class="stat-value">{{ $stats['activeSubscriptions'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon indigo">📦</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Lisensi Aktif</p>
                    <h3 class="stat-value">{{ $stats['activeLicenses'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon green">🔑</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Total Pengeluaran</p>
                    <h3 class="stat-value">Rp {{ number_format($stats['totalSpent'] ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="stat-icon blue">💰</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Invoice Pending</p>
                    <h3 class="stat-value">{{ $stats['pendingInvoices'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon yellow">📄</div>
            </div>
        </div>
    </div>
</div>

<!-- Active Subscriptions -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">Subscripsi Aktif</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Berlaku Sampai</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLicenses ?? [] as $license)
                <tr>
                    <td>{{ $license->subscription->product->name ?? '-' }}</td>
                    <td>{{ $license->subscription->plan->name ?? '-' }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'active' => 'badge-success',
                                'trial' => 'badge-info',
                                'expired' => 'badge-danger',
                                'cancelled' => 'badge-secondary',
                                'inactive' => 'badge-secondary',
                                'revoked' => 'badge-danger'
                            ][$license->status] ?? 'badge-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($license->status) }}</span>
                    </td>
                    <td>{{ $license->expires_at ? \Carbon\Carbon::parse($license->expires_at)->format('d M Y') : '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('customer.licenses.index') }}" class="text-decoration-none">Lihat Lisensi</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        Belum ada subscripsi aktif. Mulai dengan membeli produk pertama Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Licenses -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">Lisensi Saya</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kode Lisensi</th>
                    <th>Domain</th>
                    <th>Status</th>
                    <th>Diaktifkan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($licenses ?? [] as $license)
                <tr>
                    <td class="font-monospace">{{ $license->license_code }}</td>
                    <td>{{ $license->domain ?? '-' }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'active' => 'badge-success',
                                'inactive' => 'badge-secondary',
                                'expired' => 'badge-danger',
                                'revoked' => 'badge-danger'
                            ][$license->status] ?? 'badge-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($license->status) }}</span>
                    </td>
                    <td>{{ $license->activated_at ? \Carbon\Carbon::parse($license->activated_at)->format('d M Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        Belum ada lisensi. Lisensi akan dibuat setelah subscripsi diaktifkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">Transaksi Terakhir</h5>
        <a href="{{ route('customer.invoices.index') }}" class="text-decoration-none">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions ?? [] as $trx)
                <tr>
                    <td class="fw-medium">{{ $trx->invoice_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</td>
                    <td>Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'pending' => 'badge-warning',
                                'paid' => 'badge-success',
                                'failed' => 'badge-danger',
                                'refunded' => 'badge-info'
                            ][$trx->status] ?? 'badge-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($trx->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
