<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    campaign: Object,
});
</script>

<template>
    <Head title="Email Campaign Detail" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Email Campaign Detail</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <Link :href="route('admin.email-campaigns.index')" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Campaigns</Link>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Campaign Information</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Campaign Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ campaign.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Subject</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ campaign.subject }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Recipients</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ campaign.recipients_count }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Recipient Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ campaign.recipients }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Schedule</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            <span :class="getStatusClass(campaign.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ campaign.status }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Scheduled At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ campaign.scheduled_at ? formatDate(campaign.scheduled_at) : 'Immediate' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Sent At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ campaign.sent_at ? formatDate(campaign.sent_at) : '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(campaign.created_at) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Email Content Preview</h3>
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="prose max-w-none" v-html="campaign.content"></div>
                            </div>
                        </div>

                        <div v-if="campaign.statistics" class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Campaign Statistics</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <dt class="text-sm font-medium text-green-800">Delivered</dt>
                                    <dd class="mt-1 text-2xl font-bold text-green-900">{{ campaign.statistics.delivered || 0 }}</dd>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <dt class="text-sm font-medium text-blue-800">Opened</dt>
                                    <dd class="mt-1 text-2xl font-bold text-blue-900">{{ campaign.statistics.opened || 0 }}</dd>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <dt class="text-sm font-medium text-purple-800">Clicked</dt>
                                    <dd class="mt-1 text-2xl font-bold text-purple-900">{{ campaign.statistics.clicked || 0 }}</dd>
                                </div>
                            </div>
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
            return new Date(date).toLocaleString('id-ID');
        },
        getStatusClass(status) {
            const classes = {
                'draft': 'bg-yellow-100 text-yellow-800',
                'scheduled': 'bg-blue-100 text-blue-800',
                'sent': 'bg-green-100 text-green-800',
                'failed': 'bg-red-100 text-red-800',
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }
    }
};
</script>
