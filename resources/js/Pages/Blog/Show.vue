<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

interface BlogPost {
    id: string;
    title: string;
    slug: string;
    content: string;
    excerpt: string;
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
    post: BlogPost;
    relatedPosts: BlogPost[];
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
    <Head :title="`${post.title} - Cooca.id Blog`" />

    <AppLayout>
        <!-- Article -->
        <article class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <header class="mb-12">
                    <Link
                        v-if="post.category"
                        :href="`/blog?category=${post.category}`"
                        class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm"
                    >
                        ← {{ post.category }}
                    </Link>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mt-4 mb-6">{{ post.title }}</h1>
                    <div class="flex items-center space-x-6 text-gray-600">
                        <div class="flex items-center space-x-3">
                            <div v-if="post.author" class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ post.author.name.charAt(0) }}
                            </div>
                            <span class="font-medium">{{ post.author?.name || 'Admin' }}</span>
                        </div>
                        <span>📅 {{ formatDate(post.published_at) }}</span>
                        <span>👁 {{ post.view_count }} views</span>
                    </div>
                </header>

                <!-- Content -->
                <div class="prose prose-lg max-w-none mb-12">
                    <div v-html="post.content"></div>
                </div>

                <!-- Tags -->
                <div v-if="post.tags && post.tags.length > 0" class="mb-12">
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="tag in post.tags"
                            :key="tag"
                            class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-200 cursor-pointer"
                        >
                            #{{ tag }}
                        </span>
                    </div>
                </div>

                <!-- Share -->
                <div class="border-t border-b border-gray-200 py-8 mb-12">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bagikan artikel ini</h3>
                    <div class="flex space-x-4">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Facebook
                        </button>
                        <button class="bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600 transition-colors">
                            Twitter
                        </button>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            WhatsApp
                        </button>
                        <button class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition-colors">
                            LinkedIn
                        </button>
                    </div>
                </div>

                <!-- Related Posts -->
                <section v-if="relatedPosts && relatedPosts.length > 0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Artikel Terkait</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <Link
                            v-for="relatedPost in relatedPosts"
                            :key="relatedPost.id"
                            :href="`/blog/${relatedPost.slug}`"
                            class="group"
                        >
                            <div class="bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors">
                                <span v-if="relatedPost.category" class="text-sm text-indigo-600 font-semibold">{{ relatedPost.category }}</span>
                                <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ relatedPost.title }}</h3>
                                <p class="text-gray-600 text-sm line-clamp-2">{{ relatedPost.excerpt }}</p>
                            </div>
                        </Link>
                    </div>
                </section>
            </div>
        </article>

        <!-- CTA -->
        <section class="py-20 bg-indigo-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Siap Mencoba ERP Kami?</h2>
                <p class="text-xl mb-8 text-indigo-100">Mulai trial gratis 7 hari tanpa kartu kredit</p>
                <Link
                    href="/customer/register"
                    class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                >
                    Daftar Sekarang
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
