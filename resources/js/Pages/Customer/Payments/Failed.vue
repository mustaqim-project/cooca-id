<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

interface Props {
    transaction?: {
        invoice_ref: string;
        product_name: string;
        amount: number;
    };
    error_message?: string;
}

defineProps<Props>();
</script>

<template>
    <Head title="Pembayaran Gagal" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Failed Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>

                        <!-- Failed Message -->
                        <h1 class="text-3xl font-bold text-center text-gray-900 mb-2">
                            Pembayaran Gagal
                        </h1>
                        <p class="text-center text-gray-600 mb-8">
                            Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau hubungi support jika masalah berlanjut.
                        </p>

                        <!-- Transaction Info -->
                        <div v-if="transaction" class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Transaksi</h2>
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
                                    <span class="text-gray-600">Total</span>
                                    <span class="font-medium text-gray-900">Rp {{ transaction.amount.toLocaleString('id-ID') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div v-if="error_message" class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-red-900">Keterangan Kegagalan</h4>
                                    <p class="text-sm text-red-700 mt-1">{{ error_message }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Common Issues -->
                        <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-yellow-900 mb-2">Penyebab Umum Kegagalan:</h4>
                            <ul class="text-sm text-yellow-800 space-y-1">
                                <li>• Saldo tidak mencukupi</li>
                                <li>• Batas waktu pembayaran telah habis</li>
                                <li>• Masalah koneksi ke bank/payment gateway</li>
                                <li>• Data pembayaran tidak valid</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <Link
                                :href="route('customer.subscriptions.create')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                            >
                                Coba Lagi
                            </Link>
                            <Link
                                :href="route('customer.tickets.create')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300"
                            >
                                Hubungi Support
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
