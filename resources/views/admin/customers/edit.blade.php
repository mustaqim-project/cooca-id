@extends('layouts.admin')

@section('title', 'Edit Customer Account — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.customers.index') }}">Customers</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Customer Account</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $customer->name) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-input" required value="{{ old('email', $customer->email) }}">
            </div>
            <div class="form-group">
                <label class="form-label">New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Business / Company Name</label>
                <input type="text" name="business_name" class="form-input" value="{{ old('business_name', $customer->business_name) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Company Domain</label>
                <input type="text" name="domain" class="form-input" placeholder="e.g. clientdomain.com" value="{{ old('domain', $customer->domain) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone', $customer->phone) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Assigned Affiliate Partner</label>
                <select name="affiliator_id" class="form-select">
                    <option value="">No Affiliate Partner</option>
                    @foreach($affiliators as $aff)
                        <option value="{{ $aff->id }}" {{ old('affiliator_id', $customer->affiliator_id) == $aff->id ? 'selected' : '' }}>{{ $aff->name }} ({{ $aff->referral_code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Company Logo</label>
                @if($customer->logo_path)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ $customer->logo_url }}" alt="Logo" style="max-height: 50px; border-radius: 4px; border: 1px solid var(--border);">
                    </div>
                @endif
                <input type="file" name="logo_path" class="form-input" accept="image/*" style="padding: 10px;">
                <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">Upload new logo image to replace the current one (Max 2MB).</small>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Update Customer</button>
        </form>
    </div>
</div>
@endsection
