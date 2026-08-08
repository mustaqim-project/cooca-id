@extends('layouts.admin')

@section('title', 'Edit Project — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.projects.index') }}">Projects</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Project</h1>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-outline">← Back to Detail</a>
    </div>
</div>

<div class="card" style="max-width: 720px;">
    <div class="card-body">
        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <p class="text-xs text-muted font-bold uppercase mb-3" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Project Details</p>

            <div class="form-group">
                <label class="form-label">Project Name *</label>
                <input type="text" name="project_name" class="form-input" required value="{{ old('project_name', $project->project_name) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Client</label>
                <select name="customer_id" class="form-select">
                    <option value="">-- No specific client --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $project->customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->business_name ?? $customer->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Schedule & Budget</p>

            <div class="grid-12" style="gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-input" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">End Date (Deadline)</label>
                    <input type="date" name="end_date" class="form-input" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Budget (Rp)</label>
                <input type="number" name="budget" class="form-input" min="0" value="{{ old('budget', $project->budget ?? 0) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Estimated Hours</label>
                <input type="text" name="estimated_hrs" class="form-input" placeholder="e.g. 120 hours" value="{{ old('estimated_hrs', $project->estimated_hrs) }}">
            </div>

            <p class="text-xs text-muted font-bold uppercase mb-3 mt-4" style="border-bottom: 1px solid var(--border); padding-bottom: 8px;">Contract & Documents</p>

            <div class="form-group">
                <label class="form-label">Link to Contract</label>
                <select name="contract_id" class="form-select">
                    <option value="">No contract linked</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ old('contract_id', $project->contract_id) == $contract->id ? 'selected' : '' }}>
                            {{ $contract->contract_number ?? $contract->id }} — {{ optional($contract->customer)->name ?? 'Unknown' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Agreement Document (PDF/DOC/ZIP)</label>
                @if($project->agreement_document)
                    <div style="margin-bottom: 8px;">
                        <a href="{{ $project->agreement_document }}" target="_blank" class="btn btn-outline btn-sm">📎 Current Document</a>
                    </div>
                @endif
                <input type="file" name="agreement_document" class="form-input" accept=".pdf,.doc,.docx,.zip" style="padding: 10px;">
                <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">Upload a new file to replace the current document.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Project Description</label>
                <textarea name="description" class="form-input" rows="4">{{ old('description', $project->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">💾 Update Project</button>
        </form>
    </div>
</div>
@endsection
