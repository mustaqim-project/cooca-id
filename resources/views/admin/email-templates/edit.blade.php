@extends('admin.layouts.app')

@section('title', 'Edit Email Template')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 1000px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.email-templates.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Edit Email Template</h2>
                <p class="text-secondary mb-0">Update the layout, content structure, or properties for this email template.
                </p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.email-templates.update', $template->id ?? 1) }}" method="POST"
                class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <!-- Section 1: Basic Info -->
                <div>
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> 1. Template Info</h5>
                    <div class="row g-4">
                        <div class="col-12 col-md-8">
                            <div class="form-floating">
                                <input type="text"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', 'Welcome to Cooca ID') }}"
                                    placeholder="Template Name" required>
                                <label for="name">Template Name (Internal) <span class="text-danger">*</span></label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <select
                                    class="form-select rounded-3 shadow-none border bg-transparent @error('category') is-invalid @enderror"
                                    id="category" name="category" required>
                                    <option value="" disabled>Select category...</option>
                                    <option value="Transactional"
                                        {{ old('category', 'Transactional') == 'Transactional' ? 'selected' : '' }}>
                                        Transactional</option>
                                    <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>
                                        Marketing</option>
                                    <option value="Billing" {{ old('category') == 'Billing' ? 'selected' : '' }}>Billing
                                    </option>
                                    <option value="Notification" {{ old('category') == 'Notification' ? 'selected' : '' }}>
                                        System Notification</option>
                                </select>
                                <label for="category">Category <span class="text-danger">*</span></label>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('subject') is-invalid @enderror"
                                    id="subject" name="subject"
                                    value="{{ old('subject', 'Welcome aboard! Let\'s get you started.') }}"
                                    placeholder="Default Subject Line" required>
                                <label for="subject">Default Subject Line <span class="text-danger">*</span></label>
                                <div class="form-text fs-7">You can use variables here (e.g., "Welcome {name}!").</div>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <!-- Section 2: Designer -->
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-code me-2 text-primary"></i> 2. Template
                            Design</h5>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i> Live Preview
                        </button>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <!-- Designer Toolbar Mock -->
                            <div
                                class="border border-bottom-0 rounded-top-3 p-2 bg-light d-flex flex-wrap gap-2 text-secondary">
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-type-bold"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-type-italic"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-type-underline"></i></button>
                                <div class="vr mx-1"></div>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-justify-left"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-justify"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-justify-right"></i></button>
                                <div class="vr mx-1"></div>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-link-45deg"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-image"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                        class="bi bi-table"></i></button>

                                <div class="ms-auto d-flex gap-2">
                                    <select class="form-select form-select-sm" style="width: auto;">
                                        <option>Insert Variable...</option>
                                        <option>{name}</option>
                                        <option>{email}</option>
                                        <option>{app_name}</option>
                                        <option>{login_url}</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-dark px-3"><i
                                            class="bi bi-code-slash me-1"></i> HTML Mode</button>
                                </div>
                            </div>

                            <textarea
                                class="form-control border shadow-none bg-white rounded-bottom-3 rounded-top-0 @error('content') is-invalid @enderror"
                                id="content" name="content" style="min-height: 500px; font-family: monospace; resize: vertical;" required>{{ old(
                                    'content',
                                    '<!DOCTYPE html>
                                                                <html>
                                                                <head>
                                                                    <style>
                                                                        body { font-family: Inter, sans-serif; background-color: #f8f9fa; margin: 0; padding: 40px; }
                                                                        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; }
                                                                        .btn { display: inline-block; padding: 10px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; }
                                                                    </style>
                                                                </head>
                                                                <body>
                                                                    <div class="container">
                                                                        <h2>Welcome aboard, {name}!</h2>
                                                                        <p>We are thrilled to have you join Cooca ID. Our enterprise suite is designed to streamline all your business processes efficiently.</p>
                                                                        <p>To get started, please log in to your dashboard and verify your organization profile settings.</p>
                                                                        <div style="text-align: center; margin: 30px 0;">
                                                                            <a href="{login_url}" class="btn">Access Dashboard</a>
                                                                        </div>
                                                                        <p>If you need any assistance, reach out to our dedicated support team at <a href="mailto:{support_email}">{support_email}</a>.</p>
                                                                    </div>
                                                                </body>
                                                                </html>',
                                ) }}</textarea>

                            @error('content')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2 mt-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.email-templates.index') }}"
                        class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
