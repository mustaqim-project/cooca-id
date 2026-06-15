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
    testimonials: Array,
});

const showDeleteModal = ref(false);
const deletingTestimonial = ref(null);
const form = useForm({
    name: '',
    role: '',
    company: '',
    content: '',
    image_url: '',
    order: 0,
    is_featured: false,
    is_active: true,
});

const openDeleteModal = (testimonial) => {
    deletingTestimonial.value = testimonial;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingTestimonial.value = null;
};

const deleteTestimonial = () => {
    if (deletingTestimonial.value) {
        router.delete(route('admin.testimonials.destroy', deletingTestimonial.value.id), {
            preserveScroll: true,
            onSuccess: () => closeDeleteModal(),
        });
    }
};

const toggleFeatured = (testimonial) => {
    router.post(route('admin.testimonials.toggle-featured', testimonial.id), {}, {
        preserveScroll: true,
    });
};

const reorderTestimonials = (direction) => {
    const ids = testimonials.value.map(t => t.id);
    if (direction === 'up') {
        ids.push(ids.shift());
    } else {
        ids.unshift(ids.pop());
    }
    router.post(route('admin.testimonials.reorder'), { ids }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Testimonial Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Testimonial Management</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Customer Testimonials</h3>
                            <Link :href="route('admin.testimonials.create')">
                                <PrimaryButton>Add New Testimonial</PrimaryButton>
                            </Link>
                        </div>

                        <!-- Reorder Controls -->
                        <div class="flex gap-2 mb-4">
                            <SecondaryButton @click="reorderTestimonials('up')">Move First to Last</SecondaryButton>
                            <SecondaryButton @click="reorderTestimonials('down')">Move Last to First</SecondaryButton>
                        </div>

                        <!-- Testimonial Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role/Company</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(testimonial, index) in testimonials" :key="testimonial.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ testimonial.name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ testimonial.role }}<br>
                                            <span class="text-xs text-gray-400">{{ testimonial.company }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-md">{{ testimonial.content }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button 
                                                @click="toggleFeatured(testimonial)"
                                                :class="[
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer',
                                                    testimonial.is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'
                                                ]"
                                            >
                                                {{ testimonial.is_featured ? '★ Featured' : '☆ Not Featured' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                testimonial.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            ]">
                                                {{ testimonial.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('admin.testimonials.edit', testimonial.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                            <button @click="openDeleteModal(testimonial)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="testimonials.length === 0">
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No testimonials found</td>
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
                <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Testimonial</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to delete this testimonial? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Cancel</SecondaryButton>
                    <DangerButton @click="deleteTestimonial" :disabled="form.processing">
                        Delete Testimonial
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
