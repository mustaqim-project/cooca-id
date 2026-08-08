@extends('layouts.admin')

@section('title', 'Edit Affiliator — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.affiliators.index') }}">Affiliators</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Affiliate Partner</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.affiliators.show', $affiliator->id ?? 1) }}" class="btn btn-outline">← Back to Detail</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.affiliators.update', $affiliator->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')

            <p class="text-xs text-muted font-bold uppercase mb-3" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Account Info</p>

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $affiliator->name ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" required value="{{ old('email', $affiliator->email ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">New Password <span class="text-muted" style="font-weight: normal;">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone', $affiliator->phone ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Account Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $affiliator->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ old('status', $affiliator->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Referral Settings</p>

            <div class="form-group">
                <label class="form-label">Referral Code</label>
                <input type="text" name="referral_code" class="form-input" value="{{ old('referral_code', $affiliator->referral_code ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Commission Rate (%)</label>
                <input type="number" step="0.1" min="0" max="100" name="commission_rate" class="form-input" value="{{ old('commission_rate', $affiliator->commission_rate ?? 25) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Upline Partner</label>
                <select name="parent_affiliator_id" class="form-select">
                    <option value="">No Upline (Top-level Partner)</option>
                    @foreach($allAffiliators as $aff)
                        <option value="{{ $aff->id }}" {{ old('parent_affiliator_id', $affiliator->parent_affiliator_id) == $aff->id ? 'selected' : '' }}>{{ $aff->name }} ({{ $aff->referral_code }})</option>
                    @endforeach
                </select>
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Bank / Payment Details</p>

            <div class="form-group">
                <label class="form-label">Bank Name</label>
                <input type="text" name="bank_name" class="form-input" placeholder="e.g. BCA, Mandiri, BRI" value="{{ old('bank_name', $affiliator->bank_name ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Bank Account Number</label>
                <input type="text" name="bank_account" class="form-input" value="{{ old('bank_account', $affiliator->bank_account ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Wallet Balance (Rp)</label>
                <input type="number" name="balance" class="form-input" min="0" value="{{ old('balance', $affiliator->balance ?? 0) }}">
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">
                <span>💾</span> Update Partner
            </button>
        </form>
    </div>
</div>
@endsection
