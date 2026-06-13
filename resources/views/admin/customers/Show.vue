<template>
    <AdminLayout title="Customer Details">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ customer.name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ customer.email }}</p>
                </div>
                <a href="/admin/customers" class="text-blue-600 hover:text-blue-900">← Back to Customers</a>
            </div>

            <!-- Customer Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ customer.name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ customer.email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ customer.phone || '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Registered</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(customer.created_at) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Subscriptions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Subscriptions</h3>
                <p class="text-gray-500">Subscription details would be displayed here</p>
            </div>

            <!-- Licenses -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Licenses</h3>
                <p class="text-gray-500">License details would be displayed here</p>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    customer: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID');
};
</script>
