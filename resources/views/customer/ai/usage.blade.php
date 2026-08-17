@extends('layouts.customer')

@section('title', 'AI Gateway Usage')

@section('breadcrumb')
    <a href="{{ route('customer.dashboard') }}" class="crumb">Dashboard</a>
    <span class="crumb-separator">/</span>
    <span class="crumb-current">AI Usage</span>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">API Keys</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Prefix</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($keys as $key)
                            <tr>
                                <td>{{ $key->name }}</td>
                                <td><code>{{ $key->key_prefix }}••••••••</code></td>
                                <td>
                                    <span class="badge bg-{{ $key->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($key->status) }}
                                    </span>
                                </td>
                                <td>{{ $key->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No API keys found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Current Usage Cycles</h5>
            </div>
            <div class="card-body">
                @forelse($cycles as $cycle)
                @php
                    $percentage = $cycle->token_quota > 0 ? ($cycle->tokens_used / $cycle->token_quota) * 100 : 0;
                    $percentageStr = number_format(min($percentage, 100), 1);
                    $color = $percentage > 90 ? 'danger' : ($percentage > 75 ? 'warning' : 'primary');
                @endphp
                <div class="mb-4 last-mb-0">
                    <div class="d-flex justify-content-between mb-1">
                        <strong>License ID: {{ substr($cycle->license_id, 0, 8) }}...</strong>
                        <span class="text-muted text-sm">
                            {{ $cycle->cycle_start->format('M d') }} - {{ $cycle->cycle_end->format('M d, Y') }}
                        </span>
                    </div>
                    
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $percentageStr }}%
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-2 text-sm">
                        <span>Used: <strong>{{ number_format($cycle->tokens_used) }}</strong> tokens</span>
                        <span>Quota: <strong>{{ number_format($cycle->token_quota) }}</strong> tokens</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">No active usage cycles.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
