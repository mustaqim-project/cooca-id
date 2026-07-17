@extends('admin.layouts.app')

@section('title', 'System Error Logs')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">System Error Logs</h2>
                <p class="text-secondary mb-0">Monitor application exceptions, warnings, and errors.</p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.error-logs.clear') }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-trash me-2"></i> Clear All Logs
                    </button>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <select class="form-select form-select-sm rounded-pill border shadow-none bg-light text-secondary"
                        style="width: auto;">
                        <option value="">All Levels</option>
                        <option value="error">Error</option>
                        <option value="warning">Warning</option>
                        <option value="info">Info</option>
                        <option value="critical">Critical</option>
                    </select>
                    <select class="form-select form-select-sm rounded-pill border shadow-none bg-light text-secondary"
                        style="width: auto;">
                        <option value="">All Log Files</option>
                        <option value="laravel.log">laravel.log</option>
                        <option value="worker.log">worker.log</option>
                    </select>
                </div>

                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search error messages...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Timestamp</th>
                            <th class="py-3 px-3 border-0">Level</th>
                            <th class="py-3 px-3 border-0">Message</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($logs ?? [
                                (object)
    ['timestamp' => now()->subMinutes(2), 'level' => 'ERROR', 'message' => 'Undefined type \'App\Jobs\SendEmailCampaignJob\' in app/Http/Controllers/Admin/EmailCampaignController.php on line 141'],
                                (object)['timestamp' => now()->subHours(1), 'level' => 'WARNING', 'message' => 'Stripe webhook received unhandled event type: invoice.payment_failed'],
                                (object)['timestamp' => now()->subDays(1), 'level' => 'CRITICAL', 'message' => 'Database connection timeout on production cluster'],
                                (object)['timestamp' => now()->subDays(2), 'level' => 'INFO', 'message' => 'Maintenance mode activated by Admin (admin@cooca.id)'],
                            ] as $index => $log)
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
                                    @php
                                        $levelColors = [
                                            'error' => 'danger',
                                            'warning' => 'warning',
                                            'critical' => 'dark',
                                            'info' => 'info',
                                            'debug' => 'secondary',
                                        ];
                                        $color = $levelColors[strtolower($log->level)] ?? 'secondary';
                                    @endphp
                                    <span
                                        class="badge bg-{{ $color }}-subtle text-{{ $color }} border border-{{ $color }}-subtle rounded-pill px-3 py-1 text-uppercase">{{ $log->level }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium fs-7 text-truncate" style="max-width: 450px;"
                                        title="{{ $log->message }}">
                                        {{ $log->message }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <button type="button"
                                        class="btn btn-sm btn-light border-0 rounded-circle p-2 hover-lift"
                                        data-bs-toggle="collapse" data-bs-target="#trace-{{ $index }}"
                                        title="View Stack Trace">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="trace-{{ $index }}">
                                <td colspan="4" class="p-0 border-0">
                                    <div class="p-4 bg-light border-top border-bottom border-light">
                                        <label class="fw-bold fs-7 mb-2 text-secondary">Full Log Detail</label>
                                        <pre class="p-3 bg-dark text-light rounded-3 font-monospace fs-8 overflow-auto mb-0" style="max-height: 300px;">[{{ is_object($log->timestamp ?? null) ? $log->timestamp->format('Y-m-d H:i:s') : '-' }}] local.{{ $log->level }}: {{ $log->message }}
Stack trace:
#0 [internal function]: Illuminate\Foundation\Bootstrap\HandleExceptions->handleError(2, 'Undefined type ...', '/var/www/html/a...', 141)
#1 /var/www/html/app/Http/Controllers/Admin/EmailCampaignController.php(141): include()
#2 /var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Controller.php(54): App\Http\Controllers\Admin\EmailCampaignController->store(Object(Illuminate\Http\Request))
#3 /var/www/html/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php(43): Illuminate\Routing\Controller->callAction('store', Array)
#4 /var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Route.php(260): Illuminate\Routing\ControllerDispatcher->dispatch(Object(Illuminate\Routing\Route), Object(App\Http\Controllers\Admin\EmailCampaignController), 'store')</pre>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-check-circle fs-1 text-success"></i></div>
                                    <h6 class="fw-medium">No Errors Found</h6>
                                    <p class="fs-7">Your application is running smoothly.</p>
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
