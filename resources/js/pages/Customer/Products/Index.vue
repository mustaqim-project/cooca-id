<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

interface Product {
  id: string;
  name: string;
  slug: string;
  short_description: string;
  base_price: number;
  thumbnail: string | null;
  features: string[];
  is_featured: boolean;
}

interface Plan {
  id: string;
  name: string;
  duration_months: number;
  price: number;
  discount_percent: number;
}

interface Props {
  products: Product[];
  plans: Record<string, Plan[]>;
}

const props = defineProps<Props>();

const selectedProduct = ref<string | null>(null);

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
};

const getDurationLabel = (months: number) => {
  if (months === 1) return 'Bulanan';
  if (months === 3) return 'Triwulanan';
  if (months === 6) return 'Semesteran';
  if (months === 12) return 'Tahunan';
  return `${months} Bulan`;
};

const calculateDiscountedPrice = (price: number, discount: number) => {
  return price - (price * discount / 100);
};

const selectProduct = (productId: string) => {
  selectedProduct.value = productId === selectedProduct.value ? null : productId;
};
</script>

<template>
  <Head title="Harga & Paket" />

  <AppLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-600 to-purple-700 py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
          Pilih Paket yang Sesuai Bisnis Anda
        </h1>
        <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
          Sistem ERP profesional dengan harga terjangkau. Semua paket termasuk lisensi, 
          update gratis, dan support 24/7.
        </p>
      </div>
    </section>

    <!-- Products Grid -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">Sistem ERP Tersedia</h2>
          <p class="text-lg text-gray-600">Pilih sistem yang sesuai dengan jenis bisnis Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="product in products"
            :key="product.id"
            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
          >
            <div class="h-48 bg-gray-200 flex items-center justify-center">
              <img
                v-if="product.thumbnail"
                :src="product.thumbnail"
                :alt="product.name"
                class="w-full h-full object-cover"
              />
              <div v-else class="text-6xl">🏢</div>
            </div>
            
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">{{ product.name }}</h3>
                <span
                  v-if="product.is_featured"
                  class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                >
                  Populer
                </span>
              </div>
              
              <p class="text-gray-600 mb-4 line-clamp-2">{{ product.short_description }}</p>
              
              <div class="mb-4">
                <p class="text-2xl font-bold text-indigo-600">{{ formatCurrency(product.base_price) }}</p>
                <p class="text-sm text-gray-500">per bulan</p>
              </div>

              <ul class="space-y-2 mb-6">
                <li
                  v-for="(feature, index) in product.features.slice(0, 5)"
                  :key="index"
                  class="flex items-start text-sm text-gray-600"
                >
                  <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  {{ feature }}
                </li>
              </ul>

              <button
                @click="selectProduct(product.id)"
                :class="[
                  selectedProduct === product.id ? 'bg-indigo-700' : 'bg-indigo-600 hover:bg-indigo-700',
                  'w-full py-3 px-4 rounded-lg text-white font-medium transition-colors duration-200',
                ]"
              >
                {{ selectedProduct === product.id ? 'Lihat Paket' : 'Pilih Sistem' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing Plans Modal -->
    <div
      v-if="selectedProduct"
      class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
      @click.self="selectedProduct = null"
    >
      <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
          <h3 class="text-2xl font-bold text-gray-900">
            Pilih Paket Langganan
          </h3>
          <button
            @click="selectedProduct = null"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="plan in plans[selectedProduct] || []"
              :key="plan.id"
              :class="[
                'border-2 rounded-xl p-6 transition-all duration-200 cursor-pointer',
                plan.discount_percent >= 20
                  ? 'border-yellow-400 bg-yellow-50'
                  : 'border-gray-200 hover:border-indigo-300',
              ]"
            >
              <div class="text-center mb-4">
                <h4 class="text-lg font-bold text-gray-900">{{ plan.name }}</h4>
                <p class="text-sm text-gray-500">{{ getDurationLabel(plan.duration_months) }}</p>
              </div>

              <div class="text-center mb-4">
                <div v-if="plan.discount_percent > 0" class="flex items-center justify-center space-x-2">
                  <span class="text-gray-400 line-through text-sm">{{ formatCurrency(plan.price * plan.duration_months) }}</span>
                  <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">
                    -{{ plan.discount_percent }}%
                  </span>
                </div>
                <p class="text-3xl font-bold text-indigo-600">
                  {{ formatCurrency(calculateDiscountedPrice(plan.price * plan.duration_months, plan.discount_percent)) }}
                </p>
                <p class="text-sm text-gray-500">total untuk {{ plan.duration_months }} bulan</p>
              </div>

              <ul class="space-y-2 mb-6 text-sm">
                <li class="flex items-center text-gray-600">
                  <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  Lisensi penuh
                </li>
                <li class="flex items-center text-gray-600">
                  <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  Update gratis
                </li>
                <li class="flex items-center text-gray-600">
                  <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  Support 24/7
                </li>
                <li v-if="plan.duration_months >= 12" class="flex items-center text-gray-600">
                  <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  Setup gratis
                </li>
              </ul>

              <Link
                :href="route('customer.subscriptions.create', { product: selectedProduct, plan: plan.id })"
                class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 rounded-lg font-medium transition-colors duration-200"
              >
                Berlangganan Sekarang
              </Link>
            </div>
          </div>

          <div v-if="!plans[selectedProduct] || plans[selectedProduct].length === 0" class="text-center py-12">
            <p class="text-gray-500">Paket langganan sedang dalam proses penyiapan.</p>
          </div>
        </div>

        <div class="p-6 border-t border-gray-200 bg-gray-50">
          <div class="flex items-center justify-center space-x-6 text-sm text-gray-600">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Garansi 30 hari
            </div>
            <div class="flex items-center">
              <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Cancel kapan saja
            </div>
            <div class="flex items-center">
              <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Secure payment
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">Kenapa Memilih Cooca.id?</h2>
          <p class="text-lg text-gray-600">Lebih dari sekadar software, kami adalah partner bisnis Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="text-4xl mb-4">🔒</div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Keamanan Terjamin</h3>
            <p class="text-gray-600">Data Anda dilindungi dengan enkripsi tingkat enterprise dan backup otomatis</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">⚡</div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Performa Tinggi</h3>
            <p class="text-gray-600">Infrastruktur cloud yang scalable memastikan sistem selalu responsif</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">🎧</div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Support 24/7</h3>
            <p class="text-gray-600">Tim support kami siap membantu Anda kapan saja melalui berbagai channel</p>
          </div>
          <div class="text-center">
            <div class="text-4xl mb-4">🔄</div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Update Berkala</h3>
            <p class="text-gray-600">Fitur baru dan perbaikan bug secara rutin tanpa biaya tambahan</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
          Siap Mengoptimalkan Bisnis Anda?
        </h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
          Mulai sekarang juga dengan trial gratis 14 hari. Tidak perlu kartu kredit.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <Link
            :href="route('customer.register')"
            class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition-colors duration-200"
          >
            Daftar Gratis
          </Link>
          <Link
            :href="route('landing.about')"
            class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:bg-opacity-10 transition-colors duration-200"
          >
            Pelajari Lebih Lanjut
          </Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
