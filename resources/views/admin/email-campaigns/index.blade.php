@extends('layouts.admin')

@section('title', 'Email Broadcast Campaigns — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Email Broadcast</span>
        </div>
        <h1 class="page-title">Email Marketing & Broadcast Campaigns</h1>
        <p class="page-subtitle">Send newsletter broadcasts, feature updates, and promotional announcements to clients.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.email-campaigns.create') }}" class="btn btn-primary">
            <span>📧</span> New Campaign
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject Title</th>
                        <th>Target Audience</th>
                        <th>Status</th>
                        <th>Sent Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns ?? [] as $camp)
                        @php $cObj = is_array($camp) ? (object)$camp : $camp; @endphp
                        <tr>
                            <td class="font-bold text-base">{{ $cObj->subject ?? 'Broadcast' }}</td>
                            <td><span class="badge badge-purple">{{ $cObj->target_audience ?? 'All Customers' }}</span></td>
                            <td>
                                @if(($cObj->status ?? '') === 'sent')
                                    <span class="badge badge-success">SENT</span>
                                @else
                                    <span class="badge badge-warning">DRAFT</span>
                                @endif
                            </td>
                            <td class="font-semibold">{{ $cObj->sent_count ?? 0 }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.email-campaigns.edit', $cObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No email campaigns created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
