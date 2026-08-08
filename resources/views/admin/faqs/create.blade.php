@extends('layouts.admin')

@section('title', 'Add FAQ — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.faqs.index') }}">FAQs</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Add New FAQ</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-input" placeholder="General, Pricing, Support" value="{{ old('category', 'General') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Question *</label>
                <input type="text" name="question" class="form-input" required value="{{ old('question') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Answer *</label>
                <textarea name="answer" class="form-textarea" rows="4" required>{{ old('answer') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">❓ Save FAQ</button>
        </form>
    </div>
</div>
@endsection
