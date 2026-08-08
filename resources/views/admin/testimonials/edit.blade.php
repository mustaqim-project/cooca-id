@extends('layouts.admin')

@section('title', 'Edit Testimonial — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.testimonials.index') }}">Testimonials</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Testimonial</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.testimonials.update', $testimonial->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Client Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $testimonial->name ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" name="company" class="form-input" value="{{ old('company', $testimonial->company ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Role / Position</label>
                <input type="text" name="position" class="form-input" value="{{ old('position', $testimonial->position ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Testimonial Quote *</label>
                <textarea name="quote" class="form-textarea" rows="4" required>{{ old('quote', $testimonial->quote ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection
