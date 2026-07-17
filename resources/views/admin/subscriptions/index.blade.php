@extends('admin.layouts.app')

@section('title', 'Subscriptions')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Subscriptions</h2>
                <p class="text-secondary mb-0">Manage customer subscriptions and billing cycles.</p>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search subscriptions...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">ID</th>
                            <th class="py-3 px-3 border-0">Customer</th>
                            <th class="py-3 px-3 border-0">Product</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Starts At</th>
                            <th class="py-3 px-3 border-0">Ends At</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td class="py-3 px-4 text-secondary fs-7">#{{ $subscription->id }}</td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium">
                                        {{ $subscription->customer->name ?? ($subscription->user->name ?? 'N/A') }}</div>
                                    <div class="text-secondary fs-7">
                                        {{ $subscription->customer->email ?? ($subscription->user->email ?? '') }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-light text-dark border">{{ $subscription->subscriptionPlan->product->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($subscription->status == 'active')
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 text-capitalize">{{ $subscription->status }}</span>
                                    @elseif($subscription->status == 'expired')
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 text-capitalize">{{ $subscription->status }}</span>
                                    @elseif($subscription->status == 'cancelled')
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1 text-capitalize">{{ $subscription->status }}</span>
                                    @else
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 text-capitalize">{{ $subscription->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $subscription->started_at ? $subscription->started_at->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $subscription->expires_at ? $subscription->expires_at->format('d M Y') : '-' }}</td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.subscriptions.show', $subscription->id) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> View Details</a></li>
                                            <li>
                                                <button type="button" class="dropdown-item py-2 text-danger"
                                                    data-bs-toggle="modal" data-bs-target="#cancelModal{{ $loop->index }}"
                                                    {{ $subscription->status != 'active' ? 'disabled' : '' }}>
                                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-arrow-repeat fs-1"></i></div>
                                    <h6 class="fw-medium">No Subscriptions Found</h6>
                                    <p class="fs-7">No subscriptions have been created yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($subscriptions) && $subscriptions->hasPages())
                <div
                    class="card-footer bg-transparent border-top border-light p-4 d-flex justify-content-between align-items-center">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Cancel Modals --}}
    @if (isset($subscriptions))
        @foreach ($subscriptions as $subscription)
            <div class="modal fade" id="cancelModal{{ $loop->index }}" tabindex="-1">
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
                                    <strong>{{ $subscription->customer->name ?? 'N/A' }}</strong>?
                                </p>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control rounded-3" rows="3" required maxlength="500"
                                        placeholder="Why is this subscription being cancelled?"></textarea>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="immediate" value="1" class="form-check-input"
                                        id="immediate{{ $loop->index }}">
                                    <label class="form-check-label" for="immediate{{ $loop->index }}">
                                        Cancel immediately & revoke license
                                    </label>
                                    <div class="form-text text-warning fs-7">If unchecked, subscription remains active
                                        until expiry.</div>
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
        @endforeach
    @endif
@endsection
