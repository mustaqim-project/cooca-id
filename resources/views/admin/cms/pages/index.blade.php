@extends('admin.layouts.app')

@section('title', 'Custom Pages CMS')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">CMS Pages</h2>
                <p class="text-secondary mb-0">Create and manage dynamic pages like Terms, Privacy, and About Us.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cms.pages.create') }}"
                    class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Create New Page
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
                        placeholder="Search pages by title or slug...">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light border rounded-circle p-2" title="Export CSV"><i
                            class="bi bi-download"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Title & Slug</th>
                            <th class="py-3 px-3 border-0">Template</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Last Updated</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($pages ?? [
                                (object)
    ['id' => 1, 'title' => 'Terms of Service', 'slug' => 'terms-of-service', 'template' => 'default', 'is_published' => true, 'updated_at' => now()->subDays(2)],
                                (object)['id' => 2, 'title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'template' => 'default', 'is_published' => true, 'updated_at' => now()->subDays(5)],
                                (object)['id' => 3, 'title' => 'About Us', 'slug' => 'about-us', 'template' => 'full-width', 'is_published' => false, 'updated_at' => now()->subHours(4)]
                            ] as $page)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold fs-6">{{ $page->title }}</div>
                                    <div class="text-secondary fs-7 font-monospace">/{{ $page->slug }}</div>
                                </td>
                                <td class="py-3 px-3 text-capitalize text-secondary">
                                    <span
                                        class="badge bg-light text-dark border px-3 py-1 rounded-pill">{{ $page->template ?? 'default' }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($page->is_published ?? true)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Published</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Draft</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ is_object($page->updated_at) ? $page->updated_at->format('d M Y, H:i') : 'Oct 15, 2026' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.cms.pages.edit', $page->id ?? 1) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit Page</a></li>
                                            <li><a class="dropdown-item py-2" href="#" target="_blank"><i
                                                        class="bi bi-box-arrow-up-right me-2 text-primary"></i> Preview
                                                    Live</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.cms.pages.destroy', $page->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this page?');"><i
                                                            class="bi bi-trash me-2"></i> Delete Page</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-secondary">
                                    <div class="mb-3"><i class="bi bi-file-earmark-text fs-1"></i></div>
                                    <h6 class="fw-medium">No Pages Created</h6>
                                    <p class="fs-7">Get started by creating your first custom CMS page.</p>
                                    <a href="{{ route('admin.cms.pages.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Create Page</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($pages) && method_exists($pages, 'hasPages') && $pages->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
