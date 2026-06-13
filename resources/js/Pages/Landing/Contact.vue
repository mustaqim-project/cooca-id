<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

interface ContactInfo {
    type: string;
    label: string;
    value: string;
    icon: string;
}

interface Props {
    contactInfo: ContactInfo[];
}

const props = defineProps<Props>();

const form = ref({
    name: '',
    email: '',
    company: '',
    message: '',
});

const submitForm = () => {
    // TODO: Implement form submission
    console.log('Form submitted:', form.value);
};
</script>

<template>
    <Head title="Kontak Kami - Cooca.id" />

    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Hubungi Kami</h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Tim kami siap membantu Anda menemukan solusi ERP yang tepat
                </p>
            </div>
        </section>

        <!-- Contact Info & Form -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Info -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Kontak</h2>
                        <div class="space-y-6">
                            <div
                                v-for="info in contactInfo"
                                :key="info.label"
                                class="flex items-start space-x-4"
                            >
                                <div class="text-3xl">{{ info.icon }}</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ info.label }}</h3>
                                    <p class="text-gray-600">{{ info.value }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-6 bg-indigo-100 rounded-xl">
                            <h3 class="font-bold text-indigo-900 mb-2">Jam Operasional</h3>
                            <p class="text-indigo-700">Senin - Jumat: 09:00 - 18:00 WIB</p>
                            <p class="text-indigo-700">Sabtu: 09:00 - 15:00 WIB</p>
                            <p class="text-indigo-700 text-sm mt-2">Support 24/7 tersedia untuk pelanggan aktif</p>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Masukkan nama Anda"
                                />
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="nama@perusahaan.com"
                                />
                            </div>
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Perusahaan</label>
                                <input
                                    id="company"
                                    v-model="form.company"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Nama perusahaan Anda"
                                />
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                                <textarea
                                    id="message"
                                    v-model="form.message"
                                    rows="4"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Ceritakan kebutuhan Anda..."
                                ></textarea>
                            </div>
                            <button
                                type="submit"
                                class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors"
                            >
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Placeholder -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Lokasi Kami</h2>
                    <p class="text-xl text-gray-600">Jakarta, Indonesia</p>
                </div>
                <div class="bg-gray-200 rounded-xl h-96 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <div class="text-6xl mb-4">📍</div>
                        <p>Map integration coming soon</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
