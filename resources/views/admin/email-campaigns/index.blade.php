@extends('admin.layouts.app')

@section('title', 'Email Campaigns')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Email Campaigns</h2>
                <p class="text-secondary mb-0">Create, send, and track automated email marketing campaigns.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-3 hover-lift text-secondary">
                    <i class="bi bi-filter me-2"></i> Filter
                </button>
                <a href="{{ route('admin.email-campaigns.create') }}"
                    class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Create Campaign
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search campaigns...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Campaign Details</th>
                            <th class="py-3 px-3 border-0">Target Audience</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Performance</th>
                            <th class="py-3 px-3 border-0">Scheduled Date</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($campaigns ?? [
                                (object)
    ['id' => 1, 'name' => 'Q4 Black Friday Promotion', 'subject' => 'Huge Savings on ERP Subscriptions!', 'target' => 'All Customers', 'status' => 'Sent', 'sent' => 4500, 'opened' => 2100, 'clicked' => 840, 'scheduled_at' => now()->subDays(5)],
                                (object)['id' => 2, 'name' => 'Monthly Product Update - Nov', 'subject' => 'See what\'s new in your dashboard', 'target' => 'Active Subscribers', 'status' => 'Scheduled', 'sent' => 0, 'opened' => 0, 'clicked' => 0, 'scheduled_at' => now()->addDays(2)],
                                (object)['id' => 3, 'name' => 'Win-back Inactive Users', 'subject' => 'We miss you! Here is a special offer', 'target' => 'Inactive Customers', 'status' => 'Draft', 'sent' => 0, 'opened' => 0, 'clicked' => 0, 'scheduled_at' => null]
                            ] as $campaign)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold fs-6">{{ $campaign->name }}</div>
                                    <div class="text-secondary fs-7 text-truncate" style="max-width: 250px;">
                                        {{ $campaign->subject }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1"><i
                                            class="bi bi-people me-1"></i> {{ $campaign->target }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($campaign->status === 'Sent')
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Sent</span>
                                    @elseif($campaign->status === 'Scheduled')
                                        <span
                                            class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1">Scheduled</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Draft</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if ($campaign->status === 'Sent')
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-center">
                                                <div class="fs-7 fw-bold text-success">
                                                    {{ round(($campaign->opened / $campaign->sent) * 100) }}%</div>
                                                <div class="fs-8 text-secondary">Open</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fs-7 fw-bold text-primary">
                                                    {{ round(($campaign->clicked / $campaign->opened) * 100) }}%</div>
                                                <div class="fs-8 text-secondary">Click</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-secondary fs-7 fst-italic">Pending...</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ $campaign->scheduled_at ? $campaign->scheduled_at->format('M d, Y H:i') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.email-campaigns.show', $campaign->id ?? 1) }}"><i
                                                        class="bi bi-bar-chart me-2 text-primary"></i> View Report</a></li>
                                            @if ($campaign->status !== 'Sent')
                                                <li><a class="dropdown-item py-2"
                                                        href="{{ route('admin.email-campaigns.edit', $campaign->id ?? 1) }}"><i
                                                            class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            @endif
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form
                                                    action="{{ route('admin.email-campaigns.destroy', $campaign->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this campaign?');"><i
                                                            class="bi bi-trash me-2"></i> Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-envelope-paper fs-1"></i></div>
                                    <h6 class="fw-medium">No Email Campaigns Found</h6>
                                    <p class="fs-7">Start engaging your customers by creating a new email campaign.</p>
                                    <a href="{{ route('admin.email-campaigns.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Create Campaign</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($campaigns) && method_exists($campaigns, 'hasPages') && $campaigns->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
