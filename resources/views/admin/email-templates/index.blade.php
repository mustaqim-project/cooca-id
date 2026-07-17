@extends('admin.layouts.app')

@section('title', 'Email Templates')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Email Templates</h2>
                <p class="text-secondary mb-0">Design and manage reusable HTML templates for transactional and marketing
                    emails.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.email-templates.create') }}"
                    class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Create Template
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search templates...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Template Details</th>
                            <th class="py-3 px-3 border-0">Type / Category</th>
                            <th class="py-3 px-3 border-0">Variables</th>
                            <th class="py-3 px-3 border-0">Last Updated</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($templates ?? [
                                (object)
    ['id' => 1, 'name' => 'Welcome to Cooca ID', 'subject' => 'Welcome aboard! Let\'s get you started.', 'type' => 'Transactional', 'variables' => ['name', 'login_url', 'support_email'], 'updated_at' => now()->subDays(2)],
                                (object)['id' => 2, 'name' => 'Invoice & Receipt Notification', 'subject' => 'Your receipt for invoice #{invoice_id}', 'type' => 'Billing', 'variables' => ['name', 'invoice_id', 'amount', 'due_date'], 'updated_at' => now()->subDays(10)],
                                (object)['id' => 3, 'name' => 'Monthly Newsletter Layout', 'subject' => '{month} Product Updates & Insights', 'type' => 'Marketing', 'variables' => ['name', 'month', 'highlights'], 'updated_at' => now()->subDays(25)]
                            ] as $template)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold fs-6">{{ $template->name }}</div>
                                    <div class="text-secondary fs-7 text-truncate" style="max-width: 250px;">Subj:
                                        {{ $template->subject }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span
                                        class="badge bg-light text-dark border rounded-pill px-3 py-1">{{ $template->type }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($template->variables as $var)
                                            <span
                                                class="badge bg-primary-subtle text-primary border border-primary-subtle rounded px-2 py-0 fs-8">{{ '{' . $var . '}' }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ is_object($template->updated_at ?? null) ? $template->updated_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.email-templates.show', $template->id ?? 1) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> Preview</a></li>
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.email-templates.edit', $template->id ?? 1) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form
                                                    action="{{ route('admin.email-templates.destroy', $template->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this template?');"><i
                                                            class="bi bi-trash me-2"></i> Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-layout-text-window fs-1"></i></div>
                                    <h6 class="fw-medium">No Email Templates Found</h6>
                                    <p class="fs-7">Create customized email templates for your users.</p>
                                    <a href="{{ route('admin.email-templates.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Create Template</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($templates) && method_exists($templates, 'hasPages') && $templates->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $templates->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
