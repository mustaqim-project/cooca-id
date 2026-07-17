@extends('admin.layouts.app')

@section('title', 'Api Integrations')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">API Integrations</h2>
                <p class="text-secondary mb-0">Manage third-party API keys, webhooks, and service connections.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#createIntegrationModal">
                    <i class="bi bi-plus-lg me-2"></i> Add Integration
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <select class="form-select form-select-sm rounded-pill border shadow-none bg-light text-secondary"
                        style="width: auto;" data-filter-key="type">
                        <option value="">All Services</option>
                        <option value="payment">Payment Gateways</option>
                        <option value="whatsapp">WhatsApp API</option>
                        <option value="email">Email Service</option>
                    </select>
                    <select class="form-select form-select-sm rounded-pill border shadow-none bg-light text-secondary"
                        style="width: auto;" data-filter-key="status">
                        <option value="">Status</option>
                        <option value="active">Active / Connected</option>
                        <option value="inactive">Disconnected</option>
                    </select>
                </div>

                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search integrations...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Service Name</th>
                            <th class="py-3 px-3 border-0">Type</th>
                            <th class="py-3 px-3 border-0">Environment</th>
                            <th class="py-3 px-3 border-0">Last Synced</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($integrations ?? [
                                (object)
    ['id' => 1, 'name' => 'Midtrans Payment Gateway', 'type' => 'payment', 'env' => 'production', 'last_sync' => '2026-07-15 14:30:00', 'is_active' => true, 'icon' => 'credit-card', 'color' => 'primary'],
                                (object)['id' => 2, 'name' => 'Tripay Indonesia', 'type' => 'payment', 'env' => 'production', 'last_sync' => '2026-07-15 10:15:00', 'is_active' => true, 'icon' => 'wallet2', 'color' => 'info'],
                                (object)['id' => 3, 'name' => 'Fonnte WhatsApp API', 'type' => 'whatsapp', 'env' => 'production', 'last_sync' => '2026-07-15 15:45:00', 'is_active' => true, 'icon' => 'whatsapp', 'color' => 'success'],
                                (object)['id' => 4, 'name' => 'Resend Mailer', 'type' => 'email', 'env' => 'development', 'last_sync' => '2026-07-10 09:00:00', 'is_active' => false, 'icon' => 'envelope-paper', 'color' => 'danger']
                            ] as $item)
                            <tr data-type="{{ $item->type ?? 'api' }}"
                                data-status="{{ $item->is_active ?? true ? 'active' : 'inactive' }}">
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-{{ $item->color ?? 'primary' }}-subtle text-{{ $item->color ?? 'primary' }} rounded-circle p-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-{{ $item->icon ?? 'plug' }} fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item->name }}</div>
                                            <div class="text-secondary fs-7 text-uppercase font-monospace">API Key:
                                                ****{{ substr(md5($item->id), -4) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-light text-secondary border rounded-pill px-3 py-1 text-capitalize">{{ $item->type ?? 'api' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if (($item->env ?? 'production') === 'production')
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1"><i
                                                class="bi bi-shield-check me-1"></i> Production</span>
                                    @else
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1"><i
                                                class="bi bi-shield-exclamation me-1"></i> Sandbox / Dev</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ \Carbon\Carbon::parse($item->last_sync ?? now())->diffForHumans() }}
                                </td>
                                <td class="py-3 px-3">
                                    @if ($item->is_active ?? true)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="d-inline-block bg-success rounded-circle"
                                                style="width: 8px; height: 8px;"></span>
                                            <span class="text-success fw-medium fs-7">Connected</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="d-inline-block bg-danger rounded-circle"
                                                style="width: 8px; height: 8px;"></span>
                                            <span class="text-danger fw-medium fs-7">Disconnected</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 hover-lift text-secondary"
                                        data-bs-toggle="modal" data-bs-target="#editIntegrationModal{{ $item->id }}">
                                        Configure
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Modal (Simplified for UI display) -->
                            <div class="modal fade" id="editIntegrationModal{{ $item->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden glass">
                                        <div class="modal-header border-bottom border-light p-4">
                                            <h5 class="modal-title fw-bold">Configure {{ $item->name }}</h5>
                                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div
                                                class="alert alert-warning border-warning-subtle bg-warning-subtle text-warning-emphasis rounded-3 fs-7 mb-4">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Keep your API keys
                                                secure. Never share them publicly.
                                            </div>
                                            <div class="d-flex flex-column gap-3">
                                                <div class="form-floating">
                                                    <input type="text"
                                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                                        value="{{ $item->name }}" readonly>
                                                    <label>Service Name</label>
                                                </div>
                                                <div class="form-floating">
                                                    <input type="password"
                                                        class="form-control rounded-3 shadow-none border bg-transparent"
                                                        value="dummy-api-key-here" placeholder="API Key">
                                                    <label>API Key / Secret</label>
                                                </div>
                                                <div class="form-floating">
                                                    <select class="form-select rounded-3 shadow-none border bg-transparent">
                                                        <option value="production"
                                                            {{ ($item->env ?? '') === 'production' ? 'selected' : '' }}>
                                                            Production (Live)</option>
                                                        <option value="development"
                                                            {{ ($item->env ?? '') === 'development' ? 'selected' : '' }}>
                                                            Sandbox (Development)</option>
                                                    </select>
                                                    <label>Environment</label>
                                                </div>
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input shadow-none cursor-pointer"
                                                        type="checkbox" role="switch" id="status{{ $item->id }}"
                                                        {{ $item->is_active ?? false ? 'checked' : '' }}>
                                                    <label class="form-check-label cursor-pointer"
                                                        for="status{{ $item->id }}">Enable Service Integration</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-light p-4 bg-light">
                                            <button type="button" class="btn btn-light rounded-pill px-4"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary rounded-pill px-4"
                                                data-bs-dismiss="modal">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-plug fs-1 text-secondary"></i></div>
                                    <h6 class="fw-medium">No API Integrations Setup</h6>
                                    <p class="fs-7">Connect third-party services like payment gateways or email
                                        providers.</p>
                                    <button class="btn btn-sm btn-primary rounded-pill px-3 mt-2" data-bs-toggle="modal"
                                        data-bs-target="#createIntegrationModal">Add Integration</button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($integrations) && method_exists($integrations, 'hasPages') && $integrations->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $integrations->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createIntegrationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden glass">
                <div class="modal-header border-bottom border-light p-4">
                    <h5 class="modal-title fw-bold">Add New Integration</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <div class="form-floating">
                            <select class="form-select rounded-3 shadow-none border bg-transparent">
                                <option value="">Select Service Provider</option>
                                <option value="midtrans">Midtrans</option>
                                <option value="tripay">Tripay</option>
                                <option value="stripe">Stripe</option>
                                <option value="paypal">PayPal</option>
                                <option value="fonnte">Fonnte (WhatsApp)</option>
                                <option value="resend">Resend (Email)</option>
                                <option value="custom">Custom Webhook</option>
                            </select>
                            <label>Service Provider</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3 shadow-none border bg-transparent"
                                placeholder="Display Name">
                            <label>Display Name</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3 shadow-none border bg-transparent"
                                placeholder="API Key / Secret">
                            <label>API Key / Secret</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select rounded-3 shadow-none border bg-transparent">
                                <option value="development">Sandbox (Development)</option>
                                <option value="production">Production (Live)</option>
                            </select>
                            <label>Environment</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-4 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">Connect
                        Service</button>
                </div>
            </div>
        </div>
    </div>
@endsection
