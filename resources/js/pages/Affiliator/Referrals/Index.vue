<template>
    <AffiliatorLayout title="Referrals">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">My Referrals</h2>
                            <div class="bg-indigo-50 px-4 py-2 rounded-md">
                                <p class="text-sm text-indigo-800">Your Referral Code: <span class="font-mono font-bold">{{ referralCode }}</span></p>
                            </div>
                        </div>

                        <!-- Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                                <h3 class="text-sm font-medium opacity-80">Total Referrals</h3>
                                <p class="text-3xl font-bold mt-2">{{ stats.total_referrals }}</p>
                            </div>
                            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                                <h3 class="text-sm font-medium opacity-80">Active Customers</h3>
                                <p class="text-3xl font-bold mt-2">{{ stats.active_customers }}</p>
                            </div>
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                                <h3 class="text-sm font-medium opacity-80">Total Commission</h3>
                                <p class="text-3xl font-bold mt-2">Rp {{ formatCurrency(stats.total_commission) }}</p>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="mb-6">
                            <input 
                                v-model="search" 
                                @input="fetchReferrals"
                                type="text" 
                                placeholder="Search referrals..." 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <!-- Referrals Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission Earned</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="referral in referrals.data" :key="referral.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ referral.customer?.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ referral.customer?.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ referral.subscription?.product?.name || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(referral.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': referral.customer?.status === 'active',
                                                'bg-red-100 text-red-800': referral.customer?.status === 'inactive'
                                            }">
                                                {{ referral.customer?.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ formatCurrency(referral.total_commission || 0) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="referrals.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in referrals.last_page" 
                                    :key="page"
                                    @click="fetchReferrals(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === referrals.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== referrals.current_page
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
import AffiliatorLayout from '@/layouts/AffiliatorLayout.vue';

const referrals = ref({ data: [], current_page: 1, last_page: 1 });
const stats = ref({ total_referrals: 0, active_customers: 0, total_commission: 0 });
const search = ref('');
const referralCode = ref('');

const fetchReferrals = (page = 1) => {
    router.get(route('affiliator.referrals.index'), { 
        search: search.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            referrals.value = response.props.referrals;
            stats.value = response.props.stats || {};
            referralCode.value = response.props.referralCode || '';
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
    fetchReferrals();
});
</script>
