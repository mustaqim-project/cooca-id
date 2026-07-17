@extends('layouts.admin')
@section('title', 'ERP Request #' . $erp->id)
@section('subtitle', 'View and manage ERP setup request')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.erp-requests.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Requests
        </a>
    </div>

    @php
        $statusMap = [
            'pending' => ['label' => 'Pending', 'class' => 'badge-saas-warning'],
            'approved' => ['label' => 'Approved', 'class' => 'badge-saas-info'],
            'rejected' => ['label' => 'Rejected', 'class' => 'badge-saas-danger'],
            'waiting_setup' => ['label' => 'Waiting Setup', 'class' => 'badge-saas-neutral'],
            'in_setup' => ['label' => 'In Setup', 'class' => 'badge-saas-primary'],
            'domain_setup' => ['label' => 'Domain Setup', 'class' => 'badge-saas-primary'],
            'testing' => ['label' => 'Testing', 'class' => 'badge-saas-warning'],
            'ready' => ['label' => 'Ready', 'class' => 'badge-saas-success'],
        ];
        $s = $statusMap[$erp->status] ?? ['label' => ucfirst($erp->status), 'class' => 'badge-saas-neutral'];
    @endphp

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-person-vcard me-2"></i>Customer Information</h5>
                </div>
                <div class="card-saas-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Customer Name</div>
                            <div style="font-weight:600">{{ $erp->customer->name ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Email</div>
                            <div>{{ $erp->customer->email ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Company Name</div>
                            <div style="font-weight:600">{{ $erp->company_name ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Phone</div>
                            <div>{{ $erp->phone ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-pc-display-horizontal me-2"></i>Request Details</h5>
                </div>
                <div class="card-saas-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Product</div>
                            <div style="font-weight:600">{{ $erp->product->name ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Status</div>
                            <div><span class="badge-saas {{ $s['class'] }}">{{ $s['label'] }}</span></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Submitted</div>
                            <div>{{ $erp->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-saas-label mb-1">Last Updated</div>
                            <div>{{ $erp->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                        @if ($erp->notes)
                            <div class="col-12">
                                <div class="form-saas-label mb-1">Notes</div>
                                <div class="card-saas" style="background:var(--surface-raised)">
                                    <div class="card-saas-body" style="font-size:.9rem;line-height:1.8">{{ $erp->notes }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-diagram-3 me-2"></i>Workflow Actions</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    @if ($erp->status === 'pending')
                        <form action="{{ route('admin.erp-requests.approve', $erp) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check-circle me-1"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.erp-requests.reject', $erp) }}" method="POST"
                            class="form-confirm-submit">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-danger w-100">
                                <i class="bi bi-x-circle me-1"></i> Reject
                            </button>
                        </form>
                    @endif

                    @if ($erp->status === 'approved')
                        <form action="{{ route('admin.erp-requests.mark-waiting-setup', $erp) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-secondary w-100">
                                <i class="bi bi-hourglass-split me-1"></i> Mark: Waiting Setup
                            </button>
                        </form>
                    @endif

                    @if ($erp->status === 'waiting_setup')
                        <form action="{{ route('admin.erp-requests.mark-in-setup', $erp) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-secondary w-100">
                                <i class="bi bi-gear-wide-connected me-1"></i> Mark: In Setup
                            </button>
                        </form>
                    @endif

                    @if ($erp->status === 'in_setup')
                        <form action="{{ route('admin.erp-requests.mark-domain-setup', $erp) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-secondary w-100">
                                <i class="bi bi-globe me-1"></i> Mark: Domain Setup
                            </button>
                        </form>
                    @endif

                    @if ($erp->status === 'domain_setup')
                        <form action="{{ route('admin.erp-requests.mark-testing', $erp) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-secondary w-100">
                                <i class="bi bi-bug me-1"></i> Mark: Testing
                            </button>
                        </form>
                    @endif

                    @if ($erp->status === 'testing')
                        <form action="{{ route('admin.erp-requests.confirm-ready', $erp) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check2-all me-1"></i> Confirm Ready
                            </button>
                        </form>
                    @endif

                    @if ($erp->status === 'ready')
                        <div class="text-center py-2" style="color:var(--success);font-weight:600;font-size:.9rem">
                            <i class="bi bi-check-circle-fill me-1"></i> Setup Complete
                        </div>
                    @endif

                    @if ($erp->status === 'rejected')
                        <div class="text-center py-2" style="color:var(--danger);font-weight:600;font-size:.9rem">
                            <i class="bi bi-x-circle-fill me-1"></i> Request Rejected
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-saas mt-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-list-check me-2"></i>Status Timeline</h5>
                </div>
                <div class="card-saas-body">
                    @php
                        $steps = [
                            'pending',
                            'approved',
                            'waiting_setup',
                            'in_setup',
                            'domain_setup',
                            'testing',
                            'ready',
                        ];
                        $currentIdx = array_search($erp->status, $steps);
                    @endphp
                    <div style="font-size:.85rem">
                        @foreach ($steps as $i => $step)
                            @php
                                $done = $currentIdx !== false && $i <= $currentIdx && $erp->status !== 'rejected';
                            @endphp
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div
                                    style="width:20px;height:20px;border-radius:50%;flex-shrink:0;background:{{ $done ? 'var(--success)' : 'var(--border)' }};display:flex;align-items:center;justify-content:center">
                                    @if ($done)
                                        <i class="bi bi-check" style="color:#fff;font-size:.7rem"></i>
                                    @endif
                                </div>
                                <span
                                    style="color:{{ $done ? 'var(--text)' : 'var(--text-muted)' }}">{{ ucwords(str_replace('_', ' ', $step)) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.swal-alert')
@endsection
