@extends('layouts.admin')

@section('title', '{{ $project->project_name ?? "Project Detail" }} — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.projects.index') }}">Projects</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">{{ $project->project_name ?? 'Project Detail' }}</h1>
        <p class="page-subtitle">Status: <span class="badge badge-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'purple' : 'warning') }}">{{ strtoupper(str_replace('_', ' ', $project->status ?? 'pending')) }}</span></p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-outline">✏️ Edit Project</a>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-ghost">← Back</a>
    </div>
</div>

<div class="grid-31">
    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header"><div class="card-title">📋 Project Info</div></div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Client</div>
                    <div class="font-semibold text-sm">{{ optional($project->customer)->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Budget</div>
                    <div class="font-bold text-success text-lg">Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Start Date</div>
                    <div class="font-semibold text-sm">{{ optional($project->start_date)->format('d M Y') ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Deadline (End Date)</div>
                    <div class="font-semibold text-sm">{{ optional($project->end_date)->format('d M Y') ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Estimated Hours</div>
                    <div class="font-semibold text-sm">{{ $project->estimated_hrs ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Description</div>
                    <div class="text-sm" style="white-space: pre-line;">{{ $project->description ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-col gap-5">
        {{-- Linked Contract --}}
        <div class="card">
            <div class="card-header"><div class="card-title">📄 Linked Contract</div></div>
            <div class="card-body flex-col gap-3">
                @if($project->contract)
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Contract Number</div>
                        <div class="font-bold text-sm">{{ $project->contract->contract_number ?? $project->contract->id }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Client</div>
                        <div class="font-semibold text-sm">{{ optional($project->contract->customer)->name ?? '—' }}</div>
                    </div>
                @else
                    <div class="text-muted text-sm">No contract linked to this project.</div>
                @endif
            </div>
        </div>

        {{-- Agreement Document --}}
        @if($project->agreement_document)
        <div class="card">
            <div class="card-header"><div class="card-title">📎 Agreement Document</div></div>
            <div class="card-body">
                <a href="{{ $project->agreement_document }}" target="_blank" class="btn btn-outline w-full">⬇️ Download / View Agreement</a>
            </div>
        </div>
        @endif

        {{-- Delete --}}
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Are you sure you want to delete this project?')">🗑️ Delete Project</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
