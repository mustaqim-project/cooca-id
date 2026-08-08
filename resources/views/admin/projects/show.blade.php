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

        {{-- Billing & Payment Links --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">💳 Project Payments & Invoices</div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success" style="background:#def7ec;color:#03543f;padding:10px;border-radius:var(--radius);margin-bottom:15px;font-size:13px;font-weight:bold;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" style="background:#fde8e8;color:#9b1c1c;padding:10px;border-radius:var(--radius);margin-bottom:15px;font-size:13px;font-weight:bold;">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="data-table-wrap mb-4" style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->transactions ?? [] as $txn)
                                <tr>
                                    <td class="font-bold text-sm">
                                        {{ $txn->invoice_number }}
                                    </td>
                                    <td class="text-sm">Rp {{ number_format($txn->net_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $txn->status === 'paid' ? 'success' : ($txn->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ strtoupper($txn->status) }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-muted">{{ $txn->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding:20px;font-size:13px;">No invoices generated yet for this project.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="divider" style="margin:20px 0;border-top:1px solid var(--border);"></div>

                <div class="font-bold text-sm mb-3">🛠️ Generate Payment Link</div>
                <form action="{{ route('admin.projects.billing', $project->id) }}" method="POST">
                    @csrf
                    <div class="flex-col gap-3">
                        <div style="margin-bottom: 15px;">
                            <label class="form-label" style="display:block;margin-bottom:5px;font-size:12px;font-weight:bold;color:var(--text-muted);">Amount (Rp)</label>
                            <input type="number" name="amount" class="form-control w-full" value="{{ max(0, ($project->budget ?? 0) - ($project->transactions?->where('status', 'paid')->sum('net_amount') ?? 0)) }}" required min="1000" style="padding:8px;border:1px solid var(--border);border-radius:var(--radius);background:var(--bg);color:var(--text);width:100%;box-sizing:border-box;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label class="form-label" style="display:block;margin-bottom:5px;font-size:12px;font-weight:bold;color:var(--text-muted);">Description / Milestone Name</label>
                            <input type="text" name="description" class="form-control w-full" placeholder="e.g. Down Payment 30%" required style="padding:8px;border:1px solid var(--border);border-radius:var(--radius);background:var(--bg);color:var(--text);width:100%;box-sizing:border-box;">
                        </div>
                        <button type="submit" class="btn btn-primary w-full" style="justify-content:center;">⚡ Create Payment Link</button>
                    </div>
                </form>
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
