<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import StatCards from '@/Components/ui/StatCards.vue';
import Button from '@/Components/ui/Button.vue';

interface Commission {
  id: string;
  transaction_invoice: string;
  customer_name: string;
  level: 1 | 2;
  gross_amount: number;
  commission_percent: number;
  commission_amount: number;
  status: 'pending' | 'cleared' | 'cancelled';
  created_at: string;
}

interface Referral {
  id: string;
  name: string;
  email: string;
  joined_at: string;
  level: 1 | 2;
  total_purchases: number;
  total_commission: number;
}

interface Props {
  commissions: Commission[];
  referrals: Referral[];
  downlines: Referral[];
  stats: {
    totalBalance: number;
    totalEarned: number;
    pendingCommission: number;
    totalReferrals: number;
    level1Count: number;
    level2Count: number;
  };
}

const props = defineProps<Props>();

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    cleared: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const activeTab = ref<'commissions' | 'referrals' | 'downlines'>('commissions');
</script>

<template>
  <Head title="Dashboard" />

  <AffiliatorLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Affiliator</h1>
        <Link :href="route('affiliator.withdrawals.create')" class="inline-flex">
          <Button variant="primary">Tarik Saldo</Button>
        </Link>
      </div>

      <!-- Stats -->
      <StatCards
        :stats="[
          { label: 'Saldo Tersedia', value: formatCurrency(stats.totalBalance), icon: '💰', trend: 'up' },
          { label: 'Total Pendapatan', value: formatCurrency(stats.totalEarned), icon: '📈', trend: 'up' },
          { label: 'Komisi Pending', value: formatCurrency(stats.pendingCommission), icon: '⏳', trend: 'stable' },
          { label: 'Total Referral', value: stats.totalReferrals.toString(), icon: '👥', trend: 'up' },
        ]"
      />

      <!-- Level Breakdown -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-blue-100 text-sm">Level 1 (25%)</p>
              <p class="text-3xl font-bold mt-2">{{ stats.level1Count }}</p>
              <p class="text-blue-100 text-xs mt-1">Referral langsung</p>
            </div>
            <div class="text-4xl">🎯</div>
          </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-purple-100 text-sm">Level 2 (5%)</p>
              <p class="text-3xl font-bold mt-2">{{ stats.level2Count }}</p>
              <p class="text-purple-100 text-xs mt-1">Dari downline</p>
            </div>
            <div class="text-4xl">🌟</div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            @click="activeTab = 'commissions'"
            :class="[
              activeTab === 'commissions'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
            ]"
          >
            Komisi
          </button>
          <button
            @click="activeTab = 'referrals'"
            :class="[
              activeTab === 'referrals'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
            ]"
          >
            Referral Langsung
          </button>
          <button
            @click="activeTab = 'downlines'"
            :class="[
              activeTab === 'downlines'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
            ]"
          >
            Downline (Level 2)
          </button>
        </nav>
      </div>

      <!-- Commissions Tab -->
      <div v-if="activeTab === 'commissions'" class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Riwayat Komisi</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komisi (%)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diterima</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="comm in commissions" :key="comm.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ comm.transaction_invoice }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ comm.customer_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="comm.level === 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'" class="px-2 py-1 text-xs font-medium rounded-full">
                    Level {{ comm.level }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(comm.gross_amount) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ comm.commission_percent }}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ formatCurrency(comm.commission_amount) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', getStatusColor(comm.status)]">
                    {{ comm.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(comm.created_at) }}</td>
              </tr>
              <tr v-if="commissions.length === 0">
                <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">
                  Belum ada komisi. Komisi akan muncul setelah referral melakukan pembayaran.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Referrals Tab -->
      <div v-if="activeTab === 'referrals'" class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Referral Langsung (Level 1)</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembelian</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Komisi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="ref in referrals" :key="ref.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ref.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ref.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(ref.joined_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ref.total_purchases }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">{{ formatCurrency(ref.total_commission) }}</td>
              </tr>
              <tr v-if="referrals.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                  Belum ada referral langsung. Bagikan kode referral Anda untuk mulai mengundang.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Downlines Tab -->
      <div v-if="activeTab === 'downlines'" class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Downline (Level 2)</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembelian</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Komisi (5%)</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="down in downlines" :key="down.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ down.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ down.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(down.joined_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ down.total_purchases }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-600">{{ formatCurrency(down.total_commission) }}</td>
              </tr>
              <tr v-if="downlines.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                  Belum ada downline. Downline akan muncul ketika referral Anda mengundang orang lain.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Referral Code Section -->
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow p-6 text-white">
        <h3 class="text-lg font-semibold mb-4">Kode Referral Anda</h3>
        <div class="flex items-center space-x-4">
          <div class="flex-1 bg-white bg-opacity-20 rounded-lg px-4 py-3 font-mono text-xl text-center">
            {{ $page.props.auth.affiliator?.referral_code || 'LOADING...' }}
          </div>
          <Button variant="white" @click="$toast.success('Kode referral disalin!')">
            Salin
          </Button>
        </div>
        <p class="mt-4 text-sm text-indigo-100">
          Bagikan kode ini untuk mendapatkan 25% komisi dari setiap pembelian referral Anda, 
          dan 5% dari pembelian downline mereka.
        </p>
      </div>
    </div>
  </AffiliatorLayout>
</template>
