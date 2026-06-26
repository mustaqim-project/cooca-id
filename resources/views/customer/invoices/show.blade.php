@extends('layouts.customer')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('subtitle', 'View invoice details and payment status')

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
            <a href="{{ route('customer.invoices.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Invoices
    </a>
</div>

<div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden max-w-4xl mx-auto">
    <!-- Invoice Header -->
    <div class="p-6 md:p-8 border-b border-surface-200 dark:border-surface-700">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-surface-900 dark:text-white">INVOICE</h2>
                <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">#{{ $invoice->invoice_number }}</p>
            </div>
            
            <div class="text-right">
                @php
                    $statusClass = match($invoice->status) {
                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                        'unpaid' => ($invoice->due_date && $invoice->due_date->isPast()) 
                                    ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' 
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'cancelled' => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300',
                        default => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-300'
                    };
                    
                    $statusText = ($invoice->status == 'unpaid' && $invoice->due_date && $invoice->due_date->isPast()) 
                                ? 'OVERDUE' 
                                : strtoupper($invoice->status);
                @endphp
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $statusClass }}">
                    {{ $statusText }}
                </span>
            </div>
        </div>
        
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">Billed To</h3>
                <address class="not-italic text-sm text-surface-700 dark:text-surface-300">
                    <span class="block font-medium text-surface-900 dark:text-white">{{ auth()->guard('customer')->user()->name }}</span>
                    <span class="block">{{ auth()->guard('customer')->user()->business_name ?? '' }}</span>
                    <span class="block">{{ auth()->guard('customer')->user()->email }}</span>
                    <span class="block">{{ auth()->guard('customer')->user()->phone ?? '' }}</span>
                </address>
            </div>
            
            <div class="md:text-right">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Date Issued</h3>
                    <p class="text-sm font-medium text-surface-900 dark:text-white">{{ $invoice->created_at->format('F d, Y') }}</p>
                </div>
                @if($invoice->status == 'unpaid' && $invoice->due_date)
                <div>
                    <h3 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Due Date</h3>
                    <p class="text-sm font-medium {{ $invoice->due_date->isPast() ? 'text-red-600 dark:text-red-400' : 'text-surface-900 dark:text-white' }}">
                        {{ $invoice->due_date->format('F d, Y') }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Invoice Items -->
    <div class="p-6 md:p-8">
        <h3 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-4">Invoice Items</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
                <thead>
                    <tr>
                        <th scope="col" class="pb-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Description</th>
                        <th scope="col" class="pb-3 text-right text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-200 dark:divide-surface-700">
                    <tr>
                        <td class="py-4 text-sm text-surface-900 dark:text-white">
                            <span class="font-medium">
                                {{ $invoice->subscription->plan->product->name ?? 'Subscription Plan' }}
                            </span>
                            @if($invoice->subscription && $invoice->subscription->plan)
                                <div class="text-surface-500 dark:text-surface-400 text-xs mt-1">
                                    {{ $invoice->subscription->plan->name }} ({{ $invoice->subscription->plan->billing_cycle ?? 'Monthly' }})
                                </div>
                            @endif
                        </td>
                        <td class="py-4 text-sm text-right text-surface-900 dark:text-white">
                            Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    @if($invoice->discount > 0)
                    <tr>
                        <td class="pt-4 pb-2 text-sm text-right text-surface-500 dark:text-surface-400">Discount</td>
                        <td class="pt-4 pb-2 text-sm text-right text-green-600 dark:text-green-400">
                            - Rp {{ number_format($invoice->discount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    @if($invoice->tax > 0)
                    <tr>
                        <td class="py-2 text-sm text-right text-surface-500 dark:text-surface-400">Tax</td>
                        <td class="py-2 text-sm text-right text-surface-900 dark:text-white">
                            Rp {{ number_format($invoice->tax, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="py-4 text-base text-right font-bold text-surface-900 dark:text-white border-t border-surface-200 dark:border-surface-700">Total</td>
                        <td class="py-4 text-lg text-right font-bold text-primary-600 dark:text-primary-400 border-t border-surface-200 dark:border-surface-700">
                            Rp {{ number_format($invoice->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Payment Information -->
    @if($invoice->transaction)
    <div class="px-6 py-5 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700">
        <h3 class="text-sm font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-4">Payment Details</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
            <div class="sm:col-span-1">
                <dt class="text-xs font-medium text-surface-500 dark:text-surface-400">Payment Method</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white font-medium">{{ strtoupper($invoice->transaction->payment_type ?? '-') }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-xs font-medium text-surface-500 dark:text-surface-400">Transaction ID</dt>
                <dd class="mt-1 text-sm text-surface-900 dark:text-white font-mono">{{ $invoice->transaction->id }}</dd>
            </div>
        </dl>
    </div>
    @endif
    
    <!-- Action Buttons -->
    <div class="p-6 border-t border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900/50">
        <a href="{{ route('customer.invoices.download', $invoice->id) }}" class="inline-flex items-center text-sm font-medium text-surface-600 dark:text-surface-300 hover:text-surface-900 dark:text-white dark:hover:text-white">
            <i data-lucide="download" class="w-4 h-4"></i> Download PDF
        </a>
        
        @if($invoice->status == 'unpaid')
            <a href="{{ route('customer.payments.show', $invoice->transaction->id ?? 0) }}" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Pay Now <i data-lucide="credit-card" class="w-4 h-4 ml-2"></i>
            </a>
        @endif
    </div>
        </div>
    </div>
</div>
@endsection
