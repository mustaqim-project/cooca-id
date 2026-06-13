<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

interface Props {
    transaction?: {
        invoice_ref: string;
        product_name: string;
        plan_name: string;
        amount: number;
        payment_method: string;
    };
}

defineProps<Props>();
</script>

<template>
    <Head title="Pembayaran Berhasil" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Success Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <h1 class="text-3xl font-bold text-center text-gray-900 mb-2">
                            Pembayaran Berhasil!
                        </h1>
                        <p class="text-center text-gray-600 mb-8">
                            Terima kasih telah melakukan pembayaran. Transaksi Anda telah berhasil diproses.
                        </p>

                        <!-- Transaction Summary -->
                        <div v-if="transaction" class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Transaksi</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nomor Invoice</span>
                                    <span class="font-medium text-gray-900">{{ transaction.invoice_ref }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Produk</span>
                                    <span class="font-medium text-gray-900">{{ transaction.product_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Plan</span>
                                    <span class="font-medium text-gray-900">{{ transaction.plan_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Metode Pembayaran</span>
                                    <span class="font-medium text-gray-900 capitalize">{{ transaction.payment_method }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3 flex justify-between">
                                    <span class="text-gray-900 font-semibold">Total Dibayar</span>
                                    <span class="text-green-600 font-bold">Rp {{ transaction.amount.toLocaleString('id-ID') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="text-sm font-semibold text-blue-900 mb-2">Langkah Selanjutnya</h3>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Lisensi Anda sedang diproses dan akan aktif dalam beberapa menit</li>
                                <li>• Anda akan menerima email konfirmasi dengan detail akses</li>
                                <li>• Cek halaman Lisensi untuk melihat kredensial akses Anda</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <Link
                                :href="route('customer.invoices.index')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                            >
                                Lihat Invoice
                            </Link>
                            <Link
                                :href="route('customer.dashboard')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300"
                            >
                                Kembali ke Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
