<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Payment {
    id: number;
    invoice_number: string;
    amount: number;
    status: 'pending' | 'paid' | 'failed';
    paid_at: string | null;
}

interface License {
    id: number;
    license_code: string;
    domain: string;
    status: 'active' | 'expired' | 'revoked';
}

interface Subscription {
    id: number;
    customer_name: string;
    customer_email: string;
    product_name: string;
    plan: string;
    status: 'active' | 'expired' | 'cancelled';
    start_date: string;
    end_date: string;
    price: number;
    payments: Payment[];
    licenses: License[];
}

interface Props {
    subscription: Subscription;
}

defineProps<Props>();

const formatDate = (dateString: string | null) => {
    if (!dateString) return '-';
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

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'expired':
            return 'bg-red-100 text-red-800';
        case 'cancelled':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-yellow-100 text-yellow-800';
    }
};

const cancelSubscription = () => {
    if (confirm('Apakah Anda yakin ingin membatalkan subscription ini? Customer akan tetap memiliki akses hingga periode berakhir.')) {
        router.post(route('admin.subscriptions.cancel', props.subscription.id));
    }
};
</script>

<template>
    <Head :title="`Subscription ${subscription.customer_name}`" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900">Detail Subscription</h2>
                                <p class="mt-1 text-sm text-gray-600">{{ subscription.customer_name }} - {{ subscription.product_name }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    :class="getStatusColor(subscription.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                >
                                    {{ subscription.status }}
                                </span>
                                <SecondaryButton @click="router.get(route('admin.subscriptions.index'))">
                                    Kembali
                                </SecondaryButton>
                            </div>
                        </div>

                        <!-- Subscription Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ subscription.customer_name }}</dd>
                                        <dd class="text-xs text-gray-500">{{ subscription.customer_email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Produk & Plan</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ subscription.product_name }}</dd>
                                        <dd class="text-xs text-gray-500 capitalize">{{ subscription.plan }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Harga</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ formatCurrency(subscription.price) }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(subscription.start_date) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Berakhir</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(subscription.end_date) }}</dd>
                                    </div>
                                    <div v-if="subscription.status === 'active'">
                                        <dt class="text-sm font-medium text-gray-500">Sisa Hari</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ Math.max(0, Math.ceil((new Date(subscription.end_date).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24))) }} hari
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Payment History -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pembayaran</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Bayar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-if="subscription.payments.length === 0">
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada pembayaran</td>
                                        </tr>
                                        <tr v-for="payment in subscription.payments" :key="payment.id">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ payment.invoice_number }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ formatCurrency(payment.amount) }}</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                                        'bg-green-100 text-green-800': payment.status === 'paid',
                                                        'bg-red-100 text-red-800': payment.status === 'failed',
                                                    }"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                                >
                                                    {{ payment.status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(payment.paid_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Licenses -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">License Terkait</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">License Code</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-if="subscription.licenses.length === 0">
                                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada license</td>
                                        </tr>
                                        <tr v-for="license in subscription.licenses" :key="license.id">
                                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ license.license_code }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ license.domain || '-' }}</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    :class="{
                                                        'bg-green-100 text-green-800': license.status === 'active',
                                                        'bg-red-100 text-red-800': license.status === 'expired',
                                                        'bg-gray-100 text-gray-800': license.status === 'revoked',
                                                    }"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                                >
                                                    {{ license.status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div v-if="subscription.status === 'active'" class="border-t border-gray-200 pt-6">
                            <PrimaryButton @click="cancelSubscription" class="bg-red-600 hover:bg-red-700">
                                Cancel Subscription
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
