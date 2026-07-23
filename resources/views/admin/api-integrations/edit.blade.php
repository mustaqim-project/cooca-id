@extends('admin.layouts.app')

@section('title', 'Edit ' . ($schema['name'] ?? $provider))

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">{{ $schema['name'] ?? ucfirst($provider) }}</h2>
                <p class="text-secondary mb-0">Configure API integration settings</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.api-integrations.index') }}" class="btn btn-light rounded-pill px-4 hover-lift">
                    <i class="bi bi-arrow-left me-2"></i> Back
                </a>
            </div>
        </div>

        <!-- Error & Success Messages -->
        @if (session('success'))
            <div
                class="alert alert-success border-success-subtle bg-success-subtle text-success-emphasis rounded-3 fs-7 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger-emphasis rounded-3 fs-7 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div
                class="alert alert-info border-info-subtle bg-info-subtle text-info-emphasis rounded-3 fs-7 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill"></i> {{ session('info') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Edit Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="mb-0 fw-semibold">Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.api-integrations.update', $provider) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="d-flex flex-column gap-4">
                                @foreach ($schema['fields'] as $field => $meta)
                                    <div class="form-group">
                                        <label for="config_{{ $field }}" class="form-label fw-medium mb-2">
                                            {{ $meta['label'] }}
                                            @if (!empty($meta['required']))
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>

                                        @if ($meta['type'] === 'boolean')
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="config[{{ $field }}]" value="0">
                                                <input class="form-check-input shadow-none cursor-pointer" type="checkbox"
                                                    role="switch" id="config_{{ $field }}"
                                                    name="config[{{ $field }}]" value="1"
                                                    {{ old("config.{$field}", $integration->config[$field] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label cursor-pointer"
                                                    for="config_{{ $field }}">
                                                    Enable {{ $meta['label'] }}
                                                </label>
                                            </div>
                                        @elseif ($meta['type'] === 'password')
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control rounded-3 shadow-none border bg-transparent @error("config.{$field}") is-invalid @enderror"
                                                    id="config_{{ $field }}" name="config[{{ $field }}]"
                                                    placeholder="{{ $meta['label'] }}" autocomplete="off">
                                                <button class="btn btn-outline-secondary rounded-3" type="button"
                                                    onclick="togglePassword('config_{{ $field }}', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            @if (isset($integration->config[$field]) && !empty($integration->config[$field]))
                                                <div class="form-text text-secondary mt-1">
                                                    <i class="bi bi-lock me-1"></i> Leave blank to keep existing value.
                                                </div>
                                            @endif
                                            @error("config.{$field}")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        @elseif ($meta['type'] === 'email')
                                            <input type="email"
                                                class="form-control rounded-3 shadow-none border bg-transparent @error("config.{$field}") is-invalid @enderror"
                                                id="config_{{ $field }}" name="config[{{ $field }}]"
                                                value="{{ old("config.{$field}", $integration->config[$field] ?? '') }}"
                                                placeholder="{{ $meta['label'] }}">
                                            @error("config.{$field}")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        @elseif ($meta['type'] === 'number')
                                            <input type="number"
                                                class="form-control rounded-3 shadow-none border bg-transparent @error("config.{$field}") is-invalid @enderror"
                                                id="config_{{ $field }}" name="config[{{ $field }}]"
                                                value="{{ old("config.{$field}", $integration->config[$field] ?? '') }}"
                                                placeholder="{{ $meta['label'] }}">
                                            @error("config.{$field}")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        @else
                                            <input type="text"
                                                class="form-control rounded-3 shadow-none border bg-transparent @error("config.{$field}") is-invalid @enderror"
                                                id="config_{{ $field }}" name="config[{{ $field }}]"
                                                value="{{ old("config.{$field}", $integration->config[$field] ?? '') }}"
                                                placeholder="{{ $meta['label'] }}">
                                            @error("config.{$field}")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                @endforeach

                                <!-- Active Status Toggle -->
                                <div class="form-check form-switch mt-3 pt-3 border-top border-light">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox"
                                        role="switch" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', $integration->is_active ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label cursor-pointer fw-medium" for="is_active">
                                        <i class="bi bi-toggle-on me-1"></i> Enable this integration
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-4 pt-3 border-top border-light">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                                    <i class="bi bi-check-lg me-2"></i> Save Configuration
                                </button>
                                <a href="{{ route('admin.api-integrations.index') }}"
                                    class="btn btn-light rounded-pill px-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Actions & Info -->
            <div class="col-lg-5">
                <!-- Status Card -->
                <div class="card border-0 shadow-sm rounded-4 glass mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Status</h6>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if ($integration && $integration->is_active)
                                <span class="d-inline-block bg-success rounded-circle"
                                    style="width: 12px; height: 12px;"></span>
                                <span class="text-success fw-medium">Active & Connected</span>
                            @else
                                <span class="d-inline-block bg-danger rounded-circle"
                                    style="width: 12px; height: 12px;"></span>
                                <span class="text-danger fw-medium">Inactive / Disconnected</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.api-integrations.toggle', $provider) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm {{ $integration && $integration->is_active ? 'btn-warning' : 'btn-success' }} rounded-pill px-3">
                                    <i
                                        class="bi bi-{{ $integration && $integration->is_active ? 'pause' : 'play' }} me-1"></i>
                                    {{ $integration && $integration->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.api-integrations.test', $provider) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info rounded-pill px-3 text-white"
                                    {{ !$integration || !$integration->is_active ? 'disabled' : '' }}>
                                    <i class="bi bi-plug me-1"></i> Test Connection
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Integration Info</h6>
                        <div class="d-flex flex-column gap-2 fs-7">
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Provider</span>
                                <span class="fw-medium text-uppercase">{{ $provider }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Service Name</span>
                                <span class="fw-medium">{{ $schema['name'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Last Updated</span>
                                <span class="fw-medium">
                                    @if ($integration && $integration->updated_at)
                                        {{ $integration->updated_at->diffForHumans() }}
                                    @else
                                        Not configured yet
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Encryption</span>
                                <span class="fw-medium text-success"><i class="bi bi-shield-check me-1"></i>
                                    AES-256</span>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div
                            class="alert alert-info border-info-subtle bg-info-subtle text-info-emphasis rounded-3 fs-7 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Configuration values are stored encrypted at rest using Laravel's built-in encryption.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            if (input) {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            }
        }
    </script>
@endpush
