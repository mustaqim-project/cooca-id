@extends('layouts.admin')

@section('title', 'Category Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.product-categories.index') }}">Categories</a>
            <span>/</span>
            <span>Show</span>
        </div>
        <h1 class="page-title">{{ $category->name ?? 'Category Detail' }}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="font-bold text-lg mb-2">Category: {{ $category->name ?? '' }}</div>
        <div class="text-sm text-muted">Slug: /{{ $category->slug ?? '' }}</div>
    </div>
</div>
@endsection
