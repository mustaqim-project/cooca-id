<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Template {
    id: string;
    name: string;
    key: string;
    subject: string;
    category: string | null;
    is_active: boolean;
    created_at: string;
}

interface Props {
    templates: {
        data: Template[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: { url: string | null; label: string; active: boolean }[];
    };
    categories: string[];
    filters: {
        search?: string;
        category?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const searchQuery = ref(props.filters.search || '');
const categoryFilter = ref(props.filters.category || '');
const statusFilter = ref(props.filters.status || '');

const handleSearch = () => {
    router.get(route('admin.email-templates.index'), {
        search: searchQuery.value,
        category: categoryFilter.value,
        status: statusFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    searchQuery.value = '';
    categoryFilter.value = '';
    statusFilter.value = '';
    router.get(route('admin.email-templates.index'));
};

const toggleActive = (template: Template) => {
    router.post(route('admin.email-templates.toggle-active', template.id), {}, {
        onSuccess: () => {},
    });
};

const deleteTemplate = (template: Template) => {
    if (confirm(`Apakah Anda yakin ingin menghapus template "${template.name}"?`)) {
        router.delete(route('admin.email-templates.destroy', template.id), {
            onSuccess: () => {},
        });
    }
};
</script>

<script setup lang="ts">
import { ref } from 'vue';
</script>

<template>
    <Head title="Template Email" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Template Email</h2>
                    <Link :href="route('admin.email-templates.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Template
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                            <input
                                id="search"
                                v-model="searchQuery"
                                @keyup.enter="handleSearch"
                                type="text"
                                placeholder="Nama, subjek, atau key..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select
                                id="category"
                                v-model="categoryFilter"
                                @change="handleSearch"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="">Semua</option>
                                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select
                                id="status"
                                v-model="statusFilter"
                                @change="handleSearch"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="">Semua</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button
                                @click="handleSearch"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Filter
                            </button>
                            <button
                                @click="resetFilters"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="template in templates.data" :key="template.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ template.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ template.key }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ template.subject }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ template.category || '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="template.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    >
                                        {{ template.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <Link :href="route('admin.email-templates.edit', template.id)" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                    <Link :href="route('admin.email-templates.show', template.id)" class="text-blue-600 hover:text-blue-900">Preview</Link>
                                    <button @click="toggleActive(template)" class="text-blue-600 hover:text-blue-900">
                                        {{ template.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                    <button @click="deleteTemplate(template)" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                            <tr v-if="templates.data.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data template email.
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="templates.last_page > 1" class="px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-center space-x-2">
                            <Link
                                v-for="(link, index) in templates.links"
                                :key="index"
                                :href="link.url || '#'"
                                :class="{
                                    'bg-indigo-600 text-white': link.active,
                                    'bg-gray-200 text-gray-700 hover:bg-gray-300': !link.active && link.url,
                                    'bg-gray-100 text-gray-400 cursor-not-allowed': !link.url,
                                }"
                                class="px-3 py-1 rounded-md text-sm"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
