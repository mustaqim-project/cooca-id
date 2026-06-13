<template>
    <AdminLayout title="Tickets">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Support Tickets</h1>
                <p class="mt-1 text-sm text-gray-500">Manage customer support tickets</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-gray-500">Total</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ stats.total }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-green-600">Open</dt>
                    <dd class="mt-1 text-2xl font-semibold text-green-600">{{ stats.open }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-blue-600">In Progress</dt>
                    <dd class="mt-1 text-2xl font-semibold text-blue-600">{{ stats.in_progress }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-yellow-600">Resolved</dt>
                    <dd class="mt-1 text-2xl font-semibold text-yellow-600">{{ stats.resolved }}</dd>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <dt class="text-sm font-medium text-gray-600">Closed</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-600">{{ stats.closed }}</dd>
                </div>
            </div>

            <!-- Tickets Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="ticket in tickets.data" :key="ticket.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ticket.subject }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ticket.customer?.name || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-red-100 text-red-800': ticket.priority === 'high',
                                        'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                        'bg-gray-100 text-gray-800': ticket.priority === 'low'
                                    }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ ticket.priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'bg-green-100 text-green-800': ticket.status === 'open',
                                        'bg-blue-100 text-blue-800': ticket.status === 'in_progress',
                                        'bg-yellow-100 text-yellow-800': ticket.status === 'resolved',
                                        'bg-gray-100 text-gray-800': ticket.status === 'closed'
                                    }" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                        {{ ticket.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(ticket.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a :href="`/admin/tickets/${ticket.id}`" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            <tr v-if="tickets.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No tickets found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            Showing {{ tickets.from || 0 }} to {{ tickets.to || 0 }} of {{ tickets.total || 0 }} results
                        </p>
                        <div class="flex space-x-2">
                            <button v-for="link in tickets.links" :key="link.label" 
                                    :disabled="!link.url"
                                    class="px-3 py-1 rounded border"
                                    :class="link.active ? 'bg-blue-500 text-white' : 'bg-white'">
                                <span v-html="link.label"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    tickets: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID');
};
</script>
