<template>
    <AdminLayout title="Licenses">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Licenses</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage software licenses</p>
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Generate License
                </button>
            </div>

            <!-- Licenses Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="license in licenses.data" :key="license.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-900">{{ license.license_code }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ license.customer?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ license.subscription?.product?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-green-100 text-green-800': license.status === 'active',
                                        'bg-red-100 text-red-800': license.status === 'revoked',
                                        'bg-yellow-100 text-yellow-800': license.status === 'expired'
                                    }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ license.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(license.expires_at) }}
                                </td>
                            </tr>
                            <tr v-if="licenses.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No licenses found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            Showing {{ licenses.from || 0 }} to {{ licenses.to || 0 }} of {{ licenses.total || 0 }} results
                        </p>
                        <div class="flex space-x-2">
                            <button v-for="link in licenses.links" :key="link.label" 
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
    licenses: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID');
};
</script>
