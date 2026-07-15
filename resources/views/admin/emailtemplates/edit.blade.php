@extends('layouts.admin')
@section('title', 'Edit Template')
@section('subtitle', 'Update email template')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.email-templates.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Templates
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="{{ route('admin.email-templates.update', $template) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-pencil-square me-2"></i>Edit: {{ $template->name }}</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-saas-group">
                            <label class="form-saas-label" for="name">Template Name <span class="text-danger">*</span></label>
                            <input class="form-saas-input @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name', $template->name) }}" autofocus>
                            @error('name')<div class="form-saas-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="subject">Email Subject <span class="text-danger">*</span></label>
                            <input class="form-saas-input @error('subject') is-invalid @enderror" type="text" name="subject" id="subject" value="{{ old('subject', $template->subject) }}">
                            @error('subject')<div class="form-saas-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="body">Email Body <span class="text-danger">*</span></label>
                            <textarea class="form-saas-textarea @error('body') is-invalid @enderror" name="body" id="body" rows="12">{{ old('body', $template->body) }}</textarea>
                            @error('body')<div class="form-saas-error">{{ $message }}</div>@enderror
                            <div class="form-saas-hint">HTML supported.</div>
                        </div>

                        <div class="form-saas-group mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-saas-footer d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.email-templates.index') }}" class="btn-saas btn-saas-secondary">Cancel</a>
                        <button type="submit" class="btn-saas btn-saas-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Template
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-info-circle me-2"></i>Available Placeholders</h5>
                </div>
                <div class="card-saas-body">
                    <ul class="mb-0" style="padding-left:1.2rem;color:var(--text-muted);font-size:.88rem;line-height:2">
                        <li><code>{{{{ name }}}}</code> — Customer name</li>
                        <li><code>{{{{ email }}}}</code> — Customer email</li>
                        <li><code>{{{{ platform_name }}}}</code> — Platform name</li>
                        <li><code>{{{{ subscription_plan }}}}</code> — Plan name</li>
                        <li><code>{{{{ expiry_date }}}}</code> — Expiry date</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
