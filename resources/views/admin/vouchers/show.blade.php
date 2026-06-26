@extends('layouts.admin')

@section('title', 'Voucher Details')
@section('subtitle', 'View complete details and usage statistics for voucher')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <!-- Details Card -->
    <div class="corporate-card">
        <div class="card-header">
            <h3 class="card-title">Information Details</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Vouchers
    </a>
    
    <div class="flex gap-2">
        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="inline-flex items-center px-4 py-2 border border-surface-300 dark:border-surface-600 rounded-md shadow-sm text-sm font-medium text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 focus:outline-none">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
        </a>
        
        @if($voucher->is_active)
            <form class="form-confirm-submit" action="{{ route('admin.vouchers.deactivate', $voucher->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none">
                    <i data-lucide="pause-circle" class="w-4 h-4 mr-2"></i> Deactivate
                </button>
            </form>
        @else
            <form class="form-confirm-submit" action="{{ route('admin.vouchers.activate', $voucher->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                    <i data-lucide="play-circle" class="w-4 h-4 mr-2"></i> Activate
                </button>
            </form>
        @endif
        
        <form class="form-confirm-delete" action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="inline" >
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded bg-primary-100 flex items-center justify-center text-primary-600 mr-4">
                        <i data-lucide="ticket" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-surface-900 dark:text-white font-mono tracking-wider">{{ $voucher->code }}</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400">{{ $voucher->name }}</p>
                    </div>
                </div>
                <div>
                    @php
                        $isExpired = $voucher->valid_until && \Carbon\Carbon::parse($voucher->valid_until)->isPast();
                        $isMaxed = $voucher->max_usage > 0 && $voucher->used_count >= $voucher->max_usage;
                        
                        if (!$voucher->is_active) {
                            $statusClass = 'bg-surface-100 text-surface-800 border border-surface-200';
                            $statusText = 'Inactive';
                        } elseif ($isExpired) {
                            $statusClass = 'bg-red-50 text-red-700 border border-red-200';
                            $statusText = 'Expired';
                        } elseif ($isMaxed) {
                            $statusClass = 'bg-orange-50 text-orange-700 border border-orange-200';
                            $statusText = 'Fully Used';
                        } else {
                            $statusClass = 'bg-green-50 text-green-700 border border-green-200';
                            $statusText = 'Active';
                        }
                    @endphp
                    <span class="px-4 py-1.5 inline-flex text-sm font-bold rounded-full {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>
            
            <div class="px-6 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-surface-200 dark:divide-surface-700">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Discount Value</dt>
                        <dd class="mt-1 text-sm font-bold text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            @if($voucher->type == 'percentage')
                                <span class="text-primary-600 text-lg">{{ $voucher->value }}% OFF</span>
                                @if($voucher->max_discount > 0)
                                    <span class="text-surface-500 dark:text-surface-400 font-normal ml-2">(Up to Rp {{ number_format($voucher->max_discount, 0, ',', '.') }})</span>
                                @endif
                            @else
                                <span class="text-primary-600 text-lg">Rp {{ number_format($voucher->value, 0, ',', '.') }} OFF</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-surface-50 dark:bg-surface-900">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Description</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $voucher->description ?: 'No description provided.' }}
                        </dd>
                    </div>
                    
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-surface-500 dark:text-surface-400">Requirements</dt>
                        <dd class="mt-1 text-sm text-surface-900 dark:text-white sm:mt-0 sm:col-span-2">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Minimum purchase: <strong>{{ $voucher->min_purchase > 0 ? 'Rp ' . number_format($voucher->min_purchase, 0, ',', '.') : 'None' }}</strong></li>
                                <li>Limit per customer: <strong>{{ $voucher->per_user_limit }} time(s)</strong></li>
                                @if(!empty($voucher->applicable_products))
                                    <li>Applicable to specific products only</li>
                                @else
                                    <li>Applicable to all products</li>
                                @endif
                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
    </div>
    
    <!-- Right Column: Summary & Actions -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Usage Stats -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Usage Statistics</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-surface-500 dark:text-surface-400">Total Uses</span>
                    <span class="text-sm font-bold text-surface-900 dark:text-white">{{ $voucher->used_count }} / {{ $voucher->max_usage > 0 ? $voucher->max_usage : 'Unlimited' }}</span>
                </div>
                
                @if($voucher->max_usage > 0)
                    <div class="w-full bg-surface-200 rounded-full h-2.5 mb-4">
                        @php
                            $percentage = min(100, ($voucher->used_count / $voucher->max_usage) * 100);
                            $colorClass = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 75 ? 'bg-yellow-500' : 'bg-primary-600');
                        @endphp
                        <div class="{{ $colorClass }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-xs text-surface-500 dark:text-surface-400 text-center">
                        {{ $voucher->max_usage - $voucher->used_count }} uses remaining
                    </p>
                @endif
            </div>
        </div>
        
        <!-- Validity Period -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-base font-medium text-surface-900 dark:text-white">Validity Period</h3>
            </div>
            <ul class="divide-y divide-surface-200 dark:divide-surface-700">
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-surface-500 dark:text-surface-400">Valid From</span>
                        <span class="text-sm font-medium {{ $voucher->valid_from ? 'text-surface-900 dark:text-white' : 'text-surface-400 italic' }}">
                            {{ $voucher->valid_from ? \Carbon\Carbon::parse($voucher->valid_from)->format('M d, Y H:i') : 'Immediately' }}
                        </span>
                    </div>
                </li>
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-surface-500 dark:text-surface-400">Valid Until</span>
                        <span class="text-sm font-medium {{ $voucher->valid_until ? (\Carbon\Carbon::parse($voucher->valid_until)->isPast() 'text-red-600' : 'text-surface-900 dark:text-white') 'text-surface-400 italic' }}">
                            {{ $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->format('M d, Y H:i') : 'Never expires' }}
                        </span>
                    </div>
                </li>
            </ul>
        </div>
        
    </div>
        </div>
    </div>
</div>
@endsection
