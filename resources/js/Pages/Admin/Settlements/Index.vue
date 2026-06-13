<template>
    <AdminLayout title="Settlements">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Settlement Management</h2>
                        </div>

                        <!-- Filters -->
                        <div class="mb-6 flex gap-4">
                            <select v-model="status" @change="fetchSettlements" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>

                        <!-- Settlements Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affiliator</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="settlement in settlements.data" :key="settlement.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ settlement.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ settlement.affiliator?.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ formatCurrency(settlement.amount) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ formatCurrency(settlement.fee) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ formatCurrency(settlement.net_amount) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ settlement.payment_method === 'bank' ? 'Bank Transfer' : 'E-Wallet' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-yellow-100 text-yellow-800': settlement.status === 'pending',
                                                'bg-green-100 text-green-800': settlement.status === 'approved',
                                                'bg-red-100 text-red-800': settlement.status === 'rejected',
                                                'bg-blue-100 text-blue-800': settlement.status === 'paid'
                                            }">
                                                {{ settlement.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(settlement.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="viewSettlement(settlement.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                            <button v-if="settlement.status === 'pending'" @click="approveSettlement(settlement.id)" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                            <button v-if="settlement.status === 'pending'" @click="rejectSettlement(settlement.id)" class="text-red-600 hover:text-red-900">Reject</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="settlements.last_page > 1">
                            <div class="flex justify-center gap-2">
                                <button 
                                    v-for="page in settlements.last_page" 
                                    :key="page"
                                    @click="fetchSettlements(page)"
                                    :class="{
                                        'px-4 py-2 rounded-md': true,
                                        'bg-indigo-600 text-white': page === settlements.current_page,
                                        'bg-gray-200 text-gray-700 hover:bg-gray-300': page !== settlements.current_page
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

const settlements = ref({ data: [], current_page: 1, last_page: 1 });
const status = ref('');

const fetchSettlements = (page = 1) => {
    router.get(route('admin.settlements.index'), { 
        status: status.value,
        page 
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            settlements.value = response.props.settlements;
        }
    });
};

const viewSettlement = (id) => {
    router.visit(route('admin.settlements.show', id));
};

const approveSettlement = (id) => {
    if (confirm('Approve this settlement?')) {
        router.post(route('admin.settlements.approve', id));
    }
};

const rejectSettlement = (id) => {
    if (confirm('Reject this settlement?')) {
        router.post(route('admin.settlements.reject', id));
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID').format(amount);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

onMounted(() => {
    fetchSettlements();
});
</script>
