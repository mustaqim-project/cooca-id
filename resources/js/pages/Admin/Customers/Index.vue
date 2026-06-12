<template>
    <AdminLayout title="Customers">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Customer Management</h2>
                        </div>

                        <!-- Search and Filters -->
                        <div class="mb-6 flex gap-4">
                            <input 
                                v-model="search" 
                                @input="fetchCustomers"
                                type="text" 
                                placeholder="Search customers..." 
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <select v-model="status" @change="fetchCustomers" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>

                        <!-- Customers Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="customer in customers.data" :key="customer.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.business_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.domain || customer.subdomain + '.cooca.id' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': customer.status === 'active',
                                                'bg-red-100 text-red-800': customer.status === 'inactive',
                                                'bg-yellow-100 text-yellow-800': customer.status === 'suspended'
                                            }">
                                                {{ customer.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(customer.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="viewCustomer(customer.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                            <button @click="editCustomer(customer.id)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                            <button @click="deleteCustomer(customer.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="customers.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in customers.last_page" 
                                    :key="page"
                                    @click="fetchCustomers(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === customers.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== customers.current_page
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
import AdminLayout from '@/layouts/AdminLayout.vue';

const customers = ref({ data: [], current_page: 1, last_page: 1 });
const search = ref('');
const status = ref('');

const fetchCustomers = (page = 1) => {
    router.get(route('admin.customers.index'), { 
        search: search.value, 
        status: status.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            customers.value = response.props.customers;
        }
    });
};

const viewCustomer = (id) => {
    router.visit(route('admin.customers.show', id));
};

const editCustomer = (id) => {
    router.visit(route('admin.customers.edit', id));
};

const deleteCustomer = (id) => {
    if (confirm('Are you sure you want to delete this customer?')) {
        router.delete(route('admin.customers.destroy', id));
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

onMounted(() => {
    fetchCustomers();
});
</script>
