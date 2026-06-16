<template>
  <AdminLayout title="ERP Requests">
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-semibold mb-6">ERP Requests</h1>

            <!-- Status Filter -->
            <div class="mb-4 flex gap-2 flex-wrap">
              <button 
                v-for="status in statuses" 
                :key="status"
                @click="filterStatus = filterStatus === status ? '' : status"
                :class="[
                  'px-3 py-1 rounded-full text-sm',
                  filterStatus === status 
                    ? 'bg-blue-600 text-white' 
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                ]"
              >
                {{ formatStatus(status) }}
              </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="request in filteredRequests" :key="request.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ request.customer.name }}</div>
                      <div class="text-sm text-gray-500">{{ request.customer.email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ request.requested_domain || request.requested_subdomain }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ request.product?.name || 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(request.status)">
                        {{ formatStatus(request.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(request.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link :href="route('admin.erp-requests.show', request.id)" class="text-blue-600 hover:text-blue-900 mr-3">
                        View
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
              <Pagination :links="requests.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  requests: Object,
});

const filterStatus = ref('');

const statuses = [
  'submitted',
  'waiting_approval',
  'waiting_setup',
  'in_setup',
  'domain_setup',
  'testing',
  'active_trial',
  'trial_expired',
  'rejected'
];

const filteredRequests = computed(() => {
  if (!filterStatus.value) {
    return props.requests.data;
  }
  return props.requests.data.filter(r => r.status === filterStatus.value);
});

const formatStatus = (status) => {
  return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};

const getStatusClass = (status) => {
  const classes = {
    submitted: 'bg-gray-100 text-gray-800',
    waiting_approval: 'bg-yellow-100 text-yellow-800',
    waiting_setup: 'bg-blue-100 text-blue-800',
    in_setup: 'bg-purple-100 text-purple-800',
    domain_setup: 'bg-indigo-100 text-indigo-800',
    testing: 'bg-orange-100 text-orange-800',
    active_trial: 'bg-green-100 text-green-800',
    trial_expired: 'bg-red-100 text-red-800',
    rejected: 'bg-red-200 text-red-900'
  };
  return `px-2 py-1 rounded-full text-xs ${classes[status] || 'bg-gray-100 text-gray-800'}`;
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};
</script>
