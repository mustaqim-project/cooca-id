@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Customer Testimonials</h2>
                <p class="text-secondary mb-0">Manage reviews, ratings, and client feedback displayed on landing pages.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.testimonials.create') }}"
                    class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Add Testimonial
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
                        <option value="">All Ratings</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars & Below</option>
                    </select>
                    <select class="form-select form-select-sm rounded-pill border shadow-none bg-light text-secondary"
                        style="width: auto;">
                        <option value="">Status</option>
                        <option value="active">Active / Featured</option>
                        <option value="hidden">Hidden</option>
                    </select>
                </div>

                <div class="input-group input-group-sm rounded-pill overflow-hidden border"
                    style="max-width: 320px; background: var(--color-bg);">
                    <span class="input-group-text bg-transparent border-0 pe-1"><i
                            class="bi bi-search text-secondary"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search client name, company...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--color-text-primary);">
                    <thead class="bg-light text-secondary text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-4 border-0">Client / Author</th>
                            <th class="py-3 px-3 border-0">Company & Role</th>
                            <th class="py-3 px-3 border-0">Rating & Quote</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($testimonials ?? [
                            (object)
    ['id' => 1, 'name' => 'Sarah Jenkins', 'role' => 'VP of Operations', 'company' => 'Apex Global', 'quote' => 'Cooca-ID streamlined our inventory and ERP workflows effortlessly.', 'rating' => 5, 'is_active' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Jenkins&background=6366f1&color=fff'],
                            (object)['id' => 2, 'name' => 'Michael Chang', 'role' => 'Lead Developer', 'company' => 'NexTech Solutions', 'quote' => 'The licensing validation and webhook delivery speed are blazing fast.', 'rating' => 5, 'is_active' => true, 'avatar' => 'https://ui-avatars.com/api/?name=Michael+Chang&background=10b981&color=fff'],
                            (object)['id' => 3, 'name' => 'David Ross', 'role' => 'Founder', 'company' => 'ScaleUp Inc', 'quote' => 'Great product, but hoping for more payment gateways in the next update.', 'rating' => 4, 'is_active' => false, 'avatar' => 'https://ui-avatars.com/api/?name=David+Ross&background=f59e0b&color=fff']
                        ] as $item)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($item->name ?? 'User') }}"
                                            class="rounded-circle border" width="40" height="40" alt="Avatar">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item->name }}</div>
                                            <div class="text-secondary fs-7">ID: #TST-0{{ $item->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="fw-medium text-dark">{{ $item->company ?? '-' }}</div>
                                    <div class="text-secondary fs-7">{{ $item->role ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-3" style="max-width: 320px;">
                                    <div class="text-warning mb-1 fs-7">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= ($item->rating ?? 5) ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="text-secondary fs-7 text-truncate" title="{{ $item->quote ?? '' }}">
                                        "{{ $item->quote ?? '' }}"</div>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($item->is_active ?? true)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Featured</span>
                                    @else
                                        <span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Hidden</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.testimonials.edit', $item->id ?? 1) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.testimonials.destroy', $item->id ?? 1) }}"
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
                                    <div class="mb-3"><i class="bi bi-chat-quote fs-1 text-secondary"></i></div>
                                    <h6 class="fw-medium">No Testimonials Found</h6>
                                    <p class="fs-7">Add client feedback to showcase social proof on your landing pages.
                                    </p>
                                    <a href="{{ route('admin.testimonials.create') }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3 mt-2">Add Testimonial</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($testimonials) && method_exists($testimonials, 'hasPages') && $testimonials->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $testimonials->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
