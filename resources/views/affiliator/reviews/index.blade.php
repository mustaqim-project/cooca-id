@extends('affiliator.layouts.app')

@section('title', 'Referral Reviews')

@section('breadcrumb')
    <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">Dashboard</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Customer Reviews</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="page-title">Referral Customer Reviews</div>
            <div class="page-subtitle">Reviews & ratings submitted by customers you referred.</div>
        </div>
        <div class="page-actions">
            <a href="{{ route('affiliator.reviews.my_reviews') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-user-pen"></i> My Own Reviews
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
                            <th>Customer</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews ?? [] as $review)
                            <tr>
                                <td>
                                    <div class="font-semibold text-sm">{{ $review->title ?? 'Customer Review' }}</div>
                                    <div class="text-xs text-muted">
                                        {{ Str::limit($review->content ?? ($review->comment ?? ''), 60) }}</div>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                            {{ strtoupper(substr($review->customer->name ?? 'C', 0, 2)) }}
                                        </div>
                                        <span class="text-sm">{{ $review->customer->name ?? 'Customer' }}</span>
                                    </div>
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
                                    <i class="fa-solid fa-star-half-stroke"
                                        style="font-size:28px;margin-bottom:8px;display:block;color:var(--text-faint);"></i>
                                    No customer reviews submitted yet.
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
