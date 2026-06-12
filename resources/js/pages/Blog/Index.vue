<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

interface BlogPost {
    id: string;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    published_at: string;
    view_count: number;
    category?: string;
    tags?: string[];
    author?: {
        name: string;
        avatar?: string;
    };
}

interface Props {
    posts: {
        data: BlogPost[];
        links: any;
        meta: any;
    };
    categories: string[];
    featuredPosts: BlogPost[];
    filters?: {
        category?: string;
        search?: string;
    };
}

const props = defineProps<Props>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Blog - Cooca.id" />

    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Blog</h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Artikel, tips, dan insight tentang ERP dan manajemen bisnis
                </p>
            </div>
        </section>

        <!-- Featured Posts -->
        <section v-if="featuredPosts && featuredPosts.length > 0" class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Artikel Pilihan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <Link
                        v-for="post in featuredPosts"
                        :key="post.id"
                        :href="`/blog/${post.slug}`"
                        class="group"
                    >
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="h-48 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                <span class="text-6xl">📝</span>
                            </div>
                            <div class="p-6">
                                <span v-if="post.category" class="text-sm text-indigo-600 font-semibold">{{ post.category }}</span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-2 group-hover:text-indigo-600 transition-colors">{{ post.title }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ post.excerpt }}</p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ formatDate(post.published_at) }}</span>
                                    <span>👁 {{ post.view_count }}</span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Filters -->
        <section class="py-8 bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap gap-4">
                    <Link
                        href="/blog"
                        :class="[
                            !filters?.category ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            'px-4 py-2 rounded-full text-sm font-medium transition-colors'
                        ]"
                    >
                        Semua
                    </Link>
                    <Link
                        v-for="category in categories"
                        :key="category"
                        :href="`/blog?category=${category}`"
                        :class="[
                            filters?.category === category ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                            'px-4 py-2 rounded-full text-sm font-medium transition-colors'
                        ]"
                    >
                        {{ category }}
                    </Link>
                </div>
            </div>
        </section>

        <!-- All Posts -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <Link
                        v-for="post in posts.data"
                        :key="post.id"
                        :href="`/blog/${post.slug}`"
                        class="group"
                    >
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow h-full">
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                                <span class="text-6xl">📄</span>
                            </div>
                            <div class="p-6">
                                <span v-if="post.category" class="text-sm text-indigo-600 font-semibold">{{ post.category }}</span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ post.title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ post.excerpt }}</p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <div v-if="post.author" class="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs">
                                            {{ post.author.name.charAt(0) }}
                                        </div>
                                        <span>{{ post.author?.name || 'Admin' }}</span>
                                    </div>
                                    <span>👁 {{ post.view_count }}</span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="posts.meta.last_page > 1" class="mt-12 flex justify-center">
                    <nav class="flex space-x-2">
                        <Link
                            v-for="(link, index) in posts.links.data"
                            :key="index"
                            :href="link.url || '#'"
                            :class="[
                                link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100',
                                'px-4 py-2 rounded-lg border transition-colors'
                            ]"
                            :disabled="!link.url"
                        >
                            <span v-html="link.label"></span>
                        </Link>
                    </nav>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
