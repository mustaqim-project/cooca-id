<template>
    <AdminLayout title="Affiliators">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Affiliator Management</h2>
                        </div>

                        <!-- Search and Filters -->
                        <div class="mb-6 flex gap-4">
                            <input 
                                v-model="search" 
                                @input="fetchAffiliators"
                                type="text" 
                                placeholder="Search affiliators..." 
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <select v-model="status" @change="fetchAffiliators" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Affiliators Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referral Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Referrals</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Commission</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="affiliator in affiliators.data" :key="affiliator.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ affiliator.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ affiliator.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ affiliator.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ affiliator.referral_code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ affiliator.total_referrals || 0 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ formatCurrency(affiliator.total_commission || 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': affiliator.status === 'active',
                                                'bg-red-100 text-red-800': affiliator.status === 'inactive'
                                            }">
                                                {{ affiliator.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="viewAffiliator(affiliator.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                            <button @click="editAffiliator(affiliator.id)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                            <button @click="deleteAffiliator(affiliator.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="affiliators.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in affiliators.last_page" 
                                    :key="page"
                                    @click="fetchAffiliators(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === affiliators.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== affiliators.current_page
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

const affiliators = ref({ data: [], current_page: 1, last_page: 1 });
const search = ref('');
const status = ref('');

const fetchAffiliators = (page = 1) => {
    router.get(route('admin.affiliators.index'), { 
        search: search.value, 
        status: status.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            affiliators.value = response.props.affiliators;
        }
    });
};

const viewAffiliator = (id) => {
    router.visit(route('admin.affiliators.show', id));
};

const editAffiliator = (id) => {
    router.visit(route('admin.affiliators.edit', id));
};

const deleteAffiliator = (id) => {
    if (confirm('Are you sure you want to delete this affiliator?')) {
        router.delete(route('admin.affiliators.destroy', id));
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID').format(amount);
};

onMounted(() => {
    fetchAffiliators();
});
</script>
