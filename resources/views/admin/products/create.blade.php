@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 900px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold">Create Product</h2>
                <p class="text-secondary mb-0">Fill in the details to create a new software product or service.</p>
            </div>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" class="d-flex flex-column gap-4">
            @csrf

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Basic Info -->
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
                                        name="name" value="{{ old('name') }}" placeholder="e.g. Cooca Pro" required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="text-secondary fs-7 mb-1 d-block">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control rounded-3 shadow-none border bg-transparent @error('description') is-invalid @enderror"
                                        name="description" rows="4" placeholder="Describe the product...">{{ old('description') }}</textarea>
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
                                                {{ old('product_type', 'saas') === $key ? 'selected' : '' }}>
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
                                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', '1') ? 'checked' : '' }}>
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
                            <h5 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-primary"></i>Business Configuration</h5>
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
                                                {{ old('license_type') === $key ? 'selected' : '' }}>
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
                                        value="{{ old('max_domains', 1) }}" min="1">
                                </div>
                                <div class="col-md-3 field-version">
                                    <label class="text-secondary fs-7 mb-1 d-block">Version</label>
                                    <input type="text" name="version"
                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                        value="{{ old('version') }}" placeholder="e.g. 1.0.0">
                                </div>
                                {{-- Fees: shown for custom_dev, project, maintenance --}}
                                <div class="col-md-6 field-fees">
                                    <label class="text-secondary fs-7 mb-1 d-block">Setup Fee (Rp)</label>
                                    <input type="number" name="setup_fee"
                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                        value="{{ old('setup_fee', 0) }}" min="0" step="100">
                                </div>
                                <div class="col-md-6 field-fees">
                                    <label class="text-secondary fs-7 mb-1 d-block">Maintenance Fee (Rp)</label>
                                    <input type="number" name="maintenance_fee"
                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                        value="{{ old('maintenance_fee', 0) }}" min="0" step="100">
                                </div>
                                {{-- Requirements: shown for custom_dev, project --}}
                                <div class="col-12 field-requirements">
                                    <label class="text-secondary fs-7 mb-1 d-block">Requirements</label>
                                    <textarea class="form-control rounded-3 shadow-none border bg-transparent" name="requirements" rows="3"
                                        placeholder="System or project requirements...">{{ old('requirements') }}</textarea>
                                </div>
                                {{-- Bundle flag --}}
                                <div class="col-12 field-bundle">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="is_bundleable"
                                            value="1" {{ old('is_bundleable') ? 'checked' : '' }}>
                                        <label class="text-muted" style="font-size:.85rem;">Can be bundled with other
                                            products</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Plans -->
                    <div class="card border-0 shadow-sm rounded-4 glass mt-4">
                        <div
                            class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="bi bi-tags me-2 text-primary"></i>Pricing Plans</h5>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="addPlanBtn">
                                <i class="bi bi-plus me-1"></i>Add Plan
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div id="plansContainer"></div>
                            <div id="plansEmpty" class="text-center text-secondary py-4">
                                <i class="bi bi-tags fs-3 d-block mb-2 opacity-50"></i>
                                <p class="fs-7 mb-0">No plans yet. Click "Add Plan" to create pricing tiers.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Base Pricing -->
                    <div class="card border-0 shadow-sm rounded-4 glass">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-currency-dollar me-2 text-primary"></i>Base Pricing
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <label class="text-secondary fs-7 mb-1 d-block">Base Price (Rp)</label>
                            <input type="number" name="base_price"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('base_price') is-invalid @enderror"
                                value="{{ old('base_price', 0) }}" min="0" step="100" placeholder="0">
                            @error('base_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Integration -->
                    <div class="card border-0 shadow-sm rounded-4 glass mt-4">
                        <div class="card-header bg-transparent border-bottom border-light p-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-link-45deg me-2 text-primary"></i>Integration</h5>
                        </div>
                        <div class="card-body p-4">
                            <label class="text-secondary fs-7 mb-1 d-block">Demo URL</label>
                            <input type="url" name="demo_url"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('demo_url') is-invalid @enderror"
                                value="{{ old('demo_url') }}" placeholder="https://...">
                            @error('demo_url')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <label class="text-secondary fs-7 mb-1 d-block mt-3">Webhook URL</label>
                            <input type="url" name="webhook_url"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('webhook_url') is-invalid @enderror"
                                value="{{ old('webhook_url') }}" placeholder="https://...">
                            @error('webhook_url')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift flex-fill">
                            <i class="bi bi-check2 me-2"></i> Save Product
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                            class="btn btn-light border rounded-pill px-4">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // === Product Type conditional fields ===
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

            // === Plans ===
            let planCount = 0;

            function updateEmpty() {
                const container = document.getElementById('plansContainer');
                const empty = document.getElementById('plansEmpty');
                empty.style.display = container.children.length === 0 ? '' : 'none';
            }

            function addPlan() {
                const idx = planCount++;
                const container = document.getElementById('plansContainer');
                const div = document.createElement('div');
                div.className = 'p-3 mb-3 rounded-3 border';
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold" style="font-size:.9rem;color:var(--color-primary);">Plan #\${idx + 1}</span>
                        <button type="button" class="btn btn-sm btn-light border-0 rounded-circle p-2" onclick="this.closest('[data-plan]').remove();updatePlanEmpty();" title="Remove">
                            <i class="bi bi-trash3 text-danger"></i>
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-secondary fs-7 mb-1 d-block">Plan Name</label>
                            <input type="text" name="plans[\${idx}][name]" class="form-control rounded-3 shadow-none border bg-transparent" placeholder="e.g. Starter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary fs-7 mb-1 d-block">Duration (months)</label>
                            <input type="number" name="plans[\${idx}][duration_months]" class="form-control rounded-3 shadow-none border bg-transparent" value="1" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-secondary fs-7 mb-1 d-block">Price (Rp)</label>
                            <input type="number" name="plans[\${idx}][price]" class="form-control rounded-3 shadow-none border bg-transparent" value="0" min="0" step="100" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-secondary fs-7 mb-1 d-block">Discount (%)</label>
                            <input type="number" name="plans[\${idx}][discount_percent]" class="form-control rounded-3 shadow-none border bg-transparent" value="0" min="0" max="100" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="text-secondary fs-7 mb-1 d-block">Sort Order</label>
                            <input type="number" name="plans[\${idx}][sort_order]" class="form-control rounded-3 shadow-none border bg-transparent" value="\${idx}">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="plans[\${idx}][is_active]" value="1" checked>
                                <label class="text-muted" style="font-size:.85rem;">Active</label>
                            </div>
                        </div>
                    </div>
                `;
                div.setAttribute('data-plan', idx);
                container.appendChild(div);
                updateEmpty();
            }

            document.getElementById('addPlanBtn').addEventListener('click', addPlan);
            window.updatePlanEmpty = updateEmpty;
            updateEmpty();
        })();
    </script>
@endpush
