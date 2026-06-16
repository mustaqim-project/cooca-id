<template>
  <AdminLayout title="ERP Request Details">
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
              <h1 class="text-2xl font-semibold">ERP Request #{{ request.id.toString().split('-')[0] }}</h1>
              <span :class="getStatusClass(request.status)">
                {{ formatStatus(request.status) }}
              </span>
            </div>

            <!-- Customer Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium text-gray-700 mb-2">Customer Information</h3>
                <p class="text-sm"><span class="text-gray-500">Name:</span> {{ request.customer.name }}</p>
                <p class="text-sm"><span class="text-gray-500">Email:</span> {{ request.customer.email }}</p>
                <p class="text-sm"><span class="text-gray-500">Business:</span> {{ request.customer.business_name || 'N/A' }}</p>
              </div>

              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium text-gray-700 mb-2">Request Details</h3>
                <p class="text-sm"><span class="text-gray-500">Product:</span> {{ request.product?.name || 'N/A' }}</p>
                <p class="text-sm"><span class="text-gray-500">Domain:</span> {{ request.requested_domain || request.requested_subdomain || 'Not specified' }}</p>
                <p class="text-sm"><span class="text-gray-500">Affiliator:</span> {{ request.affiliator?.name || 'None' }}</p>
              </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
              <div v-if="request.notes" class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-4">
                <h3 class="font-medium text-yellow-800 mb-2">Customer Notes</h3>
                <p class="text-sm text-yellow-700">{{ request.notes }}</p>
              </div>
              <div v-if="request.admin_notes" class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                <h3 class="font-medium text-blue-800 mb-2">Admin Notes</h3>
                <p class="text-sm text-blue-700">{{ request.admin_notes }}</p>
              </div>
            </div>

            <!-- Timeline -->
            <div class="mb-6">
              <h3 class="font-medium text-gray-700 mb-3">Timeline</h3>
              <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Submitted:</span> {{ formatDate(request.created_at) }}</p>
                <p v-if="request.approved_at"><span class="text-gray-500">Approved:</span> {{ formatDate(request.approved_at) }}</p>
                <p v-if="request.setup_started_at"><span class="text-gray-500">Setup Started:</span> {{ formatDate(request.setup_started_at) }}</p>
                <p v-if="request.testing_at"><span class="text-gray-500">Testing:</span> {{ formatDate(request.testing_at) }}</p>
                <p v-if="request.activated_at"><span class="text-gray-500">Activated:</span> {{ formatDate(request.activated_at) }}</p>
                <p v-if="request.trial_starts_at"><span class="text-gray-500">Trial Starts:</span> {{ formatDate(request.trial_starts_at) }}</p>
                <p v-if="request.trial_ends_at"><span class="text-gray-500">Trial Ends:</span> {{ formatDate(request.trial_ends_at) }}</p>
              </div>
            </div>

            <!-- License Info -->
            <div v-if="request.license" class="mb-6 bg-green-50 border border-green-200 p-4 rounded-lg">
              <h3 class="font-medium text-green-800 mb-2">License Generated</h3>
              <p class="text-sm text-green-700"><span class="font-medium">License Code:</span> {{ request.license.license_code }}</p>
              <p class="text-sm text-green-700"><span class="font-medium">Token Code:</span> {{ request.license.token_code }}</p>
              <p class="text-sm text-green-700"><span class="font-medium">Domain:</span> {{ request.license.domain }}</p>
              <p class="text-sm text-green-700"><span class="font-medium">Expires:</span> {{ formatDate(request.license.expires_at) }}</p>
            </div>

            <!-- Actions -->
            <div class="border-t pt-6">
              <h3 class="font-medium text-gray-700 mb-3">Actions</h3>
              
              <!-- Approve Button -->
              <form v-if="request.status === 'submitted'" @submit.prevent="approve" class="inline-block mr-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                  Approve Request
                </button>
              </form>

              <!-- Reject Button -->
              <form v-if="request.status === 'submitted' || request.status === 'waiting_approval'" @submit.prevent="reject" class="inline-block mr-2">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                  Reject
                </button>
              </form>

              <!-- Status Progression Buttons -->
              <div v-if="['waiting_approval', 'waiting_setup'].includes(request.status)" class="inline-block mr-2">
                <Link :href="route('admin.erp-requests.mark-in-setup', request.id)" method="post" as="button" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                  Mark In Setup
                </Link>
              </div>

              <div v-if="request.status === 'in_setup'" class="inline-block mr-2">
                <Link :href="route('admin.erp-requests.mark-domain-setup', request.id)" method="post" as="button" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                  Mark Domain Setup
                </Link>
              </div>

              <div v-if="request.status === 'domain_setup'" class="inline-block mr-2">
                <Link :href="route('admin.erp-requests.mark-testing', request.id)" method="post" as="button" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                  Mark Testing
                </Link>
              </div>

              <!-- Confirm Ready Button -->
              <div v-if="request.status === 'testing'" class="inline-block">
                <form @submit.prevent="confirmReady" class="flex gap-2">
                  <input type="number" v-model="trialDays" min="1" max="365" placeholder="Days" class="border rounded px-2 py-1 w-20" />
                  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Confirm Ready & Activate Trial
                  </button>
                </form>
              </div>
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
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  request: Object,
});

const trialDays = ref(14);

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
  return `px-3 py-1 rounded-full text-sm ${classes[status] || 'bg-gray-100 text-gray-800'}`;
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const approve = () => {
  router.post(route('admin.erp-requests.approve', props.request.id));
};

const reject = () => {
  const reason = prompt('Enter rejection reason:');
  if (reason) {
    router.post(route('admin.erp-requests.reject', props.request.id), { rejection_reason: reason });
  }
};

const confirmReady = () => {
  router.post(route('admin.erp-requests.confirm-ready', props.request.id), { trial_days: trialDays.value });
};
</script>
