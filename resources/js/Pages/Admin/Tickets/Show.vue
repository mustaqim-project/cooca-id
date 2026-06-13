<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Textarea from '@/Components/Textarea.vue';
import { Head } from '@inertiajs/vue3';

interface Reply {
    id: number;
    from_name: string;
    from_type: 'customer' | 'affiliator' | 'admin';
    message: string;
    created_at: string;
}

interface Ticket {
    id: number;
    ticket_number: string;
    from_name: string;
    from_type: 'customer' | 'affiliator';
    subject: string;
    category: string;
    status: 'open' | 'in_progress' | 'resolved' | 'closed';
    priority: 'low' | 'medium' | 'high';
    created_at: string;
    replies: Reply[];
}

interface Props {
    ticket: Ticket;
}

defineProps<Props>();

const replyForm = useForm({
    message: '',
});

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const submitReply = () => {
    replyForm.post(route('admin.tickets.reply', props.ticket.id), {
        onSuccess: () => {
            replyForm.reset();
        },
    });
};

const resolveTicket = () => {
    if (confirm('Tandai tiket ini sebagai resolved?')) {
        router.post(route('admin.tickets.resolve', props.ticket.id));
    }
};

const closeTicket = () => {
    if (confirm('Tutup tiket ini? Tindakan ini tidak dapat dibatalkan.')) {
        router.post(route('admin.tickets.close', props.ticket.id));
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'open':
            return 'bg-blue-100 text-blue-800';
        case 'in_progress':
            return 'bg-yellow-100 text-yellow-800';
        case 'resolved':
            return 'bg-green-100 text-green-800';
        case 'closed':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head :title="`Tiket ${ticket.ticket_number}`" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900">{{ ticket.subject }}</h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ ticket.ticket_number }} • Dari: {{ ticket.from_name }} ({{ ticket.from_type }})
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    :class="getStatusColor(ticket.status)"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                >
                                    {{ ticket.status }}
                                </span>
                                <SecondaryButton @click="router.get(route('admin.tickets.index'))">
                                    Kembali
                                </SecondaryButton>
                            </div>
                        </div>

                        <!-- Ticket Info -->
                        <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 rounded-lg p-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-900 capitalize">{{ ticket.category }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Priority</dt>
                                <dd class="mt-1 text-sm text-gray-900 capitalize">{{ ticket.priority }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(ticket.created_at) }}</dd>
                            </div>
                        </div>

                        <!-- Thread -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Thread Percakapan</h3>
                            <div class="space-y-4">
                                <div
                                    v-for="reply in ticket.replies"
                                    :key="reply.id"
                                    :class="reply.from_type === 'admin' ? 'ml-12' : 'mr-12'"
                                >
                                    <div
                                        :class="reply.from_type === 'admin' ? 'bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-gray-200'"
                                        class="rounded-lg border p-4"
                                    >
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-900">{{ reply.from_name }}</span>
                                            <span class="text-xs text-gray-500">{{ formatDate(reply.created_at) }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ reply.message }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form -->
                        <div v-if="ticket.status !== 'closed'" class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Balas Tiket</h3>
                            <form @submit.prevent="submitReply">
                                <InputLabel for="message" value="Pesan Balasan" />
                                <Textarea
                                    id="message"
                                    v-model="replyForm.message"
                                    class="mt-1 block w-full min-h-[120px]"
                                    required
                                />
                                <InputError class="mt-2" :message="replyForm.errors.message" />
                                <div class="mt-4">
                                    <PrimaryButton :class="{ 'opacity-25': replyForm.processing }" :disabled="replyForm.processing">
                                        Kirim Balasan
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        <!-- Actions -->
                        <div v-if="ticket.status !== 'closed' && ticket.status !== 'resolved'" class="border-t border-gray-200 pt-6 flex gap-4">
                            <PrimaryButton
                                v-if="ticket.status !== 'resolved'"
                                @click="resolveTicket"
                                class="bg-green-600 hover:bg-green-700"
                            >
                                Tandai Resolved
                            </PrimaryButton>
                            <PrimaryButton
                                @click="closeTicket"
                                class="bg-red-600 hover:bg-red-700"
                            >
                                Tutup Tiket
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
