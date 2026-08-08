@extends('layouts.admin')

@section('title', 'New Email Template — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.email-templates.index') }}">Email Templates</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Create Transactional Template</h1>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('admin.email-templates.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Template Unique Key *</label>
                <input type="text" name="key" class="form-input" placeholder="e.g. license-issued" required value="{{ old('key') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Template Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Subject Line *</label>
                <input type="text" name="subject" class="form-input" required value="{{ old('subject') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Body HTML Layout</label>
                <textarea name="body" id="body" class="form-textarea tinymce" rows="10" required>{{ old('body') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">✉️ Create Template</button>
        </form>
    </div>
</div>
@endsection
