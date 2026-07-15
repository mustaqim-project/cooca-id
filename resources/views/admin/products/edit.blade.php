@extends('layouts.admin')
@section('title', 'Edit Product')

@section('content')
    <div class="page-toolbar">
        <div class="page-toolbar-left">
            <a href="{{ route('admin.products.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <div>
                <h1 class="page-title">Edit Product</h1>
                <p class="page-subtitle">{{ $product->name }}</p>
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.products.show', $product) }}" class="btn-saas btn-saas-ghost btn-saas-sm">
                <i class="bi bi-eye me-1"></i>View
            </a>
        </div>
    </div>

    @include('components.swal-alert')

    {{-- Tabs Nav --}}
    <nav id="productTabs" class="d-flex gap-1 mb-4">
        <button class="tab-btn active" data-tab="details"><i class="bi bi-pencil me-1"></i>Details</button>
        <button class="tab-btn" data-tab="plans"><i class="bi bi-tags me-1"></i>Plans ({{ $plans->count() }})</button>
    </nav>

    {{-- TAB: DETAILS --}}
    <div class="tab-panel" id="tab-details">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-saas mb-4">
                        <div class="card-saas-header">
                            <h5 class="card-saas-title"><i class="bi bi-box me-2"></i>Basic Info</h5>
                        </div>
                        <div class="card-saas-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Product Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name"
                                            class="form-saas-input @error('name') is-invalid @enderror"
                                            value="{{ old('name', $product->name) }}" required>
                                        @error('name')
                                            <div class="form-saas-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Description</label>
                                        <textarea name="description" class="form-saas-textarea @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="form-saas-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Category</label>
                                        <select name="category_id"
                                            class="form-saas-select @error('category_id') is-invalid @enderror">
                                            <option value="">— No Category —</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="form-saas-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Status</label>
                                        <div class="d-flex align-items-center gap-3 mt-2">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    value="1"
                                                    {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            </div>
                                            <span class="text-muted" style="font-size:.9rem;">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card-saas mb-4">
                        <div class="card-saas-header">
                            <h5 class="card-saas-title"><i class="bi bi-currency-dollar me-2"></i>Base Pricing</h5>
                        </div>
                        <div class="card-saas-body">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Base Price (Rp)</label>
                                <input type="number" name="price"
                                    class="form-saas-input @error('price') is-invalid @enderror"
                                    value="{{ old('price', $product->price) }}" min="0" step="100">
                                @error('price')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-saas-group mb-0">
                                <label class="form-saas-label d-block mb-2">Pricing Type</label>
                                <div class="d-flex flex-column gap-2">
                                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;">
                                        <input type="radio" name="type" value="subscription"
                                            {{ old('type', $product->type) === 'subscription' ? 'checked' : '' }}>
                                        <span><strong>Subscription</strong> — recurring</span>
                                    </label>
                                    <label class="d-flex align-items-center gap-2" style="cursor:pointer;">
                                        <input type="radio" name="type" value="one_time"
                                            {{ old('type', $product->type) === 'one_time' ? 'checked' : '' }}>
                                        <span><strong>One Time</strong> — single payment</span>
                                    </label>
                                </div>
                                @error('type')
                                    <div class="form-saas-error mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-saas mb-4">
                        <div class="card-saas-header">
                            <h5 class="card-saas-title"><i class="bi bi-link-45deg me-2"></i>Integration</h5>
                        </div>
                        <div class="card-saas-body">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Webhook URL</label>
                                <input type="url" name="webhook_url"
                                    class="form-saas-input @error('webhook_url') is-invalid @enderror"
                                    value="{{ old('webhook_url', $product->webhook_url) }}" placeholder="https://...">
                                @error('webhook_url')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-saas-group mb-0">
                                <label class="form-saas-label">Demo URL</label>
                                <input type="url" name="demo_url"
                                    class="form-saas-input @error('demo_url') is-invalid @enderror"
                                    value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://...">
                                @error('demo_url')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-saas btn-saas-primary flex-fill">
                            <i class="bi bi-check2 me-1"></i>Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- TAB: PLANS --}}
    <div class="tab-panel d-none" id="tab-plans">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-tags me-2"></i>Existing Plans</h5>
                    </div>
                    <div class="card-saas-body">
                        @forelse($plans as $plan)
                            <div class="mb-3 rounded" style="border:1px solid var(--border);overflow:hidden;">
                                {{-- VIEW MODE --}}
                                <div id="plan-view-{{ $plan->id }}" class="p-3">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <strong>{{ $plan->name }}</strong>
                                                @if ($plan->is_active)
                                                    <span class="badge-saas badge-saas-success">Active</span>
                                                @else
                                                    <span class="badge-saas badge-saas-neutral">Inactive</span>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-3" style="font-size:.85rem;color:var(--text-muted);">
                                                <span><i class="bi bi-calendar3 me-1"></i>{{ $plan->duration_months }}
                                                    month(s)</span>
                                                <span><i class="bi bi-tag me-1"></i>Rp
                                                    {{ number_format($plan->price, 0, ',', '.') }}</span>
                                                @if ($plan->discount_percent > 0)
                                                    <span class="text-success"><i
                                                            class="bi bi-percent me-1"></i>{{ $plan->discount_percent }}%
                                                        off</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <button type="button" class="btn-saas btn-saas-ghost btn-saas-sm"
                                                onclick="togglePlanEdit({{ $plan->id }})">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </button>
                                            <form action="{{ route('admin.products.plans.toggle', [$product, $plan]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm">
                                                    @if ($plan->is_active)
                                                        <i class="bi bi-toggle-on me-1"></i>Disable
                                                    @else
                                                        <i class="bi bi-toggle-off me-1"></i>Enable
                                                    @endif
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.products.plans.destroy', [$product, $plan]) }}"
                                                method="POST" class="d-inline form-confirm-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-saas btn-saas-danger btn-saas-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- EDIT MODE --}}
                                <div id="plan-edit-{{ $plan->id }}" class="d-none p-3"
                                    style="background:var(--surface-raised);border-top:1px solid var(--border);">
                                    <form action="{{ route('admin.products.plans.update', [$product, $plan]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-saas-group mb-0">
                                                    <label class="form-saas-label">Plan Name</label>
                                                    <input type="text" name="name" class="form-saas-input"
                                                        value="{{ $plan->name }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-saas-group mb-0">
                                                    <label class="form-saas-label">Duration (months)</label>
                                                    <input type="number" name="duration_months" class="form-saas-input"
                                                        value="{{ $plan->duration_months }}" min="1" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-saas-group mb-0">
                                                    <label class="form-saas-label">Price (Rp)</label>
                                                    <input type="number" name="price" class="form-saas-input"
                                                        value="{{ $plan->price }}" min="0" step="100"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-saas-group mb-0">
                                                    <label class="form-saas-label">Discount (%)</label>
                                                    <input type="number" name="discount_percent" class="form-saas-input"
                                                        value="{{ $plan->discount_percent }}" min="0"
                                                        max="100" step="0.01">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-saas-group mb-0">
                                                    <label class="form-saas-label">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-saas-input"
                                                        value="{{ $plan->sort_order }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                                    </div>
                                                    <span style="font-size:.85rem;color:var(--text-muted);">Active</span>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex gap-2">
                                                <button type="submit" class="btn-saas btn-saas-primary btn-saas-sm">
                                                    <i class="bi bi-check2 me-1"></i>Save
                                                </button>
                                                <button type="button" class="btn-saas btn-saas-ghost btn-saas-sm"
                                                    onclick="togglePlanEdit({{ $plan->id }})">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state py-4">
                                <div class="empty-state-icon"><i class="bi bi-tags"></i></div>
                                <p class="empty-state-title">No plans yet</p>
                                <p class="empty-state-description">Add a plan below.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Add New Plan --}}
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-plus-circle me-2"></i>Add New Plan</h5>
                    </div>
                    <div class="card-saas-body">
                        <form action="{{ route('admin.products.plans.store', $product) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Plan Name</label>
                                        <input type="text" name="name" class="form-saas-input"
                                            placeholder="e.g. Pro Annual" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Duration (months)</label>
                                        <input type="number" name="duration_months" class="form-saas-input"
                                            value="1" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Price (Rp)</label>
                                        <input type="number" name="price" class="form-saas-input" value="0"
                                            min="0" step="100" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Discount (%)</label>
                                        <input type="number" name="discount_percent" class="form-saas-input"
                                            value="0" min="0" max="100" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-saas-group">
                                        <label class="form-saas-label">Sort Order</label>
                                        <input type="number" name="sort_order" class="form-saas-input"
                                            value="{{ $plans->count() }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_active"
                                                value="1" checked>
                                        </div>
                                        <span style="font-size:.85rem;color:var(--text-muted);">Active</span>
                                    </div>
                                    <button type="submit" class="btn-saas btn-saas-primary btn-saas-sm">
                                        <i class="bi bi-plus me-1"></i>Add Plan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('#productTabs .tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#productTabs .tab-btn').forEach(b => b.classList.remove(
                    'active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('d-none'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.remove('d-none');
            });
        });

        function togglePlanEdit(id) {
            const view = document.getElementById('plan-view-' + id);
            const edit = document.getElementById('plan-edit-' + id);
            view.classList.toggle('d-none');
            edit.classList.toggle('d-none');
        }
    </script>
@endpush
