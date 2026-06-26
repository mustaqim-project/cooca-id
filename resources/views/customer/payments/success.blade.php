@extends('layouts.customer')

@section('title', 'Payment Success')
@section('subtitle', 'Your transaction was completed successfully')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden text-center p-10">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 dark:bg-green-900/30 mb-6">
            <i data-lucide="check-lg" class="w-4 h-4 text-5xl text-green-600 dark:text-green-400"></i>
        </div>
        
        <h2 class="text-3xl font-extrabold text-surface-900 dark:text-white mb-2">Payment Successful!</h2>
        <p class="text-lg text-surface-500 dark:text-surface-400 mb-8">
            Thank you for your purchase. Your payment has been processed successfully and your subscription is now active.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('customer.subscriptions.index') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                View Subscriptions
            </a>
            <a href="{{ route('customer.invoices.index') }}" class="inline-flex justify-center items-center px-6 py-3 border border-surface-300 dark:border-surface-600 shadow-sm text-base font-medium rounded-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                View Invoices
            </a>
        </div>
    </div>
</div>
@endsection