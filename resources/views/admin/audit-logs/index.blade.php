@extends('admin.layouts.app')

@section('title', 'System Audit Logs')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">System Audit Logs</h2>
                <p class="text-secondary mb-0">Track and monitor all user and system activities for security and compliance.
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-3 hover-lift text-secondary">
                    <i class="bi bi-calendar-range me-2"></i> Date Range
                </button>
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-3 hover-lift text-secondary">
                    <i class="bi bi-filter me-2"></i> Filter
                </button>
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
                        placeholder="Search by user, action, or IP...">
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
                            <th class="py-3 px-4 border-0">Timestamp</th>
                            <th class="py-3 px-3 border-0">User / Actor</th>
                            <th class="py-3 px-3 border-0">Action</th>
                            <th class="py-3 px-3 border-0">Module</th>
                            <th class="py-3 px-3 border-0">IP Address</th>
                            <th class="py-3 px-4 border-0 text-end">Details</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($logs ?? [
                            (object)
    ['id' => 'AL-9021', 'timestamp' => now()->subMinutes(5), 'user_name' => 'Admin Super', 'user_role' => 'System Admin', 'action' => 'updated', 'module' => 'Settings', 'description' => 'Changed global tax rate from 10% to 11%', 'ip_address' => '192.168.1.100'],
                            (object)['id' => 'AL-9020', 'timestamp' => now()->subMinutes(45), 'user_name' => 'John Doe', 'user_role' => 'Customer', 'action' => 'login', 'module' => 'Auth', 'description' => 'Successful login via Web', 'ip_address' => '114.120.45.12'],
                            (object)['id' => 'AL-9019', 'timestamp' => now()->subHours(2), 'user_name' => 'System', 'user_role' => 'Automation', 'action' => 'deleted', 'module' => 'Subscriptions', 'description' => 'Auto-purged expired trial subscriptions', 'ip_address' => '127.0.0.1'],
                            (object)['id' => 'AL-9018', 'timestamp' => now()->subHours(5), 'user_name' => 'Jane Smith', 'user_role' => 'Support', 'action' => 'created', 'module' => 'Tickets', 'description' => 'Created reply for ticket TCK-2026-1042', 'ip_address' => '10.0.0.55'],
                        ] as $log)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-medium">
                                        {{ is_object($log->timestamp ?? null) ? $log->timestamp->format('M d, Y') : '-' }}
                                    </div>
                                    <div class="text-secondary fs-7">
                                        {{ is_object($log->timestamp ?? null) ? $log->timestamp->format('H:i:s') : '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user_name) }}&background=random"
                                            class="rounded-circle" width="28" height="28" alt="User">
                                        <div>
                                            <div class="fw-medium fs-7">{{ $log->user_name }}</div>
                                            <div class="text-secondary fs-8">{{ $log->user_role }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $actionColors = [
                                            'created' => 'success',
                                            'updated' => 'info',
                                            'deleted' => 'danger',
                                            'login' => 'primary',
                                            'logout' => 'secondary',
                                        ];
                                        $color = $actionColors[strtolower($log->action)] ?? 'secondary';
                                    @endphp
                                    <span
                                        class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle rounded-pill px-2 py-1 text-uppercase"
                                        style="font-size: 0.65rem;">{{ $log->action }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium fs-7">{{ $log->module }}</div>
                                    <div class="text-secondary fs-8 text-truncate" style="max-width: 200px;"
                                        title="{{ $log->description }}">{{ $log->description }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="font-monospace fs-7 text-secondary bg-light px-2 py-1 rounded">{{ $log->ip_address }}</span>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <a href="{{ route('admin.audit-logs.show', $log->id ?? 1) }}"
                                        class="btn btn-sm btn-light border-0 rounded-circle p-2 hover-lift"
                                        title="View Details">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-shield-check fs-1"></i></div>
                                    <h6 class="fw-medium">No Audit Logs Found</h6>
                                    <p class="fs-7">There are no activities matching your current filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($logs) && method_exists($logs, 'hasPages') && $logs->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
