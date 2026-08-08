@extends('layouts.admin')

@section('title', 'Project Details — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.projects.index') }}">Projects</a>
            <span>/</span>
            <span>Show</span>
        </div>
        <h1 class="page-title">{{ $project->name ?? 'Project Detail' }}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="font-bold text-lg text-primary mb-2">{{ $project->name ?? '' }}</div>
        <div class="text-sm text-muted">Status: {{ strtoupper($project->status ?? 'IN PROGRESS') }}</div>
    </div>
</div>
@endsection
