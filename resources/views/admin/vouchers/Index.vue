<template>
    <AdminLayout title="Vouchers">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Vouchers</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage discount vouchers</p>
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Create Voucher
                </button>
            </div>

            <!-- Vouchers Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="voucher in vouchers.data" :key="voucher.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-900">{{ voucher.code }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ voucher.type === 'percentage' ? 'Percentage' : 'Fixed' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ voucher.type === 'percentage' ? voucher.value + '%' : 'Rp ' + formatNumber(voucher.value) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-green-100 text-green-800': voucher.is_active,
                                        'bg-red-100 text-red-800': !voucher.is_active
                                    }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ voucher.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(voucher.expires_at) }}
                                </td>
                            </tr>
                            <tr v-if="vouchers.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No vouchers found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            Showing {{ vouchers.from || 0 }} to {{ vouchers.to || 0 }} of {{ vouchers.total || 0 }} results
                        </p>
                        <div class="flex space-x-2">
                            <button v-for="link in vouchers.links" :key="link.label" 
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
    vouchers: {
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
