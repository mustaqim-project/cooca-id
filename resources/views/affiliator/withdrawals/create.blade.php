@extends('layouts.affiliator')

@section('title', 'Request Withdrawal')
@section('subtitle', 'Transfer your available balance to your bank account')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form action="{{ route('affiliator.withdrawals.store') }}" method="POST"  class="form-confirm-submit">
        @csrf
        
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="p-6 sm:p-8 space-y-6">
                    
                    <!-- Bank Account Info -->
                    <div>
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-3">Destination Account</h3>
                        
                        @if(empty($bankAccount['account_number']))
                            <div class="rounded-md bg-red-50 dark:bg-red-900/30 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Bank account not set</h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                            <p>Please update your bank details in your profile before requesting a withdrawal.</p>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('affiliator.profile.edit') }}#bank" class="text-red-800 dark:text-red-300 font-medium hover:underline">Go to Profile &rarr;</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-surface-50 dark:bg-surface-900/50 border border-surface-200 dark:border-surface-700 rounded-lg p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-md bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 mr-4">
                                        <i data-lucide="bank" class="w-4 h-4 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-surface-900 dark:text-white">{{ strtoupper($bankAccount['bank_name']) }} - {{ $bankAccount['account_number'] }}</p>
                                        <p class="text-sm text-surface-500 dark:text-surface-400">A/N: {{ $bankAccount['account_holder'] }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('affiliator.profile.edit') }}#bank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">Change</a>
                            </div>
                            
                            <!-- Hidden fields for the request -->
                            <input type="hidden" name="withdrawal_method" value="bank">
                            <input type="hidden" name="account_number" value="{{ $bankAccount['account_number'] }}">
                            <input type="hidden" name="account_name" value="{{ $bankAccount['account_holder'] }}">
                        @endif
                    </div>
                    
                    @if(!empty($bankAccount['account_number']))
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Withdrawal Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-surface-500 dark:text-surface-400 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="amount" id="amount" 
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 sm:text-sm border-surface-300 dark:border-surface-600 dark:bg-surface-700 dark:text-white rounded-md py-3" 
                                placeholder="0" 
                                min="{{ $minimumWithdrawal ?? 50000 }}" 
                                max="{{ $availableBalance ?? 0 }}"
                                value="{{ old('amount', $availableBalance) }}"
                                required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="document.getElementById('amount').value = '{{ $availableBalance }}'" class="text-primary-600 dark:text-primary-400 text-sm font-medium hover:text-primary-800">
                                    Max
                                </button>
                            </div>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Fee info -->
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-sm text-blue-700 dark:text-blue-300">
                        <div class="flex">
                            <i data-lucide="info" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                            <div class="ml-2">
                                <p>A withdrawal fee of <strong>Rp {{ number_format($withdrawalFee['bank'] ?? 2500, 0, ',', '.') }}</strong> will be deducted from your withdrawal amount.</p>
                                <p class="mt-1 text-xs opacity-80">Processing usually takes 1-2 business days.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if(!empty($bankAccount['account_number']))
                <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700 flex justify-end">
                    
                </div>
                @endif
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
