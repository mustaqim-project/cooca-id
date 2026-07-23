@extends('customer.layouts.app')

@section('title', 'License Details')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.licenses.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">License Details</h2>
                    <p class="text-secondary mb-0">View your license information and status</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <!-- Status Header -->
                <div class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-shield-check me-2"></i> License Information</h5>
                    
                    <div>
                        @if($license->status == 'active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-4 py-2 fs-6">
                                <i class="bi bi-check-circle me-1"></i> Active
                            </span>
                        @elseif($license->status == 'suspended')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-4 py-2 fs-6">
                                <i class="bi bi-x-circle me-1"></i> Suspended
                            </span>
                        @elseif($license->status == 'expired')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-4 py-2 fs-6">
                                <i class="bi bi-exclamation-triangle me-1"></i> Expired
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-4 py-2 fs-6">
                                <i class="bi bi-hourglass-split me-1"></i> {{ ucfirst($license->status) }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Details Body -->
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush border-0">
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Product Name</div>
                            <div class="fw-semibold text-dark">{{ $license->product->name ?? 'Unknown Product' }}</div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-light border-light d-flex flex-column flex-md-row align-items-md-center">
                            <div class="fw-medium text-secondary" style="width: 250px;">License Key</div>
                            <div class="d-flex align-items-center">
                                <code class="bg-white px-3 py-1 rounded border border-light font-monospace text-primary me-3">
                                    {{ $license->license_key }}
                                </code>
                                <button onclick="navigator.clipboard.writeText('{{ $license->license_key }}')" class="btn btn-sm btn-link text-secondary hover-lift p-0" title="Copy to clipboard">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Current Domain</div>
                            <div class="text-dark">
                                @if($license->domain)
                                    <span class="text-dark">{{ $license->domain }}</span>
                                @else
                                    <span class="text-secondary fst-italic">Not set (Unrestricted or pending activation)</span>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-light border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Activated At</div>
                            <div class="text-dark">
                                {{ $license->activated_at ? \Carbon\Carbon::parse($license->activated_at)->format('F d, Y - H:i') : 'Not activated yet' }}
                            </div>
                        </li>
                        <li class="list-group-item px-4 py-3 bg-transparent border-light d-flex flex-column flex-md-row">
                            <div class="fw-medium text-secondary" style="width: 250px;">Created At</div>
                            <div class="text-dark">{{ \Carbon\Carbon::parse($license->created_at)->format('F d, Y - H:i') }}</div>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="card-footer bg-transparent border-top border-light p-4 d-flex justify-content-end gap-2">
                    @if($license->status == 'active')
                        <a href="{{ route('customer.licenses.credentials', $license->id) }}" class="btn btn-primary rounded-pill px-4 py-2 hover-lift fw-medium">
                            <i class="bi bi-shield-lock me-2"></i> View API Credentials
                        </a>
                    @elseif($license->status == 'pending' || $license->status == 'inactive')
                        <form action="{{ route('customer.licenses.activate', $license->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2 hover-lift fw-medium">
                                <i class="bi bi-play-circle me-2"></i> Activate License
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('customer.subscriptions.create', ['license_id' => $license->id]) }}" class="btn btn-light border rounded-pill px-4 py-2 hover-lift fw-medium text-secondary">
                        <i class="bi bi-arrow-repeat me-2"></i> Extend via Subscription
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
