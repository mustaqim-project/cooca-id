<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

interface Benefit {
    icon: string;
    title: string;
    description: string;
}

interface Step {
    step: number;
    title: string;
    description: string;
}

interface CommissionExample {
    monthly: {
        customers: number;
        avgPrice: number;
        commission: number;
    };
    yearly: {
        customers: number;
        avgPrice: number;
        commission: number;
    };
}

interface Props {
    benefits: Benefit[];
    howItWorks: Step[];
    commissionExample: CommissionExample;
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
    <Head title="Program Affiliate - Cooca.id" />

    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Program Affiliate</h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Dapatkan komisi hingga 25% dari setiap referral<br />
                    Plus 5% dari downline level 2 Anda
                </p>
                <div class="mt-8 flex justify-center space-x-4">
                    <Link
                        href="/affiliator/register"
                        class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                    >
                        Daftar Sekarang - Gratis!
                    </Link>
                    <Link
                        href="#how-it-works"
                        class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors"
                    >
                        Pelajari Lebih Lanjut
                    </Link>
                </div>
            </div>
        </section>

        <!-- Benefits -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Kenapa Jadi Affiliator?</h2>
                    <p class="text-xl text-gray-600">Keuntungan yang akan Anda dapatkan</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        v-for="benefit in benefits"
                        :key="benefit.title"
                        class="bg-white rounded-xl shadow-lg p-6 text-center"
                    >
                        <div class="text-5xl mb-4">{{ benefit.icon }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ benefit.title }}</h3>
                        <p class="text-gray-600">{{ benefit.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Cara Kerja</h2>
                    <p class="text-xl text-gray-600">Mulai menghasilkan dalam 4 langkah mudah</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div
                        v-for="step in howItWorks"
                        :key="step.step"
                        class="text-center relative"
                    >
                        <div class="w-16 h-16 bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            {{ step.step }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ step.title }}</h3>
                        <p class="text-gray-600">{{ step.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Commission Calculator -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Potensi Pendapatan</h2>
                    <p class="text-xl text-gray-600">Contoh perhitungan komisi</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Monthly -->
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Pembayaran Bulanan</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah Customer</span>
                                <span class="font-semibold">{{ commissionExample.monthly.customers }} customer</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga Rata-rata</span>
                                <span class="font-semibold">{{ formatCurrency(commissionExample.monthly.avgPrice) }}/bulan</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Transaksi</span>
                                <span class="font-semibold">{{ formatCurrency(commissionExample.monthly.customers * commissionExample.monthly.avgPrice) }}</span>
                            </div>
                            <div class="border-t pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Komisi Anda (25%)</span>
                                    <span class="text-3xl font-bold text-green-600">{{ formatCurrency(commissionExample.monthly.commission) }}</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Per bulan, recurring!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Yearly -->
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold mb-6">Pembayaran Tahunan</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-indigo-100">Jumlah Customer</span>
                                <span class="font-semibold">{{ commissionExample.yearly.customers }} customer</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-indigo-100">Harga Rata-rata</span>
                                <span class="font-semibold">{{ formatCurrency(commissionExample.yearly.avgPrice) }}/tahun</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-indigo-100">Total Transaksi</span>
                                <span class="font-semibold">{{ formatCurrency(commissionExample.yearly.customers * commissionExample.yearly.avgPrice) }}</span>
                            </div>
                            <div class="border-t border-indigo-400 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold">Komisi Anda (25%)</span>
                                    <span class="text-3xl font-bold text-yellow-300">{{ formatCurrency(commissionExample.yearly.commission) }}</span>
                                </div>
                                <p class="text-sm text-indigo-200 mt-2">Per tahun, langsung cair!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Multi-Level System -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Sistem Multi-Level</h2>
                    <p class="text-xl text-gray-600">Bangun jaringan dan dapatkan passive income</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div class="bg-blue-50 rounded-xl p-8 border-2 border-blue-200">
                        <div class="text-4xl mb-4">🎯</div>
                        <h3 class="text-2xl font-bold text-blue-900 mb-2">Level 1 - 25%</h3>
                        <p class="text-blue-700 mb-4">Referral langsung yang Anda ajak</p>
                        <ul class="space-y-2 text-blue-600">
                            <li>✓ Komisi 25% seumur hidup</li>
                            <li>✓ Akses ke dashboard lengkap</li>
                            <li>✓ Marketing materials provided</li>
                        </ul>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-8 border-2 border-purple-200">
                        <div class="text-4xl mb-4">🌟</div>
                        <h3 class="text-2xl font-bold text-purple-900 mb-2">Level 2 - 5%</h3>
                        <p class="text-purple-700 mb-4">Dari downline yang diajak referral Anda</p>
                        <ul class="space-y-2 text-purple-600">
                            <li>✓ Komisi 5% otomatis</li>
                            <li>✓ Tanpa batas jumlah downline</li>
                            <li>✓ Passive income sejati</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20 bg-indigo-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Siap Memulai?</h2>
                <p class="text-xl mb-8 text-indigo-100">Daftar sekarang dan mulai hasilkan komisi!</p>
                <Link
                    href="/affiliator/register"
                    class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                >
                    Daftar Sebagai Affiliator
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
