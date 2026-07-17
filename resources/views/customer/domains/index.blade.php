@extends('layouts.customer')

@section('title', 'My Domains')
@section('subtitle', 'Manage custom domains for your deployed products')

@section('content')
    <div class="space-y-6">
        <div class="corporate-card">
            <div class="overflow-x-auto">
                <table class="corporate-table">
                    <thead class="table-thead">
                        <tr>
                            <th scope="col" class="table-th">Product</th>
                            <th scope="col" class="table-th">Current Subdomain</th>
                            <th scope="col" class="table-th">Custom Domain</th>
                            <th scope="col" class="table-th">Status</th>
                            <th scope="col" class="table-th">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-tbody">
                        @forelse($tenants as $tenant)
                            <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-surface-900 dark:text-white">
                                    {{ $tenant->product->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                    {{ $tenant->subdomain }}.cooca.id
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">
                                    {{ $tenant->custom_domain ?: 'Not Set' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($tenant->status === 'active')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">{{ ucfirst($tenant->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button type="button" x-data
                                        x-on:click="$dispatch('open-modal', 'edit-domain-{{ $tenant->id }}')"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                        Edit Domain
                                    </button>
                                    @if ($tenant->custom_domain)
                                        <form action="{{ route('customer.domains.verify', $tenant) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                Verify
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Edit Modal (AlpineJS based - assuming modal component exists or implementing basic one) -->
                            <div x-data="{ show: false }" x-show="show"
                                x-on:open-modal.window="if ($event.detail === 'edit-domain-{{ $tenant->id }}') show = true"
                                style="display: none;" class="fixed z-50 inset-0 overflow-y-auto">
                                <div
                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="show" class="fixed inset-0 transition-opacity" aria-hidden="true"
                                        x-on:click="show = false">
                                        <div
                                            class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900 dark:opacity-90">
                                        </div>
                                    </div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>
                                    <div x-show="show"
                                        class="inline-block align-bottom bg-white dark:bg-surface-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <form action="{{ route('customer.domains.update', $tenant) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="bg-white dark:bg-surface-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <h3 class="text-lg leading-6 font-medium text-surface-900 dark:text-white"
                                                    id="modal-title">
                                                    Edit Custom Domain
                                                </h3>
                                                <div class="mt-4">
                                                    <label
                                                        class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Custom
                                                        Domain</label>
                                                    <input type="text" name="custom_domain"
                                                        value="{{ $tenant->custom_domain }}"
                                                        placeholder="e.g. erp.mycompany.com" required
                                                        class="w-full px-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg dark:bg-surface-700 dark:text-white">
                                                    <p class="mt-2 text-sm text-surface-500">Please point your domain's
                                                        CNAME record to {{ $tenant->subdomain }}.cooca.id</p>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-gray-50 dark:bg-surface-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                                    Save
                                                </button>
                                                <button type="button" x-on:click="show = false"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-surface-300 dark:border-surface-600 shadow-sm px-4 py-2 bg-white dark:bg-surface-800 text-base font-medium text-surface-700 dark:text-surface-300 hover:bg-gray-50 dark:hover:bg-surface-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-surface-500 dark:text-surface-400">
                                    No active products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>
@endsection
