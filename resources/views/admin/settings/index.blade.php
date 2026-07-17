@extends('admin.layouts.app')

@section('title', 'Global Settings')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Global Settings</h2>
                <p class="text-secondary mb-0">Configure your application settings, integrations, and preferences.</p>
            </div>
            <div>
                <button type="submit" form="settings-form" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-check2-circle me-2"></i> Save Changes
                </button>
            </div>
        </div>

        <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">

                <!-- Left Sidebar Navigation for Settings -->
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 glass position-sticky" style="top: 100px;">
                        <div class="list-group list-group-flush rounded-4 overflow-hidden p-2">
                            <a href="#general" data-bs-toggle="list"
                                class="list-group-item list-group-item-action bg-transparent border-0 rounded-3 mb-1 active">
                                <i class="bi bi-sliders me-2"></i> General
                            </a>
                            <a href="#payment" data-bs-toggle="list"
                                class="list-group-item list-group-item-action bg-transparent border-0 rounded-3 mb-1">
                                <i class="bi bi-credit-card me-2"></i> Payment Gateway
                            </a>
                            <a href="#mail" data-bs-toggle="list"
                                class="list-group-item list-group-item-action bg-transparent border-0 rounded-3 mb-1">
                                <i class="bi bi-envelope-at me-2"></i> SMTP & Mail
                            </a>
                            <a href="#company" data-bs-toggle="list"
                                class="list-group-item list-group-item-action bg-transparent border-0 rounded-3 mb-1">
                                <i class="bi bi-building me-2"></i> Company Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Content Panels -->
                <div class="col-12 col-md-9">
                    <div class="tab-content">

                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general">
                            <div class="card border-0 shadow-sm rounded-4 glass p-4 mb-4">
                                <h5 class="fw-bold mb-4">General Preferences</h5>

                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="app_name"
                                                name="app_name" value="Cooca ID" placeholder="App Name" required>
                                            <label for="app_name">Application Name</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select rounded-3" id="app_timezone" name="app_timezone">
                                                <option value="Asia/Jakarta" selected>Asia/Jakarta (UTC+7)</option>
                                                <option value="UTC">UTC</option>
                                            </select>
                                            <label for="app_timezone">Timezone</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="text-secondary fs-7 mb-2 d-block">Application Logo</label>
                                        <div class="d-flex align-items-center gap-4 p-3 bg-light rounded-3 border">
                                            <div class="bg-white p-2 rounded border" style="width: 80px; height: 80px;">
                                                <img src="https://ui-avatars.com/api/?name=Cooca&background=primary&color=fff"
                                                    class="w-100 h-100 object-fit-contain" alt="Logo">
                                            </div>
                                            <div>
                                                <input type="file" class="form-control form-control-sm mb-2"
                                                    id="app_logo" name="app_logo"
                                                    accept="image/png, image/jpeg, image/svg+xml">
                                                <span class="fs-8 text-secondary">Recommended: 512x512px, transparent PNG or
                                                    SVG.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div
                                            class="form-check form-switch p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center m-0">
                                            <div>
                                                <label class="form-check-label fw-medium d-block"
                                                    for="maintenance_mode">Maintenance Mode</label>
                                                <span class="fs-8 text-secondary">Turn off application access for
                                                    users.</span>
                                            </div>
                                            <input class="form-check-input fs-4 m-0" type="checkbox" role="switch"
                                                id="maintenance_mode" name="maintenance_mode">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Settings -->
                        <div class="tab-pane fade" id="payment">
                            <div class="card border-0 shadow-sm rounded-4 glass p-4 mb-4">
                                <h5 class="fw-bold mb-4">Midtrans Integration</h5>

                                <div
                                    class="alert bg-primary-subtle text-primary border-primary-subtle rounded-3 d-flex gap-3 align-items-center mb-4">
                                    <i class="bi bi-info-circle-fill fs-4"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Sandbox Mode</h6>
                                        <p class="mb-0 fs-7">Payments are currently operating in test mode. No real cards
                                            will be charged.</p>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select class="form-select rounded-3" id="midtrans_environment"
                                                name="midtrans_environment">
                                                <option value="sandbox" selected>Sandbox (Testing)</option>
                                                <option value="production">Production (Live)</option>
                                            </select>
                                            <label for="midtrans_environment">Environment</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control rounded-3 font-monospace fs-7"
                                                id="midtrans_server_key" name="midtrans_server_key"
                                                value="SB-Mid-server-xxxxxxxxxxxxxx" placeholder="Server Key">
                                            <label for="midtrans_server_key">Server Key</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3 font-monospace fs-7"
                                                id="midtrans_client_key" name="midtrans_client_key"
                                                value="SB-Mid-client-xxxxxxxxxxxxxx" placeholder="Client Key">
                                            <label for="midtrans_client_key">Client Key</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mail Settings -->
                        <div class="tab-pane fade" id="mail">
                            <div class="card border-0 shadow-sm rounded-4 glass p-4 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold mb-0">SMTP Configuration</h5>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="bi bi-send me-1"></i> Send Test Mail
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="mail_host"
                                                name="mail_host" value="smtp.mailgun.org" placeholder="Host">
                                            <label for="mail_host">Mail Host</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control rounded-3" id="mail_port"
                                                name="mail_port" value="587" placeholder="Port">
                                            <label for="mail_port">Port</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-floating">
                                            <select class="form-select rounded-3" id="mail_encryption"
                                                name="mail_encryption">
                                                <option value="tls" selected>TLS</option>
                                                <option value="ssl">SSL</option>
                                                <option value="">None</option>
                                            </select>
                                            <label for="mail_encryption">Encryption</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="mail_username"
                                                name="mail_username" value="postmaster@cooca.id" placeholder="Username">
                                            <label for="mail_username">Username</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control rounded-3" id="mail_password"
                                                name="mail_password" value="********" placeholder="Password">
                                            <label for="mail_password">Password</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control rounded-3" id="mail_from_address"
                                                name="mail_from_address" value="hello@cooca.id"
                                                placeholder="From Address">
                                            <label for="mail_from_address">From Address</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="mail_from_name"
                                                name="mail_from_name" value="Cooca Support" placeholder="From Name">
                                            <label for="mail_from_name">From Name</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Profile -->
                        <div class="tab-pane fade" id="company">
                            <div class="card border-0 shadow-sm rounded-4 glass p-4 mb-4">
                                <h5 class="fw-bold mb-4">Company Details</h5>

                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="company_name"
                                                name="company_name" value="PT Cooca Teknologi Indonesia"
                                                placeholder="Company Legal Name">
                                            <label for="company_name">Company Legal Name</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control rounded-3" id="company_email"
                                                name="company_email" value="contact@cooca.id"
                                                placeholder="Contact Email">
                                            <label for="company_email">Contact Email</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="company_phone"
                                                name="company_phone" value="+62 812 3456 7890"
                                                placeholder="Contact Phone">
                                            <label for="company_phone">Contact Phone</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control rounded-3" id="company_address" name="company_address" placeholder="Physical Address"
                                                style="height: 100px;">Gedung Cyber 1 Lt. 2, Jl. Kuningan Barat Raya No.8, Jakarta Selatan 12710</textarea>
                                            <label for="company_address">Physical Address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
