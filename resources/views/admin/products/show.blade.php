@extends('layouts.admin')

@section('title', 'Product Detail — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">{{ $product->name ?? 'Product Detail' }}</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary"><i class="fa-solid fa-pen-to-square mr-1"></i> Edit Product</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h2 class="text-xl font-bold text-primary mb-2">{{ $product->name ?? '' }}</h2>
        <div class="text-sm text-muted mb-4">{{ $product->short_description ?? '' }}</div>
        <div class="font-bold text-lg">Base Price: Rp {{ number_format($product->base_price ?? $product->price ?? 0, 0, ',', '.') }}</div>
    </div>
</div>
@endsection
