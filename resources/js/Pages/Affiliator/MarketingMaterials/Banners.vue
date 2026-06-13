<script setup lang="ts">
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Banner {
    id: number;
    name: string;
    sizes: {
        size: string;
        url: string;
        width: number;
        height: number;
    }[];
}

interface Props {
    banners: Banner[];
}

defineProps<Props>();

const downloadBanner = (url: string, name: string) => {
    const link = document.createElement('a');
    link.href = url;
    link.download = name;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head title="Banner Promosi" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Banner Promosi</h2>
                    <p class="mt-1 text-sm text-gray-600">Download banner dalam berbagai ukuran untuk kebutuhan promosi Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="banner in banners" :key="banner.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ banner.name }}</h3>
                            
                            <!-- Preview placeholder -->
                            <div class="bg-gray-100 rounded-lg h-48 mb-4 flex items-center justify-center">
                                <span class="text-gray-400 text-sm">Preview Banner</span>
                            </div>

                            <div class="space-y-3">
                                <div v-for="sizeOption in banner.sizes" :key="sizeOption.size" class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ sizeOption.size }}</div>
                                        <div class="text-xs text-gray-500">{{ sizeOption.width }} x {{ sizeOption.height }} px</div>
                                    </div>
                                    <button
                                        @click="downloadBanner(sizeOption.url, `${banner.name}-${sizeOption.size}.png`)"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                    >
                                        Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!banners || banners.length === 0" class="text-center py-12">
                    <p class="text-gray-500">Belum ada banner tersedia.</p>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>
