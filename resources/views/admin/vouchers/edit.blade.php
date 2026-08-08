@extends('layouts.admin')

@section('title', 'Edit Voucher — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.vouchers.index') }}">Vouchers</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Voucher</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.vouchers.update', $voucher->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Coupon Code *</label>
                <input type="text" name="code" class="form-input" required value="{{ old('code', $voucher->code ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Discount Value *</label>
                <input type="number" name="discount_amount" class="form-input" required value="{{ old('discount_amount', $voucher->discount_amount ?? $voucher->value ?? 10) }}">
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection
