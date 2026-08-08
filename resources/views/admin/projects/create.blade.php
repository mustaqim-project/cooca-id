@extends('layouts.admin')

@section('title', 'New Project — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.projects.index') }}">Projects</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Create New Project</h1>
        <p class="page-subtitle">Register a new enterprise deployment or implementation project.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline">← Back</a>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <p class="text-xs text-muted font-bold uppercase mb-3" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Project Details</p>

            <div class="form-group">
                <label class="form-label">Project Name *</label>
                <input type="text" name="project_name" class="form-input" required value="{{ old('project_name') }}" placeholder="e.g. PT Maju Bersama — ERP Deployment Q3">
            </div>
            <div class="form-group">
                <label class="form-label">Client</label>
                <select name="customer_id" class="form-select">
                    <option value="">-- No specific client --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->business_name ?? $customer->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Schedule & Budget</p>

            <div class="grid-12" style="gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-input" value="{{ old('start_date') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">End Date (Deadline)</label>
                    <input type="date" name="end_date" class="form-input" value="{{ old('end_date') }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Budget (Rp)</label>
                <input type="number" name="budget" class="form-input" min="0" value="{{ old('budget', 0) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Estimated Hours</label>
                <input type="text" name="estimated_hrs" class="form-input" placeholder="e.g. 120 hours" value="{{ old('estimated_hrs') }}">
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Contract & Documents</p>

            <div class="form-group">
                <label class="form-label">Link to Contract</label>
                <select name="contract_id" class="form-select">
                    <option value="">No contract linked</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                            {{ $contract->contract_number ?? $contract->id }} — {{ optional($contract->customer)->name ?? 'Unknown' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Agreement Document (PDF/DOC/ZIP)</label>
                <input type="file" name="agreement_document" class="form-input" accept=".pdf,.doc,.docx,.zip" style="padding: 10px;">
            </div>
            <div class="form-group">
                <label class="form-label">Project Description</label>
                <textarea name="description" class="form-input" rows="4" placeholder="Scope of work, deliverables, notes...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">🛠️ Create Project</button>
        </form>
    </div>
</div>
@endsection
