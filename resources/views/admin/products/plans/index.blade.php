@extends('admin.layouts.app')

@section('title', 'Plans - ' . ($product->name ?? 'Product'))

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold">Pricing Plans</h2>
                    <p class="text-secondary mb-0">
                        {{ $product->name ?? 'Product' }} — manage subscription tiers and pricing.
                    </p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product->id) }}#plans"
                    class="btn btn-primary rounded-pill px-3 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Add Plan
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search plans...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">#</th>
                            <th class="py-3 px-3 border-0">Plan Name</th>
                            <th class="py-3 px-3 border-0">Duration</th>
                            <th class="py-3 px-3 border-0">Price</th>
                            <th class="py-3 px-3 border-0">Discount</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($plans as $plan)
                            <tr id="plan-row-{{ $plan->id }}">
                                {{-- VIEW MODE --}}
                                <td class="py-3 px-4 text-secondary fs-7 plan-view">{{ $loop->iteration }}</td>
                                <td class="py-3 px-3 fw-medium plan-view">{{ $plan->name }}</td>
                                <td class="py-3 px-3 text-secondary fs-7 plan-view">
                                    {{ $plan->duration_months }} month(s)
                                </td>
                                <td class="py-3 px-3 fw-medium plan-view">
                                    Rp {{ number_format($plan->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7 plan-view">
                                    @if ($plan->discount_percent > 0)
                                        <span class="text-success">{{ (float) $plan->discount_percent }}%</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-3 plan-view">
                                    @if ($plan->is_active)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Active</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-end plan-view">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="button"
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Edit"
                                            onclick="togglePlanEdit('{{ $plan->id }}')">
                                            <i class="bi bi-pencil text-primary"></i>
                                        </button>
                                        <form action="{{ route('admin.products.plans.toggle', [$product, $plan]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;"
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
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this plan?');">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                                {{-- EDIT MODE --}}
                                <td colspan="7" class="p-0 plan-edit d-none">
                                    <form action="{{ route('admin.products.plans.update', [$product, $plan]) }}"
                                        method="POST" class="p-3 rounded-3 m-2"
                                        style="background:var(--color-bg);border:1px solid var(--color-border);">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="text-secondary fs-7 mb-1 d-block">Plan Name</label>
                                                <input type="text" name="name"
                                                    class="form-control rounded-3 shadow-none border bg-transparent"
                                                    value="{{ $plan->name }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="text-secondary fs-7 mb-1 d-block">Duration (months)</label>
                                                <input type="number" name="duration_months"
                                                    class="form-control rounded-3 shadow-none border bg-transparent"
                                                    value="{{ $plan->duration_months }}" min="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="text-secondary fs-7 mb-1 d-block">Price (Rp)</label>
                                                <input type="number" name="price"
                                                    class="form-control rounded-3 shadow-none border bg-transparent"
                                                    value="{{ $plan->price }}" min="0" step="100" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="text-secondary fs-7 mb-1 d-block">Discount (%)</label>
                                                <input type="number" name="discount_percent"
                                                    class="form-control rounded-3 shadow-none border bg-transparent"
                                                    value="{{ (float) $plan->discount_percent }}" min="0"
                                                    max="100" step="0.01">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="text-secondary fs-7 mb-1 d-block">Sort Order</label>
                                                <input type="number" name="sort_order"
                                                    class="form-control rounded-3 shadow-none border bg-transparent"
                                                    value="{{ $plan->sort_order }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end gap-1">
                                                <div class="form-check form-switch mb-2" title="Active">
                                                    <input class="form-check-input" type="checkbox" name="is_active"
                                                        value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;" title="Save">
                                                    <i class="bi bi-check2"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;" title="Cancel"
                                                    onclick="togglePlanEdit('{{ $plan->id }}')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-tags fs-1"></i></div>
                                    <h6 class="fw-medium">No Plans Found</h6>
                                    <p class="fs-7">This product does not have any pricing plans yet.</p>
                                    <a href="{{ route('admin.products.edit', $product->id) }}#plans"
                                        class="btn btn-sm btn-primary rounded-pill px-3 mt-2">Add Plan</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($plans) && method_exists($plans, 'hasPages') && $plans->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $plans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePlanEdit(id) {
            const row = document.getElementById('plan-row-' + id);
            if (!row) return;
            row.querySelectorAll('.plan-view').forEach(el => el.classList.toggle('d-none'));
            row.querySelectorAll('.plan-edit').forEach(el => el.classList.toggle('d-none'));
        }
    </script>
@endpush
