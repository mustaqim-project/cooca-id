<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface Product {
    id: string;
    name: string;
    slug: string;
    description: string;
    base_price: number;
    is_active: boolean;
    is_featured: boolean;
    category?: {
        name: string;
    };
}

interface Props {
    products: Product[];
    filters?: {
        search?: string;
        category?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const columns = [
    { key: 'name', label: 'Product Name', sortable: true },
    {
        key: 'category',
        label: 'Category',
        sortable: true,
        format: (value: { name: string } | null) => value?.name || '-'
    },
    {
        key: 'base_price',
        label: 'Base Price',
        sortable: true,
        format: (value: number) => `Rp ${value.toLocaleString('id-ID')}`
    },
    {
        key: 'is_active',
        label: 'Status',
        sortable: true,
        format: (value: boolean) => value ? 'Active' : 'Inactive'
    },
    {
        key: 'is_featured',
        label: 'Featured',
        format: (value: boolean) => value ? 'Yes' : 'No'
    }
];
</script>

<template>
    <div>
        <Head title="Products" />

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-gray-600 mt-1">Manage your ERP products and subscriptions</p>
            </div>
            <Link
                href="/admin/products/create"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center space-x-2"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Product</span>
            </Link>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input
                        type="text"
                        placeholder="Search products..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
                <div>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">All Categories</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="clinic">Clinic</option>
                        <option value="legal">Legal</option>
                        <option value="workshop">Workshop</option>
                    </select>
                </div>
                <div>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                v-for="column in columns"
                                :key="column.key"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ column.label }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="!products || products.length === 0">
                            <td :colspan="columns.length + 1" class="px-6 py-8 text-center text-gray-500">
                                No products found
                            </td>
                        </tr>
                        <tr
                            v-for="product in products"
                            :key="product.id"
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                                    <div class="text-sm text-gray-500">{{ product.slug }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ product.category?.name || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ product.base_price.toLocaleString('id-ID') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="{
                                        'px-2 py-1 text-xs font-semibold rounded-full': true,
                                        'bg-green-100 text-green-800': product.is_active,
                                        'bg-red-100 text-red-800': !product.is_active
                                    }"
                                >
                                    {{ product.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span
                                    :class="{
                                        'px-2 py-1 text-xs font-semibold rounded-full': true,
                                        'bg-yellow-100 text-yellow-800': product.is_featured,
                                        'bg-gray-100 text-gray-800': !product.is_featured
                                    }"
                                >
                                    {{ product.is_featured ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <Link
                                        :href="`/admin/products/${product.id}/edit`"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Edit
                                    </Link>
                                    <button class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
