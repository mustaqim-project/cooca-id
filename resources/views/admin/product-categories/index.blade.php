@extends('layouts.admin')

@section('title', 'Product Categories — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Categories</span>
        </div>
        <h1 class="page-title">Product Categories</h1>
        <p class="page-subtitle">Group applications by Industry solutions (e.g. Retail POS, F&B, ERP, Accounting).</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
            <span>🏷️</span> Add Category
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories ?? [] as $cat)
                        @php $cObj = is_array($cat) ? (object)$cat : $cat; @endphp
                        <tr>
                            <td class="font-bold text-base">{{ $cObj->name ?? 'Category' }}</td>
                            <td><code>/{{ $cObj->slug ?? '' }}</code></td>
                            <td class="font-semibold">{{ $cObj->sort_order ?? 0 }}</td>
                            <td>
                                @if($cObj->is_active ?? true)
                                    <span class="badge badge-success">ACTIVE</span>
                                @else
                                    <span class="badge badge-muted">INACTIVE</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.product-categories.edit', $cObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No categories configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
