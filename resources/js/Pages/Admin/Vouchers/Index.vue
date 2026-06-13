<template>
    <AdminLayout title="Vouchers">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Voucher Management</h2>
                            <a :href="route('admin.vouchers.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                                Create Voucher
                            </a>
                        </div>

                        <!-- Search and Filters -->
                        <div class="mb-6 flex gap-4">
                            <input 
                                v-model="search" 
                                @input="fetchVouchers"
                                type="text" 
                                placeholder="Search vouchers..." 
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <select v-model="status" @change="fetchVouchers" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>

                        <!-- Vouchers Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min. Purchase</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Until</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="voucher in vouchers.data" :key="voucher.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ voucher.code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-blue-100 text-blue-800': voucher.type === 'percentage',
                                                'bg-purple-100 text-purple-800': voucher.type === 'nominal'
                                            }">
                                                {{ voucher.type === 'percentage' ? '%' : 'Rp' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ voucher.type === 'percentage' ? voucher.value + '%' : formatCurrency(voucher.value) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatCurrency(voucher.min_purchase || 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ voucher.usage_count }} / {{ voucher.max_usage || '∞' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(voucher.valid_until) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': voucher.status === 'active',
                                                'bg-red-100 text-red-800': voucher.status === 'inactive',
                                                'bg-gray-100 text-gray-800': voucher.status === 'expired'
                                            }">
                                                {{ voucher.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a :href="route('admin.vouchers.edit', voucher.id)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <button @click="deleteVoucher(voucher.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="vouchers.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in vouchers.last_page" 
                                    :key="page"
                                    @click="fetchVouchers(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === vouchers.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== vouchers.current_page
                                    }"
                                >
                                    {{ page }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const vouchers = ref({ data: [], current_page: 1, last_page: 1 });
const search = ref('');
const status = ref('');

const fetchVouchers = (page = 1) => {
    router.get(route('admin.vouchers.index'), { 
        search: search.value, 
        status: status.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            vouchers.value = response.props.vouchers;
        }
    });
};

const deleteVoucher = (id) => {
    if (confirm('Are you sure you want to delete this voucher?')) {
        router.delete(route('admin.vouchers.destroy', id));
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID').format(amount);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

onMounted(() => {
    fetchVouchers();
});
</script>
