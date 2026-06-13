<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head } from '@inertiajs/vue3';

interface Page {
    id: number;
    title: string;
    slug: string;
    content: string;
    meta_title: string | null;
    meta_description: string | null;
    status: 'draft' | 'published';
    created_at: string;
    updated_at: string;
}

interface Props {
    page: Page;
}

defineProps<Props>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="page.title" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900">{{ page.title }}</h2>
                                <p class="mt-1 text-sm text-gray-600">Slug: /{{ page.slug }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    :class="page.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                >
                                    {{ page.status === 'published' ? 'Published' : 'Draft' }}
                                </span>
                                <SecondaryButton @click="router.get(route('admin.cms.pages.edit', page.id))">
                                    Edit
                                </SecondaryButton>
                                <PrimaryButton @click="router.get(route('admin.cms.pages.index'))">
                                    Kembali
                                </PrimaryButton>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatDate(page.created_at) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diubah</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatDate(page.updated_at) }}</dd>
                                </div>
                                <div v-if="page.meta_title">
                                    <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ page.meta_title }}</dd>
                                </div>
                                <div v-if="page.meta_description">
                                    <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ page.meta_description }}</dd>
                                </div>
                            </div>

                            <div class="prose max-w-none">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Konten Halaman</h3>
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <pre class="whitespace-pre-wrap text-sm text-gray-700">{{ page.content }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
