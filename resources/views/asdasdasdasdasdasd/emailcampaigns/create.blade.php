@extends('layouts.admin')
@section('title', 'New Campaign')
@section('subtitle', 'Create a new email campaign')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.email-campaigns.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Campaigns
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="{{ route('admin.email-campaigns.store') }}" method="POST">
                @csrf
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-envelope-plus me-2"></i>Campaign Details</h5>
                    </div>
                    <div class="card-saas-body">
                        <div class="form-saas-group">
                            <label class="form-saas-label" for="name">Campaign Name <span
                                    class="text-danger">*</span></label>
                            <input class="form-saas-input @error('name') is-invalid @enderror" type="text" name="name"
                                id="name" value="{{ old('name') }}" placeholder="e.g. July Newsletter" autofocus>
                            @error('name')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="subject">Email Subject <span
                                    class="text-danger">*</span></label>
                            <input class="form-saas-input @error('subject') is-invalid @enderror" type="text"
                                name="subject" id="subject" value="{{ old('subject') }}"
                                placeholder="e.g. Exciting updates from Cooca!">
                            @error('subject')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="body">Email Body <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-saas-textarea @error('body') is-invalid @enderror" name="body" id="body" rows="12"
                                placeholder="Write your email content here...">{{ old('body') }}</textarea>
                            @error('body')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                            <div class="form-saas-hint">HTML is supported.</div>
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="status">Status</label>
                            <select class="form-saas-select @error('status') is-invalid @enderror" name="status"
                                id="status">
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="sent" {{ old('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                            </select>
                            @error('status')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-saas-footer d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.email-campaigns.index') }}" class="btn-saas btn-saas-secondary">Cancel</a>
                        <button type="submit" class="btn-saas btn-saas-primary">
                            <i class="bi bi-send me-1"></i> Create Campaign
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-info-circle me-2"></i>Tips</h5>
                </div>
                <div class="card-saas-body">
                    <ul class="mb-0" style="padding-left:1.2rem;color:var(--text-muted);font-size:.9rem;line-height:2">
                        <li>Use a clear, compelling subject line.</li>
                        <li>Keep the body concise and action-focused.</li>
                        <li>Save as Draft to review before sending.</li>
                        <li>HTML markup is supported in the body.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
