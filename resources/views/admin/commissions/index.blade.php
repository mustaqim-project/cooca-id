@extends('admin.layouts.app')

@section('title', 'Commission Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Commission Management</h1>
                <a href="{{ route('admin.commissions.index') }}" class="btn btn-primary">
                    <i class="fas fa-refresh"></i> Refresh
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending (Holding Period)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Available Balance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['available'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Requested</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['requested'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cleared</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['cleared'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.commissions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="available" {{ ($filters['status'] ?? '') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="requested" {{ ($filters['status'] ?? '') == 'requested' ? 'selected' : '' }}>Requested</option>
                        <option value="cleared" {{ ($filters['status'] ?? '') == 'cleared' ? 'selected' : '' }}>Cleared</option>
                        <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="voided" {{ ($filters['status'] ?? '') == 'voided' ? 'selected' : '' }}>Voided</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="affiliator_email" class="form-label">Affiliator Email</label>
                    <input type="email" class="form-control" name="affiliator_email" id="affiliator_email" 
                           value="{{ $filters['affiliator_email'] ?? '' }}" placeholder="Search by email">
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="date_from" id="date_from" 
                           value="{{ $filters['date_from'] ?? '' }}">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="date_to" id="date_to" 
                           value="{{ $filters['date_to'] ?? '' }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Commissions List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Affiliator</th>
                            <th>Customer</th>
                            <th>Subscription</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Available At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                        <tr>
                            <td>
                                <div>{{ $commission->affiliateCode->affiliator->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $commission->affiliateCode->affiliator->user->email ?? '' }}</small>
                            </td>
                            <td>
                                <div>{{ $commission->subscription->customer->user->name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <small>{{ $commission->subscription->id }}</small>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($commission->amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @include('admin.commissions.partials.status-badge', ['status' => $commission->status])
                            </td>
                            <td>{{ $commission->created_at?->format('d M Y') ?? '-' }}</td>
                            <td>
                                @if($commission->available_at)
                                    @if($commission->available_at->isPast())
                                        <span class="text-success">{{ $commission->available_at->format('d M Y') }}</span>
                                    @else
                                        <span class="text-warning">{{ $commission->available_at->diffForHumans() }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.commissions.show', $commission->id) }}" 
                                   class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No commissions found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($commissions->hasPages())
            <div class="mt-3">
                {{ $commissions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
