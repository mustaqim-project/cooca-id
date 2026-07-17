@extends('admin.layouts.app')

@section('title', 'Subscriptions Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.subscriptions.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Subscription Details</h2>
                    <p class="text-secondary mb-0">View full information and billing cycle.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#cancelSubModal" {{ $subscription->status != 'active' ? 'disabled' : '' }}>
                    <i class="bi bi-x-circle me-2"></i> Cancel Subscription
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-arrow-repeat fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $subscription->subscriptionPlan->product->name ?? 'Unknown Product' }}</h4>
                    <p class="text-secondary mb-3">ID: #{{ $subscription->id }}</p>
                    <div>
                        @if ($subscription->status == 'active')
                            <span
                                class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle text-capitalize">{{ $subscription->status }}</span>
                        @elseif($subscription->status == 'expired')
                            <span
                                class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 border border-danger-subtle text-capitalize">{{ $subscription->status }}</span>
                        @elseif($subscription->status == 'cancelled')
                            <span
                                class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border border-secondary-subtle text-capitalize">{{ $subscription->status }}</span>
                        @else
                            <span
                                class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 border border-warning-subtle text-capitalize">{{ $subscription->status }}</span>
                        @endif
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Started At</span>
                        <span
                            class="fw-medium fs-7">{{ $subscription->started_at ? $subscription->started_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Expires At</span>
                        <span
                            class="fw-medium fs-7">{{ $subscription->expires_at ? $subscription->expires_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span class="fw-medium fs-7">{{ $subscription->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span class="fw-medium fs-7">{{ $subscription->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Detailed Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Assigned Customer / User</label>
                                <div class="fw-medium">
                                    {{ $subscription->customer->name ?? ($subscription->user->name ?? 'N/A') }}</div>
                                <div class="text-secondary fs-7">
                                    {{ $subscription->customer->email ?? ($subscription->user->email ?? '') }}</div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Associated Product</label>
                                <div class="fw-medium">{{ $subscription->subscriptionPlan->product->name ?? 'N/A' }}</div>
                                @if ($subscription->subscriptionPlan?->product)
                                    <a href="{{ route('admin.products.edit', $subscription->subscriptionPlan->product->id) }}"
                                        class="text-primary fs-7 text-decoration-none">View Product <i
                                            class="bi bi-arrow-right"></i></a>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Associated License</label>
                                <div class="fw-medium">
                                    @if ($subscription->license_id)
                                        <a href="{{ route('admin.licenses.show', $subscription->license_id) }}"
                                            class="text-primary text-decoration-none">License
                                            #{{ $subscription->license_id }}</a>
                                    @else
                                        <span class="text-secondary">N/A</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Associated Transaction</label>
                                <div class="fw-medium">
                                    @if ($subscription->transaction_id)
                                        <span class="text-primary">#{{ $subscription->transaction_id }}</span>
                                    @else
                                        <span class="text-secondary">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel Modal --}}
    <div class="modal fade" id="cancelSubModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Cancel Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-secondary mb-3">Cancel subscription for
                            <strong>{{ $subscription->customer->name ?? 'N/A' }}</strong>
                            ({{ $subscription->subscriptionPlan->product->name ?? 'Unknown' }})?
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control rounded-3" rows="3" required maxlength="500"
                                placeholder="Why is this subscription being cancelled?"></textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="immediate" value="1" class="form-check-input"
                                id="immediateCancel">
                            <label class="form-check-label" for="immediateCancel">
                                Cancel immediately & revoke license
                            </label>
                            <div class="form-text text-warning fs-7">If unchecked, subscription remains active until
                                expiry.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Confirm Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
