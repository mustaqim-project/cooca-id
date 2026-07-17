@extends('admin.layouts.app')

@section('title', 'Template Preview')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.email-templates.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Template Preview</h2>
                    <p class="text-secondary mb-0">Review email template layout, variables, and raw HTML structure.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.email-templates.edit', 1) }}"
                    class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary">
                    <i class="bi bi-pencil me-2"></i> Edit Template
                </a>
                <form action="{{ route('admin.email-templates.destroy', 1) }}" method="POST"
                    onsubmit="return confirm('Delete this template?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-trash me-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass mb-4">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Visual Preview</h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary active"><i
                                    class="bi bi-laptop me-1"></i> Desktop</button>
                            <button type="button" class="btn btn-outline-secondary"><i class="bi bi-phone me-1"></i>
                                Mobile</button>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light rounded-bottom-4">
                        <div class="bg-white rounded-3 p-4 shadow-sm mx-auto border" style="max-width: 600px;">
                            <div class="text-center mb-4 pb-3 border-bottom">
                                <h3 class="fw-bold text-primary mb-0">Cooca ID</h3>
                            </div>
                            <h4 style="color: #212529; font-weight: 600; margin-bottom: 16px;">Welcome aboard, John Doe!
                            </h4>
                            <p style="color: #495057; line-height: 1.6; margin-bottom: 16px;">We are thrilled to have you
                                join Cooca ID. Our enterprise suite is designed to streamline all your business processes
                                efficiently.</p>
                            <p style="color: #495057; line-height: 1.6; margin-bottom: 24px;">To get started, please log in
                                to your dashboard and verify your organization profile settings.</p>
                            <div class="text-center my-4">
                                <a href="#"
                                    style="background-color: #0d6efd; color: #ffffff; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block;">Access
                                    Dashboard</a>
                            </div>
                            <p style="color: #495057; line-height: 1.6; margin-bottom: 0;">If you need any assistance, reach
                                out to our dedicated support team at <a href="mailto:support@cooca.id"
                                    style="color: #0d6efd;">support@cooca.id</a>.</p>
                            <div class="mt-4 pt-3 border-top text-center" style="color: #adb5bd; font-size: 12px;">
                                &copy; 2026 Cooca ID Inc. All rights reserved.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Raw HTML Code -->
                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Raw HTML Source</h5>
                        <button class="btn btn-sm btn-light border rounded-pill px-3"><i class="bi bi-clipboard me-1"></i>
                            Copy Code</button>
                    </div>
                    <div class="card-body p-0">
                        <pre class="m-0 p-4 bg-dark text-light rounded-bottom-4 fs-7 overflow-auto" style="max-height: 350px;"><code><!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Inter, sans-serif; background-color: #f8f9fa; margin: 0; padding: 40px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome aboard, {name}!</h2>
        <p>We are thrilled to have you join Cooca ID.</p>
        <a href="{login_url}">Access Dashboard</a>
    </div>
</body>
</html></code></pre>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4 d-flex flex-column gap-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-4">Template Info</h5>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Internal Name</label>
                        <div class="fw-bold fs-5">Welcome to Cooca ID</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Subject Line</label>
                        <div class="fw-medium">Welcome aboard! Let's get you started.</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Category</label>
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1">Transactional</span>
                    </div>

                    <hr class="border-light my-2">

                    <div class="d-flex justify-content-between text-start my-3">
                        <span class="text-secondary fs-7">Created At</span>
                        <span class="fw-medium fs-7">Oct 12, 2026</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span class="fw-medium fs-7">Oct 15, 2026</span>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-3">Available Variables</h5>
                    <p class="text-secondary fs-7 mb-3">The following dynamic placeholders can be injected into this
                        template:</p>

                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded border">
                            <code class="text-primary">{name}</code>
                            <span class="text-secondary fs-8">Recipient's full name</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded border">
                            <code class="text-primary">{login_url}</code>
                            <span class="text-secondary fs-8">Authentication link</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded border">
                            <code class="text-primary">{support_email}</code>
                            <span class="text-secondary fs-8">Helpdesk email address</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
