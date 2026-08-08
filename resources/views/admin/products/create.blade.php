@extends('layouts.admin')

@section('title', 'Add New Product — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Add New SaaS Product</h1>
        <p class="page-subtitle">Configure software details, features, technology stack, and initial setup.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">← Back to Products</a>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" class="grid-31" enctype="multipart/form-data">
    @csrf
    
    <div class="flex-col gap-5">
        {{-- Main Info --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Product Details</div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-input" placeholder="e.g. COOCA POS Pro" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="product_category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label">Product Type *</label>
                    <select name="product_type" class="form-select" required>
                        @foreach(\App\Models\Product::TYPES as $key => $val)
                            <option value="{{ $key }}" {{ old('product_type', 'saas') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">License Type</label>
                    <select name="license_type" class="form-select">
                        <option value="">None / Default</option>
                        @foreach(\App\Models\Product::LICENSE_TYPES as $key => $val)
                            <option value="{{ $key }}" {{ old('license_type') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Current Version</label>
                <input type="text" name="version" class="form-input" placeholder="e.g. 1.0.0" value="{{ old('version') }}">
            </div>
            <input type="hidden" name="max_domains" value="1">

            <div class="form-group">
                <label class="form-label">Short Description</label>
                <input type="text" name="short_description" class="form-input" placeholder="Brief elevator pitch for product list..." value="{{ old('short_description') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Full Specification & Overview</label>
                <textarea name="description" class="form-textarea" rows="6" placeholder="Detailed product features, system capabilities, hardware specs...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">System Requirements</label>
                <textarea name="requirements" class="form-textarea" rows="3" placeholder="System requirements, e.g. PHP >= 8.2, MySQL >= 8.0">{{ old('requirements') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label">Base Price (IDR)</label>
                    <input type="number" name="base_price" class="form-input" placeholder="0" value="{{ old('base_price', 0) }}">
                </div>
                <div class="form-group" id="maintenance_fee_group" style="display: none;">
                    <label class="form-label">Maintenance Fee (IDR)</label>
                    <input type="number" name="maintenance_fee" class="form-input" placeholder="0" value="{{ old('maintenance_fee', 0) }}">
                </div>
            </div>
            <input type="hidden" name="setup_fee" value="0">

            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', 0) }}">
            </div>
        </div>
        </div>
    </div>

    {{-- Product Features --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Product Features</div>
        </div>
        <div class="card-body">
            <div id="features-container" class="flex-col gap-3">
                @if(old('features'))
                    @foreach(old('features') as $index => $feature)
                        <div class="feature-item grid-31" style="align-items: start; gap: 10px; background: var(--background); padding: 12px; border-radius: 8px; border: 1px solid var(--border);">
                            <div class="flex-col gap-2">
                                <input type="text" name="features[{{ $index }}][title]" class="form-input" placeholder="Feature Title" value="{{ $feature['title'] ?? '' }}" required>
                                <input type="text" name="features[{{ $index }}][description]" class="form-input" placeholder="Feature Description" value="{{ $feature['description'] ?? '' }}">
                            </div>
                            <div class="flex-col gap-2 icon-picker-wrapper" style="position: relative;">
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <div class="icon-preview-box" style="width: 38px; height: 38px; border: 1px solid var(--border); border-radius: 6px; display: flex; align-items: center; justify-content: center; background: var(--surface); cursor: pointer;" title="Click to pick icon">
                                        <i class="{{ $feature['icon'] ?? 'fa-solid fa-sparkles' }}"></i>
                                    </div>
                                    <input type="text" name="features[{{ $index }}][icon]" class="form-input icon-input" placeholder="Icon Class" value="{{ $feature['icon'] ?? 'fa-solid fa-sparkles' }}" style="flex: 1;">
                                </div>
                                <div class="icon-grid-dropdown" style="display: none; position: absolute; top: 44px; right: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 12px; width: 240px; grid-template-columns: repeat(5, 1fr); gap: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 10;">
                                </div>
                                <button type="button" class="btn btn-outline btn-remove-feature w-full" style="color: var(--danger); border-color: var(--danger); padding: 6px 12px; font-size: 12px;">Remove</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="feature-item grid-31" style="align-items: start; gap: 10px; background: var(--background); padding: 12px; border-radius: 8px; border: 1px solid var(--border);">
                        <div class="flex-col gap-2">
                            <input type="text" name="features[0][title]" class="form-input" placeholder="Feature Title" required>
                            <input type="text" name="features[0][description]" class="form-input" placeholder="Feature Description">
                        </div>
                        <div class="flex-col gap-2 icon-picker-wrapper" style="position: relative;">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <div class="icon-preview-box" style="width: 38px; height: 38px; border: 1px solid var(--border); border-radius: 6px; display: flex; align-items: center; justify-content: center; background: var(--surface); cursor: pointer;" title="Click to pick icon">
                                    <i class="fa-solid fa-sparkles"></i>
                                </div>
                                <input type="text" name="features[0][icon]" class="form-input icon-input" placeholder="Icon Class" value="fa-solid fa-sparkles" style="flex: 1;">
                            </div>
                            <div class="icon-grid-dropdown" style="display: none; position: absolute; top: 44px; right: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 12px; width: 240px; grid-template-columns: repeat(5, 1fr); gap: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 10;">
                            </div>
                            <button type="button" class="btn btn-outline btn-remove-feature w-full" style="color: var(--danger); border-color: var(--danger); padding: 6px 12px; font-size: 12px;">Remove</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" id="btn-add-feature" class="btn btn-outline mt-3 w-full border-dashed" style="justify-content: center;">
                <i class="fa-solid fa-plus"></i> Add Feature
            </button>
        </div>
    </div>

    </div> <!-- Close left column -->

    {{-- Sidebar Config --}}
    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Publishing & Status</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active (Visible on Landing)</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive / Draft</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Featured Badge</label>
                    <select name="is_featured" class="form-select">
                        <option value="0">Standard Product</option>
                        <option value="1">Featured / Popular Product</option>
                    </select>
                </div>

                <input type="hidden" name="is_bundleable" value="0">

                <div class="form-group">
                    <label class="form-label">Product Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-input" accept="image/*" style="padding: 10px;">
                    <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">Upload the main image for this product (Max 2MB).</small>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-4">
                    <span>💾</span> Save Product
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('features-container');
        const btnAdd = document.getElementById('btn-add-feature');
        let featureIndex = container.children.length;

        const popularIcons = [
            'fa-solid fa-sparkles', 'fa-solid fa-bolt', 'fa-solid fa-shield', 'fa-solid fa-star', 'fa-solid fa-check',
            'fa-solid fa-rocket', 'fa-solid fa-chart-line', 'fa-solid fa-mobile-screen', 'fa-solid fa-users', 'fa-solid fa-headset',
            'fa-solid fa-credit-card', 'fa-solid fa-globe', 'fa-solid fa-cloud', 'fa-solid fa-lock', 'fa-solid fa-server',
            'fa-solid fa-code', 'fa-solid fa-bag-shopping', 'fa-solid fa-cart-shopping', 'fa-solid fa-bell', 'fa-solid fa-envelope',
            'fa-solid fa-calendar', 'fa-solid fa-file-invoice', 'fa-solid fa-wallet', 'fa-solid fa-chart-pie', 'fa-solid fa-tags'
        ];

        function populateGrid(dropdown) {
            if (dropdown.innerHTML.trim() !== '') return;
            let html = '';
            popularIcons.forEach(icon => {
                html += `<div class="icon-option" data-icon="${icon}" style="display:flex; align-items:center; justify-content:center; padding:8px; border-radius:4px; cursor:pointer; background:rgba(0,0,0,0.05);" onmouseover="this.style.background='rgba(79,70,229,0.1)'" onmouseout="this.style.background='rgba(0,0,0,0.05)'"><i class="${icon}"></i></div>`;
            });
            dropdown.innerHTML = html;
        }

        // Initialize existing dropdowns
        document.querySelectorAll('.icon-grid-dropdown').forEach(populateGrid);

        btnAdd.addEventListener('click', function() {
            const template = `
                <div class="feature-item grid-31" style="align-items: start; gap: 10px; background: var(--background); padding: 12px; border-radius: 8px; border: 1px solid var(--border);">
                    <div class="flex-col gap-2">
                        <input type="text" name="features[${featureIndex}][title]" class="form-input" placeholder="Feature Title" required>
                        <input type="text" name="features[${featureIndex}][description]" class="form-input" placeholder="Feature Description">
                    </div>
                    <div class="flex-col gap-2 icon-picker-wrapper" style="position: relative;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <div class="icon-preview-box" style="width: 38px; height: 38px; border: 1px solid var(--border); border-radius: 6px; display: flex; align-items: center; justify-content: center; background: var(--surface); cursor: pointer;" title="Click to pick icon">
                                <i class="fa-solid fa-sparkles"></i>
                            </div>
                            <input type="text" name="features[${featureIndex}][icon]" class="form-input icon-input" placeholder="Icon Class" value="fa-solid fa-sparkles" style="flex: 1;">
                        </div>
                        <div class="icon-grid-dropdown" style="display: none; position: absolute; top: 44px; right: 0; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 12px; width: 240px; grid-template-columns: repeat(5, 1fr); gap: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 10;">
                        </div>
                        <button type="button" class="btn btn-outline btn-remove-feature w-full" style="color: var(--danger); border-color: var(--danger); padding: 6px 12px; font-size: 12px;">Remove</button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            populateGrid(container.lastElementChild.querySelector('.icon-grid-dropdown'));
            featureIndex++;
        });

        container.addEventListener('input', function(e) {
            if (e.target.classList.contains('icon-input')) {
                const wrapper = e.target.closest('.icon-picker-wrapper');
                const previewIcon = wrapper.querySelector('.icon-preview-box i');
                if (previewIcon) {
                    previewIcon.className = e.target.value || 'fa-solid fa-sparkles';
                }
            }
        });

        container.addEventListener('click', function(e) {
            // Remove button
            if (e.target.closest('.btn-remove-feature')) {
                e.target.closest('.feature-item').remove();
                return;
            }

            // Open Dropdown
            const previewBox = e.target.closest('.icon-preview-box');
            if (previewBox) {
                const wrapper = previewBox.closest('.icon-picker-wrapper');
                const dropdown = wrapper.querySelector('.icon-grid-dropdown');
                
                // Close all other dropdowns
                document.querySelectorAll('.icon-grid-dropdown').forEach(d => {
                    if (d !== dropdown) d.style.display = 'none';
                });

                dropdown.style.display = dropdown.style.display === 'grid' ? 'none' : 'grid';
                return;
            }

            // Select Icon
            const iconOption = e.target.closest('.icon-option');
            if (iconOption) {
                const iconClass = iconOption.getAttribute('data-icon');
                const wrapper = iconOption.closest('.icon-picker-wrapper');
                
                wrapper.querySelector('.icon-input').value = iconClass;
                wrapper.querySelector('.icon-preview-box i').className = iconClass;
                wrapper.querySelector('.icon-grid-dropdown').style.display = 'none';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.icon-picker-wrapper')) {
                document.querySelectorAll('.icon-grid-dropdown').forEach(d => d.style.display = 'none');
            }
        });

        // Toggle maintenance fee visibility based on product type
        const productTypeSelect = document.querySelector('select[name="product_type"]');
        const maintenanceGroup = document.getElementById('maintenance_fee_group');
        
        function toggleMaintenanceFee() {
            if (productTypeSelect && maintenanceGroup) {
                if (productTypeSelect.value === 'lifetime') {
                    maintenanceGroup.style.display = 'block';
                } else {
                    maintenanceGroup.style.display = 'none';
                }
            }
        }
        
        if (productTypeSelect) {
            productTypeSelect.addEventListener('change', toggleMaintenanceFee);
            toggleMaintenanceFee();
        }
    });
</script>
@endpush
