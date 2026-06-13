<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head } from '@inertiajs/vue3';

interface EmailCampaign {
    id: number;
    name: string;
    subject: string;
    target: string;
    body: string;
    status: 'draft' | 'scheduled' | 'sent';
    scheduled_at: string | null;
    sent_at: string | null;
    recipients_count: number;
    open_rate: number | null;
    click_rate: number | null;
    created_at: string;
}

interface Props {
    campaign: EmailCampaign;
}

defineProps<Props>();

const formatDate = (dateString: string | null) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'draft':
            return 'bg-yellow-100 text-yellow-800';
        case 'scheduled':
            return 'bg-blue-100 text-blue-800';
        case 'sent':
            return 'bg-green-100 text-green-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head :title="campaign.name" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900">{{ campaign.name }}</h2>
                                <p class="mt-1 text-sm text-gray-600">Subject: {{ campaign.subject }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    :class="getStatusColor(campaign.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                >
                                    {{ campaign.status }}
                                </span>
                                <SecondaryButton @click="router.get(route('admin.email-campaigns.index'))">
                                    Kembali
                                </SecondaryButton>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-sm font-medium text-gray-500">Target</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900 capitalize">
                                    {{ campaign.target.replace('_', ' ') }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-sm font-medium text-gray-500">Jumlah Penerima</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ campaign.recipients_count?.toLocaleString('id-ID') || '-' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dt class="text-sm font-medium text-gray-500">Open Rate</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ campaign.open_rate ? `${campaign.open_rate}%` : '-' }}
                                </dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dijadwalkan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(campaign.scheduled_at) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dikirim</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(campaign.sent_at) }}</dd>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Preview Email</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="prose max-w-none">
                                    <pre class="whitespace-pre-wrap text-sm text-gray-700">{{ campaign.body }}</pre>
                                </div>
                            </div>
                        </div>

                        <div v-if="campaign.status === 'draft'" class="flex items-center gap-4">
                            <PrimaryButton @click="router.post(route('admin.email-campaigns.send', campaign.id))">
                                Kirim Sekarang
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
