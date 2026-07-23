@extends('customer.layouts.app')

@section('title', $product->name)

@section('content')
    <div class="d-flex flex-column gap-4">
        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <a href="{{ route('customer.products.index') }}" class="text-secondary text-decoration-none mb-2 d-inline-block hover-lift">
                    <i class="bi bi-arrow-left me-1"></i> Back to Products
                </a>
                <h2 class="mb-1 fw-bold">{{ $product->name }}</h2>
                <p class="text-secondary mb-0">Product Details & Subscription Plans</p>
            </div>
            <div class="d-flex gap-2">
            </div>
        </div>

        <!-- Details Card -->
        <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
            <div class="row g-0">
                <div class="col-md-4 bg-light border-end">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover" style="min-height: 250px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center w-100 h-100 text-secondary bg-primary-subtle" style="min-height: 250px;">
                            <i class="bi bi-box fs-1" style="font-size: 5rem !important;"></i>
                        </div>
                    @endif
                </div>
                
                <div class="col-md-8">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h3 class="fw-bold mb-1">{{ $product->name }}</h3>
                                <p class="text-secondary mb-0 fs-7">SKU: {{ $product->sku ?? 'N/A' }}</p>
                            </div>
                            @if($product->category)
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="text-secondary mb-5">
                            {{ $product->description ?? 'No detailed description provided.' }}
                        </div>
                        
                        <div class="border-top pt-4">
                            <h5 class="fw-semibold mb-4">Available Subscription Plans</h5>
                            
                            @if($plans->count() > 0)
                                <div class="row g-4">
                                    @foreach($plans as $plan)
                                        <div class="col-12 col-xl-6">
                                            <div class="card border {{ $plan->is_popular ? 'border-primary shadow-sm' : 'border-light' }} rounded-4 h-100 position-relative hover-lift">
                                                @if($plan->is_popular)
                                                    <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-primary shadow-sm" style="margin-right: -10px; margin-top: 10px;">
                                                        Popular
                                                    </span>
                                                @endif
                                                <div class="card-body p-4 d-flex flex-column">
                                                    <h5 class="fw-bold mb-2">{{ $plan->name }}</h5>
                                                    <div class="d-flex align-items-baseline mb-3">
                                                        <h3 class="fw-bolder text-primary mb-0">Rp {{ number_format($plan->price, 0, ',', '.') }}</h3>
                                                        <span class="text-secondary ms-1 fs-7">/ {{ $plan->billing_cycle ?? 'month' }}</span>
                                                    </div>
                                                    
                                                    @if($plan->description)
                                                        <p class="text-secondary fs-7 flex-grow-1">
                                                            {{ $plan->description }}
                                                        </p>
                                                    @else
                                                        <div class="flex-grow-1"></div>
                                                    @endif
                                                    
                                                    <form action="{{ route('customer.subscriptions.create') }}" method="GET" class="mt-4">
                                                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                        <button type="submit" class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-light' }} w-100 rounded-pill fw-medium">
                                                            Subscribe Now
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis rounded-4 d-flex align-items-center mb-0" role="alert">
                                    <i class="bi bi-exclamation-triangle fs-4 me-3"></i>
                                    <div>
                                        There are currently no active subscription plans available for this product. Check back later.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
