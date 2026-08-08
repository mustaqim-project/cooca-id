@extends('layouts.admin')

@section('title', 'Customer Reviews — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Reviews</span>
        </div>
        <h1 class="page-title">Customer Reviews & Moderation</h1>
        <p class="page-subtitle">Approve or reject customer feedback, star ratings, and testimonial submissions.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Comment Review</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews ?? [] as $rev)
                        @php $rObj = is_array($rev) ? (object)$rev : $rev; @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-sm">{{ $rObj->customer->name ?? 'User' }}</div>
                            </td>
                            <td><span class="text-warning font-bold">⭐ {{ $rObj->rating ?? 5 }}/5</span></td>
                            <td class="text-xs">{{ Str::limit($rObj->comment ?? 'Great product!', 80) }}</td>
                            <td>
                                @if(($rObj->is_approved ?? true))
                                    <span class="badge badge-success">APPROVED</span>
                                @else
                                    <span class="badge badge-warning">PENDING</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.reviews.show', $rObj->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No reviews submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
