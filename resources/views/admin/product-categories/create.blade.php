@extends('layouts.admin')

@section('title', 'Add Category — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.product-categories.index') }}">Categories</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Add Product Category</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.product-categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-input" placeholder="e.g. pos-retail" value="{{ old('slug') }}">
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">🏷️ Save Category</button>
        </form>
    </div>
</div>
@endsection
