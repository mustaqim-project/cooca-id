<template>
    <AdminLayout title="Transaction Details">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Transaction Details</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ transaction.transaction_id }}</p>
                </div>
                <a href="/admin/transactions" class="text-blue-600 hover:text-blue-900">← Back to Transactions</a>
            </div>

            <!-- Transaction Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Information</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ transaction.transaction_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span :class="{
                                'bg-green-100 text-green-800': transaction.status === 'paid',
                                'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
                                'bg-red-100 text-red-800': transaction.status === 'failed'
                            }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ transaction.status }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ formatNumber(transaction.amount) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ transaction.payment_method || '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ transaction.customer?.name || 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(transaction.created_at) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="flex space-x-4">
                    <button v-if="transaction.status === 'pending'" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Mark as Paid
                    </button>
                    <button v-if="transaction.status === 'paid'" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Process Refund
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    transaction: {
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
