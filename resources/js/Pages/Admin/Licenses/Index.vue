<template>
    <AdminLayout title="Licenses">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">License Management</h2>
                            <button @click="showGenerateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                                Generate License
                            </button>
                        </div>

                        <!-- Search and Filters -->
                        <div class="mb-6 flex gap-4">
                            <input 
                                v-model="search" 
                                @input="fetchLicenses"
                                type="text" 
                                placeholder="Search licenses..." 
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <select v-model="status" @change="fetchLicenses" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="revoked">Revoked</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>

                        <!-- Licenses Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="license in licenses.data" :key="license.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ license.license_code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ license.token_code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ license.customer?.name || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ license.domain || license.subdomain + '.cooca.id' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ license.product?.name || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': license.status === 'active',
                                                'bg-red-100 text-red-800': license.status === 'revoked',
                                                'bg-yellow-100 text-yellow-800': license.status === 'inactive',
                                                'bg-gray-100 text-gray-800': license.status === 'expired'
                                            }">
                                                {{ license.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(license.expires_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="viewLicense(license.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                            <button v-if="license.status === 'inactive'" @click="activateLicense(license.id)" class="text-green-600 hover:text-green-900 mr-3">Activate</button>
                                            <button v-if="license.status === 'active'" @click="revokeLicense(license.id)" class="text-red-600 hover:text-red-900">Revoke</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="licenses.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in licenses.last_page" 
                                    :key="page"
                                    @click="fetchLicenses(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === licenses.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== licenses.current_page
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

        <!-- Generate License Modal -->
        <div v-if="showGenerateModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-xl font-bold mb-4">Generate License</h3>
                <form @submit.prevent="generateLicense">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                        <select v-model="formData.customer_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Customer</option>
                            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                        <select v-model="formData.product_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Product</option>
                            <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Domain (optional)</label>
                        <input v-model="formData.domain" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="example.com or leave empty for subdomain">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subscription Plan</label>
                        <select v-model="formData.plan" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="trial">Trial</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">3 Months</option>
                            <option value="semi_annual">6 Months</option>
                            <option value="annual">1 Year</option>
                            <option value="lifetime">Lifetime</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showGenerateModal = false" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const licenses = ref({ data: [], current_page: 1, last_page: 1 });
const customers = ref([]);
const products = ref([]);
const search = ref('');
const status = ref('');
const showGenerateModal = ref(false);
const formData = ref({
    customer_id: '',
    product_id: '',
    domain: '',
    plan: 'trial'
});

const fetchLicenses = (page = 1) => {
    router.get(route('admin.licenses.index'), { 
        search: search.value, 
        status: status.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            licenses.value = response.props.licenses;
        }
    });
};

const fetchCustomers = () => {
    router.get('/api/admin/customers', {}, {
        onSuccess: (response) => {
            customers.value = response.props.customers?.data || [];
        }
    });
};

const fetchProducts = () => {
    router.get('/api/admin/products', {}, {
        onSuccess: (response) => {
            products.value = response.props.products?.data || [];
        }
    });
};

const generateLicense = () => {
    router.post(route('admin.licenses.generate'), formData.value, {
        onSuccess: () => {
            showGenerateModal.value = false;
            formData.value = { customer_id: '', product_id: '', domain: '', plan: 'trial' };
            fetchLicenses();
        }
    });
};

const viewLicense = (id) => {
    router.visit(route('admin.licenses.show', id));
};

const activateLicense = (id) => {
    if (confirm('Activate this license?')) {
        router.post(route('admin.licenses.activate', id));
    }
};

const revokeLicense = (id) => {
    if (confirm('Revoke this license? This action cannot be undone.')) {
        router.post(route('admin.licenses.revoke', id));
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

onMounted(() => {
    fetchLicenses();
    fetchCustomers();
    fetchProducts();
});
</script>
