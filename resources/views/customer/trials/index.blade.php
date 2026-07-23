@extends('customer.layouts.app')

@section('title', 'Trial Requests')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Trial Requests</h2>
                <p class="text-secondary mb-0">Manage your product trial requests.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('customer.trials.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift fw-medium">
                    <i class="bi bi-plus-lg me-1"></i> New Trial Request
                </a>
            </div>
        </div>

        @if($trials->count() > 0)
        <!-- Trials Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0" style="width: 60px;">#</th>
                            <th class="py-3 px-3 border-0">Product</th>
                            <th class="py-3 px-3 border-0">Domain</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Submitted</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($trials as $trial)
                            <tr>
                                <td class="py-3 px-4">
                                    <strong>#{{ $loop->iteration }}</strong>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $trial->product->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-secondary">
                                    @if($trial->domain_name)
                                        <span class="badge bg-light text-dark border border-light">{{ $trial->domain_name }}</span>
                                    @else
                                        <span class="fst-italic">Not Set</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusConfig = [
                                            'draft' => ['class' => 'secondary', 'icon' => 'bi-file-earmark'],
                                            'submitted' => ['class' => 'info', 'icon' => 'bi-clock'],
                                            'waiting_approval' => ['class' => 'warning', 'icon' => 'bi-hourglass-split'],
                                            'approved' => ['class' => 'success', 'icon' => 'bi-check-circle'],
                                            'rejected' => ['class' => 'danger', 'icon' => 'bi-x-circle'],
                                            'active_trial' => ['class' => 'success', 'icon' => 'bi-play-circle'],
                                            'expired' => ['class' => 'secondary', 'icon' => 'bi-archive'],
                                            'converted_to_subscription' => ['class' => 'primary', 'icon' => 'bi-arrow-repeat'],
                                        ];
                                        
                                        $config = $statusConfig[$trial->status] ?? ['class' => 'secondary', 'icon' => 'bi-question-circle'];
                                        $statusClass = $config['class'];
                                        $statusIcon = $config['icon'];
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border border-{{ $statusClass }}-subtle rounded-pill px-3 py-1">
                                        <i class="bi {{ $statusIcon }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $trial->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $trial->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('customer.trials.show', $trial) }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift">
                                        View <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (method_exists($trials, 'hasPages') && $trials->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $trials->links() }}
                </div>
            @endif
        </div>
        @else
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div class="card-body py-5 text-center text-secondary">
                <div class="mb-3"><i class="bi bi-clock-history" style="font-size: 4rem;"></i></div>
                <h5 class="fw-bold mb-2 text-dark">No Trial Requests Yet</h5>
                <p class="mb-4">Start by requesting a trial for one of our products.</p>
                <a href="{{ route('customer.trials.create') }}" class="btn btn-primary rounded-pill px-4 py-2 hover-lift fw-medium">
                    <i class="bi bi-plus-lg me-2"></i> New Trial Request
                </a>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
// Table tools from admin-table-tools.js should handle search/sort/export
</script>
@endpush
