<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

interface SubscriptionPlan {
    id: number;
    name: string;
    price: number;
    duration_months: number;
    features: string[];
}

interface Subscription {
    id: number;
    plan_name: string;
    status: 'active' | 'expired' | 'cancelled' | 'pending';
    start_date: string;
    end_date: string;
    price: number;
    auto_renew: boolean;
}

interface Props {
    subscriptions: Subscription[];
    plans: SubscriptionPlan[];
    filters: {
        status: string | null;
    };
}

defineProps<Props>();

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
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const filterByStatus = (status: string) => {
    router.get(route('customer.subscriptions.index'), 
        status ? { status } : {}, 
        { preserveState: true }
    );
};
</script>

<template>
    <Head title="My Subscriptions" />

    <CustomerLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-900">Subscription Saya</h2>
                    <Link 
                        :href="route('customer.subscriptions.create')" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Subscribe Now
                    </Link>
                </div>

                <!-- Filters -->
                <div class="mb-6 flex gap-4 flex-wrap">
                    <select
                        @change="filterByStatus(($event.target as HTMLSelectElement).value)"
                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <!-- Subscriptions List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="subscriptions.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada subscription</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai berlangganan untuk mengakses fitur ERP.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('customer.subscriptions.create')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Subscribe Now
                                </Link>
                            </div>
                        </div>

                        <div v-else class="space-y-4">
                            <div 
                                v-for="subscription in subscriptions" 
                                :key="subscription.id"
                                class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ subscription.plan_name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ formatDate(subscription.start_date) }} - {{ formatDate(subscription.end_date) }}
                                        </p>
                                    </div>
                                    <span :class="getStatusColor(subscription.status)" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        {{ subscription.status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Harga</p>
                                        <p class="text-base font-semibold text-gray-900">{{ formatCurrency(subscription.price) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Auto Renew</p>
                                        <p class="text-base font-semibold" :class="subscription.auto_renew ? 'text-green-600' : 'text-gray-400'">
                                            {{ subscription.auto_renew ? 'Yes' : 'No' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <Link
                                        :href="route('customer.subscriptions.show', subscription.id)"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        View Details
                                    </Link>
                                    <button
                                        v-if="subscription.status === 'active'"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        Cancel Subscription
                                    </button>
                                    <button
                                        v-if="subscription.status === 'expired'"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        Renew Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
