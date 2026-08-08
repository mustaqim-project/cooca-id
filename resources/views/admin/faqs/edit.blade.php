@extends('layouts.admin')

@section('title', 'Edit FAQ — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.faqs.index') }}">FAQs</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit FAQ</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.faqs.update', $faq->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-input" value="{{ old('category', $faq->category ?? 'General') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Question *</label>
                <input type="text" name="question" class="form-input" required value="{{ old('question', $faq->question ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Answer *</label>
                <textarea name="answer" class="form-textarea" rows="4" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection
