@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Customers</h2>
                <p class="text-secondary mb-0">Manage and view customers data.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.customers.create') }}"
                    class="btn btn-primary rounded-pill px-3 hover-lift shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Create Customer
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
                        placeholder="Search customers...">
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
                            <th class="py-3 px-4 border-0">ID</th>
                            <th class="py-3 px-3 border-0">Name / Title</th>
                            <th class="py-3 px-3 border-0">Status</th>
                            <th class="py-3 px-3 border-0">Date</th>
                            <th class="py-3 px-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($customers as $customer)
                            <tr>
                                <td class="py-3 px-4 text-secondary fs-7">#{{ $customer->id }}</td>
                                <td class="py-3 px-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 32px; height: 32px;">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $customer->name }}</div>
                                            <div class="text-secondary fs-7">{{ $customer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @if ($customer->email_verified_at)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Verified</span>
                                    @else
                                        <span
                                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">Unverified</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-secondary fs-7">{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle p-2" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 glass">
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.customers.show', $customer->id) }}"><i
                                                        class="bi bi-eye me-2 text-primary"></i> View Details</a></li>
                                            <li><a class="dropdown-item py-2"
                                                    href="{{ route('admin.customers.edit', $customer->id) }}"><i
                                                        class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.customers.destroy', $customer->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this customer?');"><i
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
                                    <div class="mb-3"><i class="bi bi-people fs-1"></i></div>
                                    <h6 class="fw-medium">No Customers Found</h6>
                                    <p class="fs-7">Create your first customer.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($customers) && $customers->hasPages())
                <div class="card-footer bg-transparent border-top border-light p-4">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
