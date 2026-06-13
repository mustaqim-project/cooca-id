<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head } from '@inertiajs/vue3';

interface Payment {
    id: number;
    invoice_number: string;
    amount: number;
    status: 'pending' | 'paid' | 'failed';
    payment_method: string | null;
    midtrans_response: Record<string, any> | null;
    paid_at: string | null;
}

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
    payments: Payment[];
}

interface Props {
    transaction: Transaction;
}

defineProps<Props>();

const refundForm = useForm({
    reason: '',
});

const formatDate = (dateString: string | null) => {
    if (!dateString) return '-';
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

const submitRefund = () => {
    if (confirm(`Apakah Anda yakin ingin refund transaksi ini?\n\nAlasan: ${refundForm.reason}`)) {
        refundForm.post(route('admin.transactions.refund', props.transaction.id), {
            onSuccess: () => {
                refundForm.reset();
            },
        });
    }
};
</script>

<template>
    <Head :title="`Transaksi ${transaction.invoice_ref}`" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900">Detail Transaksi</h2>
                                <p class="mt-1 text-sm text-gray-600">{{ transaction.invoice_ref }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    :class="getStatusColor(transaction.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                >
                                    {{ transaction.status }}
                                </span>
                                <SecondaryButton @click="router.get(route('admin.transactions.index'))">
                                    Kembali
                                </SecondaryButton>
                            </div>
                        </div>

                        <!-- Transaction Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ transaction.customer_name }}</dd>
                                        <dd class="text-xs text-gray-500">{{ transaction.customer_email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Produk & Plan</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ transaction.product_name }}</dd>
                                        <dd class="text-xs text-gray-500 capitalize">{{ transaction.plan }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Transaksi</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(transaction.created_at) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ transaction.payment_method || '-' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Amount Breakdown -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Breakdown Jumlah</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Gross Amount</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ formatCurrency(transaction.gross_amount) }}</dd>
                                </div>
                                <div v-if="transaction.voucher_discount > 0" class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Voucher Discount</dt>
                                    <dd class="text-sm font-medium text-red-600">- {{ formatCurrency(transaction.voucher_discount) }}</dd>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <dt class="text-base font-medium text-gray-900">Total Amount</dt>
                                    <dd class="text-base font-bold text-gray-900">{{ formatCurrency(transaction.amount) }}</dd>
                                </div>
                            </dl>
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Bayar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-if="transaction.payments.length === 0">
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada pembayaran</td>
                                        </tr>
                                        <tr v-for="payment in transaction.payments" :key="payment.id">
                                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ payment.invoice_number }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ formatCurrency(payment.amount) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 capitalize">{{ payment.payment_method || '-' }}</td>
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

                        <!-- Midtrans Response (if available) -->
                        <div v-if="transaction.payments.length > 0 && transaction.payments[0].midtrans_response" class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Midtrans Response</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <pre class="text-xs text-gray-700 overflow-x-auto">{{ JSON.stringify(transaction.payments[0].midtrans_response, null, 2) }}</pre>
                            </div>
                        </div>

                        <!-- Refund Action -->
                        <div v-if="transaction.status === 'paid'" class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Refund Transaksi</h3>
                            <form @submit.prevent="submitRefund" class="space-y-4">
                                <div>
                                    <InputLabel for="reason" value="Alasan Refund" />
                                    <Textarea
                                        id="reason"
                                        v-model="refundForm.reason"
                                        class="mt-1 block w-full min-h-[100px]"
                                        required
                                        placeholder="Jelaskan alasan refund..."
                                    />
                                    <InputError class="mt-2" :message="refundForm.errors.reason" />
                                </div>
                                <PrimaryButton
                                    :class="{ 'opacity-25': refundForm.processing }"
                                    :disabled="refundForm.processing"
                                    class="bg-red-600 hover:bg-red-700"
                                >
                                    Proses Refund
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
