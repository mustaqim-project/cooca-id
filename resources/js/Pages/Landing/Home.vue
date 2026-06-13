<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

interface Product {
    id: string;
    name: string;
    slug: string;
    description: string;
    base_price: number;
    is_featured: boolean;
    category?: { name: string };
}

interface Feature {
    icon: string;
    title: string;
    description: string;
}

interface Testimonial {
    name: string;
    business: string;
    avatar: string;
    rating: number;
    comment: string;
}

interface Props {
    products: Product[];
    features: Feature[];
    testimonials: Testimonial[];
}

const props = defineProps<Props>();

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Cooca.id - Platform SaaS ERP Multi-Tenant" />

    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Platform SaaS ERP<br />untuk Bisnis Modern
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-indigo-100">
                        Kelola bisnis Anda dengan sistem ERP terintegrasi,<br />
                        aktif dalam kurang dari 1 jam
                    </p>
                    <div class="flex justify-center space-x-4">
                        <Link
                            href="/customer/register"
                            class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center"
                        >
                            🚀 Coba Gratis 7 Hari
                        </Link>
                        <Link
                            href="/pricing"
                            class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors"
                        >
                            Lihat Harga
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Produk ERP Kami</h2>
                    <p class="text-xl text-gray-600">Solusi lengkap untuk berbagai industri</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ product.category?.name || 'ERP' }}
                            </span>
                            <span v-if="product.is_featured" class="text-yellow-500">⭐ Featured</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ product.name }}</h3>
                        <p class="text-gray-600 mb-4">{{ product.description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-indigo-600">{{ formatCurrency(product.base_price) }}<span class="text-sm text-gray-500">/bulan</span></span>
                            <Link
                                :href="`/customer/products/${product.slug}`"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
                            >
                                Coba Gratis
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Kenapa Memilih Kami?</h2>
                    <p class="text-xl text-gray-600">Fitur lengkap untuk mendukung pertumbuhan bisnis Anda</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="text-center p-6"
                    >
                        <div class="text-5xl mb-4">{{ feature.icon }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ feature.title }}</h3>
                        <p class="text-gray-600">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Apa Kata Mereka?</h2>
                    <p class="text-xl text-gray-600">Testimoni dari pelanggan kami</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        v-for="testimonial in testimonials"
                        :key="testimonial.name"
                        class="bg-white rounded-xl shadow-lg p-6"
                    >
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ testimonial.avatar }}
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900">{{ testimonial.name }}</h4>
                                <p class="text-sm text-gray-600">{{ testimonial.business }}</p>
                            </div>
                        </div>
                        <div class="flex mb-2">
                            <span v-for="i in 5" :key="i" class="text-yellow-400">
                                {{ i <= testimonial.rating ? '★' : '☆' }}
                            </span>
                        </div>
                        <p class="text-gray-600 italic">"{{ testimonial.comment }}"</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-indigo-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Memulai?</h2>
                <p class="text-xl mb-8 text-indigo-100">Coba gratis selama 7 hari, tanpa kartu kredit</p>
                <Link
                    href="/customer/register"
                    class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                >
                    Daftar Sekarang - Gratis!
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
