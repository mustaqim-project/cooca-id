@extends('affiliator.layouts.app')

@section('title', 'My Reviews')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <a href="{{ route('affiliator.reviews.index') }}" class="crumb-link">Reviews</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">My Reviews</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">My Testimonials & Product Reviews</div>
            <div class="page-subtitle">Reviews and feedback written by you.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.reviews.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Referral Reviews
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Review Title</th>
                            <th>Product</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews ?? [] as $review)
                            <tr>
                                <td>
                                    <div class="font-semibold text-sm">{{ $review->title ?? 'Review' }}</div>
                                    <div class="text-xs text-muted">
                                        {{ Str::limit($review->content ?? ($review->comment ?? ''), 60) }}</div>
                                </td>
                                <td class="text-sm">
                                    {{ $review->product->name ?? 'COOCA ERP' }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $st = strtolower($review->status ?? 'pending');
                                        $badgeClass = match ($st) {
                                            'approved' => 'badge-success',
                                            'pending' => 'badge-warning',
                                            'rejected' => 'badge-danger',
                                            default => 'badge-muted',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                                <td class="text-right text-muted text-xs">
                                    {{ $review->created_at ? $review->created_at->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="padding:28px;">
                                    <i class="fa-solid fa-pen-nib"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                    You haven't submitted any reviews yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($reviews ?? [], 'hasPages') && ($reviews ?? [])->hasPages())
                <div style="padding:16px;border-top:1px solid var(--border);">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
