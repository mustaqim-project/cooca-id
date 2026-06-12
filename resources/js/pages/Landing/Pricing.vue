<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

interface SubscriptionPlan {
    id: string;
    name: string;
    price: number;
    duration_months: number;
    features: string[];
}

interface Product {
    id: string;
    name: string;
    slug: string;
    description: string;
    subscription_plans?: SubscriptionPlan[];
}

interface FAQ {
    question: string;
    answer: string;
}

interface Props {
    products: Product[];
    faq: FAQ[];
}

const props = defineProps<Props>();

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const openFAQ = ref<number | null>(null);
</script>

<template>
    <Head title="Harga - Cooca.id" />

    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Harga Transparan</h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Pilih paket yang sesuai dengan kebutuhan bisnis Anda<br />
                    Tanpa biaya tersembunyi, tanpa kontrak jangka panjang
                </p>
            </div>
        </section>

        <!-- Pricing Cards -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow"
                    >
                        <div class="bg-indigo-600 text-white p-6">
                            <h3 class="text-2xl font-bold">{{ product.name }}</h3>
                            <p class="text-indigo-100 mt-2">{{ product.description }}</p>
                        </div>
                        <div class="p-6">
                            <div v-if="product.subscription_plans && product.subscription_plans.length > 0">
                                <div
                                    v-for="plan in product.subscription_plans"
                                    :key="plan.id"
                                    class="border-b border-gray-200 last:border-0 py-4"
                                >
                                    <div class="flex items-baseline justify-between mb-2">
                                        <span class="font-semibold text-gray-900">{{ plan.name }}</span>
                                        <span class="text-2xl font-bold text-indigo-600">
                                            {{ formatCurrency(plan.price) }}
                                            <span class="text-sm text-gray-500">/{{ plan.duration_months }} bulan</span>
                                        </span>
                                    </div>
                                    <ul class="space-y-2 mb-4">
                                        <li v-for="feature in plan.features" :key="feature" class="text-sm text-gray-600 flex items-center">
                                            <span class="text-green-500 mr-2">✓</span>
                                            {{ feature }}
                                        </li>
                                    </ul>
                                    <Link
                                        :href="`/customer/products/${product.slug}`"
                                        class="block w-full bg-indigo-600 text-white text-center py-2 rounded-lg hover:bg-indigo-700 transition-colors"
                                    >
                                        Coba Gratis 7 Hari
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 mb-4">Hubungi kami untuk harga khusus</p>
                                <Link
                                    href="/contact"
                                    class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
                                >
                                    Hubungi Sales
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Comparison -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Semua Paket Termasuk</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-6">
                        <div class="text-4xl mb-4">⚡</div>
                        <h3 class="font-bold text-gray-900 mb-2">Aktivasi Cepat</h3>
                        <p class="text-gray-600 text-sm">Aktif dalam 1 jam</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-4xl mb-4">🔐</div>
                        <h3 class="font-bold text-gray-900 mb-2">Keamanan Data</h3>
                        <p class="text-gray-600 text-sm">Enkripsi end-to-end</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-4xl mb-4">💬</div>
                        <h3 class="font-bold text-gray-900 mb-2">Support 24/7</h3>
                        <p class="text-gray-600 text-sm">Tim support responsif</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-4xl mb-4">🔄</div>
                        <h3 class="font-bold text-gray-900 mb-2">Auto Backup</h3>
                        <p class="text-gray-600 text-sm">Backup otomatis harian</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Pertanyaan Umum</h2>
                </div>
                <div class="space-y-4">
                    <div
                        v-for="(item, index) in faq"
                        :key="index"
                        class="bg-white rounded-lg shadow-sm overflow-hidden"
                    >
                        <button
                            @click="openFAQ = openFAQ === index ? null : index"
                            class="w-full px-6 py-4 text-left flex items-center justify-between"
                        >
                            <span class="font-semibold text-gray-900">{{ item.question }}</span>
                            <span class="text-indigo-600">{{ openFAQ === index ? '−' : '+' }}</span>
                        </button>
                        <div v-show="openFAQ === index" class="px-6 pb-4 text-gray-600">
                            {{ item.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20 bg-indigo-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Masih Bingung?</h2>
                <p class="text-xl mb-8 text-indigo-100">Tim kami siap membantu Anda memilih paket terbaik</p>
                <Link
                    href="/contact"
                    class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                >
                    Hubungi Kami
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
