@extends('layouts.admin')

@section('title', 'Support Tickets')
@section('subtitle', 'Manage customer support requests')

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
                    <th scope="col" class="table-th">ID</th>
                    <th scope="col" class="table-th">Subject</th>
                    <th scope="col" class="table-th">Customer</th>
                    <th scope="col" class="table-th">Priority</th>
                    <th scope="col" class="table-th">Status</th>
                    <th scope="col" class="table-th">Assigned To</th>
                    <th scope="col" class="table-th">Created</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            
                
                
                </thead>
                <tbody class="table-tbody">
                    
                    
                    
                @forelse($tickets as $ticket)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-surface-900 dark:text-white">
                        #{{ $ticket->id }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-surface-900 dark:text-white line-clamp-1 max-w-xs">
                            {{ $ticket->subject }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ $ticket->customer->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($ticket->priority === 'urgent')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Urgent</span>
                        @elseif($ticket->priority === 'high')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">High</span>
                        @elseif($ticket->priority === 'medium')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Medium</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200 dark:bg-surface-700 dark:text-surface-300">Low</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($ticket->status === 'open')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Open</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">In Progress</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Resolved</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-100 dark:bg-surface-800 text-surface-800 dark:text-surface-200 dark:bg-surface-700 dark:text-surface-300">Closed</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        @if($ticket->assignedTo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                                {{ $ticket->assignedTo->name }}
                            </span>
                        @else
                            <span class="text-surface-400">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                        {{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300" title="View">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            <button onclick="replyTicket({{ $ticket->id }})" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Reply">
                                <i data-lucide="corner-down-left" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <i data-lucide="inbox" class="w-12 h-12 text-surface-300 dark:text-surface-600 dark:text-surface-400 mx-auto mb-4 icon-3d"></i>
                        <p class="text-surface-500 dark:text-surface-400 text-lg">No tickets found</p>
                    </td>
                </tr>
                @endforelse
            
                
                
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
