<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    slug: '',
    content: '',
    meta_description: '',
    meta_keywords: '',
    status: 'draft',
});

const submit = () => {
    form.post(route('admin.cms.pages.store'));
};
</script>

<template>
    <Head title="Create CMS Page" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create CMS Page</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input id="title" v-model="form.title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
                                <span v-if="form.errors.title" class="text-red-500 text-xs">{{ form.errors.title }}</span>
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                                <input id="slug" v-model="form.slug" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g., about-us" required />
                                <span v-if="form.errors.slug" class="text-red-500 text-xs">{{ form.errors.slug }}</span>
                            </div>

                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700">Content (HTML)</label>
                                <textarea id="content" v-model="form.content" rows="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                <span v-if="form.errors.content" class="text-red-500 text-xs">{{ form.errors.content }}</span>
                            </div>

                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description (SEO)</label>
                                <textarea id="meta_description" v-model="form.meta_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                <span v-if="form.errors.meta_description" class="text-red-500 text-xs">{{ form.errors.meta_description }}</span>
                            </div>

                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords (SEO)</label>
                                <input id="meta_keywords" v-model="form.meta_keywords" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="comma separated keywords" />
                                <span v-if="form.errors.meta_keywords" class="text-red-500 text-xs">{{ form.errors.meta_keywords }}</span>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <Link :href="route('admin.cms.pages.index')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">Cancel</Link>
                                <button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50">
                                    {{ form.processing ? 'Creating...' : 'Create Page' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
