@extends('layouts.admin')

@section('title', 'Campaign Show — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.email-campaigns.index') }}">Campaigns</a>
            <span>/</span>
            <span>Show</span>
        </div>
        <h1 class="page-title">{{ $campaign->subject ?? 'Campaign Detail' }}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="font-bold text-lg mb-2">Subject: {{ $campaign->subject ?? '' }}</div>
        <div class="prose">{!! $campaign->content ?? '' !!}</div>
    </div>
</div>
@endsection
