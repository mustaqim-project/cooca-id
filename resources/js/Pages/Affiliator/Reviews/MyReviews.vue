<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import { Review, Product } from '@/types';

interface Props {
    reviews: Review[];
    products: Product[];
}

const props = defineProps<Props>();

const showForm = ref(false);
const selectedProduct = ref('');
const rating = ref(0);
const comment = ref('');

const form = useForm({
    product_id: '',
    rating: 0,
    comment: '',
});

const submitReview = () => {
    form.post(route('affiliator.reviews.store'), {
        onSuccess: () => {
            resetForm();
            showForm.value = false;
        },
    });
};

const resetForm = () => {
    form.reset();
    selectedProduct.value = '';
    rating.value = 0;
    comment.value = '';
};

const setRating = (value: number) => {
    rating.value = value;
    form.rating = value;
};

const startNewReview = () => {
    showForm.value = true;
    selectedProduct.value = '';
    rating.value = 0;
    comment.value = '';
    form.reset();
};

const statusLabels: Record<string, string> = {
    pending: 'Menunggu Moderasi',
    approved: 'Disetujui',
    rejected: 'Ditolak',
};

const statusColors: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
};

const deleteReview = (reviewId: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus review ini?')) {
        form.delete(route('affiliator.reviews.destroy', reviewId));
    }
};
</script>

<template>
    <Head title="Review Saya" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                Review yang Saya Tulis
                            </h2>
                            <button
                                @click="startNewReview"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tulis Review
                            </button>
                        </div>

                        <!-- Form Tambah Review -->
                        <div v-if="showForm" class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tulis Review Baru</h3>
                            <form @submit.prevent="submitReview">
                                <div class="mb-4">
                                    <label for="product" class="block text-sm font-medium text-gray-700 mb-1">
                                        Produk
                                    </label>
                                    <select
                                        id="product"
                                        v-model="form.product_id"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="">Pilih Produk</option>
                                        <option v-for="product in products" :key="product.id" :value="product.id">
                                            {{ product.name }}
                                        </option>
                                    </select>
                                    <span v-if="form.errors.product_id" class="text-red-500 text-xs mt-1">{{ form.errors.product_id }}</span>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Rating
                                    </label>
                                    <div class="flex gap-1">
                                        <button
                                            v-for="star in 5"
                                            :key="star"
                                            type="button"
                                            @click="setRating(star)"
                                            class="focus:outline-none"
                                        >
                                            <svg
                                                :class="[
                                                    'h-8 w-8',
                                                    star <= rating ? 'text-yellow-400 fill-current' : 'text-gray-300'
                                                ]"
                                                viewBox="0 0 20 20"
                                            >
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span v-if="form.errors.rating" class="text-red-500 text-xs mt-1">{{ form.errors.rating }}</span>
                                </div>

                                <div class="mb-4">
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                                        Komentar
                                    </label>
                                    <textarea
                                        id="comment"
                                        v-model="form.comment"
                                        rows="4"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        placeholder="Tulis pengalaman Anda menggunakan produk ini..."
                                        required
                                    ></textarea>
                                    <span v-if="form.errors.comment" class="text-red-500 text-xs mt-1">{{ form.errors.comment }}</span>
                                </div>

                                <div class="flex gap-3">
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                    >
                                        {{ form.processing ? 'Menyimpan...' : 'Simpan Review' }}
                                    </button>
                                    <button
                                        type="button"
                                        @click="showForm = false"
                                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- List Review -->
                        <div v-if="reviews.length === 0 && !showForm" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada review</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Anda belum menulis review untuk produk apapun.
                            </p>
                        </div>

                        <div v-else-if="reviews.length > 0" class="space-y-4">
                            <div v-for="review in reviews" :key="review.id" class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-sm font-medium text-gray-900">{{ review.product_name }}</span>
                                            <span :class="['px-2 py-1 text-xs rounded-full', statusColors[review.status]]">
                                                {{ statusLabels[review.status] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400">
                                                <template v-for="star in 5" :key="star">
                                                    <svg v-if="star <= review.rating" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                    <svg v-else class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </template>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 text-sm">{{ review.comment }}</p>
                                        <p class="text-xs text-gray-500 mt-2">
                                            {{ new Date(review.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                                        </p>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button
                                            @click="deleteReview(review.id)"
                                            class="text-red-600 hover:text-red-900 text-sm"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>
