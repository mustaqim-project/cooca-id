<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    post: Object,
});
</script>

<template>
    <Head title="Blog Post Detail" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blog Post Detail</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6 flex justify-between items-center">
                            <Link :href="route('admin.blog.index')" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Posts</Link>
                            <div class="space-x-2">
                                <Link :href="route('admin.blog.edit', post.id)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Edit</Link>
                            </div>
                        </div>

                        <div v-if="post.featured_image" class="mb-6">
                            <img :src="post.featured_image" :alt="post.title" class="w-full h-64 object-cover rounded-lg" />
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ post.title }}</h1>

                        <div class="flex items-center space-x-4 mb-6 text-sm text-gray-500">
                            <span>By {{ post.author?.name }}</span>
                            <span>•</span>
                            <span>{{ post.category }}</span>
                            <span>•</span>
                            <span :class="getStatusClass(post.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ post.status }}
                            </span>
                            <span>•</span>
                            <span>{{ formatDate(post.published_at || post.created_at) }}</span>
                        </div>

                        <div class="prose max-w-none">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Excerpt</h3>
                            <p class="text-gray-600 mb-4">{{ post.excerpt }}</p>

                            <h3 class="text-lg font-medium text-gray-900 mb-2">Content</h3>
                            <div class="text-gray-700 whitespace-pre-wrap">{{ post.content }}</div>
                        </div>

                        <div v-if="post.tags && post.tags.length" class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="tag in post.tags" :key="tag" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                    {{ tag }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <dt class="font-medium text-gray-500">Created At</dt>
                                    <dd class="text-gray-900">{{ formatDate(post.created_at) }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Updated At</dt>
                                    <dd class="text-gray-900">{{ formatDate(post.updated_at) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID');
        },
        getStatusClass(status) {
            const classes = {
                'published': 'bg-green-100 text-green-800',
                'draft': 'bg-yellow-100 text-yellow-800',
                'archived': 'bg-gray-100 text-gray-800',
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }
    }
};
</script>
