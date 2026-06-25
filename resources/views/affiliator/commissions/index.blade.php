@extends('layouts.affiliator')

@section('title', 'My Commissions')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>My Commissions
                </h2>
                <a href="{{ route('affiliator.commissions.export') }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Commission</p>
                            <h4 class="mb-0 text-primary">Rp {{ number_format($totalCommission ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-wallet fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Cleared Commission</p>
                            <h4 class="mb-0 text-success">Rp {{ number_format($clearedCommission ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pending Commission</p>
                            <h4 class="mb-0 text-warning">Rp {{ number_format($pendingCommission ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-clock fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">This Month</p>
                            <h4 class="mb-0 text-info">Rp {{ number_format($thisMonthCommission ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-calendar-alt fa-lg text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Commission History ({{ $commissions->total() }})</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group float-end">
                        <input type="text" class="form-control form-control-sm" placeholder="Search..." id="searchInput">
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Customer</th>
                            <th>Level</th>
                            <th>Gross Amount</th>
                            <th>Percent</th>
                            <th>Commission</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                        <tr>
                            <td>{{ $commission->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($commission->created_at)->format('d M Y') }}</td>
                            <td><code>{{ $commission->transaction?->invoice_number ?? '-' }}</code></td>
                            <td>{{ $commission->customer?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">Level {{ $commission->level }}</span>
                            </td>
                            <td>Rp {{ number_format($commission->gross_amount ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $commission->commission_percent }}%</td>
                            <td>
                                <strong class="text-success">Rp {{ number_format($commission->commission_amount ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($commission->status === 'cleared')
                                    <span class="badge bg-success">Cleared</span>
                                @elseif($commission->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($commission->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No commissions found</p>
                                <p class="small text-muted">Commissions will appear here when your referrals make purchases</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Showing {{ $commissions->firstItem() ?? 0 }} to {{ $commissions->lastItem() ?? 0 }} of {{ $commissions->total() }} entries</p>
                <div class="pagination-wrapper">
                    {{ $commissions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

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
