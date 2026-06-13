<script setup lang="ts">
import { router, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import DataTable from '@/Components/tables/DataTable.vue';
import TextInput from '@/Components/forms/TextInput.vue';
import SelectInput from '@/Components/forms/SelectInput.vue';

interface BlogPost {
    id: string;
    title: string;
    slug: string;
    excerpt?: string;
    content: string;
    category?: string;
    tags?: string[];
    featured_image?: string;
    meta_title?: string;
    meta_description?: string;
    is_published: boolean;
    is_featured: boolean;
    published_at?: string;
    views_count: number;
    author: {
        id: string;
        name: string;
        email: string;
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
    posts: PaginatedData<BlogPost>;
    categories: string[];
    filters: {
        search?: string;
        status?: string;
        category?: string;
    };
}

const props = defineProps<Props>();

const searchForm = useForm({
    search: props.filters.search || '',
    status: props.filters.status || '',
    category: props.filters.category || '',
});

const handleSearch = () => {
    router.get(route('admin.blog.index'), searchForm, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    searchForm.search = '';
    searchForm.status = '';
    searchForm.category = '';
    router.get(route('admin.blog.index'), searchForm, {
        preserveState: true,
        preserveScroll: true,
    });
};

const deletePost = (post: BlogPost) => {
    if (confirm(`Apakah Anda yakin ingin menghapus artikel "${post.title}"?`)) {
        router.delete(route('admin.blog.destroy', post.id), {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const columns = [
    { key: 'title', label: 'Judul', sortable: true },
    { 
        key: 'status', 
        label: 'Status',
        format: (value: any, row: BlogPost) => row.is_published ? 'Published' : 'Draft'
    },
    { 
        key: 'category', 
        label: 'Kategori',
        format: (value: any) => value || '-'
    },
    { 
        key: 'author.name', 
        label: 'Penulis',
        format: (value: any, row: BlogPost) => row.author?.name || '-'
    },
    { 
        key: 'published_at', 
        label: 'Tanggal Publish',
        format: (value: any, row: BlogPost) => {
            if (!row.published_at) return '-';
            return new Date(row.published_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    },
];

const getStatusBadgeClass = (post: BlogPost) => {
    return post.is_published 
        ? 'bg-green-100 text-green-800' 
        : 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <AdminLayout title="Blog Posts" :user="$page.props.auth.user">
        <div class="py-6">
            <PageHeader 
                title="Manajemen Blog" 
                subtitle="Kelola artikel dan konten blog platform"
            >
                <Link :href="route('admin.blog.create')">
                    <Button variant="primary" size="md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tulis Artikel Baru
                    </Button>
                </Link>
            </PageHeader>

            <!-- Filters -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form @submit.prevent="handleSearch" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <TextInput
                        v-model="searchForm.search"
                        label="Cari Judul"
                        placeholder="Cari berdasarkan judul..."
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
                    
                    <SelectInput
                        v-model="searchForm.category"
                        label="Filter Kategori"
                        :options="[
                            { value: '', label: 'Semua Kategori' },
                            ...categories.map(cat => ({ value: cat, label: cat }))
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
                                <th 
                                    v-for="column in columns" 
                                    :key="column.key"
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ column.label }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="posts.data.length === 0">
                                <td :colspan="columns.length + 1" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada artikel blog. Klik "Tulis Artikel Baru" untuk memulai.
                                </td>
                            </tr>
                            <tr 
                                v-for="post in posts.data" 
                                :key="post.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ post.title }}</div>
                                    <div class="text-xs text-gray-500">{{ post.slug }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="['px-2 py-1 text-xs rounded-full', getStatusBadgeClass(post)]">
                                        {{ post.is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ post.category || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ post.author?.name || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ post.published_at ? new Date(post.published_at).toLocaleDateString('id-ID') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link 
                                            :href="route('admin.blog.show', post.id)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Lihat
                                        </Link>
                                        <Link 
                                            :href="route('admin.blog.edit', post.id)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Edit
                                        </Link>
                                        <button 
                                            @click="deletePost(post)"
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
                <div v-if="posts.data.length > 0" class="border-t border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ posts.data.length }}</span> dari 
                            <span class="font-medium">{{ posts.total }}</span> hasil
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                v-for="(link, index) in posts.links"
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
