<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { License } from '@/types';

interface Props {
    license: License;
}

defineProps<Props>();

const showLicenseCode = ref(false);
const showTokenCode = ref(false);

const copyToClipboard = async (text: string, type: string) => {
    try {
        await navigator.clipboard.writeText(text);
        alert(`${type} berhasil disalin ke clipboard!`);
    } catch (err) {
        alert('Gagal menyalin ke clipboard');
    }
};
</script>

<template>
    <Head title="Kredensial Lisensi" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Kredensial Lisensi
                            </h2>
                            <Link
                                :href="route('customer.licenses.show', license.id)"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                            >
                                ← Kembali ke Detail Lisensi
                            </Link>
                        </div>

                        <!-- Info Produk -->
                        <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-indigo-700">Produk</h3>
                                    <p class="text-lg font-semibold text-indigo-900">{{ license.product_name }}</p>
                                </div>
                                <div class="text-right">
                                    <h3 class="text-sm font-medium text-indigo-700">Domain</h3>
                                    <p class="text-lg font-semibold text-indigo-900">{{ license.domain || '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- URL ERP -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">URL Akses ERP</h3>
                            <div class="flex items-center justify-between">
                                <p class="text-lg font-mono text-gray-900">
                                    https://{{ license.subdomain }}.cooca.id
                                </p>
                                <button
                                    @click="copyToClipboard(`https://${license.subdomain}.cooca.id`, 'URL ERP')"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                >
                                    Salin
                                </button>
                            </div>
                        </div>

                        <!-- License Code -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">License Code</h3>
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-mono text-gray-900">
                                    {{ showLicenseCode ? license.license_code : '****-****-****-' + license.license_code?.substring(license.license_code.length - 4) }}
                                </p>
                                <div class="flex gap-2">
                                    <button
                                        @click="showLicenseCode = !showLicenseCode"
                                        class="text-gray-600 hover:text-gray-900 text-sm"
                                    >
                                        {{ showLicenseCode ? 'Sembunyikan' : 'Lihat' }}
                                    </button>
                                    <button
                                        v-if="showLicenseCode"
                                        @click="copyToClipboard(license.license_code, 'License Code')"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm"
                                    >
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Token Code -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Token Code</h3>
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-mono text-gray-900">
                                    {{ showTokenCode ? license.token_code : '****-****-****-' + license.token_code?.substring(license.token_code.length - 4) }}
                                </p>
                                <div class="flex gap-2">
                                    <button
                                        @click="showTokenCode = !showTokenCode"
                                        class="text-gray-600 hover:text-gray-900 text-sm"
                                    >
                                        {{ showTokenCode ? 'Sembunyikan' : 'Lihat' }}
                                    </button>
                                    <button
                                        v-if="showTokenCode"
                                        @click="copyToClipboard(license.token_code, 'Token Code')"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm"
                                    >
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Peringatan Keamanan -->
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-yellow-800">Peringatan Keamanan</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Jangan pernah membagikan kode lisensi dan token ini kepada siapapun. 
                                        Kode ini digunakan untuk mengaktifkan dan mengakses sistem ERP Anda. 
                                        Jika terjadi kebocoran, segera hubungi tim support kami.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-6 flex gap-3">
                            <Link
                                :href="route('customer.licenses.show', license.id)"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300"
                            >
                                Kembali
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
