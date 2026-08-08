@extends('layouts.admin')

@section('title', 'Trial Registrations — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Trials</span>
        </div>
        <h1 class="page-title">Free Trial Applications & Provisioning</h1>
        <p class="page-subtitle">Approve and deploy 14-day free trial ERP instances for new prospective clients.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Business Name</th>
                        <th>Subdomain</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trials ?? [] as $tr)
                        @php $tObj = is_array($tr) ? (object)$tr : $tr; @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-base">{{ $tObj->customer->name ?? $tObj->applicant_name ?? 'Lead' }}</div>
                                <div class="text-xs text-muted">{{ $tObj->customer->email ?? $tObj->email ?? '' }}</div>
                            </td>
                            <td class="font-semibold text-sm">{{ $tObj->business_name ?? 'Business' }}</td>
                            <td><code class="badge badge-accent">https://{{ $tObj->subdomain ?? 'demo' }}.cooca.id</code></td>
                            <td>
                                @if(($tObj->status ?? '') === 'approved')
                                    <span class="badge badge-success">ACTIVE TRIAL</span>
                                @elseif(($tObj->status ?? '') === 'pending')
                                    <span class="badge badge-warning">PENDING APPROVAL</span>
                                @else
                                    <span class="badge badge-danger">REJECTED</span>
                                @endif
                            </td>
                            <td class="text-xs text-muted">{{ optional($tObj->created_at)->format('d M Y') }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.trials.show', $tObj->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ Details</a>
                                    @if(($tObj->status ?? '') === 'pending')
                                        <form action="{{ route('admin.trials.approve', $tObj->id ?? 1) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">No trial applications pending.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
