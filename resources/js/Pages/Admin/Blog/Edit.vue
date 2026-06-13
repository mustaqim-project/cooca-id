<script setup lang="ts">
import { ref, watch } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import TextInput from '@/Components/forms/TextInput.vue';
import SelectInput from '@/Components/forms/SelectInput.vue';
import CheckboxInput from '@/Components/forms/CheckboxInput.vue';

interface BlogPost {
    id: string;
    title: string;
    slug: string;
    excerpt?: string;
    content: string;
    category?: string;
    tags?: string[];
    featured_image?: string;
    meta_title?: string;
    meta_description?: string;
    is_published: boolean;
    is_featured: boolean;
    published_at?: string;
}

interface Props {
    post: BlogPost;
    categories: string[];
}

const props = defineProps<Props>();

const form = useForm({
    title: props.post.title,
    slug: props.post.slug,
    excerpt: props.post.excerpt || '',
    content: props.post.content,
    category: props.post.category || '',
    tags: props.post.tags || [],
    featured_image: props.post.featured_image || '',
    meta_title: props.post.meta_title || '',
    meta_description: props.post.meta_description || '',
    is_published: props.post.is_published,
    is_featured: props.post.is_featured,
    published_at: props.post.published_at || '',
});

const tagInput = ref('');

watch(() => form.title, (newTitle) => {
    if (!form.slug || form.slug === props.post.slug) {
        // Only auto-generate if user hasn't manually edited slug
        form.slug = newTitle
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});

const addTag = () => {
    if (tagInput.value.trim() && !form.tags?.includes(tagInput.value.trim())) {
        form.tags = [...(form.tags || []), tagInput.value.trim()];
        tagInput.value = '';
    }
};

const removeTag = (tag: string) => {
    form.tags = form.tags?.filter(t => t !== tag) || [];
};

const submit = () => {
    form.put(route('admin.blog.update', props.post.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            // form.reset();
        },
    });
};
</script>

<template>
    <AdminLayout title="Edit Artikel" :user="$page.props.auth.user">
        <div class="py-6">
            <PageHeader 
                title="Edit Artikel" 
                subtitle="Ubah konten artikel blog"
            >
                <Link :href="route('admin.blog.index')">
                    <Button variant="outline" size="md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </Button>
                </Link>
            </PageHeader>

            <form @submit.prevent="submit" class="mt-6 space-y-6">
                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Main Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Konten Utama</h3>
                            
                            <TextInput
                                v-model="form.title"
                                label="Judul Artikel"
                                placeholder="Masukkan judul artikel..."
                                required
                                :error="form.errors.title"
                            />

                            <TextInput
                                v-model="form.slug"
                                label="Slug URL"
                                placeholder="judul-artikel-anda"
                                helpText="Akan otomatis generate dari judul jika kosong"
                                :error="form.errors.slug"
                            />

                            <TextInput
                                v-model="form.excerpt"
                                label="Ringkasan Singkat"
                                type="text"
                                placeholder="Ringkasan 1-2 kalimat..."
                                helpText="Maksimal 500 karakter"
                                :error="form.errors.excerpt"
                            />

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Konten Artikel
                                    <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    v-model="form.content"
                                    rows="12"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    :class="{ 'border-red-500': form.errors.content }"
                                    placeholder="Tulis konten artikel lengkap di sini..."
                                ></textarea>
                                <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.content }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Settings -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan</h3>

                            <SelectInput
                                v-model="form.category"
                                label="Kategori"
                                :options="[
                                    { value: '', label: 'Pilih Kategori' },
                                    ...categories.map(cat => ({ value: cat, label: cat }))
                                ]"
                            />

                            <!-- Tags -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tags
                                </label>
                                <div class="flex space-x-2 mb-2">
                                    <input
                                        v-model="tagInput"
                                        type="text"
                                        placeholder="Tambah tag..."
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        @keyup.enter.prevent="addTag"
                                    />
                                    <Button type="button" variant="outline" @click="addTag" size="sm">
                                        Tambah
                                    </Button>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="tag in form.tags"
                                        :key="tag"
                                        class="inline-flex items-center px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-md"
                                    >
                                        {{ tag }}
                                        <button
                                            type="button"
                                            @click="removeTag(tag)"
                                            class="ml-1 text-indigo-600 hover:text-indigo-900"
                                        >
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Featured Image URL
                                </label>
                                <input
                                    v-model="form.featured_image"
                                    type="url"
                                    placeholder="https://..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.featured_image }"
                                />
                                <p v-if="form.errors.featured_image" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.featured_image }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Publish
                                </label>
                                <input
                                    v-model="form.published_at"
                                    type="datetime-local"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>

                            <CheckboxInput
                                v-model="form.is_published"
                                label="Publish Sekarang"
                                helpText="Artikel akan langsung tampil di website"
                            />

                            <CheckboxInput
                                v-model="form.is_featured"
                                label="Featured"
                                helpText="Tampilkan di halaman utama"
                            />
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Meta</h3>

                            <TextInput
                                v-model="form.meta_title"
                                label="Meta Title"
                                placeholder="Judul untuk SEO..."
                                helpText="Rekomendasi: 50-60 karakter"
                                :error="form.errors.meta_title"
                            />

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Meta Description
                                </label>
                                <textarea
                                    v-model="form.meta_description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.meta_description }"
                                    placeholder="Deskripsi untuk SEO..."
                                ></textarea>
                                <p v-if="form.errors.meta_description" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.meta_description }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <Link :href="route('admin.blog.index')">
                                <Button type="button" variant="outline">
                                    Batal
                                </Button>
                            </Link>
                            <Button type="submit" variant="primary" :loading="form.processing">
                                Update Artikel
                            </Button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
