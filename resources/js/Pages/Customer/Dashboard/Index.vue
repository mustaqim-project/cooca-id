<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import StatCards from '@/Components/ui/StatCards.vue';
import Button from '@/Components/ui/Button.vue';

interface Subscription {
  id: string;
  product_name: string;
  plan_name: string;
  status: 'trial' | 'active' | 'expired' | 'cancelled';
  started_at: string;
  expires_at: string;
}

interface License {
  id: string;
  license_code: string;
  domain: string;
  status: 'inactive' | 'active' | 'expired' | 'revoked';
  activated_at: string | null;
}

interface Transaction {
  id: string;
  invoice_number: string;
  gross_amount: number;
  net_amount: number;
  status: 'pending' | 'paid' | 'failed' | 'refunded';
  created_at: string;
}

interface Props {
  subscriptions: Subscription[];
  licenses: License[];
  recentTransactions: Transaction[];
  recentLicenses: License[];
  upcomingRenewals: any[];
  notifications: any[];
  stats: {
    activeLicenses: number;
    expiringLicenses: number;
    totalSubscriptions: number;
    activeSubscriptions: number;
    pendingInvoices: number;
    unpaidInvoicesAmount: number;
    totalSpent: number;
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
    trial: 'bg-blue-100 text-blue-800',
    active: 'bg-green-100 text-green-800',
    expired: 'bg-red-100 text-red-800',
    cancelled: 'bg-gray-100 text-gray-800',
    inactive: 'bg-gray-100 text-gray-800',
    revoked: 'bg-red-100 text-red-800',
    pending: 'bg-yellow-100 text-yellow-800',
    paid: 'bg-green-100 text-green-800',
    failed: 'bg-red-100 text-red-800',
    refunded: 'bg-blue-100 text-blue-800',
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
  <Head title="Dashboard" />

  <CustomerLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <Link :href="route('customer.products.index')" class="inline-flex">
          <Button>Beli Produk Baru</Button>
        </Link>
      </div>

      <!-- Stats -->
      <StatCards
        :stats="[
          { label: 'Subscripsi Aktif', value: stats.activeSubscriptions.toString(), icon: '📦', trend: 'stable' },
          { label: 'Lisensi Aktif', value: stats.activeLicenses.toString(), icon: '🔑', trend: 'stable' },
          { label: 'Total Pengeluaran', value: formatCurrency(stats.totalSpent), icon: '💰', trend: 'up' },
          { label: 'Invoice Pending', value: stats.pendingInvoices.toString(), icon: '📄', trend: 'down' },
        ]"
      />

      <!-- Active Subscriptions -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Subscripsi Aktif</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berlaku Sampai</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="sub in recentLicenses" :key="sub.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ sub.subscription?.product?.name || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sub.subscription?.plan?.name || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', getStatusColor(sub.status)]">
                    {{ sub.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(sub.expires_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link :href="route('customer.licenses.index')" class="text-indigo-600 hover:text-indigo-900">
                    Lihat Lisensi
                  </Link>
                </td>
              </tr>
              <tr v-if="recentLicenses.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                  Belum ada subscripsi aktif. Mulai dengan membeli produk pertama Anda.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Licenses -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Lisensi Saya</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Lisensi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diaktifkan</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="license in licenses" :key="license.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ license.license_code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ license.domain }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', getStatusColor(license.status)]">
                    {{ license.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ license.activated_at ? formatDate(license.activated_at) : '-' }}
                </td>
              </tr>
              <tr v-if="licenses.length === 0">
                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                  Belum ada lisensi. Lisensi akan dibuat setelah subscripsi diaktifkan.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
          <h2 class="text-lg font-semibold text-gray-900">Transaksi Terakhir</h2>
          <Link :href="route('customer.invoices.index')" class="text-sm text-indigo-600 hover:text-indigo-900">
            Lihat Semua
          </Link>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="trx in recentTransactions" :key="trx.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ trx.invoice_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(trx.created_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(trx.gross_amount) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', getStatusColor(trx.status)]">
                    {{ trx.status }}
                  </span>
                </td>
              </tr>
              <tr v-if="recentTransactions.length === 0">
                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                  Belum ada transaksi.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>
