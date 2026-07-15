@extends('layouts.admin')

@section('title', 'Review Details')
@section('subtitle', 'Moderate customer review')

@section('content')
    <div class="mb-4 d-flex align-items-center gap-2">
        <a href="{{ route('admin.reviews.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Reviews
        </a>
    </div>

    <div class="row g-4">
        {{-- Main --}}
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <span class="card-saas-title">Review Content</span>
                    @php
                        $badge = match ($review->status) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'neutral',
                        };
                    @endphp
                    <span class="badge-saas badge-saas-{{ $badge }}">{{ ucfirst($review->status) }}</span>
                </div>
                <div class="card-saas-body">
                    {{-- Rating --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="d-flex gap-1" style="color:#f59e0b;font-size:1.2rem">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="fw-semibold">{{ $review->rating }} / 5</span>
                    </div>

                    {{-- Title --}}
                    <h5 class="fw-bold mb-2">{{ $review->title ?? 'No Title' }}</h5>

                    {{-- Body --}}
                    <div class="p-3 rounded-3 mb-4"
                        style="background:var(--surface-raised);border:1px solid var(--border);font-size:0.9rem;line-height:1.7">
                        {!! nl2br(e($review->comment)) !!}
                    </div>

                    {{-- Images --}}
                    @if (!empty($review->images) && count($review->images) > 0)
                        <div class="mb-4">
                            <div class="text-muted fw-medium mb-2" style="font-size:0.85rem">Attached Images</div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($review->images as $image)
                                    <img src="{{ $image }}" alt="Review image" class="rounded-3"
                                        style="width:96px;height:96px;object-fit:cover;border:1px solid var(--border)">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Rejection reason --}}
                    @if ($review->status === 'rejected' && $review->rejection_reason)
                        <div class="p-3 rounded-3" style="background:#fef2f2;border:1px solid #fecaca">
                            <div class="fw-semibold mb-1" style="color:#b91c1c;font-size:0.85rem">
                                <i class="bi bi-exclamation-triangle me-1"></i> Rejection Reason
                            </div>
                            <div style="color:#7f1d1d;font-size:0.85rem">{{ $review->rejection_reason }}</div>
                            @if ($review->rejected_at)
                                <div class="text-muted mt-1" style="font-size:0.78rem">Rejected:
                                    {{ $review->rejected_at->format('d M Y H:i') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Actions --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header"><span class="card-saas-title">Actions</span></div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    @if (in_array($review->status, ['pending', 'rejected']))
                        <form class="form-confirm-submit" action="{{ route('admin.reviews.approve', $review->id) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Approve Review
                            </button>
                        </form>
                    @endif

                    @if (in_array($review->status, ['pending', 'approved']))
                        <button type="button" onclick="rejectReview()" class="btn-saas btn-saas-outline w-100"
                            style="color:var(--warning);border-color:var(--warning)">
                            <i class="bi bi-x-lg me-1"></i> Reject Review
                        </button>
                    @endif

                    <form class="form-confirm-delete" action="{{ route('admin.reviews.destroy', $review->id) }}"
                        method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-saas btn-saas-danger w-100">
                            <i class="bi bi-trash3 me-1"></i> Delete Review
                        </button>
                    </form>
                </div>
            </div>

            {{-- Author --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header"><span class="card-saas-title">Author</span></div>
                <div class="card-saas-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="stat-card-icon blue" style="width:44px;height:44px;font-size:1rem;flex-shrink:0">
                            {{ strtoupper(substr($review->customer->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $review->customer->name ?? 'Unknown' }}</div>
                            <div class="text-muted" style="font-size:0.82rem">{{ $review->customer->email ?? '' }}</div>
                        </div>
                    </div>
                    @if ($review->customer)
                        <a href="{{ route('admin.customers.show', $review->customer->id) }}"
                            class="btn-saas btn-saas-ghost btn-saas-sm">
                            View Profile <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Product --}}
            <div class="card-saas mb-4">
                <div class="card-saas-header"><span class="card-saas-title">Reviewed Product</span></div>
                <div class="card-saas-body">
                    @if ($review->product)
                        <div class="fw-medium mb-1">{{ $review->product->name }}</div>
                        <div class="text-muted" style="font-size:0.82rem">
                            {{ Str::limit($review->product->description, 100) }}</div>
                        <div class="mt-3">
                            <a href="{{ route('admin.products.show', $review->product->id) }}"
                                class="btn-saas btn-saas-ghost btn-saas-sm">
                                View Product <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <span class="text-muted" style="font-size:0.85rem">Product deleted or unavailable.</span>
                    @endif
                </div>
            </div>

            {{-- Metadata --}}
            <div class="card-saas">
                <div class="card-saas-header"><span class="card-saas-title">Metadata</span></div>
                <div class="card-saas-body">
                    <table class="table table-sm table-borderless mb-0" style="font-size:0.85rem">
                        <tbody>
                            <tr>
                                <td class="text-muted">Submitted</td>
                                <td class="fw-medium">{{ $review->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @if ($review->approved_at)
                                <tr>
                                    <td class="text-muted">Approved</td>
                                    <td class="fw-medium">{{ $review->approved_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Verified Purchase</td>
                                <td class="fw-medium"
                                    style="color:{{ $review->is_verified_purchase ? 'var(--success)' : 'inherit' }}">
                                    {{ $review->is_verified_purchase ? 'Yes' : 'No' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden reject form --}}
    <form class="form-confirm-submit" id="reject-form" action="{{ route('admin.reviews.reject', $review->id) }}"
        method="POST" style="display:none">
        @csrf
        <input type="hidden" name="rejection_reason" id="rejection_reason">
    </form>
@endsection

@push('scripts')
    <script>
        function rejectReview() {
            Swal.fire({
                title: 'Reject Review',
                text: 'Please provide a reason for rejecting this review.',
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Rejection Reason (internal)',
                inputPlaceholder: 'Inappropriate language, spam, etc.',
                inputValidator: (v) => {
                    if (!v) return 'Reason is required.';
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejection_reason').value = result.value;
                    document.getElementById('reject-form').submit();
                }
            });
        }
    </script>
@endpush
