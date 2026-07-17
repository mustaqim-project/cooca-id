@extends('admin.layouts.app')

@section('title', 'Create Voucher')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.vouchers.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Create Voucher</h2>
                <p class="text-secondary mb-0">Fill in the details to create a new promotional code.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.vouchers.store') }}" method="POST" class="d-flex flex-column gap-4">
                @csrf

                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent text-uppercase @error('code') is-invalid @enderror"
                                id="code" name="code" placeholder="Code" value="{{ old('code') }}" required>
                            <label for="code">Voucher Code (e.g. PROMO2026)</label>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select
                                class="form-select rounded-3 shadow-none border bg-transparent @error('type') is-invalid @enderror"
                                id="type" name="type" required>
                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage
                                    (%)</option>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)
                                </option>
                            </select>
                            <label for="type">Discount Type</label>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('value') is-invalid @enderror"
                                id="value" name="value" placeholder="Value" value="{{ old('value') }}" min="0"
                                required>
                            <label for="value">Discount Value</label>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="number"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('max_uses') is-invalid @enderror"
                                id="max_uses" name="max_uses" placeholder="Max Uses" value="{{ old('max_uses') }}"
                                min="1">
                            <label for="max_uses">Maximum Uses (Leave empty for unlimited)</label>
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="date"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('expires_at') is-invalid @enderror"
                                id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            <label for="expires_at">Expiration Date</label>
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input shadow-none @error('is_active') is-invalid @enderror"
                                type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 mt-1" for="is_active">Voucher is Active</label>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Save Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
