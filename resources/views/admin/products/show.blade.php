@extends('admin.layouts.app')

@section('title', 'Product: ' . ($product->name ?? 'Product'))

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold">{{ $product->name ?? 'Product' }}</h2>
                    <p class="text-secondary mb-0">View full information and activity.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product->id) }}"
                    class="btn btn-light border rounded-pill px-4 hover-lift"><i class="bi bi-pencil me-2"></i>Edit</a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm"
                        onclick="return confirm('Are you sure you want to delete this product?');">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    @if ($product->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                            class="rounded-4 shadow-sm mb-3 object-fit-cover" style="width:80px;height:80px;">
                    @else
                        <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    @endif
                    <h4 class="fw-bold mb-1">{{ $product->name }}</h4>
                    <p class="text-secondary mb-3">ID: #{{ $product->id }}</p>
                    <div>
                        @if ($product->is_active)
                            <span
                                class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Active</span>
                        @else
                            <span
                                class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border border-secondary-subtle">Inactive</span>
                        @endif
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span class="fw-medium fs-7">{{ $product->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span class="fw-medium fs-7">{{ $product->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Detailed Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="text-secondary fs-7 mb-1 d-block">Description</label>
                                <div class="p-3 bg-light rounded-3 border" style="white-space: pre-wrap; line-height: 1.7;">
                                    {{ $product->description ?: 'No description provided.' }}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Product Type</label>
                                <div class="fw-medium">
                                    <span
                                        class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">{{ $product->product_type_label }}</span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Category</label>
                                <div class="fw-medium">
                                    {{ $product->category->name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Base Price</label>
                                <div class="fw-medium">
                                    Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Demo URL</label>
                                <div class="fw-medium">
                                    @if ($product->demo_url)
                                        <a href="{{ $product->demo_url }}" target="_blank" rel="noopener"
                                            class="text-decoration-none">{{ $product->demo_url }}</a>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-secondary fs-7 mb-1 d-block">Webhook URL</label>
                                <div class="fw-medium">
                                    @if ($product->webhook_url)
                                        <a href="{{ $product->webhook_url }}" target="_blank" rel="noopener"
                                            class="text-decoration-none">{{ $product->webhook_url }}</a>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Business Configuration --}}
                        @if (
                            $product->license_type ||
                                $product->version ||
                                $product->setup_fee > 0 ||
                                $product->maintenance_fee > 0 ||
                                $product->requirements ||
                                $product->is_bundleable ||
                                $product->max_domains > 1)
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-gear me-2 text-primary"></i>Business Configuration</h6>
                            <div class="row g-4">
                                @if (in_array($product->product_type, ['license', 'saas', 'lifetime']))
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">License Type</label>
                                        <div class="fw-medium">{{ $product->license_type_label ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Max Domains</label>
                                        <div class="fw-medium">{{ $product->max_domains }}</div>
                                    </div>
                                @endif

                                @if (in_array($product->product_type, ['saas', 'lifetime', 'license', 'addon']))
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Version</label>
                                        <div class="fw-medium">{{ $product->version ?? '—' }}</div>
                                    </div>
                                @endif

                                @if (in_array($product->product_type, ['custom_dev', 'project', 'maintenance']))
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Setup Fee</label>
                                        <div class="fw-medium">Rp
                                            {{ number_format($product->setup_fee ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Maintenance Fee</label>
                                        <div class="fw-medium">Rp
                                            {{ number_format($product->maintenance_fee ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                @endif

                                @if (in_array($product->product_type, ['custom_dev', 'project']))
                                    <div class="col-12">
                                        <label class="text-secondary fs-7 mb-1 d-block">Requirements</label>
                                        <div class="p-3 bg-light rounded-3 border"
                                            style="white-space: pre-wrap; line-height: 1.7;">
                                            {{ $product->requirements ?: '—' }}</div>
                                    </div>
                                @endif

                                @if (in_array($product->product_type, ['saas', 'lifetime', 'license', 'addon', 'subscription']))
                                    <div class="col-sm-6">
                                        <label class="text-secondary fs-7 mb-1 d-block">Bundleable</label>
                                        <div class="fw-medium">
                                            @if ($product->is_bundleable)
                                                <span
                                                    class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Yes</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1">No</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pricing Plans Manager --}}
                <div class="card border-0 shadow-sm rounded-4 glass mt-4">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-tags me-2 text-primary"></i>Pricing Plans</h5>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="addPlanShowBtn">
                            <i class="bi bi-plus-lg me-1"></i>Add Plan
                        </button>
                    </div>
                    <div class="card-body p-4">
                        {{-- Add New Plan (hidden by default) --}}
                        <form action="{{ route('admin.products.plans.store', $product) }}" method="POST"
                            id="addPlanShowForm" class="mb-4 p-3 rounded-3 d-none"
                            style="background:var(--color-bg);border:1px solid var(--color-border);">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="text-secondary fs-7 mb-1 d-block">Plan Name</label>
                                    <input type="text" name="name"
                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                        placeholder="e.g. Pro Annual" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-secondary fs-7 mb-1 d-block">Duration (months)</label>
                                    <input type="number" name="duration_months"
                                        class="form-control rounded-3 shadow-none border bg-transparent" value="1"
                                        min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-secondary fs-7 mb-1 d-block">Price (Rp)</label>
                                    <input type="number" name="price"
                                        class="form-control rounded-3 shadow-none border bg-transparent" value="0"
                                        min="0" step="100" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="text-secondary fs-7 mb-1 d-block">Discount (%)</label>
                                    <input type="number" name="discount_percent"
                                        class="form-control rounded-3 shadow-none border bg-transparent" value="0"
                                        min="0" max="100" step="0.01">
                                </div>
                                <div class="col-md-2">
                                    <label class="text-secondary fs-7 mb-1 d-block">Sort Order</label>
                                    <input type="number" name="sort_order"
                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                        value="{{ $product->subscriptionPlans->count() }}">
                                </div>
                                <div class="col-md-1 d-flex align-items-end gap-1">
                                    <div class="form-check form-switch mb-2" title="Active">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            checked>
                                    </div>
                                    <button type="submit"
                                        class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Save">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Cancel"
                                        onclick="document.getElementById('addPlanShowForm').classList.add('d-none');">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        @forelse($product->subscriptionPlans as $plan)
                            <div class="mb-3 rounded-3 border overflow-hidden" id="plan-show-row-{{ $plan->id }}">
                                {{-- VIEW MODE --}}
                                <div id="plan-show-view-{{ $plan->id }}" class="p-3">
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
                                            <div class="d-flex gap-3 text-secondary fs-7 mt-1">
                                                <span><i class="bi bi-calendar3 me-1"></i>{{ $plan->duration_months }}
                                                    month(s)</span>
                                                <span><i class="bi bi-tag me-1"></i>Rp
                                                    {{ number_format($plan->price, 0, ',', '.') }}</span>
                                                @if ($plan->discount_percent > 0)
                                                    <span class="text-success">{{ (float) $plan->discount_percent }}%
                                                        off</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <button type="button"
                                                class="btn btn-sm btn-light border-0 rounded-circle p-2"
                                                onclick="toggleShowPlanEdit('{{ $plan->id }}')" title="Edit">
                                                <i class="bi bi-pencil text-primary"></i>
                                            </button>
                                            <form action="{{ route('admin.products.plans.toggle', [$product, $plan]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-light border-0 rounded-circle p-2"
                                                    title="{{ $plan->is_active ? 'Disable' : 'Enable' }}">
                                                    <i
                                                        class="bi {{ $plan->is_active ? 'bi-toggle-on text-success' : 'bi-toggle-off text-secondary' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.products.plans.destroy', [$product, $plan]) }}"
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
                                <div id="plan-show-edit-{{ $plan->id }}" class="d-none p-3"
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
                                                    value="{{ $plan->price }}" min="0" step="100" required>
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
                                                    onclick="toggleShowPlanEdit('{{ $plan->id }}')">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-3" id="plansShowEmpty">
                                <i class="bi bi-tags fs-3 d-block mb-2 opacity-50"></i>
                                <p class="fs-7 mb-0">No pricing plans for this product.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('addPlanShowBtn').addEventListener('click', function() {
            document.getElementById('addPlanShowForm').classList.toggle('d-none');
        });

        function toggleShowPlanEdit(id) {
            const view = document.getElementById('plan-show-view-' + id);
            const edit = document.getElementById('plan-show-edit-' + id);
            if (view) view.classList.toggle('d-none');
            if (edit) edit.classList.toggle('d-none');
        }
    </script>
@endpush
