@extends('affiliator.layouts.app')

@section('title', 'Referral Reviews')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Referral Reviews</h2>
                <p class="text-secondary mb-0">View reviews submitted by your referred customers.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('affiliator.reviews.my_reviews') }}" class="btn btn-light border rounded-pill px-3 hover-lift fw-medium">
                    <i class="bi bi-star me-1"></i> My Reviews
                </a>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search reviews...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0" style="width: 80px;">ID</th>
                            <th class="py-3 px-3 border-0">Review Title / Customer</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Date</th>
                            <th class="py-3 px-4 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($reviews ?? [] as $review)
                            <tr>
                                <td class="py-3 px-4 fw-medium text-secondary">
                                    #{{ $review->id }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-semibold text-dark">{{ $review->title ?? 'No Title' }}</div>
                                    <div class="text-secondary fs-7"><i class="bi bi-person me-1"></i> {{ $review->customer->name ?? 'Unknown Customer' }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusClass = match($review->status ?? 'pending') {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        {{ ucfirst($review->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $review->created_at ? $review->created_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill">View Only</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-chat-left-text fs-1"></i></div>
                                    <h6 class="fw-medium">No Referral Reviews Found</h6>
                                    <p class="fs-7 mb-0">No reviews found from your referred customers.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($reviews ?? [], 'hasPages') && ($reviews ?? [])->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
