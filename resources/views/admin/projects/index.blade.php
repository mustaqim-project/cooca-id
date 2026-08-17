@extends('layouts.admin')

@section('title', 'Implementation Projects — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Projects</span>
        </div>
        <h1 class="page-title">Enterprise Deployment Projects</h1>
        <p class="page-subtitle">Track custom ERP deployment projects, server setup milestones, and client onboarding status.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">🛠️ New Project</a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects ?? [] as $proj)
                        @php $pObj = is_array($proj) ? (object)$proj : $proj; @endphp
                        <tr>
                            <td class="font-bold text-base">{{ $pObj->project_name ?? 'ERP Deployment' }}</td>
                            <td>{{ optional($pObj->customer)->name ?? 'Enterprise Client' }}</td>
                            <td><span class="badge badge-{{ $pObj->status === 'completed' ? 'success' : ($pObj->status === 'in_progress' ? 'purple' : 'warning') }}">{{ strtoupper(str_replace('_', ' ', $pObj->status ?? 'IN PROGRESS')) }}</span></td>
                            <td class="text-xs text-muted">{{ isset($pObj->end_date) ? \Carbon\Carbon::parse($pObj->end_date)->format('d M Y') : 'N/A' }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.projects.show', $pObj->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ Show</a>
                                    <a href="{{ route('admin.projects.edit', $pObj->id ?? 1) }}" class="btn btn-outline btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No deployment projects registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($projects, 'links'))
        <div class="card-footer" style="padding: 1rem;">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
