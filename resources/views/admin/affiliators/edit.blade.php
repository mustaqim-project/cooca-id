@extends('admin.layouts.app')

@section('title', 'Edit Affiliator')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.affiliators.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Edit Affiliator</h2>
                <p class="text-secondary mb-0">Update affiliator information and settings.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.affiliators.update', $affiliator->id) }}" method="POST"
                class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-floating">
                            <select
                                class="form-select rounded-3 shadow-none border bg-transparent @error('user_id') is-invalid @enderror"
                                id="user_id" name="user_id" required>
                                <option value="">Select a user...</option>
                                @foreach ($users ?? [] as $user)
                                    <option value="{{ $user->id }}"
                                        {{ (old('user_id') ?? $affiliator->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <label for="user_id">User *</label>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('affiliate_code') is-invalid @enderror"
                                id="affiliate_code" name="affiliate_code"
                                value="{{ old('affiliate_code', $affiliator->affiliate_code) }}"
                                placeholder="Affiliate Code" required>
                            <label for="affiliate_code">Affiliate Code *</label>
                            @error('affiliate_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-secondary mt-2"><i class="bi bi-info-circle me-1"></i> Custom unique
                                code for this affiliator (e.g. USERNAME20).</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select
                                class="form-select rounded-3 shadow-none border bg-transparent @error('is_active') is-invalid @enderror"
                                id="is_active" name="is_active" required>
                                <option value="1"
                                    {{ (old('is_active') ?? $affiliator->is_active) == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="0"
                                    {{ (old('is_active') ?? $affiliator->is_active) == '0' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            <label for="is_active">Status *</label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="number" step="0.01"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('discount_percent') is-invalid @enderror"
                                id="discount_percent" name="discount_percent"
                                value="{{ old('discount_percent', $affiliator->discount_percent) }}"
                                placeholder="Discount Percent">
                            <label for="discount_percent">Discount Percent (%)</label>
                            @error('discount_percent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="number" step="0.01"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('discount_amount') is-invalid @enderror"
                                id="discount_amount" name="discount_amount"
                                value="{{ old('discount_amount', $affiliator->discount_amount) }}"
                                placeholder="Discount Amount">
                            <label for="discount_amount">Discount Amount (Fixed)</label>
                            @error('discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.affiliators.index') }}"
                        class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Update Affiliator
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
