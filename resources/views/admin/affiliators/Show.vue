<template>
    <AdminLayout title="Affiliator Details">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ affiliator.name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ affiliator.email }}</p>
                </div>
                <a href="/admin/affiliators" class="text-blue-600 hover:text-blue-900">← Back to Affiliators</a>
            </div>

            <!-- Affiliator Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Affiliator Information</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ affiliator.name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ affiliator.email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Referral Code</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                {{ affiliator.referral_code }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Balance</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ formatNumber(affiliator.balance || 0) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Downlines -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Downlines (Level 2)</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referral Code</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="downline in downlines" :key="downline.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ downline.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ downline.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ downline.referral_code }}</td>
                            </tr>
                            <tr v-if="!downlines || downlines.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No downlines</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    affiliator: {
        type: Object,
        required: true
    },
    downlines: {
        type: Array,
        default: () => []
    }
});

const formatNumber = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};
</script>
