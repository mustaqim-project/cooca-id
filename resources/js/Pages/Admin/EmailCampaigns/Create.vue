<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    subject: '',
    content: '',
    recipients: 'all_customers',
    scheduled_at: null,
});

const submit = () => {
    form.post(route('admin.email-campaigns.store'));
};
</script>

<template>
    <Head title="Create Email Campaign" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Email Campaign</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Campaign Name</label>
                                <input id="name" v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g., Welcome Series" required />
                                <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700">Email Subject</label>
                                <input id="subject" v-model="form.subject" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g., Welcome to Cooca.id!" required />
                                <span v-if="form.errors.subject" class="text-red-500 text-xs">{{ form.errors.subject }}</span>
                            </div>

                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700">Email Content (HTML)</label>
                                <textarea id="content" v-model="form.content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="<html><body>...</body></html>" required></textarea>
                                <span v-if="form.errors.content" class="text-red-500 text-xs">{{ form.errors.content }}</span>
                            </div>

                            <div>
                                <label for="recipients" class="block text-sm font-medium text-gray-700">Recipients</label>
                                <select id="recipients" v-model="form.recipients" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="all_customers">All Customers</option>
                                    <option value="active_subscriptions">Active Subscriptions Only</option>
                                    <option value="trial_users">Trial Users</option>
                                </select>
                                <span v-if="form.errors.recipients" class="text-red-500 text-xs">{{ form.errors.recipients }}</span>
                            </div>

                            <div>
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Schedule Send (Optional - Leave empty for immediate)</label>
                                <input id="scheduled_at" v-model="form.scheduled_at" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                <span v-if="form.errors.scheduled_at" class="text-red-500 text-xs">{{ form.errors.scheduled_at }}</span>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <Link :href="route('admin.email-campaigns.index')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">Cancel</Link>
                                <button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50">
                                    {{ form.processing ? 'Creating...' : 'Create Campaign' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
