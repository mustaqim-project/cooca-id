@extends('admin.layouts.app')

@section('title', 'License Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.licenses.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">License Details</h2>
                    <p class="text-secondary mb-0">View full information and activation status.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                @if ($license->status === 'active')
                    <button type="button"
                        class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-warning"
                        data-bs-toggle="modal" data-bs-target="#revokeModal">
                        <i class="bi bi-slash-circle me-2"></i> Revoke License
                    </button>
                @elseif($license->status !== 'revoked')
                    <form action="{{ route('admin.licenses.activate', $license->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-success">
                            <i class="bi bi-check-circle me-2"></i> Activate License
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-key fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $license->product->name ?? 'Unknown Product' }}</h4>
                    <p class="text-secondary mb-3">ID: #{{ Str::limit($license->id, 8) }}</p>
                    <div>
                        @switch($license->status)
                            @case('active')
                                <span
                                    class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Active</span>
                            @break

                            @case('expired')
                                <span
                                    class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 border border-warning-subtle">Expired</span>
                            @break

                            @case('revoked')
                                <span
                                    class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 border border-danger-subtle">Revoked</span>
                            @break

                            @default
                                <span
                                    class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border border-secondary-subtle">Inactive</span>
                        @endswitch
                        @if ($license->is_trial)
                            <span
                                class="badge bg-info-subtle text-info rounded-pill px-3 py-2 border border-info-subtle ms-1">Trial</span>
                        @endif
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">License Code</span>
                        <span class="fw-medium fs-7"><code class="text-primary">{{ $license->license_code }}</code></span>
                    </div>
                    @if ($license->domain)
                        <div class="d-flex justify-content-between text-start mb-3">
                            <span class="text-secondary fs-7">Domain</span>
                            <span class="fw-medium fs-7"><code>{{ $license->domain }}</code></span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Activated At</span>
                        <span class="fw-medium fs-7">{{ $license->activated_at?->format('d M Y, H:i') ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Expires At</span>
                        <span class="fw-medium fs-7">{{ $license->expires_at?->format('d M Y, H:i') ?? 'Lifetime' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span class="fw-medium fs-7">{{ $license->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span class="fw-medium fs-7">{{ $license->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="d-flex flex-column gap-4">
                    <!-- Customer & Product -->
                    <div class="card border-0 shadow-sm rounded-4 glass">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0">Detailed Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="text-secondary fs-7 mb-1 d-block">Customer</label>
                                    <div class="fw-medium">{{ $license->customer->name ?? 'N/A' }}</div>
                                    <div class="text-secondary fs-7">{{ $license->customer->email ?? '' }}</div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-secondary fs-7 mb-1 d-block">Product</label>
                                    <div class="fw-medium">{{ $license->product->name ?? 'N/A' }}</div>
                                    @if ($license->product)
                                        <a href="{{ route('admin.products.show', $license->product->id) }}"
                                            class="text-primary fs-7 text-decoration-none">View Product <i
                                                class="bi bi-arrow-right"></i></a>
                                    @endif
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-secondary fs-7 mb-1 d-block">Subscription Plan</label>
                                    <div class="fw-medium">{{ $license->subscriptionPlan->name ?? 'N/A' }}</div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="text-secondary fs-7 mb-1 d-block">Domain</label>
                                    <div class="fw-medium">{{ $license->domain ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revocation Info (if revoked) -->
                    @if ($license->status === 'revoked')
                        <div class="card border-0 shadow-sm rounded-4 glass border-start border-danger border-3">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="fw-bold mb-0 text-danger"><i
                                        class="bi bi-exclamation-triangle me-2"></i>Revocation Details</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Revoked At</label>
                                        <div class="fw-medium">{{ $license->revoked_at?->format('d M Y, H:i') ?? '—' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Revoked By</label>
                                        <div class="fw-medium">{{ $license->revokedBy->name ?? 'System' }}</div>
                                    </div>
                                    @if ($license->revocation_category)
                                        <div class="col-sm-6">
                                            <label class="text-secondary fs-7 mb-1 d-block">Category</label>
                                            <div class="fw-medium text-capitalize">
                                                {{ str_replace('_', ' ', $license->revocation_category) }}</div>
                                        </div>
                                    @endif
                                    @if ($license->revocation_reason)
                                        <div class="col-12">
                                            <label class="text-secondary fs-7 mb-1 d-block">Reason</label>
                                            <div class="fw-medium">{{ $license->revocation_reason }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Appeals -->
                    @if ($license->appeals && $license->appeals->count() > 0)
                        <div class="card border-0 shadow-sm rounded-4 glass">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="fw-bold mb-0"><i class="bi bi-chat-left-text me-2"></i>License Appeals</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-secondary text-uppercase fs-7">
                                            <tr>
                                                <th class="py-3 px-4 border-0">Date</th>
                                                <th class="py-3 px-3 border-0">Reason</th>
                                                <th class="py-3 px-3 border-0">Status</th>
                                                <th class="py-3 px-4 border-0 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($license->appeals as $appeal)
                                                <tr>
                                                    <td class="py-3 px-4 fs-7">
                                                        {{ $appeal->created_at->format('d M Y, H:i') }}</td>
                                                    <td class="py-3 px-3 fs-7">{{ Str::limit($appeal->reason, 80) }}</td>
                                                    <td class="py-3 px-3">
                                                        @if ($appeal->status === 'approved')
                                                            <span
                                                                class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Approved</span>
                                                        @elseif($appeal->status === 'rejected')
                                                            <span
                                                                class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">Rejected</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 text-end">
                                                        @if ($appeal->status === 'pending')
                                                            <form
                                                                action="{{ route('admin.licenses.appeals.approve', [$license->id, $appeal->id]) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-success rounded-pill px-3">Approve</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('admin.licenses.appeals.reject', [$license->id, $appeal->id]) }}"
                                                                method="POST" class="d-inline ms-1">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3">Reject</button>
                                                            </form>
                                                        @else
                                                            <span
                                                                class="text-muted fs-7">{{ $appeal->reviewed_at?->format('d M Y') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Revoke Modal -->
    @if ($license->status === 'active')
        <div class="modal fade" id="revokeModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow">
                    <form action="{{ route('admin.licenses.revoke', $license->id) }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Revoke License</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-secondary mb-3">Revoking license <code>{{ $license->license_code }}</code> for
                                <strong>{{ $license->customer->name ?? 'N/A' }}</strong>.</p>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select rounded-3" required>
                                    <option value="">Select reason category</option>
                                    <option value="violation">Terms Violation</option>
                                    <option value="fraud">Fraud / Abuse</option>
                                    <option value="non_payment">Non-Payment</option>
                                    <option value="customer_request">Customer Request</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Reason (optional)</label>
                                <textarea name="reason" class="form-control rounded-3" rows="3" maxlength="500"
                                    placeholder="Describe the reason..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Revoke License</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
