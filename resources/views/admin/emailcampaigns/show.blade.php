@extends('layouts.admin')
@section('title', 'Campaign: ' . $campaign->name)
@section('subtitle', 'View campaign details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.email-campaigns.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Campaigns
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title"><i class="bi bi-envelope-open me-2"></i>{{ $campaign->name }}</h5>
                </div>
                <div class="card-saas-body">
                    <div class="mb-3">
                        <div class="form-saas-label mb-1">Subject</div>
                        <div style="font-size:1rem;font-weight:600">{{ $campaign->subject }}</div>
                    </div>
                    <div>
                        <div class="form-saas-label mb-2">Body</div>
                        <div class="card-saas" style="background:var(--surface-raised)">
                            <div class="card-saas-body" style="font-size:.9rem;line-height:1.8">
                                {!! nl2br(e($campaign->body ?? $campaign->content)) !!}
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
                                @if ($campaign->status === 'sent')
                                    <span class="badge-saas badge-saas-success">Sent</span>
                                @else
                                    <span class="badge-saas badge-saas-neutral">Draft</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:.5rem 0;color:var(--text-muted)">Created</td>
                            <td style="padding:.5rem 0">{{ $campaign->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td style="padding:.5rem 0;color:var(--text-muted)">Updated</td>
                            <td style="padding:.5rem 0">{{ $campaign->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if ($campaign->status !== 'sent')
                <div class="card-saas">
                    <div class="card-saas-header">
                        <h5 class="card-saas-title"><i class="bi bi-send me-2"></i>Actions</h5>
                    </div>
                    <div class="card-saas-body">
                        <p style="font-size:.88rem;color:var(--text-muted)">Send this campaign to all subscribers. This
                            action cannot be undone.</p>
                        <form action="{{ route('admin.email-campaigns.send', $campaign) }}" method="POST"
                            class="form-confirm-submit">
                            @csrf
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-send-fill me-1"></i> Send Campaign
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('components.swal-alert')
@endsection
