@extends('layouts.admin')

@section('title', 'Email System Templates — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Email Templates</span>
        </div>
        <h1 class="page-title">Transactional Email Templates</h1>
        <p class="page-subtitle">Configure automated email layouts for welcome emails, license delivery, invoice receipts, and password resets.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.email-templates.create') }}" class="btn btn-primary">
            <span>✉️</span> New Template
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Template Key</th>
                        <th>Template Name</th>
                        <th>Subject Line</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates ?? [] as $tmpl)
                        @php $tObj = is_array($tmpl) ? (object)$tmpl : $tmpl; @endphp
                        <tr>
                            <td><code class="text-primary font-bold">{{ $tObj->key ?? 'welcome-email' }}</code></td>
                            <td class="font-bold text-base">{{ $tObj->name ?? 'Welcome Template' }}</td>
                            <td class="text-sm">{{ $tObj->subject ?? 'Welcome to COOCA.ID' }}</td>
                            <td>
                                @if($tObj->is_active ?? true)
                                    <span class="badge badge-success">ACTIVE</span>
                                @else
                                    <span class="badge badge-muted">INACTIVE</span>
                                @endif
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.email-templates.edit', $tObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 40px;">No email templates created.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
