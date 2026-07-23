@extends('customer.layouts.app')

@section('title', 'My Subscriptions')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">My Subscriptions</h2>
                <p class="text-secondary mb-0">Manage your active and past subscriptions.</p>
            </div>
            <div class="d-flex gap-2">
            </div>
        </div>

        <!-- Subscriptions Table -->
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
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Product / Plan</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Started At</th>
                            <th class="py-3 px-3 border-0">Expires At</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $subscription->plan->product->name ?? 'Unknown Product' }}</div>
                                            <div class="text-secondary fs-7">{{ $subscription->plan->name ?? 'Unknown Plan' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @if($subscription->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Active</span>
                                    @elseif($subscription->is_cancelled)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">Cancelled</span>
                                    @elseif($subscription->expires_at && $subscription->expires_at->isPast())
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">Expired</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Pending</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $subscription->started_at ? $subscription->started_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y') : 'Lifetime' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('customer.subscriptions.show', $subscription->id) }}"
                                        class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                                        View Details <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                    <h6 class="fw-medium">No Subscriptions Found</h6>
                                    <p class="fs-7">You don't have any subscriptions yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if (method_exists($subscriptions, 'hasPages') && $subscriptions->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
