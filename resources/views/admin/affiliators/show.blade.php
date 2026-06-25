@extends('layouts.admin')

@section('title', 'Affiliator Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.affiliators.index') }}">Affiliators</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $affiliator->name }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>{{ $affiliator->name }}
                </h2>
                <a href="{{ route('admin.affiliators.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Affiliator Info Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="avatar bg-primary text-white rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                        {{ substr($affiliator->name, 0, 2) }}
                    </div>
                    <h4 class="mb-1">{{ $affiliator->name }}</h4>
                    <p class="text-muted mb-3">{{ $affiliator->email }}</p>
                    
                    @if($affiliator->is_active)
                        <span class="badge bg-success mb-3">Active</span>
                    @else
                        <span class="badge bg-danger mb-3">Inactive</span>
                    @endif
                    
                    <div class="mt-3">
                        <p class="mb-1"><strong>Referral Code:</strong></p>
                        <code class="bg-light px-3 py-2 rounded">{{ $affiliator->referral_code }}</code>
                    </div>
                    
                    <div class="mt-3">
                        <p class="mb-1"><strong>Joined Date:</strong></p>
                        <p>{{ \Carbon\Carbon::parse($affiliator->created_at)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Total Commission</label>
                        <h4 class="mb-0 text-success">Rp {{ number_format($affiliator->total_commission ?? 0, 0, ',', '.') }}</h4>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Total Downlines</label>
                        <h4 class="mb-0 text-primary">{{ count($downlines) }}</h4>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Total Withdrawals</label>
                        <h4 class="mb-0 text-warning">Rp {{ number_format($affiliator->total_withdrawn ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Downlines Table -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>Downlines ({{ count($downlines) }})</h5>
                </div>
                <div class="card-body p-0">
                    @if(count($downlines) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Referral Code</th>
                                    <th>Status</th>
                                    <th>Joined Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($downlines as $downline)
                                <tr>
                                    <td>{{ $downline->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-info text-white rounded-circle me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                {{ substr($downline->name, 0, 2) }}
                                            </div>
                                            <strong>{{ $downline->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $downline->email }}</td>
                                    <td><code>{{ $downline->referral_code }}</code></td>
                                    <td>
                                        @if($downline->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($downline->created_at)->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No downlines found</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush
