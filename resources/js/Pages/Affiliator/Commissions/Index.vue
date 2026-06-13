<template>
    <AffiliatorLayout title="Commissions">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Commission History</h2>

                        <!-- Stats Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h3 class="text-sm text-blue-800">Pending</h3>
                                <p class="text-2xl font-bold text-blue-900 mt-1">Rp {{ formatCurrency(stats.pending || 0) }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <h3 class="text-sm text-green-800">Paid</h3>
                                <p class="text-2xl font-bold text-green-900 mt-1">Rp {{ formatCurrency(stats.paid || 0) }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <h3 class="text-sm text-purple-800">This Month</h3>
                                <p class="text-2xl font-bold text-purple-900 mt-1">Rp {{ formatCurrency(stats.this_month || 0) }}</p>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <h3 class="text-sm text-indigo-800">Total Lifetime</h3>
                                <p class="text-2xl font-bold text-indigo-900 mt-1">Rp {{ formatCurrency(stats.total || 0) }}</p>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="mb-6 flex gap-4">
                            <select v-model="status" @change="fetchCommissions" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="withdrawn">Withdrawn</option>
                            </select>
                            <select v-model="level" @change="fetchCommissions" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Levels</option>
                                <option value="1">Level 1 (25%)</option>
                                <option value="2">Level 2 (5%)</option>
                            </select>
                        </div>

                        <!-- Commissions Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="commission in commissions.data" :key="commission.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(commission.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ commission.customer?.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ commission.subscription?.product?.name || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">#{{ commission.transaction?.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Level {{ commission.level }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ commission.rate }}%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ formatCurrency(commission.amount) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-yellow-100 text-yellow-800': commission.status === 'pending',
                                                'bg-green-100 text-green-800': commission.status === 'paid',
                                                'bg-blue-100 text-blue-800': commission.status === 'withdrawn'
                                            }">
                                                {{ commission.status }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="commissions.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in commissions.last_page" 
                                    :key="page"
                                    @click="fetchCommissions(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === commissions.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== commissions.current_page
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

const commissions = ref({ data: [], current_page: 1, last_page: 1 });
const stats = ref({});
const status = ref('');
const level = ref('');

const fetchCommissions = (page = 1) => {
    router.get(route('affiliator.commissions.index'), { 
        status: status.value,
        level: level.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            commissions.value = response.props.commissions;
            stats.value = response.props.stats || {};
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
    fetchCommissions();
});
</script>
