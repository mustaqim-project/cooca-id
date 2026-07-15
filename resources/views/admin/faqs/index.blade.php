@extends('layouts.admin')

@section('title', 'FAQs')
@section('subtitle', 'Manage frequently asked questions')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search question, answer...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.faqs.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> Add FAQ
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="faqsTable">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                            <tr>
                                <td class="text-muted" style="font-size:0.8rem">{{ $faq->id }}</td>
                                <td style="max-width:260px">
                                    <div class="fw-medium" style="font-size:0.9rem">{{ Str::limit($faq->question, 70) }}
                                    </div>
                                </td>
                                <td style="max-width:240px">
                                    <div class="text-muted text-truncate" style="font-size:0.85rem">
                                        {{ Str::limit($faq->answer, 70) }}</div>
                                </td>
                                <td>
                                    @if ($faq->is_active)
                                        <span class="badge-saas badge-saas-success">Active</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:0.85rem">{{ $faq->sort_order ?? 0 }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form class="form-confirm-delete"
                                            action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm"
                                                style="color:var(--danger)" title="Delete">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-question-circle"></i></div>
                                        <div class="empty-state-title">No FAQs yet</div>
                                        <div class="empty-state-description">Add frequently asked questions to help your
                                            customers.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#faqsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
