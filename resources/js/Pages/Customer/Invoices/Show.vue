<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Invoice } from '@/types';

interface Props {
    invoice: Invoice;
}

defineProps<Props>();

const statusLabels: Record<string, string> = {
    pending: 'Menunggu Pembayaran',
    paid: 'Lunas',
    overdue: 'Jatuh Tempo',
    cancelled: 'Dibatalkan',
};

const statusColors: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    paid: 'bg-green-100 text-green-800',
    overdue: 'bg-red-100 text-red-800',
    cancelled: 'bg-gray-100 text-gray-800',
};
</script>

<template>
    <Head title="Detail Invoice" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Detail Invoice
                            </h2>
                            <Link
                                :href="route('customer.invoices.index')"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                            >
                                ← Kembali ke Daftar Invoice
                            </Link>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-6">
                            <span :class="['px-3 py-1 text-sm rounded-full', statusColors[invoice.status]]">
                                {{ statusLabels[invoice.status] }}
                            </span>
                        </div>

                        <!-- Invoice Layout -->
                        <div class="border border-gray-200 rounded-lg p-6 mb-6">
                            <!-- Header Invoice -->
                            <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">INVOICE</h1>
                                    <p class="text-sm text-gray-500">{{ invoice.invoice_number }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-indigo-600">Cooca.id</p>
                                    <p class="text-xs text-gray-500">Platform SaaS ERP</p>
                                </div>
                            </div>

                            <!-- Info Customer & Tanggal -->
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">Kepada:</h3>
                                    <p class="text-sm text-gray-900">{{ invoice.customer_name }}</p>
                                    <p class="text-sm text-gray-600">{{ invoice.customer_email }}</p>
                                    <p class="text-sm text-gray-600">{{ invoice.business_name }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-700">Tanggal Invoice: </span>
                                        <span class="text-sm font-medium">{{ new Date(invoice.created_at).toLocaleDateString('id-ID') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-700">Jatuh Tempo: </span>
                                        <span class="text-sm font-medium">{{ new Date(invoice.due_date).toLocaleDateString('id-ID') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Item Details -->
                            <div class="mb-6">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-t border-gray-200">
                                            <td class="px-4 py-3">
                                                <p class="text-sm font-medium text-gray-900">{{ invoice.product_name }}</p>
                                                <p class="text-xs text-gray-500">{{ invoice.plan_name }}</p>
                                                <p class="text-xs text-gray-500">Periode: {{ invoice.period }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm text-gray-900">
                                                Rp {{ invoice.subtotal.toLocaleString('id-ID') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary -->
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-end">
                                    <div class="w-1/2 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Subtotal</span>
                                            <span class="text-gray-900">Rp {{ invoice.subtotal.toLocaleString('id-ID') }}</span>
                                        </div>
                                        <div v-if="invoice.voucher_discount > 0" class="flex justify-between text-sm">
                                            <span class="text-gray-600">Diskon Voucher</span>
                                            <span class="text-red-600">- Rp {{ invoice.voucher_discount.toLocaleString('id-ID') }}</span>
                                        </div>
                                        <div class="flex justify-between text-base font-semibold border-t border-gray-200 pt-2">
                                            <span class="text-gray-900">Total</span>
                                            <span class="text-indigo-600">Rp {{ invoice.amount.toLocaleString('id-ID') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <Link
                                v-if="invoice.status === 'paid'"
                                :href="route('customer.invoices.download', invoice.id)"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </Link>
                            <Link
                                v-if="invoice.status === 'pending' || invoice.status === 'overdue'"
                                :href="route('customer.payments.store', { invoice_id: invoice.id })"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                            >
                                Bayar Sekarang
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
