@extends('layouts.admin')

@section('title', 'Application Exception Logs — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Error Logs</span>
        </div>
        <h1 class="page-title">Application Exception Logs</h1>
        <p class="page-subtitle">Real-time system error stack traces, exception diagnostics, and runtime warnings.</p>
    </div>
    <div class="page-actions">
        <form action="{{ route('admin.error-logs.clear') }}" method="POST" onsubmit="return confirm('Clear all error logs?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline text-danger">🧹 Clear Logs</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <pre class="form-textarea" style="font-family: monospace; font-size: 11px; height: 500px; overflow: auto; background: var(--bg-secondary);">{{ $logContent ?? 'No error logs captured in storage.' }}</pre>
    </div>
</div>
@endsection
