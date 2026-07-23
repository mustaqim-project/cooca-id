@extends('customer.layouts.app')

@section('title', 'Trial Details')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.trials.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Trial Details</h2>
                    <p class="text-secondary mb-0">View trial status and information.</p>
                </div>
            </div>

            <!-- Details Card -->
            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <!-- Status Header -->
                <div class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i> Request Information</h5>
                    
                    <div>
                        @php
                            $statusConfig = [
                                'submitted' => 'warning',
                                'reviewing' => 'info',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'provisioning' => 'primary',
                                'trial_active' => 'success',
                                'trial_expired' => 'secondary',
                            ];
                            $statusClass = $statusConfig[$trial->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-4 py-2 fs-6">
                            {{ str_replace('_', ' ', ucfirst($trial->status)) }}
                        </span>
                    </div>
                </div>
                
                <!-- Details Body -->
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush border-0">
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Product</div>
                            <div class="fw-semibold text-dark">{{ $trial->product->name ?? 'N/A' }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-light border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Requested Subdomain</div>
                            <div class="fw-semibold text-primary">{{ $trial->requested_subdomain }}.cooca.id</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Request Date</div>
                            <div class="text-dark">{{ $trial->created_at->format('F d, Y - H:i') }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-light border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Last Updated</div>
                            <div class="text-dark">{{ $trial->updated_at->format('F d, Y - H:i') }}</div>
                        </li>
                        @if ($trial->notes)
                            <li class="list-group-item px-4 py-4 bg-transparent border-light">
                                <div class="fw-medium text-secondary mb-2">Notes</div>
                                <div class="bg-light p-3 rounded-3 text-secondary">{{ $trial->notes }}</div>
                            </li>
                        @endif
                        @if ($trial->rejection_reason)
                            <li class="list-group-item px-4 py-4 bg-transparent border-light">
                                <div class="fw-medium text-danger mb-2">Rejection Reason</div>
                                <div class="bg-danger-subtle text-danger p-3 rounded-3">{{ $trial->rejection_reason }}</div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
