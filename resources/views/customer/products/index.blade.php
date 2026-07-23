@extends('customer.layouts.app')

@section('title', 'Available Products')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Page Header & Toolbar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Available Products</h2>
                <p class="text-secondary mb-0">Browse our catalog of digital products and services.</p>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm rounded-pill overflow-hidden border bg-white"
                    style="max-width: 320px;">
                    <span class="input-group-text bg-transparent border-0 pe-1">
                        <i class="bi bi-search text-secondary"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none text-secondary"
                        placeholder="Search products...">
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift overflow-hidden glass d-flex flex-column">
                        <!-- Thumbnail -->
                        <div class="position-relative bg-light" style="height: 180px;">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" 
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="d-flex align-items-center justify-content-center w-100 h-100 text-secondary bg-primary-subtle">
                                    <i class="bi bi-box fs-1"></i>
                                </div>
                            @endif
                            
                            @if($product->category)
                                <span class="position-absolute top-0 end-0 m-3 badge bg-white text-primary shadow-sm rounded-pill px-3 py-2">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <h5 class="fw-bold mb-2 text-truncate">{{ $product->name }}</h5>
                            <p class="text-secondary mb-4 fs-7 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $product->description ?? 'No description available for this product.' }}
                            </p>
                            
                            <div class="d-flex align-items-end justify-content-between mt-auto">
                                <div>
                                    <div class="text-secondary fs-7 mb-1">Starting from</div>
                                    <h5 class="fw-bold text-primary mb-0">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="card-footer bg-transparent border-top border-light p-3">
                            <a href="{{ route('customer.products.show', $product->slug) }}" 
                                class="btn btn-light w-100 rounded-pill hover-lift text-primary fw-medium">
                                View Details <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 glass p-5 text-center">
                        <div class="text-secondary mb-3">
                            <i class="bi bi-inbox fs-1"></i>
                        </div>
                        <h5 class="fw-semibold">No Products Found</h5>
                        <p class="text-secondary mb-0">There are currently no products available in the catalog.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection