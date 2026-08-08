@extends('layouts.admin')

@section('title', 'Create Voucher — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.vouchers.index') }}">Vouchers</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Create Voucher Code</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Coupon Code *</label>
                <input type="text" name="code" class="form-input" placeholder="e.g. PROMO2026" required value="{{ old('code') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Discount Type</label>
                <select name="type" class="form-select">
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount (IDR)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Discount Value *</label>
                <input type="number" name="discount_amount" class="form-input" required placeholder="e.g. 20 for 20%" value="{{ old('discount_amount') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Maximum Uses</label>
                <input type="number" name="max_uses" class="form-input" placeholder="0 for unlimited" value="{{ old('max_uses') }}">
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">🎟️ Save Voucher</button>
        </form>
    </div>
</div>
@endsection
