@extends('layouts.admin')

@section('title', 'Edit Category — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.product-categories.index') }}">Categories</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Product Category</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.product-categories.update', $category->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $category->name ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-input" value="{{ old('slug', $category->slug ?? '') }}">
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection
