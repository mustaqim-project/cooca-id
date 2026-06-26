@extends('layouts.admin')

@section('title', 'Customer Reviews')
@section('subtitle', 'Moderate and manage product feedback')

@section('content')
<div class="space-y-6">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-surface-400"></i>
            </div>
            <input type="text" placeholder="Search..." class="block w-full pl-10 pr-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800 text-surface-900 dark:text-white placeholder-surface-400 shadow-sm transition-shadow hover:shadow-md">
        </div>
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            
        </div>
    </div>

    <!-- Data Table -->
    <div class="corporate-card">
        <div class="overflow-x-auto">
            <table class="corporate-table">
                <thead class="table-thead">
                    
                    
                    
                <tr>
                    <th scope="col" class="table-th">Reviewer / Product</th>
                    <th scope="col" class="table-th">Rating</th>
                    <th scope="col" class="table-th">Comment</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($reviews as $review)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 {{ $review->status == 'pending' ? 'bg-yellow-50' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-surface-200 rounded-full flex items-center justify-center text-surface-600 dark:text-surface-400 font-bold">
                                {{ strtoupper(substr($review->customer->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('admin.customers.show', $review->customer_id ?? 0) }}" class="text-sm font-medium text-primary-600 hover:underline">
                                    {{ $review->customer->name ?? 'Unknown Customer' }}
                                </a>
                                <div class="text-xs text-surface-500 dark:text-surface-400 mt-1">
                                    on <a href="{{ route('admin.products.show', $review->product_id ?? 0) }}" class="text-surface-900 dark:text-white hover:underline">{{ Str::limit($review->product->name ?? 'Unknown', 25) }}</a>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i data-lucide="star" class="w-4 h-4"></i>
                                @else
                                    <i data-lucide="star" class="w-4 h-4"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-xs text-surface-500 dark:text-surface-400 mt-1">{{ $review->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-surface-900 dark:text-white mb-1">{{ $review->title ?? 'No Title' }}</div>
                        <div class="text-sm text-surface-600 dark:text-surface-400 line-clamp-2" title="{{ $review->comment }}">
                            {{ $review->comment }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = match($review->status) {
                                'approved' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-surface-100 text-surface-800'
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="text-primary-600 hover:text-primary-900" title="View & Moderate">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            
                            @if($review->status == 'pending')
                            <form class="form-confirm-submit" action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline form-confirm-delete">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                            
                            <form class="form-confirm-delete" action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline form-confirm-delete" >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <i data-lucide="star" class="w-4 h-4"></i>
                        <p>No reviews found.</p>
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
