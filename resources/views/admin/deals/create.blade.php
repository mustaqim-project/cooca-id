@extends('layouts.admin')

@section('title', 'Add New Deal — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.deals.index') }}">Deals</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Add New Sales Deal</h1>
        <p class="page-subtitle">Register a new opportunity in the sales pipeline.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.deals.index') }}" class="btn btn-outline">← Back</a>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form action="{{ route('admin.deals.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <p class="text-xs text-muted font-bold uppercase mb-3" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Deal Info</p>

            <div class="form-group">
                <label class="form-label">Deal Name / Client Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}" placeholder="e.g. PT Maju Bersama — Enterprise SaaS">
            </div>
            <div class="form-group">
                <label class="form-label">Contact Phone Number</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="e.g. 08xx-xxxx-xxxx">
            </div>
            <div class="form-group">
                <label class="form-label">Estimated Deal Value (Rp)</label>
                <input type="number" name="price" class="form-input" value="{{ old('price', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">Lead Source</label>
                <input type="text" name="sources" class="form-input" value="{{ old('sources') }}" placeholder="e.g. Referral, Website, Cold Call">
            </div>
            <div class="form-group">
                <label class="form-label">Products of Interest</label>
                <input type="text" name="products" class="form-input" value="{{ old('products') }}" placeholder="e.g. ERP Module, CRM, Payroll">
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Pipeline Assignment</p>

            <div class="form-group">
                <label class="form-label">Pipeline *</label>
                <select name="pipeline_id" class="form-select" required>
                    <option value="">-- Select Pipeline --</option>
                    @foreach($pipelines as $pipeline)
                        <option value="{{ $pipeline->id }}" {{ old('pipeline_id') == $pipeline->id ? 'selected' : '' }}>{{ $pipeline->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Stage *</label>
                <select name="stage_id" class="form-select" required>
                    <option value="">-- Select Stage --</option>
                    @foreach($stages as $stage)
                        <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                    @endforeach
                </select>
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Contract & Docs</p>

            <div class="form-group">
                <label class="form-label">Link to Existing Contract</label>
                <select name="contract_id" class="form-select">
                    <option value="">No contract linked</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                            {{ $contract->contract_number ?? $contract->id }} — {{ $contract->customer->name ?? 'Unknown Client' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Agreement Document (PDF/DOC/ZIP)</label>
                <input type="file" name="agreement_document" class="form-input" accept=".pdf,.doc,.docx,.zip" style="padding: 10px;">
            </div>

            <div class="form-group">
                <label class="form-label">Notes / Internal Remarks</label>
                <textarea name="notes" class="form-input" rows="4" placeholder="Add any relevant notes about this deal...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">🎯 Save Deal</button>
        </form>
    </div>
</div>
@endsection
