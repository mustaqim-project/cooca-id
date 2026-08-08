@extends('layouts.admin')

@section('title', 'Customer Directory — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Customers</span>
        </div>
        <h1 class="page-title">Customer Accounts</h1>
        <p class="page-subtitle">Manage client accounts, business profiles, linked subscriptions, and active licenses.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
            <span>👤</span> Create Customer Account
        </a>
    </div>
</div>

<div class="filter-bar">
    <div class="filter-search">
        <span class="filter-search-icon">🔍</span>
        <input type="text" placeholder="Search customer by name, email, company, or phone...">
    </div>
    <select class="form-select" style="width: 160px;">
        <option value="">All Account Statuses</option>
        <option value="active">Active</option>
        <option value="suspended">Suspended</option>
    </select>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer / Business</th>
                        <th>Contact</th>
                        <th>Registered Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $cust)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-md">
                                        {{ strtoupper(substr($cust->name ?? 'C', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-base">{{ $cust->name }}</div>
                                        <div class="text-xs text-primary font-semibold">{{ $cust->business_name ?? 'Individual Business' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-medium">{{ $cust->email }}</div>
                                <div class="text-xs text-muted">{{ $cust->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="text-xs text-muted">
                                {{ optional($cust->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.customers.show', $cust->id) }}" class="btn btn-ghost btn-sm" title="View Customer Details">👁️ View</a>
                                    <a href="{{ route('admin.customers.edit', $cust->id) }}" class="btn btn-ghost btn-sm" title="Edit Customer">✏️ Edit</a>
                                    <form action="{{ route('admin.customers.destroy', $cust->id) }}" method="POST" onsubmit="return confirm('Delete this customer account?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger" title="Delete">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding: 40px;">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($customers, 'hasPages') && $customers->hasPages())
        <div class="card-footer">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
