<template>
    <AdminLayout title="Blog Posts">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Blog Posts</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage blog articles</p>
                </div>
                <a href="/admin/blog/create" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Create Post
                </a>
            </div>

            <!-- Blog Posts Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="post in posts.data" :key="post.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ post.title }}</div>
                                    <div class="text-sm text-gray-500">{{ post.slug }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ post.category || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-green-100 text-green-800': post.is_published,
                                        'bg-gray-100 text-gray-800': !post.is_published
                                    }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ post.is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ post.author?.name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a :href="`/admin/blog/${post.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <button class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="posts.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No blog posts found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            Showing {{ posts.from || 0 }} to {{ posts.to || 0 }} of {{ posts.total || 0 }} results
                        </p>
                        <div class="flex space-x-2">
                            <button v-for="link in posts.links" :key="link.label" 
                                    :disabled="!link.url"
                                    class="px-3 py-1 rounded border"
                                    :class="link.active ? 'bg-blue-500 text-white' : 'bg-white'">
                                <span v-html="link.label"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    posts: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        default: () => []
    }
});
</script>
