@extends('layouts.admin')

@section('title', 'Vouchers & Discounts')
@section('subtitle', 'Manage promotional codes and discounts')

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
            
        
                <a href="{{ route('admin.vouchers.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add New
                </a>
            </div>
    </div>

    <!-- Data Table -->
    <div class="corporate-card">
        <div class="overflow-x-auto">
            <table class="corporate-table">
                <thead class="table-thead">
                    
                    
                    
                <tr>
                    <th scope="col" class="table-th">Code / Name</th>
                    <th scope="col" class="table-th">Discount</th>
                    <th scope="col" class="table-th">Usage</th>
                    <th scope="col" class="table-th">Validity</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($vouchers as $voucher)
                <tr class="hover:bg-surface-50 dark:bg-surface-900">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded flex items-center justify-center text-primary-600 font-bold">
                                <i data-lucide="ticket" class="w-4 h-4"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-surface-900 dark:text-white font-mono">{{ $voucher->code }}</div>
                                <div class="text-sm text-surface-500 dark:text-surface-400">{{ $voucher->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">
                            @if($voucher->type == 'percentage')
                                {{ $voucher->value }}% OFF
                            @else
                                Rp {{ number_format($voucher->value, 0, ',', '.') }} OFF
                            @endif
                        </div>
                        @if($voucher->min_purchase > 0)
                            <div class="text-xs text-surface-500 dark:text-surface-400 mt-1">Min: Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-1 h-2 bg-surface-200 rounded-full w-24 overflow-hidden">
                                @php
                                    $percentage = $voucher->max_usage > 0 ? min(100, ($voucher->used_count / $voucher->max_usage) * 100) : 0;
                                    $colorClass = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 75 ? 'bg-yellow-500' : 'bg-green-500');
                                @endphp
                                <div class="h-full {{ $colorClass }}" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="ml-3 text-xs text-surface-600 dark:text-surface-400 font-medium">
                                {{ $voucher->used_count }} / {{ $voucher->max_usage > 0 ? $voucher->max_usage : '∞' }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        @if($voucher->valid_from)
                            <div class="{{ \Carbon\Carbon::parse($voucher->valid_from)->isFuture() ? 'text-yellow-600' : '' }}">
                                <span class="text-xs">From:</span> {{ \Carbon\Carbon::parse($voucher->valid_from)->format('M d, Y') }}
                            </div>
                        @endif
                        @if($voucher->valid_until)
                            <div class="{{ \Carbon\Carbon::parse($voucher->valid_until)->isPast() ? 'text-red-600 font-medium' : '' }}">
                                <span class="text-xs">To:</span> {{ \Carbon\Carbon::parse($voucher->valid_until)->format('M d, Y') }}
                            </div>
                        @else
                            <div class="text-xs">Never expires</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $isExpired = $voucher->valid_until && \Carbon\Carbon::parse($voucher->valid_until)->isPast();
                            $isMaxed = $voucher->max_usage > 0 && $voucher->used_count >= $voucher->max_usage;
                            
                            if (!$voucher->is_active) {
                                $statusClass = 'bg-surface-100 text-surface-800';
                                $statusText = 'Inactive';
                            } elseif ($isExpired) {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'Expired';
                            } elseif ($isMaxed) {
                                $statusClass = 'bg-orange-100 text-orange-800';
                                $statusText = 'Fully Used';
                            } else {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'Active';
                            }
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="text-primary-600 hover:text-primary-900" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            
                            @if($voucher->is_active)
                                <form class="form-confirm-submit" action="{{ route('admin.vouchers.deactivate', $voucher->id) }}" method="POST" class="inline form-confirm-delete">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Deactivate">
                                        <i data-lucide="pause-circle" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @else
                                <form class="form-confirm-submit" action="{{ route('admin.vouchers.activate', $voucher->id) }}" method="POST" class="inline form-confirm-delete">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Activate">
                                        <i data-lucide="play-circle" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <form class="form-confirm-delete" action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="inline form-confirm-delete" >
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
                    <td colspan="6" class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
                        <i data-lucide="ticket" class="w-4 h-4"></i>
                        <p>No promotional vouchers found.</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.vouchers.create') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-500">
                                Create your first voucher <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
