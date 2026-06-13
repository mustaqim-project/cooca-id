<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Review {
    id: number;
    reviewer_name: string;
    reviewer_type: 'customer' | 'affiliator';
    product_name: string;
    rating: number;
    comment: string;
    status: 'pending' | 'approved' | 'rejected';
    created_at: string;
}

interface Props {
    reviews: Review[];
    filters: {
        status: string | null;
        reviewer_type: string | null;
    };
}

defineProps<Props>();

const approveForm = useForm({});
const rejectForm = useForm({});

const approveReview = (reviewId: number) => {
    if (confirm('Apakah Anda yakin ingin menyetujui review ini?')) {
        approveForm.post(route('admin.reviews.approve', reviewId));
    }
};

const rejectReview = (reviewId: number) => {
    if (confirm('Apakah Anda yakin ingin menolak review ini?')) {
        rejectForm.post(route('admin.reviews.reject', reviewId));
    }
};

const deleteReview = (reviewId: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan.')) {
        router.delete(route('admin.reviews.destroy', reviewId));
    }
};

const renderStars = (rating: number) => {
    return Array.from({ length: 5 }, (_, i) => ({
        filled: i < rating,
        index: i,
    }));
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Moderasi Review" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-2xl font-semibold text-gray-900">Moderasi Review</h2>
                        </div>

                        <!-- Filters -->
                        <div class="mb-6 flex gap-4 flex-wrap">
                            <select
                                v-model="filters.status"
                                @change="router.get(route('admin.reviews.index'), { status: $event.target.value }, { preserveState: true })"
                                class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <select
                                v-model="filters.reviewer_type"
                                @change="router.get(route('admin.reviews.index'), { reviewer_type: $event.target.value }, { preserveState: true })"
                                class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Tipe</option>
                                <option value="customer">Customer</option>
                                <option value="affiliator">Affiliator</option>
                            </select>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Reviewer
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rating
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Komentar
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="reviews.length === 0">
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <p class="text-gray-500">Belum ada review untuk dimoderasi.</p>
                                        </td>
                                    </tr>
                                    <tr v-for="review in reviews" :key="review.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ review.reviewer_name }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ review.reviewer_type }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ review.product_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span
                                                    v-for="star in renderStars(review.rating)"
                                                    :key="star.index"
                                                    :class="star.filled ? 'text-yellow-400' : 'text-gray-300'"
                                                >
                                                    ★
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 line-clamp-2 max-w-xs">{{ review.comment }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800': review.status === 'pending',
                                                    'bg-green-100 text-green-800': review.status === 'approved',
                                                    'bg-red-100 text-red-800': review.status === 'rejected',
                                                }"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                            >
                                                {{ review.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(review.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <button
                                                    v-if="review.status === 'pending'"
                                                    @click="approveReview(review.id)"
                                                    class="text-green-600 hover:text-green-900"
                                                >
                                                    Approve
                                                </button>
                                                <button
                                                    v-if="review.status === 'pending'"
                                                    @click="rejectReview(review.id)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Reject
                                                </button>
                                                <button
                                                    @click="deleteReview(review.id)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
