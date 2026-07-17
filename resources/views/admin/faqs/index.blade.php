@extends('admin.layouts.app')

@section('title', 'FAQs')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Frequently Asked Questions</h2>
                <p class="text-secondary mb-0">Manage knowledge base and common support inquiries.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Add Question
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm rounded-4 glass">
            <div
                class="card-header bg-transparent border-bottom border-light p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex flex-wrap gap-2">
                    <select class="form-select form-select-sm rounded-pill border shadow-none bg-light text-secondary"
                        style="width: auto;">
                        <option value="">All Categories</option>
                        <option value="general">General</option>
                        <option value="billing">Billing</option>
                        <option value="technical">Technical</option>
                    </select>
                    <select class="form-select form-select-sm rounded-pill border shadow-none bg-light text-secondary"
                        style="width: auto;">
                        <option value="">Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search questions...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0" style="width: 50px;"></th>
                            <th class="py-3 px-3 border-0">Question & Category</th>
                            <th class="py-3 px-3 border-0">Visibility</th>
                            <th class="py-3 px-3 border-0">Last Updated</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($faqs ?? [
                            (object)
    ['id' => 1, 'question' => 'How do I upgrade my license tier?', 'category' => 'Billing', 'is_published' => true, 'updated_at' => now()->subDays(2)],
                            (object)['id' => 2, 'question' => 'Can I cancel my subscription anytime?', 'category' => 'General', 'is_published' => true, 'updated_at' => now()->subWeeks(1)],
                            (object)['id' => 3, 'question' => 'How to setup SSO integration?', 'category' => 'Technical', 'is_published' => false, 'updated_at' => now()->subHours(5)],
                        ] as $faq)
                            <tr>
                                <td class="py-3 px-4 text-center cursor-move text-secondary" title="Drag to reorder">
                                    <i class="bi bi-grip-vertical"></i>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium text-dark">{{ $faq->question }}</div>
                                    <div class="text-secondary fs-7"><i class="bi bi-tag me-1"></i> {{ $faq->category }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($faq->is_published)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Published</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Draft</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">
                                    {{ is_object($faq->updated_at ?? null) ? $faq->updated_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.faqs.edit', $faq->id ?? 1) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.faqs.destroy', $faq->id ?? 1) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"><i
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
                                    <div class="mb-3"><i class="bi bi-question-circle fs-1 text-secondary"></i></div>
                                    <h6 class="fw-medium">No FAQs Found</h6>
                                    <p class="fs-7">Create your first question to populate the knowledge base.</p>
                                    <a href="{{ route('admin.faqs.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3 mt-2">Add Question</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($faqs) && method_exists($faqs, 'hasPages') && $faqs->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $faqs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
