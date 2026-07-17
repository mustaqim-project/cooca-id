@extends('admin.layouts.app')

@section('title', 'Customers Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.customers.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Customer Details</h2>
                    <p class="text-secondary mb-0">View profile, subscriptions, and licenses.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.customers.edit', $customer->id) }}"
                    class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary">
                    <i class="bi bi-pencil me-2"></i> Edit
                </a>
                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm"
                        onclick="return confirm('Are you sure you want to delete this customer?');">
                        <i class="bi bi-trash me-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center h-100">
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow-sm fw-bold fs-1"
                        style="width: 80px; height: 80px;">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $customer->name }}</h4>
                    <p class="text-secondary mb-3">{{ $customer->email }}</p>
                    <div>
                        @if ($customer->email_verified_at)
                            <span
                                class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Verified</span>
                        @else
                            <span
                                class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 border border-warning-subtle">Unverified</span>
                        @endif
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Business Name</span>
                        <span class="fw-medium fs-7">{{ $customer->business_name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Phone Number</span>
                        <span class="fw-medium fs-7">{{ $customer->phone ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Registered At</span>
                        <span class="fw-medium fs-7">{{ $customer->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Last Updated</span>
                        <span class="fw-medium fs-7">{{ $customer->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Details Tabs -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100 d-flex flex-column">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <ul class="nav nav-pills card-header-pills gap-2" id="customerTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill active px-4" id="subscriptions-tab"
                                    data-bs-toggle="tab" data-bs-target="#subscriptions" type="button"
                                    role="tab">Subscriptions</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4" id="licenses-tab" data-bs-toggle="tab"
                                    data-bs-target="#licenses" type="button" role="tab">Licenses</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4 flex-grow-1">
                        <div class="tab-content" id="customerTabsContent">
                            <!-- Subscriptions Tab -->
                            <div class="tab-pane fade show active" id="subscriptions" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-secondary fs-7 text-uppercase">
                                            <tr>
                                                <th class="border-0 py-3 px-3">Product</th>
                                                <th class="border-0 py-3 px-3">Plan</th>
                                                <th class="border-0 py-3 px-3">Status</th>
                                                <th class="border-0 py-3 px-3">Expires At</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top-0">
                                            @forelse($customer->subscriptions as $sub)
                                                <tr>
                                                    <td class="py-3 px-3 fw-medium">{{ $sub->product->name ?? '-' }}</td>
                                                    <td class="py-3 px-3">{{ $sub->subscriptionPlan->name ?? '-' }}</td>
                                                    <td class="py-3 px-3">
                                                        <span
                                                            class="badge bg-{{ $sub->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $sub->status === 'active' ? 'success' : 'secondary' }} rounded-pill px-3 py-1">{{ ucfirst($sub->status) }}</span>
                                                    </td>
                                                    <td class="py-3 px-3 text-secondary fs-7">
                                                        {{ $sub->ends_at ? \Carbon\Carbon::parse($sub->ends_at)->format('d M Y') : 'Lifetime' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-secondary">No
                                                        subscriptions found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Licenses Tab -->
                            <div class="tab-pane fade" id="licenses" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-secondary fs-7 text-uppercase">
                                            <tr>
                                                <th class="border-0 py-3 px-3">Product</th>
                                                <th class="border-0 py-3 px-3">License Key</th>
                                                <th class="border-0 py-3 px-3">Status</th>
                                                <th class="border-0 py-3 px-3">Created</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top-0">
                                            @forelse($customer->licenses as $license)
                                                <tr>
                                                    <td class="py-3 px-3 fw-medium">{{ $license->product->name ?? '-' }}
                                                    </td>
                                                    <td class="py-3 px-3"><code
                                                            class="text-primary">{{ $license->license_key }}</code></td>
                                                    <td class="py-3 px-3">
                                                        <span
                                                            class="badge bg-{{ $license->is_active ? 'success' : 'danger' }}-subtle text-{{ $license->is_active ? 'success' : 'danger' }} rounded-pill px-3 py-1">{{ $license->is_active ? 'Active' : 'Revoked' }}</span>
                                                    </td>
                                                    <td class="py-3 px-3 text-secondary fs-7">
                                                        {{ $license->created_at->format('d M Y') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-secondary">No licenses
                                                        found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
