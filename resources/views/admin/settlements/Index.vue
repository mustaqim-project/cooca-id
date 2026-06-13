<template>
    <AdminLayout title="Settlements">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Settlements</h1>
                <p class="mt-1 text-sm text-gray-500">Manage affiliate withdrawal requests</p>
            </div>

            <!-- Settlements Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affiliator</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="withdrawal in withdrawals.data" :key="withdrawal.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ withdrawal.affiliator?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ formatNumber(withdrawal.amount) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ withdrawal.withdrawal_method }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-yellow-100 text-yellow-800': withdrawal.status === 'pending',
                                        'bg-green-100 text-green-800': withdrawal.status === 'approved',
                                        'bg-red-100 text-red-800': withdrawal.status === 'rejected',
                                        'bg-blue-100 text-blue-800': withdrawal.status === 'paid'
                                    }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ withdrawal.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(withdrawal.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a :href="`/admin/settlements/${withdrawal.id}`" class="text-blue-600 hover:text-blue-900">View</a>
                                    <button v-if="withdrawal.status === 'pending'" class="text-green-600 hover:text-green-900">Approve</button>
                                    <button v-if="withdrawal.status === 'pending'" class="text-red-600 hover:text-red-900">Reject</button>
                                </td>
                            </tr>
                            <tr v-if="withdrawals.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No withdrawal requests found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            Showing {{ withdrawals.from || 0 }} to {{ withdrawals.to || 0 }} of {{ withdrawals.total || 0 }} results
                        </p>
                        <div class="flex space-x-2">
                            <button v-for="link in withdrawals.links" :key="link.label" 
                                    :disabled="!link.url"
                                    class="px-3 py-1 rounded border"
                                    :class="link.active ? 'bg-blue-500 text-white' : 'bg-white'">
                                <span v-html="link.label"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    withdrawals: {
        type: Object,
        required: true
    }
});

const formatNumber = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID');
};
</script>
