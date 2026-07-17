@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold">Edit Product</h2>
                    <p class="text-secondary mb-0">{{ $product->name }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.show', $product->id) }}"
                    class="btn btn-light border rounded-pill px-4 hover-lift"><i class="bi bi-eye me-2"></i>View</a>
            </div>
        </div>

        {{-- Tabs Nav --}}
        <nav id="productTabs" class="d-flex gap-2 bg-light p-1 rounded-pill" style="width: fit-content;">
            <button class="tab-btn btn btn-sm rounded-pill px-4" data-tab="details"><i
                    class="bi bi-pencil me-1"></i>Details</button>
            <button class="tab-btn btn btn-sm rounded-pill px-4 text-secondary" data-tab="plans"><i
                    class="bi bi-tags me-1"></i>Plans ({{ $plans->count() }})</button>
        </nav>

        {{-- TAB: DETAILS --}}
        <div class="tab-panel" id="tab-details">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 glass">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="fw-bold mb-0"><i class="bi bi-box me-2 text-primary"></i>Basic Info</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="text-secondary fs-7 mb-1 d-block">Product Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control rounded-3 shadow-none border bg-transparent @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $product->name) }}"
                                            placeholder="e.g. Cooca Pro" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="text-secondary fs-7 mb-1 d-block">Description <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control rounded-3 shadow-none border bg-transparent @error('description') is-invalid @enderror"
                                            name="description" rows="4" placeholder="Describe the product...">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Product Type <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-select rounded-3 shadow-none border bg-transparent @error('product_type') is-invalid @enderror"
                                            name="product_type" id="productType" required>
                                            @foreach (\App\Models\Product::TYPES as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('product_type', $product->product_type) === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('product_type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Category</label>
                                        <select
                                            class="form-select rounded-3 shadow-none border bg-transparent @error('category_id') is-invalid @enderror"
                                            name="category_id">
                                            <option value="">— No Category —</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-secondary fs-7 mb-1 d-block">Status</label>
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

                        <!-- Business Fields (conditional) -->
                        <div class="card border-0 shadow-sm rounded-4 glass mt-4" id="businessFieldsCard">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-primary"></i>Business Configuration
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    {{-- License fields: shown for license, lifetime, saas --}}
                                    <div class="col-md-6 field-license">
                                        <label class="text-secondary fs-7 mb-1 d-block">License Type</label>
                                        <select
                                            class="form-select rounded-3 shadow-none border bg-transparent @error('license_type') is-invalid @enderror"
                                            name="license_type">
                                            <option value="">— None —</option>
                                            @foreach (\App\Models\Product::LICENSE_TYPES as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('license_type', $product->license_type) === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('license_type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 field-license">
                                        <label class="text-secondary fs-7 mb-1 d-block">Max Domains</label>
                                        <input type="number" name="max_domains"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="{{ old('max_domains', $product->max_domains ?? 1) }}" min="1">
                                    </div>
                                    <div class="col-md-3 field-version">
                                        <label class="text-secondary fs-7 mb-1 d-block">Version</label>
                                        <input type="text" name="version"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="{{ old('version', $product->version) }}" placeholder="e.g. 1.0.0">
                                    </div>
                                    {{-- Fees: shown for custom_dev, project, maintenance --}}
                                    <div class="col-md-6 field-fees">
                                        <label class="text-secondary fs-7 mb-1 d-block">Setup Fee (Rp)</label>
                                        <input type="number" name="setup_fee"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="{{ old('setup_fee', $product->setup_fee ?? 0) }}" min="0"
                                            step="100">
                                    </div>
                                    <div class="col-md-6 field-fees">
                                        <label class="text-secondary fs-7 mb-1 d-block">Maintenance Fee (Rp)</label>
                                        <input type="number" name="maintenance_fee"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="{{ old('maintenance_fee', $product->maintenance_fee ?? 0) }}"
                                            min="0" step="100">
                                    </div>
                                    {{-- Requirements: shown for custom_dev, project --}}
                                    <div class="col-12 field-requirements">
                                        <label class="text-secondary fs-7 mb-1 d-block">Requirements</label>
                                        <textarea class="form-control rounded-3 shadow-none border bg-transparent" name="requirements" rows="3"
                                            placeholder="System or project requirements...">{{ old('requirements', $product->requirements) }}</textarea>
                                    </div>
                                    {{-- Bundle flag --}}
                                    <div class="col-12 field-bundle">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_bundleable"
                                                value="1"
                                                {{ old('is_bundleable', $product->is_bundleable) ? 'checked' : '' }}>
                                            <label class="text-muted" style="font-size:.85rem;">Can be bundled with other
                                                products</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 glass">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="fw-bold mb-0"><i class="bi bi-currency-dollar me-2 text-primary"></i>Base
                                    Pricing
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <label class="text-secondary fs-7 mb-1 d-block">Base Price (Rp)</label>
                                <input type="number" name="base_price"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('base_price') is-invalid @enderror"
                                    value="{{ old('base_price', $product->base_price) }}" min="0" step="100"
                                    placeholder="0">
                                @error('base_price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 glass mt-4">
                            <div class="card-header bg-transparent border-bottom border-light p-4">
                                <h5 class="fw-bold mb-0"><i class="bi bi-link-45deg me-2 text-primary"></i>Integration
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <label class="text-secondary fs-7 mb-1 d-block">Demo URL</label>
                                <input type="url" name="demo_url"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('demo_url') is-invalid @enderror"
                                    value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://...">
                                @error('demo_url')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <label class="text-secondary fs-7 mb-1 d-block mt-3">Webhook URL</label>
                                <input type="url" name="webhook_url"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('webhook_url') is-invalid @enderror"
                                    value="{{ old('webhook_url', $product->webhook_url) }}" placeholder="https://...">
                                @error('webhook_url')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit"
                                class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift flex-fill">
                                <i class="bi bi-check2 me-2"></i> Update Product
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
                    <div class="card border-0 shadow-sm rounded-4 glass">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-tags me-2 text-primary"></i>Existing Plans</h5>
                        </div>
                        <div class="card-body p-4">
                            @forelse($plans as $plan)
                                <div class="mb-3 rounded-3 border overflow-hidden">
                                    {{-- VIEW MODE --}}
                                    <div id="plan-view-{{ $plan->id }}" class="p-3">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <strong>{{ $plan->name }}</strong>
                                                    @if ($plan->is_active)
                                                        <span
                                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Active</span>
                                                    @else
                                                        <span
                                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">Inactive</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex gap-3"
                                                    style="font-size:.85rem;color:var(--color-text-secondary);">
                                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $plan->duration_months }}
                                                        month(s)</span>
                                                    <span><i class="bi bi-tag me-1"></i>Rp
                                                        {{ number_format($plan->price, 0, ',', '.') }}</span>
                                                    @if ($plan->discount_percent > 0)
                                                        <span class="text-success"><i
                                                                class="bi bi-percent me-1"></i>{{ (float) $plan->discount_percent }}%
                                                            off</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <button type="button"
                                                    class="btn btn-sm btn-light border-0 rounded-circle p-2"
                                                    onclick="togglePlanEdit({{ $plan->id }})" title="Edit">
                                                    <i class="bi bi-pencil text-primary"></i>
                                                </button>
                                                <form
                                                    action="{{ route('admin.products.plans.toggle', [$product, $plan]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-light border-0 rounded-circle p-2"
                                                        title="{{ $plan->is_active ? 'Disable' : 'Enable' }}">
                                                        <i
                                                            class="bi {{ $plan->is_active ? 'bi-toggle-on text-success' : 'bi-toggle-off text-secondary' }}"></i>
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('admin.products.plans.destroy', [$product, $plan]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-light border-0 rounded-circle p-2"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this plan?');">
                                                        <i class="bi bi-trash3 text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- EDIT MODE --}}
                                    <div id="plan-edit-{{ $plan->id }}" class="d-none p-3"
                                        style="background:var(--color-bg);border-top:1px solid var(--color-border);">
                                        <form action="{{ route('admin.products.plans.update', [$product, $plan]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="text-secondary fs-7 mb-1 d-block">Plan Name</label>
                                                    <input type="text" name="name"
                                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                                        value="{{ $plan->name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="text-secondary fs-7 mb-1 d-block">Duration
                                                        (months)
                                                    </label>
                                                    <input type="number" name="duration_months"
                                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                                        value="{{ $plan->duration_months }}" min="1" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-secondary fs-7 mb-1 d-block">Price (Rp)</label>
                                                    <input type="number" name="price"
                                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                                        value="{{ $plan->price }}" min="0" step="100"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-secondary fs-7 mb-1 d-block">Discount (%)</label>
                                                    <input type="number" name="discount_percent"
                                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                                        value="{{ (float) $plan->discount_percent }}" min="0"
                                                        max="100" step="0.01">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="text-secondary fs-7 mb-1 d-block">Sort Order</label>
                                                    <input type="number" name="sort_order"
                                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                                        value="{{ $plan->sort_order }}">
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                                        <label class="text-muted" style="font-size:.85rem;">Active</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex gap-2">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary rounded-pill px-3">Save</button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-light border rounded-pill px-3"
                                                        onclick="togglePlanEdit({{ $plan->id }})">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-secondary py-4">
                                    <i class="bi bi-tags fs-3 d-block mb-2 opacity-50"></i>
                                    <p class="fs-7 mb-0">No plans yet. Add a plan below.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Add New Plan --}}
                    <div class="card border-0 shadow-sm rounded-4 glass mt-4">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Add New Plan</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.products.plans.store', $product) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Plan Name</label>
                                        <input type="text" name="name"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            placeholder="e.g. Pro Annual" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Duration (months)</label>
                                        <input type="number" name="duration_months"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="1" min="1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-secondary fs-7 mb-1 d-block">Price (Rp)</label>
                                        <input type="number" name="price"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="0" min="0" step="100" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-secondary fs-7 mb-1 d-block">Discount (%)</label>
                                        <input type="number" name="discount_percent"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="0" min="0" max="100" step="0.01">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-secondary fs-7 mb-1 d-block">Sort Order</label>
                                        <input type="number" name="sort_order"
                                            class="form-control rounded-3 shadow-none border bg-transparent"
                                            value="{{ $plans->count() }}">
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_active"
                                                value="1" checked>
                                            <label class="text-muted" style="font-size:.85rem;">Active</label>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 mt-3">Add
                                            Plan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function activateTab(tab) {
            if (!tab) return;
            document.querySelectorAll('#productTabs .tab-btn').forEach(b => {
                const match = b.dataset.tab === tab;
                b.classList.toggle('active', match);
                b.classList.toggle('text-secondary', !match);
            });
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('d-none'));
            const panel = document.getElementById('tab-' + tab);
            if (panel) panel.classList.remove('d-none');
        }

        document.querySelectorAll('#productTabs .tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                activateTab(this.dataset.tab);
                // keep URL in sync without jumping
                history.replaceState(null, '', '#' + this.dataset.tab);
            });
        });

        function togglePlanEdit(id) {
            const view = document.getElementById('plan-view-' + id);
            const edit = document.getElementById('plan-edit-' + id);
            view.classList.toggle('d-none');
            edit.classList.toggle('d-none');
        }

        // Activate the correct tab based on the URL hash (e.g. #plans)
        document.addEventListener('DOMContentLoaded', function() {
            const hash = (location.hash || '').replace('#', '');
            if (hash === 'details' || hash === 'plans') {
                activateTab(hash);
            }
        });

        window.addEventListener('hashchange', function() {
            const hash = (location.hash || '').replace('#', '');
            if (hash === 'details' || hash === 'plans') {
                activateTab(hash);
            }
        });

        // === Product Type conditional fields ===
        (function() {
            const typeFieldMap = {
                license: ['saas', 'lifetime', 'license'],
                version: ['saas', 'lifetime', 'license', 'addon'],
                fees: ['custom_dev', 'project', 'maintenance'],
                requirements: ['custom_dev', 'project'],
                bundle: ['saas', 'lifetime', 'license', 'addon', 'subscription'],
            };

            function toggleBusinessFields() {
                const type = document.getElementById('productType').value;
                Object.entries(typeFieldMap).forEach(([field, types]) => {
                    const show = types.includes(type);
                    document.querySelectorAll('.field-' + field).forEach(el => {
                        el.style.display = show ? '' : 'none';
                    });
                });
            }

            document.getElementById('productType').addEventListener('change', toggleBusinessFields);
            toggleBusinessFields();
        })();
    </script>
@endpush
