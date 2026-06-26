@extends('layouts.customer')

@section('title', 'Payment Pending')
@section('subtitle', 'Your transaction is being processed')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden text-center p-10">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-6">
            <i data-lucide="hourglass-split" class="w-4 h-4 text-5xl text-yellow-600 dark:text-yellow-400 animate-pulse icon-3d"></i>
        </div>
        
        <h2 class="text-3xl font-extrabold text-surface-900 dark:text-white mb-2">Payment is Pending</h2>
        <p class="text-lg text-surface-500 dark:text-surface-400 mb-8">
            We are waiting for your payment to be completed. If you chose bank transfer, please complete the transfer.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('customer.payments.index') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Check Payment Status
            </a>
        </div>
    </div>
</div>
@endsection