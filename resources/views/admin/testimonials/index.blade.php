@extends('layouts.admin')

@section('title', 'Testimonials')
@section('subtitle', 'Manage customer testimonials')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search name, role...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.testimonials.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Testimonial
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="testimonialsTable">
                    <thead>
                        <tr>
                            <th>Person</th>
                            <th>Content</th>
                            <th>Rating</th>
                            <th>Featured</th>
                            <th>Added</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $testimonial->name }}</div>
                                    <div class="text-muted" style="font-size:0.8rem">{{ $testimonial->role ?? '-' }}</div>
                                    @if ($testimonial->company)
                                        <div class="text-muted" style="font-size:0.78rem">{{ $testimonial->company }}</div>
                                    @endif
                                </td>
                                <td style="max-width:280px">
                                    <div class="text-truncate" style="font-size:0.85rem"
                                        title="{{ $testimonial->content ?? ($testimonial->description ?? '') }}">
                                        {{ Str::limit($testimonial->content ?? ($testimonial->description ?? '-'), 80) }}
                                    </div>
                                </td>
                                <td>
                                    @if (isset($testimonial->rating))
                                        <div class="d-flex gap-1" style="color:#f59e0b;font-size:0.8rem">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($testimonial->is_featured)
                                        <span class="badge-saas badge-saas-success">Featured</span>
                                    @else
                                        <span class="badge-saas badge-saas-neutral">Standard</span>
                                    @endif
                                </td>
                                <td class="text-muted" style="font-size:0.85rem">
                                    {{ $testimonial->created_at?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form class="form-confirm-delete"
                                            action="{{ route('admin.testimonials.destroy', $testimonial->id) }}"
                                            method="POST">
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
                                        <div class="empty-state-icon"><i class="bi bi-chat-quote"></i></div>
                                        <div class="empty-state-title">No testimonials yet</div>
                                        <div class="empty-state-description">Add customer testimonials to display on the
                                            website.</div>
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
            document.querySelectorAll('#testimonialsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
