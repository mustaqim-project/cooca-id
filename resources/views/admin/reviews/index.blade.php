@extends('admin.layouts.app')

@section('title', 'Reviews Moderation')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Reviews Moderation</h2>
                <p class="text-secondary mb-0">Approve, reject, and monitor customer reviews and ratings.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-3 hover-lift text-secondary">
                    <i class="bi bi-filter me-2"></i> Filter Status
                </button>
            </div>
        </div>

        <!-- Review Summary Bento -->
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">Average Rating</div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"><i
                                class="bi bi-star-fill me-1"></i> Global</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">4.8</h3>
                        <span class="text-secondary fs-8">out of 5</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">Pending Moderation</div>
                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1"><i
                                class="bi bi-hourglass-split me-1"></i> Waitlist</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">12</h3>
                        <span class="text-secondary fs-8">reviews</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">Total Reviews</div>
                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1"><i
                                class="bi bi-bar-chart me-1"></i> All Time</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">2,450</h3>
                        <span class="text-success fs-8"><i class="bi bi-arrow-up me-1"></i>+45 this week</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="text-secondary fs-7 fw-medium">Reported Reviews</div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1"><i
                                class="bi bi-flag me-1"></i> Action Needed</span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h3 class="fw-bold mb-0 fs-2">3</h3>
                        <span class="text-secondary fs-8">flagged by users</span>
                    </div>
                </div>
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
                        placeholder="Search content or author...">
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
                            <th class="py-3 px-4 border-0">Review</th>
                            <th class="py-3 px-3 border-0">Product/Target</th>
                            <th class="py-3 px-3 border-0">Rating</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Date</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($reviews ?? [
                                (object)
    ['id' => 101, 'author' => 'Budi Santoso', 'content' => 'Sangat membantu operasional bisnis saya. Fiturnya lengkap.', 'target' => 'Cooca ERP Pro', 'rating' => 5, 'status' => 'Pending', 'created_at' => now()->subHours(2)],
                                (object)['id' => 102, 'author' => 'PT Makmur Jaya', 'content' => 'Ada bug saat generate laporan bulanan, tolong diperbaiki.', 'target' => 'Cooca Accounting Addon', 'rating' => 3, 'status' => 'Approved', 'created_at' => now()->subDays(1)],
                                (object)['id' => 103, 'author' => 'Siti Aminah', 'content' => 'Link download tidak berfungsi, saya butuh segera.', 'target' => 'Premium Theme X', 'rating' => 1, 'status' => 'Reported', 'created_at' => now()->subDays(2)],
                                (object)['id' => 104, 'author' => 'Anonim', 'content' => 'Spam content link http://spam-link.com', 'target' => 'Cooca CRM', 'rating' => 5, 'status' => 'Rejected', 'created_at' => now()->subDays(5)]
                            ] as $review)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->author) }}&background=random"
                                            class="rounded-circle" width="36" height="36" alt="Author">
                                        <div>
                                            <div class="fw-bold fs-6">{{ $review->author }}</div>
                                            <div class="text-secondary fs-7 text-truncate" style="max-width: 250px;">
                                                {{ $review->content }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge bg-light text-dark border">{{ $review->target }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="text-warning fs-7">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star text-secondary text-opacity-25"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusColors = [
                                            'Approved' => 'success',
                                            'Pending' => 'warning',
                                            'Rejected' => 'secondary',
                                            'Reported' => 'danger',
                                        ];
                                        $color = $statusColors[$review->status ?? 'Pending'] ?? 'secondary';
                                    @endphp
                                    <span
                                        class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle rounded-pill px-3 py-1">{{ $review->status }}</span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ is_object($review->created_at ?? null) ? $review->created_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.reviews.show', $review->id ?? 1) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> View Detail</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            @if ($review->status !== 'Approved')
                                                <li>
                                                    <form action="{{ route('admin.reviews.approve', $review->id ?? 1) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-2 text-success">
                                                            <i class="bi bi-check-circle me-2"></i> Approve
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if ($review->status !== 'Rejected')
                                                <li>
                                                    <form action="{{ route('admin.reviews.reject', $review->id ?? 1) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-2 text-warning">
                                                            <i class="bi bi-x-circle me-2"></i> Reject
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <form action="{{ route('admin.reviews.destroy', $review->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-star fs-1"></i></div>
                                    <h6 class="fw-medium">No Reviews Found</h6>
                                    <p class="fs-7">There are no reviews matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($reviews) && method_exists($reviews, 'hasPages') && $reviews->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
