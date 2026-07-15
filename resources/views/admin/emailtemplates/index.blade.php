@extends('layouts.admin')
@section('title', 'Email Templates')
@section('subtitle', 'Manage reusable email templates')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search templates...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.email-templates.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> New Template
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="templatesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td class="text-muted" style="font-size:.8rem">{{ $template->id }}</td>
                                <td>
                                    <div style="font-weight:600">{{ $template->name }}</div>
                                </td>
                                <td>{{ $template->subject }}</td>
                                <td>
                                    @if ($template->is_active)
                                        <span class="badge-saas badge-saas-success">Active</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:.85rem">{{ $template->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="{{ route('admin.email-templates.edit', $template) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.email-templates.destroy', $template) }}"
                                            method="POST" class="form-confirm-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-saas btn-saas-danger btn-saas-sm">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-file-earmark-text"></i></div>
                                        <div class="empty-state-title">No templates yet</div>
                                        <div class="empty-state-description">Create your first email template.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.swal-alert')
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#templatesTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
