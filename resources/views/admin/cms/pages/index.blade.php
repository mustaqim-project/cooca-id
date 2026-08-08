@extends('layouts.admin')

@section('title', 'Custom Pages CMS — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>CMS Pages</span>
        </div>
        <h1 class="page-title">Custom Pages Management</h1>
        <p class="page-subtitle">Manage static pages like Privacy Policy, Terms of Service, Solutions, and Landing Subpages.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.cms.pages.create') }}" class="btn btn-primary">
            <span>📄</span> Create New Page
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Page Title</th>
                        <th>URL Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages ?? [] as $page)
                        @php $pObj = is_array($page) ? (object)$page : $page; @endphp
                        <tr>
                            <td class="font-bold text-base">{{ $pObj->title ?? 'Page' }}</td>
                            <td><code>/{{ $pObj->slug ?? '' }}</code></td>
                            <td>
                                @if($pObj->is_published ?? true)
                                    <span class="badge badge-success">PUBLISHED</span>
                                @else
                                    <span class="badge badge-muted">DRAFT</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.cms.pages.edit', $pObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding: 40px;">No custom CMS pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
