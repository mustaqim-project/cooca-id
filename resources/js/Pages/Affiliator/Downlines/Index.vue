<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Downline {
    id: number;
    name: string;
    email: string;
    referrals_count: number;
    total_commission_l2: number;
}

interface Summary {
    total_downlines: number;
    total_commission_l2: number;
}

interface Props {
    downlines: Downline[];
    summary: Summary;
}

defineProps<Props>();

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Downlines" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <dt class="text-sm font-medium text-gray-500">Total Downline Level 1</dt>
                            <dd class="mt-2 text-3xl font-bold text-gray-900">{{ summary.total_downlines }}</dd>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <dt class="text-sm font-medium text-gray-500">Total Komisi Level 2</dt>
                            <dd class="mt-2 text-3xl font-bold text-indigo-600">{{ formatCurrency(summary.total_commission_l2) }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Downlines Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Daftar Downline</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah Referral
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Komisi L2
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="downlines.length === 0">
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <p class="text-gray-500">Belum ada downline.</p>
                                        </td>
                                    </tr>
                                    <tr v-for="downline in downlines" :key="downline.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ downline.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ downline.email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ downline.referrals_count }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-indigo-600">{{ formatCurrency(downline.total_commission_l2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('affiliator.downlines.show', downline.id)"
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
    </AffiliatorLayout>
</template>
