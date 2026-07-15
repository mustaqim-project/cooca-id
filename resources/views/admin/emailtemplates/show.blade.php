@extends('layouts.admin')
@section('title', 'Template: ' . $template->name)
@section('subtitle', 'View email template details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.email-templates.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Templates
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-file-earmark-text me-2"></i>{{ $template->name }}</h5>
                </div>
                <div class="card-saas-body">
                    <div class="mb-3">
                        <div class="form-saas-label mb-1">Subject</div>
                        <div style="font-size:1rem;font-weight:600">{{ $template->subject }}</div>
                    </div>
                    <div>
                        <div class="form-saas-label mb-2">Body</div>
                        <div class="card-saas" style="background:var(--surface-raised)">
                            <div class="card-saas-body" style="font-size:.9rem;line-height:1.8">
                                {!! nl2br(e($template->body)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-saas mb-4">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-info-circle me-2"></i>Details</h5>
                </div>
                <div class="card-saas-body">
                    <table class="w-100" style="font-size:.9rem;border-collapse:collapse">
                        <tr>
                            <td style="padding:.5rem 0;color:var(--text-muted);width:40%">Status</td>
                            <td style="padding:.5rem 0">
                                @if ($template->is_active)
                                    <span class="badge-saas badge-saas-success">Active</span>
                                @else
                                    <span class="badge-saas badge-saas-neutral">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:.5rem 0;color:var(--text-muted)">Created</td>
                            <td style="padding:.5rem 0">{{ $template->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td style="padding:.5rem 0;color:var(--text-muted)">Updated</td>
                            <td style="padding:.5rem 0">{{ $template->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-lightning me-2"></i>Actions</h5>
                </div>
                <div class="card-saas-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.email-templates.edit', $template) }}" class="btn-saas btn-saas-primary w-100">
                        <i class="bi bi-pencil me-1"></i> Edit Template
                    </a>
                    <form action="{{ route('admin.email-templates.toggle-active', $template) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-saas btn-saas-secondary w-100">
                            @if ($template->is_active)
                                <i class="bi bi-pause-circle me-1"></i> Deactivate
                            @else
                                <i class="bi bi-play-circle me-1"></i> Activate
                            @endif
                        </button>
                    </form>
                    <a href="{{ route('admin.email-templates.preview', $template) }}" target="_blank"
                        class="btn-saas btn-saas-ghost w-100">
                        <i class="bi bi-eye me-1"></i> Preview
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('components.swal-alert')
@endsection
