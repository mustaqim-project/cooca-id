<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Transaction {
    id: number;
    invoice_ref: string;
    customer_name: string;
    customer_email: string;
    product_name: string;
    plan: string;
    gross_amount: number;
    amount: number;
    voucher_discount: number;
    status: 'pending' | 'paid' | 'failed' | 'refunded';
    payment_method: string | null;
    created_at: string;
}

interface Props {
    transactions: Transaction[];
    filters: {
        status: string | null;
        date_from: string | null;
        date_to: string | null;
    };
}

defineProps<Props>();

const getStatusColor = (status: string) => {
    switch (status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'paid':
            return 'bg-green-100 text-green-800';
        case 'failed':
            return 'bg-red-100 text-red-800';
        case 'refunded':
            return 'bg-gray-100 text-gray-800';
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
    <Head title="Transaksi" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-2xl font-semibold text-gray-900">Manajemen Transaksi</h2>
                        </div>

                        <!-- Filters -->
                        <div class="mb-6 flex gap-4 flex-wrap">
                            <select
                                v-model="filters.status"
                                @change="router.get(route('admin.transactions.index'), { status: $event.target.value }, { preserveState: true })"
                                class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                            <input
                                type="date"
                                v-model="filters.date_from"
                                @change="router.get(route('admin.transactions.index'), { date_from: $event.target.value }, { preserveState: true })"
                                class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <span class="self-center text-gray-500">s/d</span>
                            <input
                                type="date"
                                v-model="filters.date_to"
                                @change="router.get(route('admin.transactions.index'), { date_to: $event.target.value }, { preserveState: true })"
                                class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoice
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Payment Method
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="transactions.length === 0">
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <p class="text-gray-500">Belum ada transaksi.</p>
                                        </td>
                                    </tr>
                                    <tr v-for="transaction in transactions" :key="transaction.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-mono font-medium text-gray-900">{{ transaction.invoice_ref }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ transaction.customer_name }}</div>
                                            <div class="text-xs text-gray-500">{{ transaction.customer_email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ transaction.product_name }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ transaction.plan }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ formatCurrency(transaction.amount) }}</div>
                                            <div v-if="transaction.voucher_discount > 0" class="text-xs text-gray-500">
                                                Discount: {{ formatCurrency(transaction.voucher_discount) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="getStatusColor(transaction.status)"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                            >
                                                {{ transaction.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 capitalize">{{ transaction.payment_method || '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(transaction.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('admin.transactions.show', transaction.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Lihat Detail
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
