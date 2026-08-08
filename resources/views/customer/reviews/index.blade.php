@extends('layouts.customer')
@section('title', 'My Reviews')
@section('breadcrumb')
    <span class="crumb-current">My Reviews</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-star" style="color:var(--warning);margin-right:10px;"></i>My Product Reviews</h1>
        <p class="page-subtitle">Your ratings and feedback on COOCA.ID software products.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Review Title</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews ?? [] as $review)
                    <tr>
                        <td class="font-bold">{{ $review->product?->name ?? 'Product' }}</td>
                        <td>
                            <div style="color:var(--warning);font-size:13px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <div class="font-semibold text-sm">{{ $review->title ?? 'Review' }}</div>
                            <div class="text-xs text-muted" style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $review->comment }}
                            </div>
                        </td>
                        <td class="text-xs text-muted">{{ $review->created_at->format('d M Y') }}</td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-warning">Pending Moderation</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">⭐</div>
                                <div class="empty-state-title">No Reviews Submitted Yet</div>
                                <div class="empty-state-text">You haven't submitted any reviews for your subscribed products yet.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
