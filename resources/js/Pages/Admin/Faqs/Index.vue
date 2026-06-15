<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    faqs: Array,
});

const showDeleteModal = ref(false);
const deletingFaq = ref(null);
const form = useForm({
    question: '',
    answer: '',
    order: 0,
    is_active: true,
});

const openDeleteModal = (faq) => {
    deletingFaq.value = faq;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingFaq.value = null;
};

const deleteFaq = () => {
    if (deletingFaq.value) {
        router.delete(route('admin.faqs.destroy', deletingFaq.value.id), {
            preserveScroll: true,
            onSuccess: () => closeDeleteModal(),
        });
    }
};

const reorderFaqs = (direction) => {
    // Simple reorder implementation - in production use drag-and-drop
    const ids = faqs.value.map(f => f.id);
    if (direction === 'up') {
        // Move first to last
        ids.push(ids.shift());
    } else {
        // Move last to first
        ids.unshift(ids.pop());
    }
    router.post(route('admin.faqs.reorder'), { ids }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="FAQ Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">FAQ Management</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Frequently Asked Questions</h3>
                            <Link :href="route('admin.faqs.create')">
                                <PrimaryButton>Add New FAQ</PrimaryButton>
                            </Link>
                        </div>

                        <!-- Reorder Controls -->
                        <div class="flex gap-2 mb-4">
                            <SecondaryButton @click="reorderFaqs('up')">Move First to Last</SecondaryButton>
                            <SecondaryButton @click="reorderFaqs('down')">Move Last to First</SecondaryButton>
                        </div>

                        <!-- FAQ Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(faq, index) in faqs" :key="faq.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ faq.question }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-md">{{ faq.answer }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                faq.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            ]">
                                                {{ faq.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('admin.faqs.edit', faq.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                            <button @click="openDeleteModal(faq)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="faqs.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No FAQs found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Delete FAQ</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to delete this FAQ? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Cancel</SecondaryButton>
                    <DangerButton @click="deleteFaq" :disabled="form.processing">
                        Delete FAQ
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
