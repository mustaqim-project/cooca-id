@extends('layouts.customer')
@section('title', 'Project Details')
@section('breadcrumb')
    <a href="{{ route('customer.projects.index') }}" class="crumb-link">Projects</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Details</span>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-diagram-project" style="color:var(--primary);margin-right:10px;"></i>Implementation Project</h1>
        <p class="page-subtitle">Onboarding & Custom Setup</p>
    </div>
    <a href="{{ route('customer.projects.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="card" style="max-width:720px;margin:0 auto;">
    <div class="card-header">
        <div class="card-title">Project Overview</div>
        <span class="badge badge-primary">In Progress</span>
    </div>
    <div class="card-body">
        <p class="text-sm text-muted mb-4">
            Our enterprise implementation engineers are setting up your custom ERP database, data migration, and API integrations.
        </p>

        <div class="mb-4">
            <div class="flex justify-between text-xs text-muted mb-1">
                <span>Overall Progress</span>
                <span class="font-bold">60%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:60%;"></div>
            </div>
        </div>

        <div class="divider"></div>
        <div class="font-bold text-sm mb-3">Milestones</div>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-dot" style="background:var(--success);"></div>
                <div class="timeline-time">Completed</div>
                <div class="timeline-text font-bold">1. Cloud Server & Database Provisioning</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot" style="background:var(--success);"></div>
                <div class="timeline-time">Completed</div>
                <div class="timeline-text font-bold">2. Master Data Migration & Initial Import</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot" style="background:var(--primary);"></div>
                <div class="timeline-time">In Progress</div>
                <div class="timeline-text font-bold">3. Custom WhatsApp Gateway Integration</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot" style="background:var(--border);"></div>
                <div class="timeline-time">Pending</div>
                <div class="timeline-text text-muted">4. User Training & Production Go-Live</div>
            </div>
        </div>
    </div>
</div>
@endsection
