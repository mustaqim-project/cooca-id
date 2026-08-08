@extends('layouts.admin')

@section('title', 'Template Show — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.email-templates.index') }}">Templates</a>
            <span>/</span>
            <span>Show</span>
        </div>
        <h1 class="page-title">{{ $template->name ?? 'Email Template' }}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="text-xs text-muted mb-2">Key: {{ $template->key ?? '' }}</div>
        <div class="font-bold text-lg mb-4">Subject: {{ $template->subject ?? '' }}</div>
        <div class="prose">{!! $template->body ?? '' !!}</div>
    </div>
</div>
@endsection
