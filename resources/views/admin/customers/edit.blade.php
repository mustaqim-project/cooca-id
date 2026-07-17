@extends('admin.layouts.app')

@section('title', 'Edit Customers')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.customers.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Edit Customer</h2>
                <p class="text-secondary mb-0">Update information for this customer.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST"
                class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $customer->name) }}"
                                placeholder="Full Name" required>
                            <label for="name">Full Name *</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="email"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $customer->email) }}"
                                placeholder="Email Address" required>
                            <label for="email">Email Address *</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="password"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password">
                            <label for="password">Password (Leave blank to keep current)</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                placeholder="Phone Number">
                            <label for="phone">Phone Number</label>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text"
                                class="form-control rounded-3 shadow-none border bg-transparent @error('business_name') is-invalid @enderror"
                                id="business_name" name="business_name"
                                value="{{ old('business_name', $customer->business_name) }}" placeholder="Business Name">
                            <label for="business_name">Business Name</label>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.customers.index') }}"
                        class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
