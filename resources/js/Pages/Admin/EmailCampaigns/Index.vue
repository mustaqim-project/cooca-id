<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import SelectInput from '@/Components/forms/SelectInput.vue';

interface EmailCampaign {
    id: string;
    name: string;
    subject: string;
    content: string;
    recipient_type: 'all' | 'segment' | 'specific';
    segment_filters?: any[];
    recipient_ids?: string[];
    recipient_count: number;
    status: 'draft' | 'scheduled' | 'sending' | 'sent' | 'failed';
    scheduled_at?: string;
    sent_at?: string;
    open_count?: number;
    click_count?: number;
    bounce_count?: number;
    created_by: string;
    createdBy?: {
        id: string;
        name: string;
    };
    created_at: string;
    updated_at: string;
}

interface PaginatedData<T> {
    data: T[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Stats {
    total: number;
    draft: number;
    sent: number;
    scheduled: number;
}

interface Props {
    campaigns: PaginatedData<EmailCampaign>;
    stats: Stats;
    filters: {
        status?: string;
    };
}

const props = defineProps<Props>();

const filterForm = useForm({
    status: props.filters.status || '',
});

const handleFilter = () => {
    router.get(route('admin.email-campaigns.index'), filterForm, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    filterForm.status = '';
    router.get(route('admin.email-campaigns.index'), filterForm, {
        preserveState: true,
        preserveScroll: true,
    });
};

const getStatusBadgeClass = (status: string) => {
    const classes: Record<string, string> = {
        draft: 'bg-gray-100 text-gray-800',
        scheduled: 'bg-blue-100 text-blue-800',
        sending: 'bg-yellow-100 text-yellow-800',
        sent: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString?: string) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <AdminLayout title="Email Campaigns" :user="$page.props.auth.user">
        <div class="py-6">
            <PageHeader 
                title="Email Campaigns" 
                subtitle="Kelola kampanye email marketing"
            >
                <Link :href="route('admin.email-campaigns.create')">
                    <Button variant="primary" size="md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Campaign Baru
                    </Button>
                </Link>
            </PageHeader>

            <!-- Stats Cards -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-sm font-medium text-gray-500">Total Campaign</div>
                    <div class="text-2xl font-bold text-gray-900">{{ stats.total }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-sm font-medium text-gray-500">Draft</div>
                    <div class="text-2xl font-bold text-gray-900">{{ stats.draft }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-sm font-medium text-gray-500">Scheduled</div>
                    <div class="text-2xl font-bold text-blue-600">{{ stats.scheduled }}</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-sm font-medium text-gray-500">Sent</div>
                    <div class="text-2xl font-bold text-green-600">{{ stats.sent }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form @submit.prevent="handleFilter" class="flex items-end space-x-4">
                    <div class="flex-1">
                        <SelectInput
                            v-model="filterForm.status"
                            label="Filter Status"
                            :options="[
                                { value: '', label: 'Semua Status' },
                                { value: 'draft', label: 'Draft' },
                                { value: 'scheduled', label: 'Scheduled' },
                                { value: 'sending', label: 'Sending' },
                                { value: 'sent', label: 'Sent' },
                                { value: 'failed', label: 'Failed' }
                            ]"
                        />
                    </div>
                    <Button type="submit" variant="primary" :loading="filterForm.processing">
                        Filter
                    </Button>
                    <Button type="button" variant="outline" @click="resetFilters" :disabled="filterForm.processing">
                        Reset
                    </Button>
                </form>
            </div>

            <!-- Table -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Campaign
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Target
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Kirim
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="campaigns.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada campaign. Klik "Buat Campaign Baru" untuk memulai.
                                </td>
                            </tr>
                            <tr 
                                v-for="campaign in campaigns.data" 
                                :key="campaign.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ campaign.name }}</div>
                                    <div class="text-xs text-gray-500">Oleh: {{ campaign.createdBy?.name || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ campaign.subject }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="capitalize">{{ campaign.recipient_type }}</span>
                                    <span v-if="campaign.recipient_count > 0" class="text-xs text-gray-400 ml-1">
                                        ({{ campaign.recipient_count }} penerima)
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="['px-2 py-1 text-xs rounded-full', getStatusBadgeClass(campaign.status)]">
                                        {{ campaign.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div v-if="campaign.scheduled_at">
                                        <div class="text-xs">Jadwal:</div>
                                        <div>{{ formatDate(campaign.scheduled_at) }}</div>
                                    </div>
                                    <div v-else-if="campaign.sent_at">
                                        <div class="text-xs">Terkirim:</div>
                                        <div>{{ formatDate(campaign.sent_at) }}</div>
                                    </div>
                                    <div v-else>-</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link 
                                            :href="route('admin.email-campaigns.show', campaign.id)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Lihat
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="campaigns.data.length > 0" class="border-t border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ campaigns.data.length }}</span> dari 
                            <span class="font-medium">{{ campaigns.total }}</span> hasil
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                v-for="(link, index) in campaigns.links"
                                :key="index"
                                :href="link.url || '#'"
                                :class="[
                                    'px-3 py-1 rounded-md text-sm',
                                    link.active 
                                        ? 'bg-indigo-600 text-white' 
                                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                    !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
