<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { License } from '@/types';

interface Props {
    license: License;
}

const props = defineProps<Props>();

const showCode = ref(false);

const statusLabels: Record<string, string> = {
    active: 'Aktif',
    expired: 'Kedaluwarsa',
    revoked: 'Dicabut',
    pending: 'Menunggu Aktivasi',
};

const statusColors: Record<string, string> = {
    active: 'bg-green-100 text-green-800',
    expired: 'bg-red-100 text-red-800',
    revoked: 'bg-gray-100 text-gray-800',
    pending: 'bg-yellow-100 text-yellow-800',
};

const maskedCode = (code: string) => {
    if (!code) return '';
    return code.substring(0, 4) + '-****-****-' + code.substring(code.length - 4);
};

const toggleCodeVisibility = () => {
    showCode.value = !showCode.value;
};
</script>

<template>
    <Head title="Detail Lisensi" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Detail Lisensi
                            </h2>
                            <Link
                                :href="route('customer.licenses.index')"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                            >
                                ← Kembali ke Daftar Lisensi
                            </Link>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-6">
                            <span :class="['px-3 py-1 text-sm rounded-full', statusColors[license.status]]">
                                {{ statusLabels[license.status] }}
                            </span>
                        </div>

                        <!-- Info Utama -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Produk</h3>
                                <p class="text-lg font-semibold text-gray-900">{{ license.product_name }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Domain Terikat</h3>
                                <p class="text-lg font-semibold text-gray-900">{{ license.domain || '-' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Tanggal Aktivasi</h3>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ license.activated_at ? new Date(license.activated_at).toLocaleDateString('id-ID') : '-' }}
                                </p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Tanggal Kedaluwarsa</h3>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ license.expired_at ? new Date(license.expired_at).toLocaleDateString('id-ID') : 'Tidak ada' }}
                                </p>
                            </div>
                        </div>

                        <!-- License Code -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Kode Lisensi</h3>
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-mono text-gray-900">
                                    {{ showCode ? license.license_code : maskedCode(license.license_code) }}
                                </p>
                                <button
                                    @click="toggleCodeVisibility"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                >
                                    {{ showCode ? 'Sembunyikan' : 'Lihat' }}
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <Link
                                v-if="license.status === 'pending'"
                                :href="route('customer.licenses.activate', license.id)"
                                method="post"
                                as="button"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Aktivasi Lisensi
                            </Link>
                            <Link
                                :href="route('customer.licenses.credentials', license.id)"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Lihat Kredensial
                            </Link>
                        </div>

                        <!-- Peringatan -->
                        <div v-if="license.status === 'expired'" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800">
                                ⚠️ Lisensi ini telah kedaluwarsa. Silakan perpanjang langganan Anda untuk继续使用.
                            </p>
                        </div>
                        <div v-if="license.status === 'revoked'" class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-800">
                                ℹ️ Lisensi ini telah dicabut oleh administrator. Hubungi support untuk informasi lebih lanjut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
