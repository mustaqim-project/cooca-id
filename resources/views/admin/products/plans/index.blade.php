@extends('layouts.admin')

@section('title', 'Subscription Plans — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <span>/</span>
            <span>Plans</span>
        </div>
        <h1 class="page-title">Pricing Plans: {{ $product->name ?? 'Software' }}</h1>
    </div>
</div>

<div class="grid-31">
    <div class="card">
        <div class="card-header"><div class="card-title">Existing Pricing Tiers</div></div>
        <div class="card-body" style="padding: 0;">
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Plan Tier</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans ?? [] as $plan)
                            @php 
                                $pObj = is_array($plan) ? (object)$plan : $plan; 
                                $origPrice = (float)($pObj->price ?? 0);
                                $discount = (float)($pObj->discount_percent ?? 0);
                                $finalPrice = $discount > 0 ? $origPrice * (1 - $discount / 100) : $origPrice;
                            @endphp
                            <tr>
                                <td class="font-bold">{{ $pObj->name ?? 'Tier' }}</td>
                                <td>
                                    @if(($pObj->duration_months ?? 1) >= 999)
                                        <span class="badge badge-success" style="font-weight: bold;">Lifetime</span>
                                    @else
                                        {{ $pObj->duration_months ?? 1 }} Months
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
                                        <span class="badge badge-accent">{{ number_format($discount, ($discount == floor($discount) ? 0 : 2)) }}%</span>
                                    @else
                                        <span class="badge badge-muted">0%</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.products.plans.destroy', [$product->id ?? 1, $pObj->id ?? 1]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 30px;">No pricing plans configured yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">Add Pricing Tier</div></div>
        <div class="card-body">
            <form action="{{ route('admin.products.plans.store', $product->id ?? 1) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tier Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="Starter / Business / Enterprise" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Duration (Period) *</label>
                    <select name="duration_months" class="form-select" required>
                        <option value="1">1 Month (Monthly)</option>
                        <option value="3">3 Months (Quarterly)</option>
                        <option value="6">6 Months (Semi-Annually)</option>
                        <option value="12" selected>12 Months (Annually)</option>
                        <option value="999">Lifetime (One-Time Purchase)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Price (IDR) *</label>
                    <input type="number" name="price" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Discount %</label>
                    <input type="number" step="any" name="discount_percent" class="form-input" value="0">
                </div>
                <button type="submit" class="btn btn-primary w-full mt-4"><i class="fa-solid fa-plus mr-1"></i> Add Tier</button>
            </form>
        </div>
    </div>
</div>
@endsection
