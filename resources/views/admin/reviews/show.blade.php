@extends('layouts.admin')

@section('title', 'Review Details')
@section('subtitle', 'Moderate customer review')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Reviews
    </a>
    
    <div class="flex gap-2">
        @if($review->status == 'pending' || $review->status == 'rejected')
            <form class="form-confirm-submit" action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                    <i data-lucide="check" class="w-4 h-4"></i> Approve Review
                </button>
            </form>
        @endif
        
        @if($review->status == 'pending' || $review->status == 'approved')
            <button type="button" onclick="rejectReview()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none">
                <i data-lucide="x" class="w-4 h-4"></i> Reject Review
            </button>
        @endif
        
        <form class="form-confirm-delete" action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline" >
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- The Review -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900 flex justify-between items-center">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Review Content</h3>
                @php
                    $statusClass = match($review->status) {
                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                        default => 'bg-surface-100 text-surface-800 border-surface-200'
                    };
                @endphp
                <span class="px-3 py-1 inline-flex text-xs font-bold uppercase tracking-wider rounded-full border {{ $statusClass }}">
                    {{ $review->status }}
                </span>
            </div>
            
            <div class="p-6">
                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400 text-2xl">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i data-lucide="star" class="w-4 h-4"></i>
                            @else
                                <i data-lucide="star" class="w-4 h-4"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="ml-3 text-lg font-medium text-surface-900 dark:text-white">{{ $review->rating }} out of 5</span>
                </div>
                
                <!-- Title -->
                <h4 class="text-xl font-bold text-surface-900 dark:text-white mb-2">{{ $review->title ?? 'No Title' }}</h4>
                
                <!-- Content -->
                <div class="prose prose-sm max-w-none text-surface-700 dark:text-surface-300 bg-surface-50 dark:bg-surface-900 p-4 rounded-md border border-surface-100">
                    {!! nl2br(e($review->comment)) !!}
                </div>
                
                <!-- Images if any -->
                @if($review->images && count($review->images) > 0)
                <div class="mt-6">
                    <h5 class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-3">Attached Images</h5>
                    <div class="flex flex-wrap gap-4">
                        @foreach($review->images as $image)
                            <div class="relative h-24 w-24 rounded-lg overflow-hidden border border-surface-200 dark:border-surface-700">
                                <img src="{{ $image }}" alt="Review image" class="object-cover w-full h-full">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($review->status == 'rejected' && $review->rejection_reason)
                <div class="mt-6 bg-red-50 p-4 rounded-md border border-red-100">
                    <h5 class="text-sm font-bold text-red-800 mb-1"><i data-lucide="alert-triangle" class="w-4 h-4"></i> Rejection Reason</h5>
                    <p class="text-sm text-red-700">{{ $review->rejection_reason }}</p>
                    <p class="text-xs text-red-500 mt-2">Rejected at: {{ $review->rejected_at ? $review->rejected_at->format('M d, Y H:i') : 'Unknown' }}</p>
                </div>
                @endif
            </div>
        </div>
        
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Author Info -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Author</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl">
                        {{ strtoupper(substr($review->customer->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-bold text-surface-900 dark:text-white">{{ $review->customer->name ?? 'Unknown Customer' }}</h4>
                        <p class="text-sm text-surface-500 dark:text-surface-400">{{ $review->customer->email ?? '' }}</p>
                    </div>
                </div>
                
                @if($review->customer)
                <div class="mt-4 pt-4 border-t border-surface-100">
                    <a href="{{ route('admin.customers.show', $review->customer->id) }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium flex items-center">
                        View Customer Profile <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Reviewed Product</h3>
            </div>
            <div class="p-6">
                @if($review->product)
                    <h4 class="text-md font-medium text-surface-900 dark:text-white">{{ $review->product->name }}</h4>
                    <p class="text-sm text-surface-500 dark:text-surface-400 mt-1 line-clamp-2">{{ Str::limit($review->product->description, 100) }}</p>
                    
                    <div class="mt-4 pt-4 border-t border-surface-100">
                        <a href="{{ route('admin.products.show', $review->product->id) }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium flex items-center">
                            View Product Details <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                @else
                    <p class="text-sm text-surface-500 dark:text-surface-400 italic">Product information unavailable or product has been deleted.</p>
                @endif
            </div>
        </div>
        
        <!-- Metadata -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Metadata</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-surface-500 dark:text-surface-400">Submitted</dt>
                        <dd class="text-surface-900 dark:text-white font-medium">{{ $review->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @if($review->approved_at)
                    <div class="flex justify-between border-t border-surface-100 pt-3">
                        <dt class="text-surface-500 dark:text-surface-400">Approved On</dt>
                        <dd class="text-surface-900 dark:text-white font-medium">{{ $review->approved_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-surface-100 pt-3">
                        <dt class="text-surface-500 dark:text-surface-400">Verified Purchase</dt>
                        <dd class="font-medium {{ $review->is_verified_purchase ? 'text-green-600' : 'text-surface-500 dark:text-surface-400' }}">
                            {{ $review->is_verified_purchase ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
    </div>
</div>

<!-- Rejection Form -->
<form class="form-confirm-submit" id="reject-form" action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="rejection_reason" id="rejection_reason">
</form>

@endsection

@push('scripts')
<script>
    function rejectReview() {
        Swal.fire({
            title: 'Reject Review',
            text: "Please provide a reason for rejecting this review.",
            icon: 'warning',
            input: 'textarea',
            inputLabel: 'Rejection Reason (Internal use)',
            inputPlaceholder: 'Contains inappropriate language, spam, etc.',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write a reason!'
                }
            },
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('rejection_reason').value = result.value;
                document.getElementById('reject-form').submit();
            }
        })
    }
</script>
@endpush
