@extends('layouts.admin')

@section('title', 'Create Voucher')
@section('subtitle', 'Create a new promotional code or discount')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.vouchers.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Vouchers
        </a>
    </div>

    <form action="{{ route('admin.vouchers.store') }}" method="POST" class="form-confirm-submit">
        @csrf
        <div class="row g-4">
            {{-- Main form --}}
            <div class="col-lg-8">
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <div class="card-saas-title">Voucher Details</div>
                    </div>
                    <div class="card-saas-body">
                        <div class="row g-3">
                            {{-- Code --}}
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="code">Voucher Code <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="code" id="code"
                                            class="form-saas-input font-monospace @error('code') is-invalid @enderror"
                                            value="{{ old('code') }}" required style="text-transform:uppercase"
                                            placeholder="e.g. SAVE20">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="generateRandomCode()">
                                            <i class="bi bi-shuffle"></i> Generate
                                        </button>
                                    </div>
                                    @error('code')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- Name --}}
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="name">Internal Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="form-saas-input @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" required placeholder="e.g. Summer Sale 20%">
                                    @error('name')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- Description --}}
                            <div class="col-12">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="description">Description</label>
                                    <textarea name="description" id="description" rows="2"
                                        class="form-saas-textarea @error('description') is-invalid @enderror"
                                        placeholder="Visible to customers during checkout...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Discount configuration --}}
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <div class="card-saas-title">Discount Configuration</div>
                    </div>
                    <div class="card-saas-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="type">Discount Type <span
                                            class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-saas-select" required
                                        onchange="toggleDiscountFields()">
                                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                            Percentage (%)</option>
                                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount
                                            (Rp)</option>
                                    </select>
                                    @error('type')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="value">
                                        Discount Value <span class="text-danger">*</span>
                                        <span id="value-unit" class="text-muted ms-1">(%)</span>
                                    </label>
                                    <input type="number" name="value" id="value"
                                        class="form-saas-input @error('value') is-invalid @enderror"
                                        value="{{ old('value') }}" required step="0.01" min="0.01">
                                    @error('value')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6" id="max-discount-wrap">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="max_discount">Max Discount Cap (Rp)</label>
                                    <input type="number" name="max_discount" id="max_discount"
                                        class="form-saas-input @error('max_discount') is-invalid @enderror"
                                        value="{{ old('max_discount') }}" min="0" step="1"
                                        placeholder="Leave empty for no cap">
                                    <div class="form-saas-hint">Only applies to percentage discounts.</div>
                                    @error('max_discount')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="min_purchase">Minimum Purchase (Rp)</label>
                                    <input type="number" name="min_purchase" id="min_purchase"
                                        class="form-saas-input @error('min_purchase') is-invalid @enderror"
                                        value="{{ old('min_purchase', 0) }}" min="0" step="1">
                                    @error('min_purchase')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="max_usage">Total Usage Limit</label>
                                    <input type="number" name="max_usage" id="max_usage"
                                        class="form-saas-input @error('max_usage') is-invalid @enderror"
                                        value="{{ old('max_usage', 0) }}" min="0" step="1"
                                        placeholder="0 = unlimited">
                                    <div class="form-saas-hint">Total times this code can be used by anyone.</div>
                                    @error('max_usage')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="per_user_limit">Per-User Limit</label>
                                    <input type="number" name="per_user_limit" id="per_user_limit"
                                        class="form-saas-input @error('per_user_limit') is-invalid @enderror"
                                        value="{{ old('per_user_limit', 1) }}" min="1" step="1">
                                    <div class="form-saas-hint">Times a single customer can use this code.</div>
                                    @error('per_user_limit')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Validity --}}
                <div class="card-saas">
                    <div class="card-saas-header">
                        <div class="card-saas-title">Validity Period</div>
                    </div>
                    <div class="card-saas-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="valid_from">Valid From</label>
                                    <input type="datetime-local" name="valid_from" id="valid_from"
                                        class="form-saas-input @error('valid_from') is-invalid @enderror"
                                        value="{{ old('valid_from') }}">
                                    <div class="form-saas-hint">Leave empty to be valid immediately.</div>
                                    @error('valid_from')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="valid_until">Valid Until</label>
                                    <input type="datetime-local" name="valid_until" id="valid_until"
                                        class="form-saas-input @error('valid_until') is-invalid @enderror"
                                        value="{{ old('valid_until') }}">
                                    <div class="form-saas-hint">Leave empty for no expiry.</div>
                                    @error('valid_until')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card-saas">
                    <div class="card-saas-header">
                        <div class="card-saas-title">Publish</div>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                            <div class="form-saas-hint mt-1">Customers can use this voucher immediately when active.</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn-saas btn-saas-primary">
                                <i class="bi bi-check-lg me-1"></i> Create Voucher
                            </button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn-saas btn-saas-outline">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function generateRandomCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = '';
            for (let i = 0; i < 8; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
            document.getElementById('code').value = code;
        }

        function toggleDiscountFields() {
            const type = document.getElementById('type').value;
            const cap = document.getElementById('max-discount-wrap');
            const unit = document.getElementById('value-unit');
            if (type === 'percentage') {
                cap.style.display = '';
                unit.textContent = '(%)';
            } else {
                cap.style.display = 'none';
                unit.textContent = '(Rp)';
            }
        }

        toggleDiscountFields();
    </script>
@endpush
