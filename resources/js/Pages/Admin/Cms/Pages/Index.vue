<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import TextInput from '@/Components/forms/TextInput.vue';
import SelectInput from '@/Components/forms/SelectInput.vue';

interface Page {
    id: string;
    title: string;
    slug: string;
    excerpt?: string;
    content: string;
    meta_title?: string;
    meta_description?: string;
    is_published: boolean;
    published_at?: string;
    author?: {
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

interface Props {
    pages: PaginatedData<Page>;
    filters: {
        search?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const searchForm = useForm({
    search: props.filters.search || '',
    status: props.filters.status || '',
});

const handleSearch = () => {
    router.get(route('admin.cms.pages.index'), searchForm, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    searchForm.search = '';
    searchForm.status = '';
    router.get(route('admin.cms.pages.index'), searchForm, {
        preserveState: true,
        preserveScroll: true,
    });
};

const deletePage = (page: Page) => {
    if (confirm(`Apakah Anda yakin ingin menghapus halaman "${page.title}"?`)) {
        router.delete(route('admin.cms.pages.destroy', page.id), {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <AdminLayout title="CMS Pages" :user="$page.props.auth.user">
        <div class="py-6">
            <PageHeader 
                title="Manajemen CMS Pages" 
                subtitle="Kelola halaman statis website"
            >
                <Link :href="route('admin.cms.pages.create')">
                    <Button variant="primary" size="md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Halaman
                    </Button>
                </Link>
            </PageHeader>

            <!-- Filters -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form @submit.prevent="handleSearch" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <TextInput
                        v-model="searchForm.search"
                        label="Cari Halaman"
                        placeholder="Cari berdasarkan judul atau slug..."
                    />
                    
                    <SelectInput
                        v-model="searchForm.status"
                        label="Filter Status"
                        :options="[
                            { value: '', label: 'Semua Status' },
                            { value: 'published', label: 'Published' },
                            { value: 'draft', label: 'Draft' }
                        ]"
                    />
                    
                    <div class="flex items-end space-x-2">
                        <Button type="submit" variant="primary" :loading="searchForm.processing" class="flex-1">
                            Cari
                        </Button>
                        <Button type="button" variant="outline" @click="resetFilters" :disabled="searchForm.processing">
                            Reset
                        </Button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Slug
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Terakhir Diupdate
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="pages.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada halaman. Klik "Tambah Halaman" untuk memulai.
                                </td>
                            </tr>
                            <tr 
                                v-for="page in pages.data" 
                                :key="page.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ page.title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ page.slug }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[
                                        'px-2 py-1 text-xs rounded-full',
                                        page.is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                    ]">
                                        {{ page.is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(page.updated_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link 
                                            :href="route('admin.cms.pages.show', page.id)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Lihat
                                        </Link>
                                        <Link 
                                            :href="route('admin.cms.pages.edit', page.id)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Edit
                                        </Link>
                                        <button 
                                            @click="deletePage(page)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="pages.data.length > 0" class="border-t border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ pages.data.length }}</span> dari 
                            <span class="font-medium">{{ pages.total }}</span> hasil
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                v-for="(link, index) in pages.links"
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
