<script setup lang="ts">
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AffiliatorLayout from "@/Layouts/AffiliatorLayout.vue";
import { AffiliateWithdrawal } from "@/types";

interface Props {
    withdrawals: AffiliateWithdrawal[];
}

defineProps<Props>();

const statusFilter = ref("all");
const dateFrom = ref("");
const dateTo = ref("");

const statusLabels: Record<string, string> = {
    pending: "Menunggu",
    approved: "Disetujui",
    rejected: "Ditolak",
    paid: "Sudah Dibayar",
};

const statusColors: Record<string, string> = {
    pending: "bg-yellow-100 text-yellow-800",
    approved: "bg-blue-100 text-blue-800",
    rejected: "bg-red-100 text-red-800",
    paid: "bg-green-100 text-green-800",
};

const filteredWithdrawals = (withdrawals: AffiliateWithdrawal[]) => {
    return withdrawals.filter((w) => {
        if (statusFilter.value !== "all" && w.status !== statusFilter.value) {
            return false;
        }
        if (dateFrom.value) {
            const fromDate = new Date(dateFrom.value);
            const withdrawalDate = new Date(w.created_at);
            if (withdrawalDate < fromDate) {
                return false;
            }
        }
        if (dateTo.value) {
            const toDate = new Date(dateTo.value);
            const withdrawalDate = new Date(w.created_at);
            if (withdrawalDate > toDate) {
                return false;
            }
        }
        return true;
    });
};
</script>

<template>
    <Head title="Riwayat Penarikan" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Riwayat Penarikan
                            </h2>
                            <Link
                                :href="route('affiliator.withdrawals.create')"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                                Ajukan Penarikan
                            </Link>
                        </div>

                        <!-- Filter -->
                        <div
                            class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label
                                        for="status"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Status
                                    </label>
                                    <select
                                        id="status"
                                        v-model="statusFilter"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option value="all">
                                            Semua Status
                                        </option>
                                        <option value="pending">
                                            Menunggu
                                        </option>
                                        <option value="approved">
                                            Disetujui
                                        </option>
                                        <option value="rejected">
                                            Ditolak
                                        </option>
                                        <option value="paid">
                                            Sudah Dibayar
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        for="dateFrom"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Dari Tanggal
                                    </label>
                                    <input
                                        id="dateFrom"
                                        v-model="dateFrom"
                                        type="date"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="dateTo"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Sampai Tanggal
                                    </label>
                                    <input
                                        id="dateTo"
                                        v-model="dateTo"
                                        type="date"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    />
                                </div>
                                <div class="flex items-end">
                                    <button
                                        @click="
                                            statusFilter = 'all';
                                            dateFrom = '';
                                            dateTo = '';
                                        "
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        Reset Filter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel -->
                        <div
                            v-if="filteredWithdrawals(withdrawals).length === 0"
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
                                    d="M9 14l6-6m-5.5.5a.5.5 0 010 1h5a.5.5 0 010 1h-5a.5.5 0 010-1m0 2a.5.5 0 010 1h5a.5.5 0 010 1h-5a.5.5 0 010-1m0 2a.5.5 0 010 1h5a.5.5 0 010 1h-5a.5.5 0 010-1"
                                />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                Belum ada penarikan
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Anda belum pernah mengajukan penarikan dana.
                            </p>
                            <div class="mt-6">
                                <Link
                                    :href="
                                        route('affiliator.withdrawals.create')
                                    "
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                >
                                    Ajukan Penarikan Pertama
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
                                            Nomor Ref
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Tanggal
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Jumlah
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Fee
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Diterima
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Metode
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
                                        v-for="withdrawal in filteredWithdrawals(
                                            withdrawals,
                                        )"
                                        :key="withdrawal.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ withdrawal.reference_number }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ new Date(withdrawal.created_at).toLocaleDateString('id-ID") }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            Rp
                                            {{ withdrawal.amount.toLocaleString('id-ID") }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            Rp
                                            {{ withdrawal.fee.toLocaleString('id-ID") }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600"
                                        >
                                            Rp
                                            {{ withdrawal.net_amount.toLocaleString('id-ID") }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize"
                                        >
                                            {{
                                                withdrawal.method === "bank"
                                                    ? "Bank"
                                                    : "E-Wallet"
                                            }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="[
                                                    'px-2 py-1 text-xs rounded-full',
                                                    statusColors[
                                                        withdrawal.status
                                                    ],
                                                ]"
                                            >
                                                {{
                                                    statusLabels[
                                                        withdrawal.status
                                                    ]
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'affiliator.withdrawals.show',
                                                        withdrawal.id,
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Detail
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
    </AffiliatorLayout>
</template>
