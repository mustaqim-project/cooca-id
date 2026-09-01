@extends('layouts.admin')

@section('title', ($product->name ?? 'Product Details') . ' — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <span>/</span>
            <span>{{ $product->name }}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 12px; margin-top: 4px;">
            <h1 class="page-title" style="margin: 0;">{{ $product->name }}</h1>
            @if($product->version)
                <span class="badge badge-accent">v{{ $product->version }}</span>
            @endif
            @if($product->is_active)
                <span class="badge badge-success"><span class="status-dot active"></span> Active</span>
            @else
                <span class="badge badge-muted"><span class="status-dot inactive"></span> Inactive</span>
            @endif
            @if($product->is_featured)
                <span class="badge badge-warning"><i class="fa-solid fa-star mr-1"></i> Featured</span>
            @endif
        </div>
        <p class="page-subtitle" style="margin-top: 4px;">{{ $product->short_description ?? 'Comprehensive product architecture, subscription tiers, and feature catalogue.' }}</p>
    </div>
    <div class="page-actions" style="display: flex; gap: 10px; flex-wrap: wrap;">
        @if($product->demo_url)
            <a href="{{ $product->demo_url }}" target="_blank" class="btn btn-ghost" title="Live Preview / Demo">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Live Demo
            </a>
        @endif
        <a href="{{ route('admin.products.plans.index', $product->id) }}" class="btn btn-outline">
            <i class="fa-solid fa-layer-group mr-1"></i> Manage Plans ({{ $product->subscriptionPlans->count() }})
        </a>
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Product
        </a>
    </div>
</div>

<div class="grid-31" style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 24px; align-items: start;">

    {{-- Main Column (Left) --}}
    <div class="flex-col gap-5">

        {{-- Overview Card with Banner/Icon --}}
        <div class="card">
            <div class="card-body">
                <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                    <div style="width: 100px; height: 100px; border-radius: var(--radius-md); overflow: hidden; background: var(--bg-secondary); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                        @if($product->thumbnail_url)
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($product->icon)
                            @if(str_starts_with($product->icon, 'fa-') || str_starts_with($product->icon, 'fa-solid'))
                                <i class="{{ $product->icon }}" style="font-size: 40px; color: var(--primary);"></i>
                            @else
                                <span style="font-size: 44px;">{{ $product->icon }}</span>
                            @endif
                        @else
                            <i class="fa-solid fa-cubes" style="font-size: 36px; color: var(--text-muted);"></i>
                        @endif
                    </div>

                    <div style="flex: 1; min-width: 260px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                            @if($product->category)
                                <span class="badge badge-purple">
                                    <i class="fa-solid fa-tag mr-1"></i> {{ $product->category->name }}
                                </span>
                            @endif
                            <span class="badge badge-accent">
                                <i class="fa-solid fa-cube mr-1"></i> {{ $product->product_type_label }}
                            </span>
                            @if($product->license_type_label)
                                <span class="badge badge-muted">
                                    <i class="fa-solid fa-key mr-1"></i> {{ $product->license_type_label }}
                                </span>
                            @endif
                        </div>

                        <div style="font-size: 14px; color: var(--text); line-height: 1.6; margin-bottom: 12px;">
                            {{ $product->short_description ?? 'No short description configured.' }}
                        </div>

                        <div style="display: flex; gap: 16px; font-size: 13px; color: var(--text-muted); flex-wrap: wrap;">
                            <div><strong>Slug:</strong> <code>/products/{{ $product->slug }}</code></div>
                            <div><strong>Views:</strong> {{ number_format($product->views ?? 0) }}</div>
                            <div><strong>Max Domains:</strong> {{ $product->max_domains ?? 1 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing Plans Table --}}
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-layer-group text-primary"></i> Subscription & Pricing Plans
                </div>
                <a href="{{ route('admin.products.plans.index', $product->id) }}" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-plus mr-1"></i> Add / Edit Tiers
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Plan Tier</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->subscriptionPlans as $plan)
                                @php
                                    $origPrice = (float)($plan->price ?? 0);
                                    $discount = (float)($plan->discount_percent ?? 0);
                                    $finalPrice = $discount > 0 ? $origPrice * (1 - $discount / 100) : $origPrice;
                                @endphp
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span class="font-bold text-primary">{{ $plan->name }}</span>
                                            @if($plan->is_popular)
                                                <span class="badge badge-warning" style="font-size: 10px; padding: 2px 6px;">Popular</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if(($plan->duration_months ?? 1) >= 999)
                                            <span class="badge badge-success">Lifetime</span>
                                        @else
                                            <span class="font-medium">{{ $plan->duration_months ?? 1 }} Months</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($discount > 0)
                                            <div class="text-xs text-muted" style="text-decoration: line-through;">Rp {{ number_format($origPrice, 0, ',', '.') }}</div>
                                            <div class="font-bold text-primary">Rp {{ number_format($finalPrice, 0, ',', '.') }}</div>
                                        @else
                                            <div class="font-bold text-primary">Rp {{ number_format($origPrice, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($discount > 0)
                                            <span class="badge badge-accent">{{ number_format($discount, ($discount == floor($discount) ? 0 : 2)) }}% OFF</span>
                                        @else
                                            <span class="badge badge-muted">0%</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($plan->is_active ?? true)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-muted">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                        <div style="margin-bottom: 8px;">No pricing plans added for this product yet.</div>
                                        <a href="{{ route('admin.products.plans.index', $product->id) }}" class="btn btn-outline btn-sm">
                                            <i class="fa-solid fa-plus mr-1"></i> Configure Pricing Plans
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Features & Capabilities --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-list-check text-primary"></i> Included Features & Modules
                </div>
            </div>
            <div class="card-body">
                @php
                    $features = is_array($product->features) ? $product->features : json_decode($product->features ?? '[]', true);
                @endphp

                @if(!empty($features) && count($features) > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px;">
                        @foreach($features as $feature)
                            @if(is_array($feature))
                                <div style="display: flex; align-items: flex-start; gap: 10px; background: var(--bg-secondary); padding: 12px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                                    <i class="fa-solid fa-circle-check text-success" style="margin-top: 3px; font-size: 15px;"></i>
                                    <div>
                                        <div style="font-weight: 700; font-size: 13.5px; color: var(--text);">{{ $feature['title'] ?? $feature['name'] ?? 'Feature' }}</div>
                                        @if(!empty($feature['desc']) || !empty($feature['description']))
                                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ $feature['desc'] ?? $feature['description'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            @elseif(is_string($feature))
                                <div style="display: flex; align-items: center; gap: 10px; background: var(--bg-secondary); padding: 10px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                                    <i class="fa-solid fa-check text-success" style="font-size: 14px;"></i>
                                    <span style="font-size: 13.5px; color: var(--text); font-weight: 500;">{{ $feature }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-muted" style="font-size: 13px; font-style: italic;">
                        No explicit features list provided. Configure feature highlights in product edit.
                    </div>
                @endif
            </div>
        </div>

        {{-- Full Description --}}
        @if($product->description)
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-align-left text-primary"></i> Product Overview & Specification
                    </div>
                </div>
                <div class="card-body" style="font-size: 14px; line-height: 1.8; color: var(--text);">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        @endif

        {{-- Screenshots Gallery --}}
        @php
            $screenshots = is_array($product->screenshots) ? $product->screenshots : json_decode($product->screenshots ?? '[]', true);
        @endphp
        @if(!empty($screenshots) && count($screenshots) > 0)
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-images text-primary"></i> App Screenshots & Preview Gallery
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px;">
                        @foreach($screenshots as $shot)
                            <div style="border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border); aspect-ratio: 16/9; background: var(--bg-secondary);">
                                <img src="{{ asset($shot) }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Sidebar (Right) --}}
    <div class="flex-col gap-5">

        {{-- Pricing & Commercial Overview --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-wallet text-primary"></i> Financial & Pricing
                </div>
            </div>
            <div class="card-body flex-col gap-3">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid var(--border);">
                    <span class="text-xs text-muted font-bold uppercase">Base Price</span>
                    <span class="font-bold text-primary" style="font-size: 16px;">
                        Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid var(--border);">
                    <span class="text-xs text-muted font-bold uppercase">Setup Fee</span>
                    <span class="font-semibold text-text">
                        Rp {{ number_format($product->setup_fee ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid var(--border);">
                    <span class="text-xs text-muted font-bold uppercase">Maintenance Fee</span>
                    <span class="font-semibold text-text">
                        Rp {{ number_format($product->maintenance_fee ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-xs text-muted font-bold uppercase">Bundle Eligible</span>
                    @if($product->is_bundleable)
                        <span class="badge badge-success">Yes</span>
                    @else
                        <span class="badge badge-muted">No</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Metadata & Settings --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-info text-primary"></i> Product Metadata
                </div>
            </div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">UUID ID</div>
                    <code style="font-size: 11px; word-break: break-all; display: block; margin-top: 2px;">{{ $product->id }}</code>
                </div>

                <div>
                    <div class="text-xs text-muted font-bold uppercase">Sort Order</div>
                    <div class="font-bold text-text" style="margin-top: 2px;">#{{ $product->sort_order ?? 0 }}</div>
                </div>

                <div>
                    <div class="text-xs text-muted font-bold uppercase">Created Date</div>
                    <div class="text-xs font-semibold text-text" style="margin-top: 2px;">
                        {{ $product->created_at ? $product->created_at->format('d M Y, H:i') : 'N/A' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-muted font-bold uppercase">Last Updated</div>
                    <div class="text-xs font-semibold text-text" style="margin-top: 2px;">
                        {{ $product->updated_at ? $product->updated_at->format('d M Y, H:i') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">⚙️ Administrative Actions</div>
            </div>
            <div class="card-body flex-col gap-3">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Specifications
                </a>
                <a href="{{ route('admin.products.plans.index', $product->id) }}" class="btn btn-outline" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-layer-group mr-1"></i> Manage Pricing Tiers
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost text-danger" style="width: 100%; justify-content: center; border: 1px solid var(--danger-soft);">
                        <i class="fa-solid fa-trash-can mr-1"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
