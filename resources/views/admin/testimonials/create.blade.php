@extends('layouts.admin')

@section('title', 'Add Testimonial — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.testimonials.index') }}">Testimonials</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Add Client Testimonial</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.testimonials.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Client Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" name="company" class="form-input" value="{{ old('company') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Role / Position</label>
                <input type="text" name="position" class="form-input" placeholder="e.g. CEO, Store Manager" value="{{ old('position') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Testimonial Quote *</label>
                <textarea name="quote" class="form-textarea" rows="4" required>{{ old('quote') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💬 Save Testimonial</button>
        </form>
    </div>
</div>
@endsection
