<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Props {
    stats?: {
        total_views: number;
        total_clicks: number;
        conversion_rate: number;
    };
}

defineProps<Props>();
</script>

<template>
    <Head title="Marketing Materials" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Materi Marketing</h2>
                    <p class="mt-1 text-sm text-gray-600">Gunakan materi promosi ini untuk meningkatkan konversi referral Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Banner Card -->
                    <Link :href="route('affiliator.marketing_materials.banners')" class="block">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Banner Promosi</h3>
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600">
                                    Download banner promosi dalam berbagai ukuran (1080x1080, 1200x628, 1080x1920) untuk media sosial dan website Anda.
                                </p>
                            </div>
                        </div>
                    </Link>

                    <!-- Links Card -->
                    <Link :href="route('affiliator.marketing_materials.links')" class="block">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Link Referral</h3>
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600">
                                    Dapatkan link referral unik untuk setiap produk dengan kode affiliate Anda sudah terpasang otomatis.
                                </p>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Tips Section -->
                <div class="mt-8 bg-indigo-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-indigo-900 mb-4">Tips Promosi Efektif</h3>
                    <ul class="space-y-2 text-sm text-indigo-800">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 text-indigo-600 mr-2">✓</span>
                            Bagikan banner di media sosial dengan caption yang menarik
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 text-indigo-600 mr-2">✓</span>
                            Gunakan link referral di signature email dan bio media sosial
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 text-indigo-600 mr-2">✓</span>
                            Buat konten review atau tutorial tentang produk ERP Cooca.id
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-5 h-5 text-indigo-600 mr-2">✓</span>
                            Targetkan audiens yang tepat: pemilik bisnis, manajer IT, startup
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>
