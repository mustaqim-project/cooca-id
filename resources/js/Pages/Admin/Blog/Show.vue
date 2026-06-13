<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';

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
    views_count: number;
    author: {
        id: string;
        name: string;
        email: string;
    };
    created_at: string;
    updated_at: string;
}

interface Props {
    post: BlogPost;
}

const props = defineProps<Props>();

const deleteForm = useForm({});

const deletePost = () => {
    if (confirm(`Apakah Anda yakin ingin menghapus artikel "${props.post.title}"?`)) {
        deleteForm.delete(route('admin.blog.destroy', props.post.id), {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <AdminLayout title="Detail Artikel" :user="$page.props.auth.user">
        <div class="py-6">
            <PageHeader 
                title="Detail Artikel" 
                subtitle="Informasi lengkap artikel blog"
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

            <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div v-if="post.featured_image" class="w-full h-64 bg-gray-200">
                            <img :src="post.featured_image" :alt="post.title" class="w-full h-full object-cover" />
                        </div>
                        <div class="p-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ post.title }}</h1>
                            <p class="text-gray-500 italic mb-4">{{ post.excerpt }}</p>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-6">
                                <span>Ditulis oleh {{ post.author.name }}</span>
                                <span>•</span>
                                <span>{{ formatDate(post.created_at) }}</span>
                                <span>•</span>
                                <span>{{ post.views_count }} kali dilihat</span>
                            </div>

                            <div class="prose max-w-none">
                                <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">
                                    {{ post.content }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Artikel</h3>
                        
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                <dd class="text-sm text-gray-900">{{ post.slug }}</dd>
                            </div>
                            
                            <div v-if="post.category">
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="text-sm text-gray-900">{{ post.category }}</dd>
                            </div>
                            
                            <div v-if="post.tags && post.tags.length > 0">
                                <dt class="text-sm font-medium text-gray-500">Tags</dt>
                                <dd class="flex flex-wrap gap-1 mt-1">
                                    <span
                                        v-for="tag in post.tags"
                                        :key="tag"
                                        class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-md"
                                    >
                                        {{ tag }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd>
                                    <span :class="[
                                        'px-2 py-1 text-xs rounded-full',
                                        post.is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                    ]">
                                        {{ post.is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div v-if="post.published_at">
                                <dt class="text-sm font-medium text-gray-500">Tanggal Publish</dt>
                                <dd class="text-sm text-gray-900">{{ formatDate(post.published_at) }}</dd>
                            </div>
                            
                            <div v-if="post.is_featured">
                                <dt class="text-sm font-medium text-gray-500">Featured</dt>
                                <dd>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                        Featured Article
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div v-if="post.meta_title || post.meta_description" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Meta</h3>
                        
                        <dl class="space-y-3">
                            <div v-if="post.meta_title">
                                <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                                <dd class="text-sm text-gray-900">{{ post.meta_title }}</dd>
                            </div>
                            
                            <div v-if="post.meta_description">
                                <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                <dd class="text-sm text-gray-900">{{ post.meta_description }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
                        <div class="space-y-2">
                            <Link :href="route('admin.blog.edit', post.id)" class="block w-full">
                                <Button variant="primary" size="md" class="w-full justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Artikel
                                </Button>
                            </Link>
                            <Button 
                                variant="danger" 
                                size="md" 
                                class="w-full justify-center"
                                @click="deletePost"
                                :loading="deleteForm.processing"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Artikel
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
