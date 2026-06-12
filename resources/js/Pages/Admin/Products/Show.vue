<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    product: Object,
});
</script>

<template>
    <Head title="Product Detail" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Detail</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6 flex justify-between items-center">
                            <Link :href="route('admin.products.index')" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Products</Link>
                            <div class="space-x-2">
                                <Link :href="route('admin.products.edit', product.id)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Edit</Link>
                            </div>
                        </div>

                        <div v-if="product.image" class="mb-6">
                            <img :src="product.image" :alt="product.name" class="w-full h-64 object-cover rounded-lg" />
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ product.sku }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ product.category }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ product.type }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            <span :class="getStatusClass(product.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ product.status }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Base Price</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-bold">{{ formatCurrency(product.base_price) }}</dd>
                                    </div>
                                    <div v-if="product.trial_price">
                                        <dt class="text-sm font-medium text-gray-500">Trial Price</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatCurrency(product.trial_price) }}</dd>
                                    </div>
                                    <div v-if="product.discount_price">
                                        <dt class="text-sm font-medium text-gray-500">Discount Price</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatCurrency(product.discount_price) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <div class="prose max-w-none bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ product.description }}</p>
                            </div>
                        </div>

                        <div v-if="product.features && product.features.length" class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Features</h3>
                            <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg space-y-1">
                                <li v-for="feature in product.features" :key="feature" class="text-gray-700">{{ feature }}</li>
                            </ul>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <dt class="font-medium text-gray-500">Created At</dt>
                                    <dd class="text-gray-900">{{ formatDate(product.created_at) }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Updated At</dt>
                                    <dd class="text-gray-900">{{ formatDate(product.updated_at) }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Total Subscriptions</dt>
                                    <dd class="text-gray-900">{{ product.subscriptions_count || 0 }}</dd>
                                </div>
                            </dl>
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
        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
        },
        formatDate(date) {
            return new Date(date).toLocaleString('id-ID');
        },
        getStatusClass(status) {
            const classes = {
                'active': 'bg-green-100 text-green-800',
                'inactive': 'bg-red-100 text-red-800',
                'draft': 'bg-yellow-100 text-yellow-800',
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }
    }
};
</script>
