<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { Product, SubscriptionPlan } from '@/types';

interface Props {
    product: Product;
    plans: SubscriptionPlan[];
}

const props = defineProps<Props>();

const selectedPlan = ref<number | null>(null);
const voucherCode = ref('');

const form = useForm({
    product_id: props.product.id,
    subscription_plan_id: 0,
    voucher_code: '',
});

const selectPlan = (planId: number) => {
    selectedPlan.value = planId;
    form.subscription_plan_id = planId;
};

const applyVoucher = () => {
    form.voucher_code = voucherCode.value;
    // Voucher will be validated on submit
};

const subscribe = () => {
    if (!selectedPlan.value) {
        alert('Silakan pilih plan terlebih dahulu');
        return;
    }
    form.post(route('customer.subscriptions.store'));
};

const popularPlanIndex = 1; // Second plan is usually most popular
</script>

<template>
    <Head :title="product.name" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Product Header -->
                <div class="mb-8">
                    <Link
                        :href="route('customer.products.index')"
                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium mb-4 inline-block"
                    >
                        ← Kembali ke Daftar Produk
                    </Link>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ product.name }}</h1>
                    <p class="text-gray-600">{{ product.description }}</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Product Details -->
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Fitur Produk</h2>
                            <ul class="space-y-2">
                                <li v-for="(feature, index) in product.features" :key="index" class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ feature }}</span>
                                </li>
                            </ul>

                            <div v-if="product.demo_url" class="mt-6 pt-6 border-t border-gray-200">
                                <a
                                    :href="product.demo_url"
                                    target="_blank"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center gap-1"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Lihat Demo
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Plans -->
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Pilih Plan Langganan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div
                                v-for="(plan, index) in plans"
                                :key="plan.id"
                                @click="selectPlan(plan.id)"
                                :class="[
                                    'relative border-2 rounded-lg p-6 cursor-pointer transition-all',
                                    selectedPlan === plan.id
                                        ? 'border-indigo-600 bg-indigo-50'
                                        : 'border-gray-200 hover:border-gray-300'
                                ]"
                            >
                                <!-- Popular Badge -->
                                <div
                                    v-if="index === popularPlanIndex"
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2"
                                >
                                    <span class="bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                        Paling Populer
                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ plan.name }}</h3>
                                <div class="mb-4">
                                    <span class="text-3xl font-bold text-gray-900">Rp {{ plan.price.toLocaleString('id-ID') }}</span>
                                    <span class="text-gray-600 text-sm">/{{ plan.billing_cycle }}</span>
                                </div>
                                <ul class="space-y-2 mb-4">
                                    <li v-for="(feature, fIndex) in plan.features" :key="fIndex" class="text-sm text-gray-700 flex items-start gap-2">
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ feature }}
                                    </li>
                                </ul>
                                <div
                                    :class="[
                                        'w-4 h-4 rounded-full border-2 flex items-center justify-center',
                                        selectedPlan === plan.id ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'
                                    ]"
                                >
                                    <div v-if="selectedPlan === plan.id" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Voucher Code -->
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Punya Kode Voucher? (Opsional)
                            </label>
                            <div class="flex gap-2">
                                <input
                                    v-model="voucherCode"
                                    type="text"
                                    placeholder="Masukkan kode voucher"
                                    class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                />
                                <button
                                    @click="applyVoucher"
                                    type="button"
                                    class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300"
                                >
                                    Terapkan
                                </button>
                            </div>
                            <p v-if="form.errors.voucher_code" class="text-red-500 text-xs mt-1">{{ form.errors.voucher_code }}</p>
                        </div>

                        <!-- Subscribe Button -->
                        <button
                            @click="subscribe"
                            :disabled="!selectedPlan || form.processing"
                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Memproses...' : (selectedPlan ? 'Langganan Sekarang' : 'Pilih Plan Terlebih Dahulu') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
