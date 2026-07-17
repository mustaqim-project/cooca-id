@extends('layouts.admin')
@section('title', 'ERP Requests')
@section('subtitle', 'Manage customer ERP setup requests')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search requests...">
            </div>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="requestsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $erp)
                            <tr>
                                <td class="text-muted" style="font-size:.8rem">{{ $erp->id }}</td>
                                <td>
                                    <div style="font-weight:600">{{ $erp->customer->name ?? '-' }}</div>
                                    <div style="font-size:.8rem;color:var(--text-muted)">{{ $erp->customer->email ?? '' }}
                                    </div>
                                </td>
                                <td>{{ $erp->company_name ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'pending' => ['label' => 'Pending', 'class' => 'badge-saas-warning'],
                                            'approved' => ['label' => 'Approved', 'class' => 'badge-saas-info'],
                                            'rejected' => ['label' => 'Rejected', 'class' => 'badge-saas-danger'],
                                            'waiting_setup' => [
                                                'label' => 'Waiting Setup',
                                                'class' => 'badge-saas-neutral',
                                            ],
                                            'in_setup' => ['label' => 'In Setup', 'class' => 'badge-saas-primary'],
                                            'domain_setup' => [
                                                'label' => 'Domain Setup',
                                                'class' => 'badge-saas-primary',
                                            ],
                                            'testing' => ['label' => 'Testing', 'class' => 'badge-saas-warning'],
                                            'ready' => ['label' => 'Ready', 'class' => 'badge-saas-success'],
                                        ];
                                        $s = $statusMap[$erp->status] ?? [
                                            'label' => ucfirst($erp->status),
                                            'class' => 'badge-saas-neutral',
                                        ];
                                    @endphp
                                    <span class="badge-saas {{ $s['class'] }}">{{ $s['label'] }}</span>
                                </td>
                                <td class="text-muted" style="font-size:.85rem">{{ $erp->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.erp-requests.show', $erp) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-pc-display-horizontal"></i></div>
                                        <div class="empty-state-title">No ERP requests</div>
                                        <div class="empty-state-description">Customer ERP requests will appear here.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#requestsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
