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
                <span class="text-secondary fs-7 d-flex align-items-center">
                    <i class="bi bi-info-circle me-1"></i> {{ count($schemas) }} predefined integrations
                </span>
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
                        @forelse($integrations as $item)
                            @php
                                $schema = $schemas[$item->provider] ?? null;
                                $iconMap = [
                                    'midtrans' => 'credit-card',
                                    'google_oauth' => 'google',
                                    'smtp' => 'envelope',
                                    'smtp_noreply' => 'envelope',
                                    'smtp_marketing' => 'envelope',
                                    'smtp_support' => 'envelope',
                                    'smtp_billing' => 'envelope',
                                    'whatsapp' => 'whatsapp',
                                ];
                                $colorMap = [
                                    'midtrans' => 'primary',
                                    'google_oauth' => 'danger',
                                    'smtp' => 'info',
                                    'smtp_noreply' => 'info',
                                    'smtp_marketing' => 'info',
                                    'smtp_support' => 'info',
                                    'smtp_billing' => 'info',
                                    'whatsapp' => 'success',
                                ];
                                $typeMap = [
                                    'midtrans' => 'payment',
                                    'google_oauth' => 'oauth',
                                    'smtp' => 'email',
                                    'smtp_noreply' => 'email',
                                    'smtp_marketing' => 'email',
                                    'smtp_support' => 'email',
                                    'smtp_billing' => 'email',
                                    'whatsapp' => 'whatsapp',
                                ];
                                $icon = $iconMap[$item->provider] ?? 'plug';
                                $color = $colorMap[$item->provider] ?? 'secondary';
                                $type = $typeMap[$item->provider] ?? 'api';
                            @endphp
                            <tr data-type="{{ $type }}"
                                data-status="{{ $item->is_active ? 'active' : 'inactive' }}">
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-{{ $color }}-subtle text-{{ $color }} rounded-circle p-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-{{ $icon }} fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item->name }}</div>
                                            <div class="text-secondary fs-7 text-uppercase font-monospace">
                                                {{ $item->provider }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-light text-secondary border rounded-pill px-3 py-1 text-capitalize">{{ $type }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($item->is_active)
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1"><i
                                                class="bi bi-shield-check me-1"></i> Production</span>
                                    @else
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1"><i
                                                class="bi bi-shield-exclamation me-1"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    @if ($item->updated_at)
                                        {{ $item->updated_at->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if ($item->is_active)
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
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('admin.api-integrations.edit', $item->provider) }}"
                                            class="btn btn-sm btn-light border rounded-pill px-3 hover-lift text-secondary">
                                            <i class="bi bi-gear me-1"></i> Configure
                                        </a>
                                        <form action="{{ route('admin.api-integrations.toggle', $item->provider) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm {{ $item->is_active ? 'btn-warning' : 'btn-success' }} rounded-pill px-3">
                                                <i class="bi bi-{{ $item->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-plug fs-1 text-secondary"></i></div>
                                    <h6 class="fw-medium">No API Integrations Setup</h6>
                                    <p class="fs-7">Connect third-party services like payment gateways or email
                                        providers.</p>
                                    <a href="{{ route('admin.settings.index') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3 mt-2">
                                        <i class="bi bi-gear me-1"></i> Go to Settings
                                    </a>
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

    <!-- Info: Integrations are predefined -->
    <div class="text-center text-secondary fs-7 mt-2">
        <i class="bi bi-info-circle me-1"></i>
        Integrations are predefined for Midtrans, Google OAuth, various SMTPs, and WhatsApp.
        Click <strong>Configure</strong> to manage each integration's settings.
    </div>
@endsection
