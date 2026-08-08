@extends('layouts.admin')

@section('title', 'Edit Template — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.email-templates.index') }}">Templates</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Email Template</h1>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('admin.email-templates.update', $template->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Template Unique Key *</label>
                <input type="text" name="key" class="form-input" required value="{{ old('key', $template->key ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Template Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $template->name ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Subject Line *</label>
                <input type="text" name="subject" class="form-input" required value="{{ old('subject', $template->subject ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Body HTML Layout</label>
                <textarea name="body" id="body" class="form-textarea tinymce" rows="10" required>{{ old('body', $template->body ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection
