<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

interface Subscription {
    id: number;
    plan_name: string;
    status: 'active' | 'expired' | 'cancelled' | 'pending';
    start_date: string;
    end_date: string;
    price: number;
    auto_renew: boolean;
    license_code: string;
    product_name: string;
}

interface Transaction {
    id: number;
    amount: number;
    status: string;
    payment_method: string;
    paid_at: string | null;
}

interface Props {
    subscription: Subscription;
    transactions: Transaction[];
}

const props = defineProps<Props>();

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'expired':
            return 'bg-red-100 text-red-800';
        case 'cancelled':
            return 'bg-gray-100 text-gray-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head :title="`Subscription - ${subscription.plan_name}`" />

    <CustomerLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="mb-6">
                    <Link
                        :href="route('customer.subscriptions.index')"
                        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                    >
                        ← Back to Subscriptions
                    </Link>
                </div>

                <!-- Subscription Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">Subscription Details</h2>
                            <span :class="getStatusColor(subscription.status)" class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full">
                                {{ subscription.status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Plan Name</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ subscription.plan_name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Product</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ subscription.product_name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">License Code</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900 font-mono">{{ subscription.license_code }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Price</h3>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ formatCurrency(subscription.price) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Start Date</h3>
                                <p class="mt-1 text-base text-gray-900">{{ formatDate(subscription.start_date) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">End Date</h3>
                                <p class="mt-1 text-base text-gray-900">{{ formatDate(subscription.end_date) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Auto Renew</h3>
                                <p class="mt-1 text-base font-semibold" :class="subscription.auto_renew ? 'text-green-600' : 'text-gray-400'">
                                    {{ subscription.auto_renew ? 'Yes' : 'No' }}
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-6 border-t border-gray-200">
                            <button
                                v-if="subscription.status === 'active'"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                Cancel Subscription
                            </button>
                            <button
                                v-if="subscription.status === 'expired'"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Renew Now
                            </button>
                            <button
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Download Invoice
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment History</h3>

                        <div v-if="transactions.length === 0" class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">No payment history yet</p>
                        </div>

                        <div v-else class="space-y-4">
                            <div
                                v-for="transaction in transactions"
                                :key="transaction.id"
                                class="border border-gray-200 rounded-lg p-4"
                            >
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-900">Transaction #{{ transaction.id }}</p>
                                        <p class="text-sm text-gray-500">{{ transaction.payment_method }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">{{ formatCurrency(transaction.amount) }}</p>
                                        <span 
                                            :class="{
                                                'bg-green-100 text-green-800': transaction.status === 'paid',
                                                'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
                                                'bg-red-100 text-red-800': transaction.status === 'failed',
                                                'bg-gray-100 text-gray-800': transaction.status === 'refunded'
                                            }"
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            {{ transaction.status }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="transaction.paid_at" class="text-sm text-gray-500">
                                    Paid at: {{ formatDate(transaction.paid_at) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
