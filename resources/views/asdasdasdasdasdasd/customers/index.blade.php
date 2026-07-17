@extends('layouts.admin')

@section('title', 'Customers')
@section('subtitle', 'Manage registered customers.')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
        <div class="input-group shadow-sm" style="max-width: 320px;">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" id="searchInput" placeholder="Search customers...">
        </div>
        <div>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-medium">
                <i class="bi bi-plus-lg me-1"></i> Add Customer
            </a>
        </div>
    </div>

    <div class="card card-saas border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="customersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Customer</th>
                            <th>Business</th>
                            <th>Phone</th>
                            <th>Joined</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                            style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $customer->id) }}"
                                                class="fw-semibold text-dark text-decoration-none d-block">{{ $customer->name }}</a>
                                            <span class="text-muted fs-sm">{{ $customer->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($customer->business_name)
                                        <span class="fw-medium text-dark">{{ $customer->business_name }}</span>
                                    @else
                                        <span class="text-muted fst-italic">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($customer->phone)
                                        <a href="tel:{{ $customer->phone }}"
                                            class="text-decoration-none text-muted hover-text-primary">
                                            <i class="bi bi-telephone-fill me-1 opacity-50"></i>{{ $customer->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i
                                            class="bi bi-calendar3 me-1 opacity-50"></i>{{ $customer->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}"
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="View">
                                            <i class="bi bi-eye text-muted"></i>
                                        </a>
                                        <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                            class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Edit">
                                            <i class="bi bi-pencil text-primary"></i>
                                        </a>
                                        <form class="form-confirm-delete m-0"
                                            action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;" title="Delete"><i
                                                    class="bi bi-trash3 text-danger"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-people fs-1 text-muted opacity-50 d-block mb-3"></i>
                                        <h6 class="fw-semibold text-dark">No customers yet</h6>
                                        <p class="text-muted fs-sm mb-3">Customers will appear here after registration.</p>
                                        <a href="{{ route('admin.customers.create') }}"
                                            class="btn btn-primary btn-sm rounded-pill px-4">
                                            <i class="bi bi-plus-lg me-1"></i> Add Customer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (isset($customers) && method_exists($customers, 'links') && $customers->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
@endsection
