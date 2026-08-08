@extends('layouts.admin')

@section('title', 'Edit Page — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.cms.pages.index') }}">CMS Pages</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Custom Page</h1>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('admin.cms.pages.update', $page->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Page Title *</label>
                <input type="text" name="title" class="form-input" required value="{{ old('title', $page->title ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">URL Slug</label>
                <input type="text" name="slug" class="form-input" value="{{ old('slug', $page->slug ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Content (HTML / Blade Markdown)</label>
                <textarea name="content" id="content" class="form-textarea tinymce" rows="12" required>{{ old('content', $page->content ?? '') }}</textarea>
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }}>
                    <span style="font-weight: 500;">Publish page</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection
