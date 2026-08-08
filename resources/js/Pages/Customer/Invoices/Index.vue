<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Invoice } from "@/types";

interface Props {
    invoices: Invoice[];
}

defineProps<Props>();

const statusLabels: Record<string, string> = {
    pending: "Menunggu Pembayaran",
    paid: "Lunas",
    overdue: "Jatuh Tempo",
    cancelled: "Dibatalkan",
};

const statusColors: Record<string, string> = {
    pending: "bg-yellow-100 text-yellow-800",
    paid: "bg-green-100 text-green-800",
    overdue: "bg-red-100 text-red-800",
    cancelled: "bg-gray-100 text-gray-800",
};
</script>

<template>
    <Head title="Daftar Invoice" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800">
                            Daftar Invoice
                        </h2>

                        <div
                            v-if="invoices.length === 0"
                            class="text-center py-12"
                        >
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                Belum ada invoice
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Anda belum memiliki invoice. Silakan
                                berlangganan produk terlebih dahulu.
                            </p>
                            <div class="mt-6">
                                <Link
                                    :href="route('customer.products.index')"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                >
                                    Lihat Produk
                                </Link>
                            </div>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Nomor Invoice
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Produk / Plan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Jumlah
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Jatuh Tempo
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="invoice in invoices"
                                        :key="invoice.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ invoice.invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ invoice.product_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ invoice.plan_name }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            Rp
                                            {{ invoice.amount.toLocaleString('id-ID") }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ new Date(invoice.due_date).toLocaleDateString('id-ID") }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="[
                                                    'px-2 py-1 text-xs rounded-full',
                                                    statusColors[
                                                        invoice.status
                                                    ],
                                                ]"
                                            >
                                                {{
                                                    statusLabels[invoice.status]
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <div class="flex justify-end gap-2">
                                                <Link
                                                    :href="
                                                        route(
                                                            'customer.invoices.show',
                                                            invoice.id,
                                                        )
                                                    "
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    Lihat
                                                </Link>
                                                <Link
                                                    v-if="
                                                        invoice.status ===
                                                        'paid'
                                                    "
                                                    :href="
                                                        route(
                                                            'customer.invoices.download',
                                                            invoice.id,
                                                        )
                                                    "
                                                    class="text-gray-600 hover:text-gray-900"
                                                >
                                                    PDF
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
