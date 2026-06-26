@extends('layouts.admin')

@section('title', 'Edit Voucher')
@section('subtitle', 'Update promotional code details')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form class="form-confirm-submit" action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST"  class="form-confirm-submit">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Voucher Code <span class="text-red-500">*</span></label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="text" name="code" id="code" value="{{ old('code', $voucher->code) }}" required style="text-transform: uppercase;"
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-surface-100 dark:bg-surface-800 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 font-mono sm:text-sm" readonly>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="text-surface-400 text-xs"><i data-lucide="lock" class="w-4 h-4"></i> Fixed</span>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Voucher code cannot be changed once created.</p>
                </div>
                
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Internal Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $voucher->name) }}" required 
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="col-span-1 md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Description (Optional)</label>
                    <textarea name="description" id="description" rows="2" 
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">{{ old('description', $voucher->description) }}</textarea>
                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Visible to customers during checkout.</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Type & Value -->
                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-surface-50 dark:bg-surface-900 p-4 rounded-md border border-surface-200 dark:border-surface-700">
                    <div>
                        <label for="type" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Discount Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type" required onchange="toggleDiscountFields()"
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="percentage" {{ old('type', $voucher->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="value" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Discount Value <span class="text-red-500">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" id="value-prefix" style="display: none;">
                                <span class="text-surface-500 dark:text-surface-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="value" id="value" value="{{ old('value', floatval($voucher->value)) }}" required step="0.01" min="0.01"
                                class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm pr-12">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" id="value-suffix">
                                <span class="text-surface-500 dark:text-surface-400 sm:text-sm">%</span>
                            </div>
                        </div>
                        @error('value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div id="max-discount-container">
                        <label for="max_discount" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Maximum Discount (Rp)</label>
                        <input type="number" name="max_discount" id="max_discount" value="{{ old('max_discount', floatval($voucher->max_discount)) }}" min="0" step="1"
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Leave empty for unlimited">
                        <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Only applies to percentage discounts.</p>
                        @error('max_discount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Rules -->
                <div>
                    <label for="min_purchase" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Minimum Purchase (Rp)</label>
                    <input type="number" name="min_purchase" id="min_purchase" value="{{ old('min_purchase', floatval($voucher->min_purchase)) }}" min="0" step="1"
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    @error('min_purchase')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="max_usage" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Total Usage Limit</label>
                    <input type="number" name="max_usage" id="max_usage" value="{{ old('max_usage', $voucher->max_usage) }}" min="0" step="1"
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="0 for unlimited">
                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Total number of times this code can be used by anyone.</p>
                    @error('max_usage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="per_user_limit" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Per-User Limit</label>
                    <input type="number" name="per_user_limit" id="per_user_limit" value="{{ old('per_user_limit', $voucher->per_user_limit) }}" min="1" step="1"
                        class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    <p class="mt-1 text-xs text-surface-500 dark:text-surface-400">Number of times a single customer can use this code.</p>
                    @error('per_user_limit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Validity -->
                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-surface-200 dark:border-surface-700">
                    <div>
                        <label for="valid_from" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Valid From</label>
                        <input type="datetime-local" name="valid_from" id="valid_from" value="{{ old('valid_from', $voucher->valid_from ? \Carbon\Carbon::parse($voucher->valid_from)->format('Y-m-d\TH:i') : '') }}" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('valid_from')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="valid_until" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Valid Until</label>
                        <input type="datetime-local" name="valid_until" id="valid_until" value="{{ old('valid_until', $voucher->valid_until ? \Carbon\Carbon::parse($voucher->valid_until)->format('Y-m-d\TH:i') : '') }}" 
                            class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        @error('valid_until')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Status -->
                <div class="col-span-1 md:col-span-2 pt-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}
                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-surface-300 dark:border-surface-600 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-surface-700 dark:text-surface-300">Active</label>
                            <p class="text-surface-500 dark:text-surface-400">Allow customers to use this voucher immediately.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3">
            <a href="{{ route('admin.vouchers.index') }}" class="inline-flex justify-center py-2 px-4 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Cancel
            </a>
            
        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <div class="corporate-card">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting.</p>
                        
                        <div class="flex flex-col space-y-3">
                            
                            <a href="javascript:history.back()" class="btn btn-secondary w-full">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
