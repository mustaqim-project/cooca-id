<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ticket: Object,
});

const form = useForm({
    message: '',
    status: props.ticket.status,
    internal_note: '',
});

const submitReply = () => {
    form.post(route('admin.tickets.reply', props.ticket.id));
};

const updateStatus = () => {
    form.put(route('admin.tickets.update-status', props.ticket.id));
};
</script>

<template>
    <Head title="Ticket Detail" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ticket Detail</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <Link :href="route('admin.tickets.index')" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Tickets</Link>
                        </div>

                        <!-- Ticket Info -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ticket ID</dt>
                                <dd class="mt-1 text-sm font-bold text-gray-900">#{{ ticket.id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ticket.customer?.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Subject</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ticket.subject }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Priority</dt>
                                <dd class="mt-1">
                                    <span :class="getPriorityClass(ticket.priority)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        {{ ticket.priority }}
                                    </span>
                                </dd>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Conversation</h3>
                            <div class="space-y-4">
                                <div v-for="message in ticket.messages" :key="message.id" 
                                     :class="message.sender_type === 'customer' ? 'bg-blue-50' : 'bg-green-50'"
                                     class="p-4 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-medium text-sm text-gray-900">
                                            {{ message.sender_type === 'customer' ? ticket.customer?.name : 'Admin' }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ formatDate(message.created_at) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ message.message }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Send Reply</h3>
                            <form @submit.prevent="submitReply" class="space-y-4">
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">Your Reply</label>
                                    <textarea id="message" v-model="form.message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                    <span v-if="form.errors.message" class="text-red-500 text-xs">{{ form.errors.message }}</span>
                                </div>
                                <button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50">
                                    {{ form.processing ? 'Sending...' : 'Send Reply' }}
                                </button>
                            </form>
                        </div>

                        <!-- Update Status -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
                            <form @submit.prevent="updateStatus" class="flex items-center space-x-4">
                                <select v-model="form.status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="waiting_customer">Waiting Customer</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50">
                                    {{ form.processing ? 'Updating...' : 'Update Status' }}
                                </button>
                            </form>
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
        formatDate(date) {
            return new Date(date).toLocaleString('id-ID');
        },
        getPriorityClass(priority) {
            const classes = {
                'low': 'bg-green-100 text-green-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'high': 'bg-orange-100 text-orange-800',
                'urgent': 'bg-red-100 text-red-800',
            };
            return classes[priority] || 'bg-gray-100 text-gray-800';
        }
    }
};
</script>
