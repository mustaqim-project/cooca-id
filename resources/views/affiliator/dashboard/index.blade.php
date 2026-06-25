@extends('layouts.affiliator')

@section('title', 'Dashboard Affiliator')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Dashboard Affiliator</h1>
    <a href="{{ route('affiliator.withdrawals.create') }}" class="btn btn-primary">
        <i class="bi bi-cash-stack me-2"></i>Tarik Saldo
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Saldo Tersedia</p>
                    <h3 class="stat-value">Rp {{ number_format($stats['totalBalance'] ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="stat-icon indigo">💰</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Total Pendapatan</p>
                    <h3 class="stat-value">Rp {{ number_format($stats['totalEarned'] ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="stat-icon green">📈</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Komisi Pending</p>
                    <h3 class="stat-value">Rp {{ number_format($stats['pendingCommission'] ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="stat-icon blue">⏳</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="stat-label mb-1">Total Referral</p>
                    <h3 class="stat-value">{{ $stats['totalReferrals'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon yellow">👥</div>
            </div>
        </div>
    </div>
</div>

<!-- Level Breakdown -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-6">
        <div class="level-card level-1 h-100">
            <div class="d-flex align-items-center justify-content-between h-100">
                <div>
                    <p class="mb-1 opacity-75">Level 1 (25%)</p>
                    <h2 class="display-6 fw-bold mb-1">{{ $stats['level1Count'] ?? 0 }}</h2>
                    <p class="mb-0 small opacity-75">Referral langsung</p>
                </div>
                <div class="display-4">🎯</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="level-card level-2 h-100">
            <div class="d-flex align-items-center justify-content-between h-100">
                <div>
                    <p class="mb-1 opacity-75">Level 2 (5%)</p>
                    <h2 class="display-6 fw-bold mb-1">{{ $stats['level2Count'] ?? 0 }}</h2>
                    <p class="mb-0 small opacity-75">Dari downline</p>
                </div>
                <div class="display-4">🌟</div>
            </div>
        </div>
    </div>
</div>

<!-- Commissions Table -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">Riwayat Komisi</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Level</th>
                    <th>Gross Amount</th>
                    <th>Komisi (%)</th>
                    <th>Diterima</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commissions ?? [] as $comm)
                <tr>
                    <td class="fw-medium">{{ $comm->transaction_invoice }}</td>
                    <td>{{ $comm->customer_name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $comm->level == 1 ? 'badge-blue' : 'badge-purple' }}">
                            Level {{ $comm->level }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($comm->gross_amount, 0, ',', '.') }}</td>
                    <td>{{ $comm->commission_percent }}%</td>
                    <td class="fw-semibold text-success">Rp {{ number_format($comm->commission_amount, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'pending' => 'badge-warning',
                                'cleared' => 'badge-success',
                                'cancelled' => 'badge-danger'
                            ][$comm->status] ?? 'badge-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($comm->status) }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($comm->created_at)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        Belum ada komisi. Komisi akan muncul setelah referral melakukan pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Referrals Table -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">Referral Langsung (Level 1)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Bergabung</th>
                    <th>Total Pembelian</th>
                    <th>Total Komisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals ?? [] as $ref)
                <tr>
                    <td class="fw-medium">{{ $ref->name }}</td>
                    <td>{{ $ref->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($ref->joined_at)->format('d M Y') }}</td>
                    <td>{{ $ref->total_purchases }}</td>
                    <td class="fw-semibold text-success">Rp {{ number_format($ref->total_commission, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        Belum ada referral langsung. Bagikan kode referral Anda untuk mulai mengundang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Downlines Table -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">Downline (Level 2)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Bergabung</th>
                    <th>Total Pembelian</th>
                    <th>Total Komisi (5%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($downlines ?? [] as $down)
                <tr>
                    <td class="fw-medium">{{ $down->name }}</td>
                    <td>{{ $down->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($down->joined_at)->format('d M Y') }}</td>
                    <td>{{ $down->total_purchases }}</td>
                    <td class="fw-semibold text-primary">Rp {{ number_format($down->total_commission, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        Belum ada downline. Downline akan muncul ketika referral Anda mengundang orang lain.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Referral Code Section -->
<div class="referral-box">
    <h5 class="mb-3">Kode Referral Anda</h5>
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="referral-code">
            {{ auth()->guard('affiliator')->user()->referral_code ?? 'LOADING...' }}
        </div>
        <button type="button" class="btn btn-light" onclick="copyReferralCode('{{ auth()->guard('affiliator')->user()->referral_code ?? '' }}')">
            <i class="bi bi-clipboard me-1"></i>Salin
        </button>
    </div>
    <p class="mb-0 mt-3 small opacity-75">
        Bagikan kode ini untuk mendapatkan 25% komisi dari setiap pembelian referral Anda,
        dan 5% dari pembelian downline mereka.
    </p>
</div>

@endsection
