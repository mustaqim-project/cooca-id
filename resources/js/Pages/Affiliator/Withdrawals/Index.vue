<template>
    <AffiliatorLayout title="Withdrawals">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Withdrawal History</h2>
                            <a :href="route('affiliator.withdrawals.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                                Request Withdrawal
                            </a>
                        </div>

                        <!-- Balance Info -->
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white mb-6">
                            <h3 class="text-sm font-medium opacity-80">Available Balance</h3>
                            <p class="text-3xl font-bold mt-2">Rp {{ formatCurrency(balance) }}</p>
                            <p class="text-xs mt-2 opacity-70">Minimum withdrawal: Rp {{ formatCurrency(minimumWithdrawal) }}</p>
                        </div>

                        <!-- Filters -->
                        <div class="mb-6 flex gap-4">
                            <select v-model="status" @change="fetchWithdrawals" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>

                        <!-- Withdrawals Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="withdrawal in withdrawals.data" :key="withdrawal.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ withdrawal.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(withdrawal.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ formatCurrency(withdrawal.amount) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ formatCurrency(withdrawal.fee) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Rp {{ formatCurrency(withdrawal.net_amount) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ withdrawal.payment_method === 'bank' ? 'Bank Transfer' : 'E-Wallet' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ withdrawal.account_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-yellow-100 text-yellow-800': withdrawal.status === 'pending',
                                                'bg-green-100 text-green-800': withdrawal.status === 'approved',
                                                'bg-red-100 text-red-800': withdrawal.status === 'rejected',
                                                'bg-blue-100 text-blue-800': withdrawal.status === 'paid'
                                            }">
                                                {{ withdrawal.status }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-if="withdrawals.data.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No withdrawals yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Request your first withdrawal when you have enough balance.</p>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="withdrawals.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in withdrawals.last_page" 
                                    :key="page"
                                    @click="fetchWithdrawals(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === withdrawals.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== withdrawals.current_page
                                    }"
                                >
                                    {{ page }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';

const withdrawals = ref({ data: [], current_page: 1, last_page: 1 });
const balance = ref(0);
const minimumWithdrawal = ref(0);
const status = ref('');

const fetchWithdrawals = (page = 1) => {
    router.get(route('affiliator.withdrawals.index'), { 
        status: status.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            withdrawals.value = response.props.withdrawals;
            balance.value = response.props.balance || 0;
            minimumWithdrawal.value = response.props.minimumWithdrawal || 0;
        }
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID').format(amount);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

onMounted(() => {
    fetchWithdrawals();
});
</script>
