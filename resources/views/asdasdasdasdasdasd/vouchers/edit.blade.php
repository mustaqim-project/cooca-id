@extends('layouts.admin')

@section('title', 'Edit Voucher')
@section('subtitle', 'Update promotional code details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.vouchers.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Vouchers
        </a>
    </div>

    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST" class="form-confirm-submit">
        @csrf
        @method('PUT')
        <div class="row g-4">
            {{-- Main form --}}
            <div class="col-lg-8">
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <div class="card-saas-title">Voucher Details</div>
                    </div>
                    <div class="card-saas-body">
                        <div class="row g-3">
                            {{-- Code (readonly) --}}
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="code">Voucher Code</label>
                                    <div class="input-group">
                                        <input type="text" name="code" id="code"
                                            class="form-saas-input font-monospace" value="{{ $voucher->code }}" readonly
                                            style="text-transform:uppercase;background:var(--surface-raised)">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    </div>
                                    <div class="form-saas-hint">Code cannot be changed once created.</div>
                                </div>
                            </div>
                            {{-- Name --}}
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="name">Internal Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="form-saas-input @error('name') is-invalid @enderror"
                                        value="{{ old('name', $voucher->name) }}" required>
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
                                        class="form-saas-textarea @error('description') is-invalid @enderror">{{ old('description', $voucher->description) }}</textarea>
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
                                        <option value="percentage"
                                            {{ old('type', $voucher->type) == 'percentage' ? 'selected' : '' }}>Percentage
                                            (%)</option>
                                        <option value="fixed"
                                            {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)
                                        </option>
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
                                        <span id="value-unit" class="text-muted ms-1"></span>
                                    </label>
                                    <input type="number" name="value" id="value"
                                        class="form-saas-input @error('value') is-invalid @enderror"
                                        value="{{ old('value', floatval($voucher->value)) }}" required step="0.01"
                                        min="0.01">
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
                                        value="{{ old('max_discount', floatval($voucher->max_discount)) }}" min="0"
                                        step="1">
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
                                        value="{{ old('min_purchase', floatval($voucher->min_purchase)) }}"
                                        min="0" step="1">
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
                                        value="{{ old('max_usage', $voucher->max_usage) }}" min="0"
                                        step="1" placeholder="0 = unlimited">
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
                                        value="{{ old('per_user_limit', $voucher->per_user_limit) }}" min="1"
                                        step="1">
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
                                        value="{{ old('valid_from', $voucher->valid_from ? \Carbon\Carbon::parse($voucher->valid_from)->format('Y-m-d\TH:i') : '') }}">
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
                                        value="{{ old('valid_until', $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->format('Y-m-d\TH:i') : '') }}">
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
                <div class="card-saas mb-4">
                    <div class="card-saas-header">
                        <div class="card-saas-title">Status</div>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn-saas btn-saas-primary">
                                <i class="bi bi-check-lg me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="btn-saas btn-saas-outline">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-saas">
                    <div class="card-saas-header">
                        <div class="card-saas-title">Usage Stats</div>
                    </div>
                    <div class="card-saas-body">
                        @php
                            $usedPct =
                                $voucher->max_usage > 0
                                    ? min(100, ($voucher->used_count / $voucher->max_usage) * 100)
                                    : 0;
                        @endphp
                        <div class="d-flex justify-content-between mb-1" style="font-size:.85rem">
                            <span class="text-muted">Used</span>
                            <span class="fw-semibold">{{ $voucher->used_count }} /
                                {{ $voucher->max_usage > 0 ? $voucher->max_usage : '∞' }}</span>
                        </div>
                        @if ($voucher->max_usage > 0)
                            <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                                <div
                                    style="width:{{ $usedPct }}%;height:100%;background:var(--primary);border-radius:3px">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
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
