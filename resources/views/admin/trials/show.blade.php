@extends('admin.layouts.app')

@section('title', 'Trial Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Trial Details</h1>
                <div>
                    <a href="{{ route('admin.trials.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
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

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Trial Information</h6>
                    @include('admin.trials.partials.status-badge', ['status' => $trial->status])
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Customer</label>
                            <div class="fw-bold">{{ $trial->customer->user->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $trial->customer->user->email ?? '' }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Subdomain</label>
                            <div class="fw-bold"><code>{{ $trial->subdomain }}</code></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Product</label>
                            <div class="fw-bold">{{ $trial->erpProduct->name ?? $trial->erp_product_id }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Subscription Plan</label>
                            <div class="fw-bold">{{ $trial->subscriptionPlan->name ?? $trial->subscription_plan_id }}</div>
                        </div>
                    </div>

                    @if($trial->affiliateCode)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="text-muted small">Affiliate</label>
                            <div class="fw-bold">
                                {{ $trial->affiliateCode->affiliator->user->name ?? 'N/A' }}
                                <span class="badge bg-info">{{ $trial->affiliateCode->code ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Submitted At</label>
                            <div>{{ $trial->submitted_at?->format('d M Y H:i:s') ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Approved At</label>
                            <div>{{ $trial->approved_at?->format('d M Y H:i:s') ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Started At</label>
                            <div>{{ $trial->started_at?->format('d M Y H:i:s') ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Expires At</label>
                            <div class="{{ $trial->expires_at?->isPast() ? 'text-danger fw-bold' : '' }}">
                                {{ $trial->expires_at?->format('d M Y H:i:s') ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Converted At</label>
                            <div>{{ $trial->converted_at?->format('d M Y H:i:s') ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Subscription ID</label>
                            <div><code>{{ $trial->subscription_id ?? '-' }}</code></div>
                        </div>
                    </div>

                    @if($trial->rejection_reason)
                    <div class="alert alert-danger mt-3">
                        <strong>Rejection Reason:</strong>
                        <p class="mb-0">{{ $trial->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status History</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($trial->statusHistory as $history)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="badge bg-{{ $history->status == \App\Models\Trial::STATUS_ACTIVE_TRIAL ? 'success' : 'primary' }}">
                                        {{ $history->status }}
                                    </span>
                                    <span class="ms-2 text-muted small">{{ $history->created_at->diffForHumans() }}</span>
                                </div>
                                <small class="text-muted">
                                    @if($history->actor)
                                        {{ $history->actor->name ?? 'System' }}
                                    @else
                                        System
                                    @endif
                                </small>
                            </div>
                            <p class="mt-2 mb-0">{{ $history->notes }}</p>
                        </div>
                        @empty
                        <p class="text-muted text-center">No status history available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if($trial->status == \App\Models\Trial::STATUS_WAITING_APPROVAL)
                    <div class="d-grid gap-2 mb-3">
                        <button class="btn btn-success" onclick="showApproveModal()">
                            <i class="fas fa-check"></i> Approve Trial
                        </button>
                        <button class="btn btn-danger" onclick="showRejectModal()">
                            <i class="fas fa-times"></i> Reject Trial
                        </button>
                    </div>
                    @endif

                    @if(in_array($trial->status, [\App\Models\Trial::STATUS_PROVISIONING]))
                    <div class="d-grid gap-2 mb-3">
                        <form action="{{ route('admin.trials.mark-domain-setup', $trial->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-globe"></i> Mark Domain Setup
                            </button>
                        </form>
                    </div>
                    @endif

                    @if(in_array($trial->status, [\App\Models\Trial::STATUS_PROVISIONING, \App\Models\Trial::STATUS_DOMAIN_SETUP]))
                    <div class="d-grid gap-2 mb-3">
                        <form action="{{ route('admin.trials.mark-testing', $trial->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-vial"></i> Mark Testing
                            </button>
                        </form>
                    </div>
                    @endif

                    @if(in_array($trial->status, [\App\Models\Trial::STATUS_PROVISIONING, \App\Models\Trial::STATUS_DOMAIN_SETUP, \App\Models\Trial::STATUS_TESTING]))
                    <div class="d-grid gap-2">
                        <button class="btn btn-warning" onclick="showStartTrialModal()">
                            <i class="fas fa-play"></i> Start Trial Period
                        </button>
                    </div>
                    @endif

                    <hr>

                    <h6 class="small text-muted">Quick Info</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Status:</strong>
                            @include('admin.trials.partials.status-badge', ['status' => $trial->status])
                        </li>
                        @if($trial->expires_at)
                        <li class="mb-2">
                            <strong>Time Remaining:</strong>
                            @if($trial->expires_at->isPast())
                                <span class="text-danger">Expired {{ $trial->expires_at->diffForHumans() }}</span>
                            @elseif($trial->status == \App\Models\Trial::STATUS_ACTIVE_TRIAL)
                                <span class="text-success">{{ $trial->expires_at->diffForHumans() }}</span>
                            @else
                                <span class="text-muted">Not started</span>
                            @endif
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
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
            <form id="approveForm" action="{{ route('admin.trials.approve', $trial->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to approve this trial request?</p>
                    <p>This will start the provisioning process automatically.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Trial</button>
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
            <form id="rejectForm" action="{{ route('admin.trials.reject', $trial->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" rows="4" required></textarea>
                        <small class="text-muted">Minimum 10 characters</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Trial</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Start Trial Modal -->
<div class="modal fade" id="startTrialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Start Trial Period</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="startTrialForm" action="{{ route('admin.trials.start-trial', $trial->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="duration_days" class="form-label">Duration (Days)</label>
                        <input type="number" class="form-control" name="duration_days" value="14" min="1" max="30">
                        <small class="text-muted">Default: 14 days</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Start Trial</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApproveModal() {
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function showRejectModal() {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

function showStartTrialModal() {
    const modal = new bootstrap.Modal(document.getElementById('startTrialModal'));
    modal.show();
}
</script>
@endpush

@endsection
