@extends('layouts.admin')

@section('title', 'CRM Sales Pipeline & Deals — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>CRM Deals</span>
        </div>
        <h1 class="page-title">Enterprise Sales Pipeline & CRM</h1>
        <p class="page-subtitle">Track high-value enterprise leads, contract negotiations, stages, and forecast pipeline values.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.deals.create') }}" class="btn btn-primary">
            <span>🎯</span> Add New Deal
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Deal Name</th>
                        <th>Client / Lead</th>
                        <th>Pipeline Stage</th>
                        <th>Deal Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deals ?? [] as $deal)
                        @php $dObj = is_array($deal) ? (object)$deal : $deal; @endphp
                        <tr>
                            <td class="font-bold text-base">{{ $dObj->name ?? 'Enterprise Deal' }}</td>
                            <td>{{ $dObj->phone ?? '—' }}</td>
                            <td><span class="badge badge-purple">{{ $dObj->stage->name ?? 'Lead Qualification' }}</span></td>
                            <td class="font-bold text-success text-base">Rp {{ number_format($dObj->price ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.deals.show', $dObj->id ?? 1) }}" class="btn btn-ghost btn-sm">👁️ Show</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No deals registered in pipeline.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
