<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import { AffiliateWithdrawal } from '@/types';

interface Props {
    withdrawal: AffiliateWithdrawal;
}

defineProps<Props>();

const statusLabels: Record<string, string> = {
    pending: 'Menunggu Persetujuan',
    approved: 'Disetujui',
    rejected: 'Ditolak',
    paid: 'Sudah Dibayar',
};

const statusColors: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-blue-100 text-blue-800',
    rejected: 'bg-red-100 text-red-800',
    paid: 'bg-green-100 text-green-800',
};
</script>

<template>
    <Head title="Detail Penarikan" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Detail Penarikan
                            </h2>
                            <Link
                                :href="route('affiliator.withdrawals.history')"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                            >
                                ← Kembali ke Riwayat
                            </Link>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-6">
                            <span :class="['px-3 py-1 text-sm rounded-full', statusColors[withdrawal.status]]">
                                {{ statusLabels[withdrawal.status] }}
                            </span>
                        </div>

                        <!-- Info Utama -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Nomor Penarikan</h3>
                                <p class="text-lg font-semibold text-gray-900">{{ withdrawal.reference_number }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Tanggal Pengajuan</h3>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ new Date(withdrawal.created_at).toLocaleDateString('id-ID', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) }}
                                </p>
                            </div>
                        </div>

                        <!-- Rincian Keuangan -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Rincian Keuangan</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jumlah Penarikan</span>
                                    <span class="font-medium text-gray-900">Rp {{ withdrawal.amount.toLocaleString('id-ID') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Biaya Admin</span>
                                    <span class="font-medium text-gray-900">- Rp {{ withdrawal.fee.toLocaleString('id-ID') }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3 flex justify-between">
                                    <span class="text-gray-900 font-semibold">Total Diterima</span>
                                    <span class="text-indigo-600 font-bold">Rp {{ withdrawal.net_amount.toLocaleString('id-ID') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</h3>
                            <p class="text-gray-900 capitalize">{{ withdrawal.method === 'bank' ? 'Transfer Bank' : 'E-Wallet' }}</p>
                            <div class="mt-2 text-sm text-gray-600">
                                <p>{{ withdrawal.bank_name }}</p>
                                <p>{{ withdrawal.account_number }}</p>
                                <p>a.n {{ withdrawal.account_holder }}</p>
                            </div>
                        </div>

                        <!-- Timeline Status -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline Status</h3>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Pengajuan Dibuat</p>
                                        <p class="text-xs text-gray-500">
                                            {{ new Date(withdrawal.created_at).toLocaleDateString('id-ID', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            }) }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="withdrawal.approved_at" class="flex items-start gap-3">
                                    <div class="w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Disetujui</p>
                                        <p class="text-xs text-gray-500">
                                            {{ new Date(withdrawal.approved_at).toLocaleDateString('id-ID', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            }) }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="withdrawal.paid_at" class="flex items-start gap-3">
                                    <div class="w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Sudah Dibayar</p>
                                        <p class="text-xs text-gray-500">
                                            {{ new Date(withdrawal.paid_at).toLocaleDateString('id-ID', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            }) }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="withdrawal.status === 'rejected' && withdrawal.rejection_reason" class="flex items-start gap-3">
                                    <div class="w-2 h-2 mt-2 rounded-full bg-red-500"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Ditolak</p>
                                        <p class="text-xs text-gray-500">
                                            Alasan: {{ withdrawal.rejection_reason }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan jika ditolak -->
                        <div v-if="withdrawal.rejection_reason" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h3 class="text-sm font-medium text-red-800 mb-2">Alasan Penolakan</h3>
                            <p class="text-sm text-red-700">{{ withdrawal.rejection_reason }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>
