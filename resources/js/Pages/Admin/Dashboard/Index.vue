<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
defineOptions({ layout: AdminLayout });
import { Head, Link } from "@inertiajs/vue3";
import StatCards from "@/Components/ui/StatCards.vue";

interface Props {
    auth: {
        user: {
            id: string;
            name: string;
            email: string;
        };
    };
    stats: {
        totalCustomers: number;
        totalAffiliators: number;
        activeLicenses: number;
        monthlyRevenue: number;
        revenueChange: number;
    };
    recentTransactions?: any[];
}

const props = defineProps<Props>();

const statsData = [
    {
        title: "Total Customers",
        value: props.stats.totalCustomers,
        change: 12,
        icon: "users",
        color: "indigo" as const,
    },
    {
        title: "Total Affiliators",
        value: props.stats.totalAffiliators,
        change: 8,
        icon: "user-plus",
        color: "green" as const,
    },
    {
        title: "Active Licenses",
        value: props.stats.activeLicenses,
        change: 15,
        icon: "key",
        color: "blue" as const,
    },
    {
        title: "Monthly Revenue",
        value: `Rp ${Number(props.stats.monthlyRevenue).toLocaleString("id-ID")}`,
        change: props.stats.revenueChange,
        icon: "currency-dollar",
        color: "yellow" as const,
    },
];

const transactionColumns = [
    { key: "invoice_number", label: "Invoice #", sortable: true },
    {
        key: "customer_name",
        label: "Customer",
        sortable: true,
    },
    {
        key: "gross_amount",
        label: "Amount",
        sortable: true,
        format: (value: number) => `Rp ${value.toLocaleString("id-ID")}`,
    },
    {
        key: "status",
        label: "Status",
        sortable: true,
        format: (value: string) => {
            const statusMap: Record<string, string> = {
                pending: "Pending",
                paid: "Paid",
                failed: "Failed",
                refunded: "Refunded",
            };
            return statusMap[value] || value;
        },
    },
    {
        key: "paid_at",
        label: "Paid At",
        sortable: true,
        format: (value: string | null) =>
            value ? new Date(value).toLocaleDateString("id-ID") : "-",
    },
];
</script>

<template>
    <div>
        <Head title="Dashboard" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">
                Welcome back, {{ auth.user.name }}!
            </p>
        </div>

        <StatCards :stats="statsData" />

        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    Recent Transactions
                </h2>
                <Link
                    href="/admin/transactions"
                    class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"
                >
                    View All →
                </Link>
            </div>

            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    v-for="column in transactionColumns"
                                    :key="column.key"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ column.label }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-if="
                                    !recentTransactions ||
                                    recentTransactions.length === 0
                                "
                            >
                                <td
                                    :colspan="transactionColumns.length"
                                    class="px-6 py-8 text-center text-gray-500"
                                >
                                    No recent transactions
                                </td>
                            </tr>
                            <tr
                                v-for="transaction in recentTransactions"
                                :key="transaction.id"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ transaction.invoice_number }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ transaction.customer?.name || "-" }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    Rp
                                    {{ Number(transaction.gross_amount).toLocaleString('id-ID") }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="{
                                            'px-2 py-1 text-xs font-semibold rounded-full': true,
                                            'bg-yellow-100 text-yellow-800':
                                                transaction.status ===
                                                'pending',
                                            'bg-green-100 text-green-800':
                                                transaction.status === 'paid',
                                            'bg-red-100 text-red-800':
                                                transaction.status === 'failed',
                                            'bg-blue-100 text-blue-800':
                                                transaction.status ===
                                                'refunded',
                                        }"
                                    >
                                        {{ transaction.status }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{
                                        transaction.paid_at
                                            ? new Date(
                                                  transaction.paid_at,
                                              ).toLocaleDateString("id-ID")
                                            : "-"
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
