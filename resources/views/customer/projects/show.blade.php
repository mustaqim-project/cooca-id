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
        <h1 class="page-title"><i class="fa-solid fa-diagram-project" style="color:var(--primary);margin-right:10px;"></i>{{ $project->project_name }}</h1>
        <p class="page-subtitle">Project Status Tracker</p>
    </div>
    <a href="{{ route('customer.projects.index') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="card" style="max-width:720px;margin:0 auto;">
    <div class="card-header">
        <div class="card-title">Project Overview</div>
        <span class="badge badge-primary">{{ ucfirst($project->status) }}</span>
    </div>
    <div class="card-body">
        <p class="text-sm text-muted mb-4">
            {{ $project->description ?? 'Our enterprise implementation engineers are setting up your custom ERP database, data migration, and API integrations.' }}
        </p>

        @php $progress = $project->progress_percentage; @endphp
        <div class="mb-4">
            <div class="flex justify-between text-xs text-muted mb-1">
                <span>Overall Progress</span>
                <span class="font-bold">{{ $progress }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $progress }}%;"></div>
            </div>
        </div>

        <div class="divider" style="margin: 20px 0; border-top: 1px solid var(--border);"></div>
        <div class="font-bold text-sm mb-3">Milestones / Tasks</div>
        <div class="timeline">
            @forelse($project->tasks as $index => $task)
                <div class="timeline-item" style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 10px;">
                    <div class="timeline-dot" style="background:{{ $task->is_complete ? 'var(--success)' : 'var(--primary)' }}; width: 12px; height: 12px; border-radius: 50%; margin-top: 4px; flex-shrink: 0;"></div>
                    <div>
                        <div class="timeline-text {{ $task->is_complete ? 'font-bold' : 'text-muted' }}" style="font-size: 14px;">
                            {{ ($index + 1) . '. ' . $task->name }}
                        </div>
                        @if($task->description)
                            <div class="text-xs text-muted" style="margin-top: 2px;">{{ $task->description }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-muted">No tasks defined for this project yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Billing & Payments Card --}}
<div class="card mt-4" style="max-width:720px;margin:20px auto 0 auto;">
    <div class="card-header">
        <div class="card-title">💳 Billing & Payments</div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4">
                <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
            </div>
        @endif

        <div class="data-table-wrap" style="overflow-x: auto;">
            <table class="data-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->transactions ?? [] as $txn)
                        <tr>
                            <td class="font-bold text-sm">
                                {{ $txn->invoice_number }}
                            </td>
                            <td class="text-sm font-semibold">Rp {{ number_format($txn->net_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $txn->status === 'paid' ? 'success' : ($txn->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ strtoupper($txn->status) }}
                                </span>
                            </td>
                            <td class="text-right">
                                @if($txn->status !== 'paid' && $txn->invoice)
                                    <a href="{{ route('customer.projects.pay', [$project->id, $txn->invoice->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-credit-card"></i> Pay Now
                                    </a>
                                @else
                                    <span class="text-xs text-success font-bold"><i class="fa-solid fa-circle-check"></i> Paid</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding:20px;font-size:13px;">No billing invoices generated for this project.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
