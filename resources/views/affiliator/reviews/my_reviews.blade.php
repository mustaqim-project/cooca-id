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
<div class="portal-card mb-6">
    <div class="portal-card-header" style="flex-wrap:wrap;gap:12px;">
        <div>
            <div class="portal-card-title">
                <i class="fa-solid fa-user-pen" style="color:var(--primary);"></i>
                My Testimonials & Product Reviews
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Reviews and feedback written by you.</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('affiliator.reviews.index') }}" class="btn btn-s btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Referral Reviews
            </a>
        </div>
    </div>

    <div class="portal-card-body p-0">
        <div class="table-wrap">
            <table class="portal-table">
                <thead>
                    <tr>
                        <th class="portal-th">Review Title</th>
                        <th class="portal-th">Product</th>
                        <th class="portal-th text-center">Status</th>
                        <th class="portal-th text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews ?? [] as $review)
                        <tr>
                            <td class="portal-td font-medium">
                                <div style="font-weight:600;">{{ $review->title ?? 'Review' }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ Str::limit($review->content ?? $review->comment ?? '', 60) }}</div>
                            </td>
                            <td class="portal-td">
                                {{ $review->product->name ?? 'COOCA ERP' }}
                            </td>
                            <td class="portal-td text-center">
                                @php
                                    $st = strtolower($review->status ?? 'pending');
                                    $badgeClass = match($st) {
                                        'approved' => 'status-paid',
                                        'pending'  => 'status-pending',
                                        'rejected' => 'status-cancelled',
                                        default    => 'status-issued',
                                    };
                                @endphp
                                <span class="badge-status {{ $badgeClass }}">
                                    {{ ucfirst($st) }}
                                </span>
                            </td>
                            <td class="portal-td text-right text-muted" style="font-size:12px;">
                                {{ $review->created_at ? $review->created_at->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="portal-td text-center py-6 text-muted">
                                <i class="fa-solid fa-pen-nib" style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
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
