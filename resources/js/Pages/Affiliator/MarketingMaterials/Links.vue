<script setup lang="ts">
import { ref } from 'vue';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import { Head } from '@inertiajs/vue3';

interface ReferralLink {
    id: number;
    product_name: string;
    url: string;
    clicks: number;
    conversions: number;
}

interface Props {
    links: ReferralLink[];
    affiliateCode: string;
}

defineProps<Props>();

const copiedId = ref<number | null>(null);

const copyToClipboard = async (text: string, id: number) => {
    try {
        await navigator.clipboard.writeText(text);
        copiedId.value = id;
        setTimeout(() => {
            copiedId.value = null;
        }, 2000);
    } catch (err) {
        console.error('Gagal menyalin teks');
    }
};

const generateQRCode = (url: string) => {
    // Menggunakan API QR Code gratis dari qrserver.com
    return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(url)}`;
};
</script>

<template>
    <Head title="Link Referral" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Link Referral</h2>
                    <p class="mt-1 text-sm text-gray-600">Bagikan link ini untuk mendapatkan komisi dari setiap referral.</p>
                </div>

                <!-- Affiliate Code Info -->
                <div class="bg-indigo-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <dt class="text-sm font-medium text-indigo-900">Kode Affiliate Anda</dt>
                            <dd class="mt-1 text-2xl font-bold text-indigo-600 font-mono">{{ affiliateCode }}</dd>
                        </div>
                        <button
                            @click="copyToClipboard(affiliateCode, -1)"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium"
                        >
                            {{ copiedId === -1 ? 'Tersalin!' : 'Salin Kode' }}
                        </button>
                    </div>
                </div>

                <!-- Links Grid -->
                <div class="grid grid-cols-1 gap-6">
                    <div v-for="link in links" :key="link.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ link.product_name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 font-mono break-all">{{ link.url }}</p>
                                </div>
                                <button
                                    @click="copyToClipboard(link.url, link.id)"
                                    class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium whitespace-nowrap"
                                >
                                    {{ copiedId === link.id ? 'Tersalin!' : 'Salin Link' }}
                                </button>
                            </div>

                            <div class="flex items-center gap-6 mb-4">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 uppercase">Klik</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ link.clicks }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 uppercase">Konversi</dt>
                                    <dd class="mt-1 text-lg font-semibold text-green-600">{{ link.conversions }}</dd>
                                </div>
                                <div v-if="link.clicks > 0">
                                    <dt class="text-xs font-medium text-gray-500 uppercase">Conversion Rate</dt>
                                    <dd class="mt-1 text-lg font-semibold text-indigo-600">{{ ((link.conversions / link.clicks) * 100).toFixed(1) }}%</dd>
                                </div>
                            </div>

                            <!-- QR Code -->
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex items-center gap-4">
                                    <img
                                        :src="generateQRCode(link.url)"
                                        :alt="`QR Code ${link.product_name}`"
                                        class="w-24 h-24"
                                    />
                                    <div>
                                        <p class="text-sm text-gray-600">Scan QR code ini untuk membuka link referral</p>
                                        <p class="text-xs text-gray-500 mt-1">Cocok untuk materi promosi cetak atau offline</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!links || links.length === 0" class="text-center py-12">
                    <p class="text-gray-500">Belum ada link referral tersedia.</p>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>
