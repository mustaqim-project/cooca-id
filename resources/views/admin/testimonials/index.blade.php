@extends('layouts.admin')

@section('title', 'Client Testimonials — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Testimonials</span>
        </div>
        <h1 class="page-title">Client Testimonials CMS</h1>
        <p class="page-subtitle">Manage customer quotes, company logos, and featured testimonials for the landing page.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
            <span>💬</span> Add Testimonial
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Company & Position</th>
                        <th>Quote Content</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials ?? [] as $tst)
                        @php $tObj = is_array($tst) ? (object)$tst : $tst; @endphp
                        <tr>
                            <td class="font-bold text-base">{{ $tObj->name ?? 'Client' }}</td>
                            <td class="text-sm">{{ $tObj->company ?? '' }} ({{ $tObj->position ?? '' }})</td>
                            <td class="text-xs">"{{ Str::limit($tObj->quote ?? '', 60) }}"</td>
                            <td>
                                @if($tObj->is_featured ?? false)
                                    <span class="badge badge-success">FEATURED</span>
                                @else
                                    <span class="badge badge-muted">STANDARD</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.testimonials.edit', $tObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No testimonials added.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
