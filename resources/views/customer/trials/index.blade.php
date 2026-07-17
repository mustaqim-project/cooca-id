@extends('layouts.customer')

@section('title', 'Trial Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Trial Requests</h2>
        <p class="text-muted mb-0">Manage your product trial requests</p>
    </div>
    <a href="{{ route('customer.trials.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Trial Request
    </a>
</div>

@if($trials->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="trialsTable">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Product</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trials as $trial)
                    <tr>
                        <td><strong>#{{ $loop->iteration }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-box-seam text-primary"></i>
                                <span>{{ $trial->product->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($trial->domain_name)
                                <span class="badge bg-light text-dark">{{ $trial->domain_name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-secondary',
                                    'submitted' => 'bg-info',
                                    'waiting_approval' => 'bg-warning',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'active_trial' => 'bg-success',
                                    'expired' => 'bg-dark',
                                    'converted_to_subscription' => 'bg-primary',
                                ];
                                $statusIcons = [
                                    'draft' => 'bi-file-earmark',
                                    'submitted' => 'bi-clock',
                                    'waiting_approval' => 'bi-hourglass-split',
                                    'approved' => 'bi-check-circle',
                                    'rejected' => 'bi-x-circle',
                                    'active_trial' => 'bi-play-circle',
                                    'expired' => 'bi-archive',
                                    'converted_to_subscription' => 'bi-arrow-repeat',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$trial->status] ?? 'bg-secondary' }}">
                                <i class="bi {{ $statusIcons[$trial->status] ?? 'bi-question-circle' }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $trial->status)) }}
                            </span>
                        </td>
                        <td>{{ $trial->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('customer.trials.show', $trial) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@else
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-clock-history display-4 text-muted mb-3"></i>
        <h5>No Trial Requests Yet</h5>
        <p class="text-muted mb-4">Start by requesting a trial for one of our products</p>
        <a href="{{ route('customer.trials.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>New Trial Request
        </a>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Table tools from admin-table-tools.js should handle search/sort/export
</script>
@endpush
