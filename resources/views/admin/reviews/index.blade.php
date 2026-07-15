@extends('layouts.admin')

@section('title', 'Customer Reviews')
@section('subtitle', 'Moderate and manage product feedback')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search reviewer, product...">
            </div>
        </div>
        <div class="page-toolbar-right">
            {{-- no create; reviews come from customers --}}
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="reviewsTable">
                    <thead>
                        <tr>
                            <th>Reviewer / Product</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-card-icon blue"
                                            style="width:36px;height:36px;font-size:0.85rem;flex-shrink:0">
                                            {{ strtoupper(substr($review->customer->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $review->customer_id ?? 0) }}"
                                                class="fw-medium text-decoration-none"
                                                style="color:var(--primary);font-size:0.9rem">
                                                {{ $review->customer->name ?? 'Unknown' }}
                                            </a>
                                            <div class="text-muted" style="font-size:0.8rem">
                                                on <a href="{{ route('admin.products.show', $review->product_id ?? 0) }}"
                                                    class="text-decoration-none text-dark">
                                                    {{ Str::limit($review->product->name ?? 'Unknown', 25) }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1" style="color:#f59e0b">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"
                                                style="font-size:0.85rem"></i>
                                        @endfor
                                    </div>
                                    <div class="text-muted" style="font-size:0.78rem">{{ $review->rating }}/5</div>
                                </td>
                                <td style="max-width:240px">
                                    <div class="fw-medium" style="font-size:0.85rem">{{ $review->title ?? 'No Title' }}
                                    </div>
                                    <div class="text-muted text-truncate" style="font-size:0.8rem"
                                        title="{{ $review->comment }}">{{ $review->comment }}</div>
                                </td>
                                <td>
                                    @php
                                        $badge = match ($review->status) {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'neutral',
                                        };
                                    @endphp
                                    <span
                                        class="badge-saas badge-saas-{{ $badge }}">{{ ucfirst($review->status) }}</span>
                                </td>
                                <td class="text-muted" style="font-size:0.85rem">{{ $review->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.reviews.show', $review->id) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($review->status === 'pending')
                                            <form class="form-confirm-submit"
                                                action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm"
                                                    style="color:var(--success)" title="Approve">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form class="form-confirm-delete"
                                            action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST">
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
                                        <div class="empty-state-icon"><i class="bi bi-star"></i></div>
                                        <div class="empty-state-title">No reviews found</div>
                                        <div class="empty-state-description">Customer product reviews will appear here.
                                        </div>
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
            document.querySelectorAll('#reviewsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
