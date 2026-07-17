dm@extends('layouts.admin')
@section('title', 'Add Product')

@section('content')
    <div class="page-toolbar">
        <div class="page-toolbar-left">
            <a href="{{ route('admin.products.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <div>
                <h1 class="page-title">Add Product</h1>
                <p class="page-subtitle">Create a new product with pricing plans</p>
            </div>
        </div>
    </div>

    @include('components.swal-alert')

    <form action="{{ route('admin.products.store') }}" method="POST" id="productForm">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Basic Info --}}
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-box me-2"></i>Basic Info</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-saas-group">
                                    <label class="form-saas-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-saas-input @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-saas-group">
                                    <label class="form-saas-label">Description</label>
                                    <textarea name="description" class="form-saas-textarea @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
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
                                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', '1') ? 'checked' : '' }}>
                                        </div>
                                        <span class="text-muted" style="font-size:.9rem;">Active</span>
                                    </div>
                                    @error('is_active')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing Plans --}}
                <div class="card-saas">
                    <div class="card-saas-header d-flex justify-content-between align-items-center">
                        <h5 class="card-saas-title mb-0"><i class="bi bi-tags me-2"></i>Pricing Plans</h5>
                        <button type="button" class="btn-saas btn-saas-outline btn-saas-sm" id="addPlanBtn">
                            <i class="bi bi-plus me-1"></i>Add Plan
                        </button>
                    </div>
                    <div class="card-saas-body">
                        <div id="plansContainer">
                            {{-- JS renders plans here --}}
                        </div>
                        <div id="plansEmpty" class="empty-state py-4">
                            <div class="empty-state-icon"><i class="bi bi-tags"></i></div>
                            <p class="empty-state-title">No plans yet</p>
                            <p class="empty-state-description">Click "Add Plan" to create pricing tiers.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Base Pricing --}}
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-currency-dollar me-2"></i>Base Pricing</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-saas-group">
                            <label class="form-saas-label">Base Price (Rp)</label>
                            <input type="number" name="price"
                                class="form-saas-input @error('price') is-invalid @enderror" value="{{ old('price', 0) }}"
                                min="0" step="100">
                            @error('price')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-saas-group mb-0">
                            <label class="form-saas-label d-block mb-2">Pricing Type</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="d-flex align-items-center gap-2" style="cursor:pointer;">
                                    <input type="radio" name="type" value="subscription"
                                        {{ old('type', 'subscription') === 'subscription' ? 'checked' : '' }}>
                                    <span><strong>Subscription</strong> — recurring billing</span>
                                </label>
                                <label class="d-flex align-items-center gap-2" style="cursor:pointer;">
                                    <input type="radio" name="type" value="one_time"
                                        {{ old('type') === 'one_time' ? 'checked' : '' }}>
                                    <span><strong>One Time</strong> — single payment</span>
                                </label>
                            </div>
                            @error('type')
                                <div class="form-saas-error mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Integration --}}
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-link-45deg me-2"></i>Integration</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-saas-group">
                            <label class="form-saas-label">Webhook URL</label>
                            <input type="url" name="webhook_url"
                                class="form-saas-input @error('webhook_url') is-invalid @enderror"
                                value="{{ old('webhook_url') }}" placeholder="https://...">
                            @error('webhook_url')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-saas-group mb-0">
                            <label class="form-saas-label">Demo URL</label>
                            <input type="url" name="demo_url"
                                class="form-saas-input @error('demo_url') is-invalid @enderror"
                                value="{{ old('demo_url') }}" placeholder="https://...">
                            @error('demo_url')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-saas btn-saas-primary flex-fill">
                        <i class="bi bi-check2 me-1"></i>Save Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn-saas btn-saas-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function() {
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
                div.className = 'p-3 mb-3 rounded';
                div.style.cssText = 'border:1px solid var(--border);background:var(--surface-raised);';
                div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold" style="font-size:.9rem;color:var(--primary);">Plan #${idx + 1}</span>
                <button type="button" class="btn-saas btn-saas-danger btn-saas-sm btn-saas-icon" onclick="this.closest('[data-plan]').remove();updatePlanEmpty();" title="Remove">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-saas-group mb-0">
                        <label class="form-saas-label">Plan Name</label>
                        <input type="text" name="plans[${idx}][name]" class="form-saas-input" placeholder="e.g. Starter" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-saas-group mb-0">
                        <label class="form-saas-label">Duration (months)</label>
                        <input type="number" name="plans[${idx}][duration_months]" class="form-saas-input" value="1" min="1" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-saas-group mb-0">
                        <label class="form-saas-label">Price (Rp)</label>
                        <input type="number" name="plans[${idx}][price]" class="form-saas-input" value="0" min="0" step="100" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-saas-group mb-0">
                        <label class="form-saas-label">Discount (%)</label>
                        <input type="number" name="plans[${idx}][discount_percent]" class="form-saas-input" value="0" min="0" max="100" step="0.01">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-saas-group mb-0">
                        <label class="form-saas-label">Sort Order</label>
                        <input type="number" name="plans[${idx}][sort_order]" class="form-saas-input" value="${idx}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="plans[${idx}][is_active]" value="1" checked>
                        </div>
                        <span style="font-size:.85rem;color:var(--text-muted);">Active</span>
                    </div>
                </div>
            </div>
        `;
                div.setAttribute('data-plan', idx);
                // patch remove button to call closure
                div.querySelector('button').onclick = function() {
                    div.remove();
                    updateEmpty();
                };
                container.appendChild(div);
                updateEmpty();
            }

            document.getElementById('addPlanBtn').addEventListener('click', addPlan);
            // expose for inline onclick fallback
            window.updatePlanEmpty = updateEmpty;
            updateEmpty();
        })();
    </script>
@endpush
