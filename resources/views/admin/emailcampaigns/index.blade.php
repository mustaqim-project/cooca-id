@extends('layouts.admin')
@section('title', 'Email Campaigns')
@section('subtitle', 'Manage email marketing campaigns')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search campaigns...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.email-campaigns.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> New Campaign
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="campaignsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td class="text-muted" style="font-size:.8rem">{{ $campaign->id }}</td>
                                <td>
                                    <div style="font-weight:600">{{ $campaign->name }}</div>
                                </td>
                                <td>{{ $campaign->subject }}</td>
                                <td>
                                    @if ($campaign->status === 'sent')
                                        <span class="badge-saas badge-saas-success">Sent</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Draft</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:.85rem">{{ $campaign->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.email-campaigns.show', $campaign) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-envelope-paper"></i></div>
                                        <div class="empty-state-title">No campaigns yet</div>
                                        <div class="empty-state-description">Create your first email campaign to get
                                            started.</div>
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
            document.querySelectorAll('#campaignsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
