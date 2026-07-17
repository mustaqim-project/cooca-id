@extends('admin.layouts.app')

@section('title', 'Trial Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Trial Management</h1>
                <a href="{{ route('admin.trials.index') }}" class="btn btn-primary">
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Waiting Approval</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['waiting_approval'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Provisioning</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['provisioning'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Trials</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_trial'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Converted</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['converted'] ?? 0 }}</div>
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
            <form method="GET" action="{{ route('admin.trials.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="waiting_approval" {{ ($filters['status'] ?? '') == 'waiting_approval' ? 'selected' : '' }}>Waiting Approval</option>
                        <option value="provisioning" {{ ($filters['status'] ?? '') == 'provisioning' ? 'selected' : '' }}>Provisioning</option>
                        <option value="domain_setup" {{ ($filters['status'] ?? '') == 'domain_setup' ? 'selected' : '' }}>Domain Setup</option>
                        <option value="testing" {{ ($filters['status'] ?? '') == 'testing' ? 'selected' : '' }}>Testing</option>
                        <option value="active_trial" {{ ($filters['status'] ?? '') == 'active_trial' ? 'selected' : '' }}>Active Trial</option>
                        <option value="converted_to_subscription" {{ ($filters['status'] ?? '') == 'converted_to_subscription' ? 'selected' : '' }}>Converted</option>
                        <option value="expired" {{ ($filters['status'] ?? '') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="failed" {{ ($filters['status'] ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="customer_email" class="form-label">Customer Email</label>
                    <input type="email" class="form-control" name="customer_email" id="customer_email" 
                           value="{{ $filters['customer_email'] ?? '' }}" placeholder="Search by email">
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

    <!-- Trials Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Trials List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Plan</th>
                            <th>Subdomain</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Expires</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trials as $trial)
                        <tr>
                            <td>
                                <div>{{ $trial->customer->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $trial->customer->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $trial->erpProduct->name ?? $trial->erp_product_id }}</td>
                            <td>{{ $trial->subscriptionPlan->name ?? $trial->subscription_plan_id }}</td>
                            <td>
                                <code>{{ $trial->subdomain }}</code>
                            </td>
                            <td>
                                @include('admin.trials.partials.status-badge', ['status' => $trial->status])
                            </td>
                            <td>{{ $trial->submitted_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                @if($trial->expires_at)
                                    @if($trial->expires_at->isPast())
                                        <span class="text-danger">{{ $trial->expires_at->diffForHumans() }}</span>
                                    @else
                                        {{ $trial->expires_at->diffForHumans() }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.trials.show', $trial->id) }}" 
                                       class="btn btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($trial->status == \App\Models\Trial::STATUS_WAITING_APPROVAL)
                                    <button type="button" class="btn btn-success" 
                                            onclick="showApproveModal('{{ $trial->id }}')" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" 
                                            onclick="showRejectModal('{{ $trial->id }}')" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No trials found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($trials->hasPages())
            <div class="mt-3">
                {{ $trials->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Approve Trial</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <p>Are you sure you want to approve this trial request?</p>
                    <p>This will start the provisioning process automatically.</p>
                    <div class="mb-3">
                        <label for="approve_notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" id="approve_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve Trial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject Trial</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="4" required></textarea>
                        <small class="text-muted">Minimum 10 characters</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Trial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showApproveModal(trialId) {
    document.getElementById('approveForm').action = `/admin/trials/${trialId}/approve`;
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function showRejectModal(trialId) {
    document.getElementById('rejectForm').action = `/admin/trials/${trialId}/reject`;
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endpush
