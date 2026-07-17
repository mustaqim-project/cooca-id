@extends('layouts.customer')

@section('title', 'Trials')
@section('subtitle', 'Manage your product trials')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-surface-900 dark:text-white">Your Trials</h2>
            <a href="{{ route('customer.trials.create') }}"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                Request New Trial
            </a>
        </div>

        <div class="corporate-card">
            <div class="overflow-x-auto">
                <table class="corporate-table">
                    <thead class="table-thead">
                        <tr>
                            <th scope="col" class="table-th">Product</th>
                            <th scope="col" class="table-th">Requested Subdomain</th>
                            <th scope="col" class="table-th">Status</th>
                            <th scope="col" class="table-th">Requested At</th>
                            <th scope="col" class="table-th">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-tbody">
                        @forelse($trials as $trial)
                            <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-surface-900 dark:text-white">
                                    {{ $trial->product->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                    {{ $trial->requested_subdomain }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'submitted' =>
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'reviewing' =>
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                            'approved' =>
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'rejected' =>
                                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                            'provisioning' =>
                                                'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                            'trial_active' =>
                                                'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'trial_expired' =>
                                                'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                                        ];
                                        $colorClass =
                                            $statusColors[$trial->status] ?? 'bg-surface-100 text-surface-800';
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                        {{ str_replace('_', ' ', ucfirst($trial->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                    {{ $trial->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('customer.trials.show', $trial) }}"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-surface-500 dark:text-surface-400">
                                    You haven't requested any trials yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $trials->links() }}
            </div>
        </div>
    </div>
@endsection
