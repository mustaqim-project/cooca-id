@extends('layouts.admin')

@section('title', 'Create Customer Account — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.customers.index') }}">Customers</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Create Customer Account</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-input" required value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Business / Company Name</label>
                <input type="text" name="business_name" class="form-input" value="{{ old('business_name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Company Domain</label>
                <input type="text" name="domain" class="form-input" placeholder="e.g. clientdomain.com" value="{{ old('domain') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Assigned Affiliate Partner</label>
                <select name="affiliator_id" class="form-select">
                    <option value="">No Affiliate Partner</option>
                    @foreach($affiliators as $aff)
                        <option value="{{ $aff->id }}" {{ old('affiliator_id') == $aff->id ? 'selected' : '' }}>{{ $aff->name }} ({{ $aff->referral_code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Company Logo</label>
                <input type="file" name="logo_path" class="form-input" accept="image/*" style="padding: 10px;">
                <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">Upload company logo image (Max 2MB).</small>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">👤 Create Customer</button>
        </form>
    </div>
</div>
@endsection
