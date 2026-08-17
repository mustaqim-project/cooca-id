@extends('layouts.admin')

@section('title', 'AI Gateway Dashboard')

@section('breadcrumb')
    <span class="crumb-current">AI Gateway</span>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Tokens Used This Month</h6>
                <h3 class="mb-0">{{ number_format($monthlyUsage->total_tokens ?? 0) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Estimated Cost (USD)</h6>
                <h3 class="mb-0">${{ number_format($monthlyUsage->total_cost_usd ?? 0, 4) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Active Usage Cycles</h5>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Customer / License</th>
                        <th>Period</th>
                        <th>Usage</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeCycles as $cycle)
                    @php
                        $percentage = $cycle->token_quota > 0 ? ($cycle->tokens_used / $cycle->token_quota) * 100 : 0;
                        $color = $percentage > 90 ? 'danger' : ($percentage > 75 ? 'warning' : 'success');
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $cycle->license->customer->business_name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">License: {{ substr($cycle->license_id, 0, 8) }}</small>
                        </td>
                        <td>
                            {{ $cycle->cycle_start->format('M d') }} - {{ $cycle->cycle_end->format('M d') }}
                        </td>
                        <td style="min-width: 200px;">
                            <div class="d-flex justify-content-between text-sm mb-1">
                                <span>{{ number_format($cycle->tokens_used) }}</span>
                                <span>{{ number_format($cycle->token_quota) }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ min($percentage, 100) }}%;"></div>
                            </div>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('admin.ai.cycles.bonus', $cycle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Grant 10,000 bonus tokens to this cycle?');">
                                @csrf
                                <input type="hidden" name="bonus_tokens" value="10000">
                                <input type="hidden" name="reason" value="Admin Manual Grant">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Grant 10k Bonus">
                                    +10k Bonus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No active cycles found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($activeCycles->hasPages())
    <div class="card-footer">
        {{ $activeCycles->links() }}
    </div>
    @endif
</div>
@endsection
