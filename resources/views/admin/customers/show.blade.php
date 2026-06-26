@extends('layouts.admin')

@section('title', 'Customer Details')
@section('subtitle', 'View and manage customer profile')

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
            <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Customers
    </a>
        </div>
    </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Info -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-6">
            <div class="p-6 text-center border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <div class="mx-auto h-24 w-24 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-3xl mb-4">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-surface-900 dark:text-white">{{ $customer->name }}</h3>
                <p class="text-sm text-surface-500 dark:text-surface-400">{{ $customer->business_name ?? 'Individual Customer' }}</p>
                
                <div class="mt-4 flex justify-center">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $customer->email_verified_at ? 'Verified' : 'Unverified' }}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <h4 class="text-sm font-medium text-surface-900 dark:text-white mb-4 uppercase tracking-wider">Contact Information</h4>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i data-lucide="mail" class="w-4 h-4 text-surface-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-surface-900 dark:text-white">Email Address</p>
                            <p class="text-sm text-surface-500 dark:text-surface-400">{{ $customer->email }}</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="telephone" class="w-4 h-4 text-surface-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-surface-900 dark:text-white">Phone Number</p>
                            <p class="text-sm text-surface-500 dark:text-surface-400">{{ $customer->phone ?? 'Not provided' }}</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="calendar" class="w-4 h-4 text-surface-400 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-surface-900 dark:text-white">Joined Date</p>
                            <p class="text-sm text-surface-500 dark:text-surface-400">{{ $customer->created_at->format('M d, Y') }}</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                <h4 class="text-sm font-medium text-surface-900 dark:text-white uppercase tracking-wider">Actions</h4>
            </div>
            <div class="p-4 space-y-3">
                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none transition-colors">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit Customer
                </a>
                <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 border border-surface-300 dark:border-surface-600 shadow-sm text-sm font-medium rounded-md text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-900 focus:outline-none transition-colors">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i> Send Email
                </button>
                <form class="form-confirm-delete m-0 p-0" action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Delete Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 p-5">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400 truncate">Total Orders</dt>
                <dd class="mt-1 text-2xl font-semibold text-surface-900 dark:text-white">{{ $customer->transactions()->count() ?? 0 }}</dd>
            </div>
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 p-5">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400 truncate">Active Subscriptions</dt>
                <dd class="mt-1 text-2xl font-semibold text-surface-900 dark:text-white">{{ $customer->subscriptions()->where('status', 'active')->count() ?? 0 }}</dd>
            </div>
            <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 p-5">
                <dt class="text-sm font-medium text-surface-500 dark:text-surface-400 truncate">Total Spent</dt>
                <dd class="mt-1 text-2xl font-semibold text-surface-900 dark:text-white">
                    Rp {{ number_format($customer->transactions()->where('status', 'paid')->sum('gross_amount') ?? 0, 0, ',', '.') }}
                </dd>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900">
                <h4 class="text-base font-medium text-surface-900 dark:text-white">Recent Transactions</h4>
                <a href="{{ route('admin.transactions.index', ['customer_id' => $customer->id]) }}" class="text-sm text-primary-600 hover:text-primary-800">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
                    <thead class="bg-surface-50 dark:bg-surface-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-surface-800 animate-fade-in-up divide-y divide-surface-200 dark:divide-surface-700">
                        @forelse($customer->transactions()->latest()->take(5)->get() ?? [] as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-surface-900 dark:text-white">
                                Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = match($transaction->status) {
                                        'paid', 'settlement' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed', 'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-surface-100 text-surface-800'
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm text-surface-500 dark:text-surface-400">
                                No transactions found for this customer.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Active Licenses -->
        <div class="bg-white dark:bg-surface-800 animate-fade-in-up shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center bg-surface-50 dark:bg-surface-900">
                <h4 class="text-base font-medium text-surface-900 dark:text-white">Licenses</h4>
                <a href="{{ route('admin.licenses.index', ['customer_id' => $customer->id]) }}" class="text-sm text-primary-600 hover:text-primary-800">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">
                    <thead class="bg-surface-50 dark:bg-surface-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Domain</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-surface-500 dark:text-surface-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-surface-800 animate-fade-in-up divide-y divide-surface-200 dark:divide-surface-700">
                        @forelse($customer->licenses()->latest()->take(5)->get() ?? [] as $license)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-surface-900 dark:text-white">
                                {{ $license->product->name ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                {{ $license->domain ?? 'Not configured' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $license->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200' }}">
                                    {{ ucfirst($license->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm text-surface-500 dark:text-surface-400">
                                No licenses found for this customer.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection
