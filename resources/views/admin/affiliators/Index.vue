<template>
    <AdminLayout title="Affiliators">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Affiliators</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your affiliate partners</p>
            </div>

            <!-- Affiliators Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referral Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="affiliator in affiliators.data" :key="affiliator.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ affiliator.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ affiliator.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ affiliator.referral_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ formatNumber(affiliator.balance || 0) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a :href="`/admin/affiliators/${affiliator.id}`" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            <tr v-if="affiliators.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No affiliators found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            Showing {{ affiliators.from || 0 }} to {{ affiliators.to || 0 }} of {{ affiliators.total || 0 }} results
                        </p>
                        <div class="flex space-x-2">
                            <button v-for="link in affiliators.links" :key="link.label" 
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
    affiliators: {
        type: Object,
        required: true
    }
});

const formatNumber = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};
</script>
