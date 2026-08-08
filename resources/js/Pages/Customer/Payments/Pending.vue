<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import CustomerLayout from "@/Layouts/CustomerLayout.vue";

interface Props {
    transaction?: {
        invoice_ref: string;
        product_name: string;
        amount: number;
        payment_method?: string;
    };
    va_number?: string;
    expiry_time?: string;
}

defineProps<Props>();
</script>

<template>
    <Head title="Pembayaran Menunggu" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Pending Icon -->
                        <div class="flex justify-center mb-6">
                            <div
                                class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center"
                            >
                                <svg
                                    class="w-12 h-12 text-yellow-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Pending Message -->
                        <h1
                            class="text-3xl font-bold text-center text-gray-900 mb-2"
                        >
                            Menunggu Pembayaran
                        </h1>
                        <p class="text-center text-gray-600 mb-8">
                            Pembayaran Anda sedang menunggu konfirmasi. Silakan
                            selesaikan pembayaran sesuai instruksi di bawah.
                        </p>

                        <!-- Transaction Info -->
                        <div
                            v-if="transaction"
                            class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-lg"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Detail Transaksi
                            </h2>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600"
                                        >Nomor Invoice</span
                                    >
                                    <span class="font-medium text-gray-900">{{
                                        transaction.invoice_ref
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Produk</span>
                                    <span class="font-medium text-gray-900">{{
                                        transaction.product_name
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total</span>
                                    <span class="font-medium text-gray-900"
                                        >Rp
                                        {{ transaction.amount.toLocaleString('id-ID") }}</span
                                    >
                                </div>
                                <div
                                    v-if="transaction.payment_method"
                                    class="flex justify-between"
                                >
                                    <span class="text-gray-600">Metode</span>
                                    <span
                                        class="font-medium text-gray-900 capitalize"
                                        >{{ transaction.payment_method }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div
                            v-if="va_number"
                            class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-lg"
                        >
                            <h3
                                class="text-lg font-semibold text-blue-900 mb-4"
                            >
                                Instruksi Pembayaran
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-blue-700 mb-2">
                                        Virtual Account Number:
                                    </p>
                                    <div
                                        class="flex items-center justify-between p-3 bg-white rounded border border-blue-200"
                                    >
                                        <code
                                            class="text-lg font-mono text-blue-900"
                                            >{{ va_number }}</code
                                        >
                                        <button
                                            @click="
                                                navigator.clipboard.writeText(
                                                    va_number,
                                                );
                                                alert('Nomor VA disalin!');
                                            "
                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                        >
                                            Salin
                                        </button>
                                    </div>
                                </div>
                                <div
                                    v-if="expiry_time"
                                    class="pt-4 border-t border-blue-200"
                                >
                                    <p class="text-sm text-blue-700">
                                        <span class="font-semibold"
                                            >Batas waktu pembayaran:</span
                                        ><br />
                                        {{ expiry_time }}
                                    </p>
                                </div>
                                <div class="pt-4 border-t border-blue-200">
                                    <ol
                                        class="text-sm text-blue-800 space-y-2 list-decimal list-inside"
                                    >
                                        <li>
                                            Login ke mobile banking atau ATM
                                            bank Anda
                                        </li>
                                        <li>
                                            Pilih menu Transfer / Pembayaran
                                        </li>
                                        <li>
                                            Masukkan nomor Virtual Account di
                                            atas
                                        </li>
                                        <li>
                                            Masukkan jumlah pembayaran sesuai
                                            tagihan
                                        </li>
                                        <li>
                                            Konfirmasi transaksi dan simpan
                                            bukti pembayaran
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col sm:flex-row gap-3 justify-center"
                        >
                            <button
                                @click="window.location.reload()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                            >
                                Cek Status
                            </button>
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
