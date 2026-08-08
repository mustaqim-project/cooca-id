@extends('layouts.admin')

@section('title', 'Review Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.reviews.index') }}">Reviews</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">Review Detail</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <div class="text-warning font-bold text-xl mb-2">⭐ {{ $review->rating ?? 5 }}/5 Stars</div>
        <p class="text-sm my-4">"{{ $review->comment ?? 'Great product and excellent support.' }}"</p>
        <div class="flex gap-2 mt-4">
            <form action="{{ route('admin.reviews.approve', $review->id ?? 1) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Approve Review</button>
            </form>
            <form action="{{ route('admin.reviews.reject', $review->id ?? 1) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Reject Review</button>
            </form>
        </div>
    </div>
</div>
@endsection
