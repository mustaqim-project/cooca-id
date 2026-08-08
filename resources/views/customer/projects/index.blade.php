@extends('layouts.customer')
@section('title', 'My Projects')
@section('breadcrumb')
    <span class="crumb-current">Projects</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-diagram-project" style="color:var(--primary);margin-right:10px;"></i>Projects</h1>
        <p class="page-subtitle">Track implementation status, deliverables, and setup milestones for your custom ERP projects.</p>
    </div>
</div>

<div class="grid-2">
    @forelse($projects ?? [] as $project)
    <div class="card card-hover">
        <div class="card-body">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="font-bold text-base">{{ $project->project_name }}</div>
                    <div class="text-xs text-muted">Started {{ $project->created_at->format('d M Y') }}</div>
                </div>
                <span class="badge badge-primary">{{ ucfirst($project->status ?? 'in_progress') }}</span>
            </div>
            <div class="text-sm text-muted mb-4">{{ $project->description ?? 'Custom implementation & onboarding project.' }}</div>

            @php $progress = $project->progress_percentage; @endphp
            <div class="mb-3">
                <div class="flex justify-between text-xs text-muted mb-1">
                    <span>Implementation Progress</span>
                    <span class="font-bold">{{ $progress }}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ $progress }}%;"></div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-4">
                <div class="text-xs text-muted">
                    <i class="fa-solid fa-tasks"></i> {{ $project->tasks_count ?? 0 }} tasks
                </div>
                <a href="{{ route('customer.projects.show', $project->id) }}" class="btn btn-outline btn-sm">
                    View Project <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="card" style="grid-column:1/-1;">
        <div class="empty-state">
            <div class="empty-state-icon">🚀</div>
            <div class="empty-state-title">No Active Projects</div>
            <div class="empty-state-text">Custom setup and onboarding projects assigned to your account will appear here.</div>
        </div>
    </div>
    @endforelse
</div>
@endsection
