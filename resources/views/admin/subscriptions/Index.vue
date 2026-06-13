<template>
    <AdminLayout title="Subscriptions">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Subscriptions</h1>
                <p class="mt-1 text-sm text-gray-500">Manage customer subscriptions</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-gray-500">Total</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ stats.total }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-green-600">Active</dt>
                    <dd class="mt-1 text-2xl font-semibold text-green-600">{{ stats.active }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-blue-600">Trial</dt>
                    <dd class="mt-1 text-2xl font-semibold text-blue-600">{{ stats.trial }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-yellow-600">Expired</dt>
                    <dd class="mt-1 text-2xl font-semibold text-yellow-600">{{ stats.expired }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-red-600">Cancelled</dt>
                    <dd class="mt-1 text-2xl font-semibold text-red-600">{{ stats.cancelled }}</dd>
                </div>
            </div>

            <!-- Subscriptions Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="subscription in subscriptions.data" :key="subscription.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ subscription.customer?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ subscription.product?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-green-100 text-green-800': subscription.status === 'active',
                                        'bg-blue-100 text-blue-800': subscription.status === 'trial',
                                        'bg-yellow-100 text-yellow-800': subscription.status === 'expired',
                                        'bg-red-100 text-red-800': subscription.status === 'cancelled'
                                    }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ subscription.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(subscription.started_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(subscription.expires_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a :href="`/admin/subscriptions/${subscription.id}`" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            <tr v-if="subscriptions.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No subscriptions found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            Showing {{ subscriptions.from || 0 }} to {{ subscriptions.to || 0 }} of {{ subscriptions.total || 0 }} results
                        </p>
                        <div class="flex space-x-2">
                            <button v-for="link in subscriptions.links" :key="link.label" 
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
    subscriptions: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID');
};
</script>
