<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

interface Integration {
    id: string;
    name: string;
    label: string;
    category: string;
    is_active: boolean;
    credentials: Record<string, any>;
    config: Record<string, any> | null;
    description: string | null;
    last_used_at: string | null;
    tested_at: string | null;
    test_status: boolean | null;
    test_message: string | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    integrations: Integration[];
    categories: Record<string, string>;
    filters: {
        category: string | null;
        search: string | null;
    };
    success?: string;
    error?: string;
}

const props = defineProps<Props>();

const searchForm = ref({
    search: props.filters.search || '',
    category: props.filters.category || '',
});

const activeCategory = ref(props.filters.category || 'all');

const filteredIntegrations = computed(() => {
    return props.integrations;
});

const getStatusColor = (integration: Integration) => {
    if (!integration.is_active) return 'bg-gray-200 text-gray-700';
    if (integration.test_status === true) return 'bg-green-100 text-green-800';
    if (integration.test_status === false) return 'bg-red-100 text-red-800';
    return 'bg-yellow-100 text-yellow-800';
};

const getStatusText = (integration: Integration) => {
    if (!integration.is_active) return 'Inactive';
    if (integration.test_status === true) return 'Connected';
    if (integration.test_status === false) return 'Failed';
    return 'Not Tested';
};

const submitSearch = () => {
    router.get(route('admin.api-integrations.index'), {
        search: searchForm.value.search,
        category: searchForm.value.category || null,
    }, {
        preserveState: true,
    });
};

const resetFilters = () => {
    searchForm.value = { search: '', category: '' };
    router.get(route('admin.api-integrations.index'));
};

const testIntegration = (integration: Integration) => {
    if (!confirm(`Test connection for ${integration.label}?`)) return;
    router.post(route('admin.api-integrations.test', integration.id));
};

const toggleStatus = (integration: Integration) => {
    router.put(route('admin.api-integrations.update', integration.id), {
        is_active: !integration.is_active,
    }, {
        preserveState: true,
    });
};

const deleteIntegration = (integration: Integration) => {
    if (!confirm(`Are you sure you want to delete "${integration.label}"?`)) return;
    router.delete(route('admin.api-integrations.destroy', integration.id));
};
</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">API Integrations</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Manage your API integrations (Fonnte, SMTP, Google OAuth, etc.)
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <Link
                            :href="route('admin.api-integrations.seed')"
                            method="post"
                            as="button"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Seed Defaults
                        </Link>
                        <Link
                            :href="route('admin.api-integrations.create')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            + New Integration
                        </Link>
                    </div>
                </div>

                <!-- Alerts -->
                <div v-if="success" class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                    <p class="text-green-700">{{ success }}</p>
                </div>
                <div v-if="error" class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
                    <p class="text-red-700">{{ error }}</p>
                </div>

                <!-- Filters -->
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <form @submit.prevent="submitSearch" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <TextInput
                                v-model="searchForm.search"
                                type="text"
                                placeholder="Search by name or label..."
                                class="w-full"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select
                                v-model="searchForm.category"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >
                                <option value="">All Categories</option>
                                <option v-for="(label, key) in categories" :key="key" :value="key">
                                    {{ label }}
                                </option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <PrimaryButton type="submit" class="flex-1">
                                Filter
                            </PrimaryButton>
                            <button
                                type="button"
                                @click="resetFilters"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                            >
                                Reset
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Integrations Table -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Integration
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Tested
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="integration in filteredIntegrations" :key="integration.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ integration.label }}</div>
                                        <div class="text-sm text-gray-500">{{ integration.name }}</div>
                                        <div v-if="integration.description" class="text-xs text-gray-400 mt-1">
                                            {{ integration.description }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ categories[integration.category] || integration.category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span :class="getStatusColor(integration)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            {{ getStatusText(integration) }}
                                        </span>
                                        <button
                                            @click="toggleStatus(integration)"
                                            :class="integration.is_active ? 'text-green-600' : 'text-gray-400'"
                                            class="hover:text-gray-600"
                                            title="Toggle Active Status"
                                        >
                                            <svg :class="integration.is_active ? 'opacity-100' : 'opacity-25'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div v-if="integration.tested_at">
                                        {{ new Date(integration.tested_at).toLocaleString() }}
                                    </div>
                                    <div v-else class="text-gray-400">Never</div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            @click="testIntegration(integration)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                            title="Test Connection"
                                        >
                                            Test
                                        </button>
                                        <Link
                                            :href="route('admin.api-integrations.edit', integration.id)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Edit
                                        </Link>
                                        <Link
                                            :href="route('admin.api-integrations.show', integration.id)"
                                            class="text-gray-600 hover:text-gray-900"
                                        >
                                            View
                                        </Link>
                                        <button
                                            @click="deleteIntegration(integration)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredIntegrations.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No integrations found. Click "Seed Defaults" to load from .env configuration.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
