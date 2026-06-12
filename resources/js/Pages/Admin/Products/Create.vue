<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    sku: '',
    category: '',
    type: 'subscription',
    description: '',
    features: [],
    base_price: '',
    trial_price: '',
    discount_price: '',
    image: null,
    status: 'active',
});

const submit = () => {
    form.post(route('admin.products.store'));
};
</script>

<template>
    <Head title="Create Product" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Product</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                                    <input id="name" v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
                                    <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                                </div>

                                <div>
                                    <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                                    <input id="sku" v-model="form.sku" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
                                    <span v-if="form.errors.sku" class="text-red-500 text-xs">{{ form.errors.sku }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                    <input id="category" v-model="form.category" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g., ERP, POS" required />
                                    <span v-if="form.errors.category" class="text-red-500 text-xs">{{ form.errors.category }}</span>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                    <select id="type" v-model="form.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="subscription">Subscription</option>
                                        <option value="one_time">One Time</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                <span v-if="form.errors.description" class="text-red-500 text-xs">{{ form.errors.description }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="base_price" class="block text-sm font-medium text-gray-700">Base Price (IDR)</label>
                                    <input id="base_price" v-model="form.base_price" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
                                    <span v-if="form.errors.base_price" class="text-red-500 text-xs">{{ form.errors.base_price }}</span>
                                </div>

                                <div>
                                    <label for="trial_price" class="block text-sm font-medium text-gray-700">Trial Price (IDR)</label>
                                    <input id="trial_price" v-model="form.trial_price" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    <span v-if="form.errors.trial_price" class="text-red-500 text-xs">{{ form.errors.trial_price }}</span>
                                </div>

                                <div>
                                    <label for="discount_price" class="block text-sm font-medium text-gray-700">Discount Price (IDR)</label>
                                    <input id="discount_price" v-model="form.discount_price" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    <span v-if="form.errors.discount_price" class="text-red-500 text-xs">{{ form.errors.discount_price }}</span>
                                </div>
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                                <input id="image" @change="form.image = $event.target.files[0]" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <span v-if="form.errors.image" class="text-red-500 text-xs">{{ form.errors.image }}</span>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <Link :href="route('admin.products.index')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">Cancel</Link>
                                <button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50">
                                    {{ form.processing ? 'Creating...' : 'Create Product' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
