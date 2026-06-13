<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

interface Stat {
    label: string;
    value: string;
}

interface TeamMember {
    name: string;
    role: string;
    avatar: string;
}

interface Props {
    stats: Stat[];
    team: TeamMember[];
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Tentang Kami - Cooca.id" />

    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-indigo-600 to-purple-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Tentang Cooca.id</h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Platform SaaS ERP multi-tenant yang membantu bisnis Indonesia bertransformasi digital
                </p>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div v-for="stat in stats" :key="stat.label" class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">{{ stat.value }}</div>
                        <div class="text-gray-600">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Story -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Cerita Kami</h2>
                        <p class="text-gray-600 mb-4">
                            Cooca.id didirikan dengan misi untuk memudahkan bisnis UMKM dan enterprise di Indonesia 
                            dalam mengadopsi teknologi ERP yang terjangkau dan mudah digunakan.
                        </p>
                        <p class="text-gray-600 mb-4">
                            Dengan pengalaman bertahun-tahun dalam pengembangan software enterprise, 
                            kami menghadirkan solusi multi-tenant yang scalable dan aman.
                        </p>
                        <p class="text-gray-600">
                            Platform kami mendukung berbagai industri mulai dari restoran, klinik, 
                            kantor hukum, hingga bengkel dengan fitur yang disesuaikan.
                        </p>
                    </div>
                    <div class="bg-indigo-100 rounded-xl p-8">
                        <div class="text-6xl text-center">🚀</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Tim Kami</h2>
                    <p class="text-xl text-gray-600">Orang-orang hebat di balik Cooca.id</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="member in team" :key="member.name" class="text-center">
                        <div class="w-24 h-24 bg-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                            {{ member.avatar }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ member.name }}</h3>
                        <p class="text-gray-600">{{ member.role }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="py-20 bg-indigo-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Nilai-Nilai Kami</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-4xl mb-4">💡</div>
                        <h3 class="text-xl font-bold mb-2">Inovasi</h3>
                        <p class="text-indigo-100">Terus berinovasi untuk memberikan solusi terbaik</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-4">🤝</div>
                        <h3 class="text-xl font-bold mb-2">Kepercayaan</h3>
                        <p class="text-indigo-100">Membangun kepercayaan dengan setiap pelanggan</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-4">⭐</div>
                        <h3 class="text-xl font-bold mb-2">Kualitas</h3>
                        <p class="text-indigo-100">Menjaga kualitas tinggi di setiap produk</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
