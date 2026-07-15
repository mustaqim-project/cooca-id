@extends('layouts.admin')

@section('title', 'Customers')
@section('subtitle', 'Manage registered customers.')

@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Search customers...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.customers.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Customer
            </a>
        </div>
    </div>

    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Business</th>
                            <th>Phone</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $customer->name }}</div>
                                    <div class="text-muted small">{{ $customer->email }}</div>
                                </td>
                                <td>{{ $customer->business_name ?? '-' }}</td>
                                <td>{{ $customer->phone ?? '-' }}</td>
                                <td>{{ $customer->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $customer->id) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="View"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                        class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="Edit"><i
                                            class="bi bi-pencil"></i></a>
                                    <form class="form-confirm-delete d-inline"
                                        action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon text-danger"
                                            title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                                        <div class="empty-state-title">No customers yet</div>
                                        <div class="empty-state-description">Customers will appear here after registration.
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
