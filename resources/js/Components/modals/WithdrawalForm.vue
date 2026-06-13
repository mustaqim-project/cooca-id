<script setup lang="ts">
import { ref, computed } from 'vue';

interface Props {
  initialBalance?: number;
}

const props = withDefaults(defineProps<Props>(), {
  initialBalance: 0,
});

const emit = defineEmits<{
  submit: [amount: number, method: string, accountDetails: object];
}>();

const withdrawalAmount = ref<string>('');
const withdrawalMethod = ref<'bank' | 'ewallet'>('bank');
const accountNumber = ref('');
const accountName = ref('');
const bankName = ref('');
const ewalletName = ref('');

const balance = computed(() => props.initialBalance);

const amount = computed(() => {
  const parsed = parseFloat(withdrawalAmount.value);
  return isNaN(parsed) ? 0 : parsed;
});

const fee = computed(() => {
  return amount.value > 0 ? Math.min(amount.value * 0.01, 50000) : 0; // 1% fee, max 50k
});

const netAmount = computed(() => {
  return amount.value - fee.value;
});

const isValid = computed(() => {
  const hasValidAmount = amount.value > 10000 && amount.value <= balance.value;
  const hasAccountDetails =
    withdrawalMethod.value === 'bank'
      ? accountNumber.value && accountName.value && bankName.value
      : accountNumber.value && accountName.value && ewalletName.value;
  return hasValidAmount && hasAccountDetails;
});

const handleSubmit = () => {
  if (!isValid.value) return;

  const accountDetails =
    withdrawalMethod.value === 'bank'
      ? { bank_name: bankName.value, account_number: accountNumber.value, account_name: accountName.value }
      : { ewallet_name: ewalletName.value, account_number: accountNumber.value, account_name: accountName.value };

  emit('submit', amount.value, withdrawalMethod.value, accountDetails);
};

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(value);
};
</script>

<template>
  <div class="space-y-6">
    <!-- Balance Info -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white">
      <p class="text-sm opacity-90">Saldo Tersedia</p>
      <p class="text-3xl font-bold mt-2">{{ formatCurrency(balance) }}</p>
      <p class="text-xs mt-2 opacity-75">Minimum penarikan: Rp 10.000</p>
    </div>

    <!-- Amount Input -->
    <div>
      <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Penarikan</label>
      <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
        <input
          id="amount"
          v-model="withdrawalAmount"
          type="number"
          min="10000"
          :max="balance"
          class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          placeholder="Masukkan jumlah"
        />
      </div>
      <p v-if="amount > balance" class="mt-1 text-sm text-red-600">Jumlah melebihi saldo tersedia</p>
      <p v-else-if="amount > 0 && amount < 10000" class="mt-1 text-sm text-red-600">Minimum penarikan Rp 10.000</p>
    </div>

    <!-- Method Selection -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Metode Penarikan</label>
      <div class="grid grid-cols-2 gap-4">
        <button
          @click="withdrawalMethod = 'bank'"
          :class="[
            withdrawalMethod === 'bank'
              ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500'
              : 'border-gray-300 hover:border-gray-400',
            'p-4 border-2 rounded-lg transition-all duration-200',
          ]"
        >
          <div class="text-center">
            <div class="text-2xl mb-2">🏦</div>
            <div class="font-medium text-gray-900">Transfer Bank</div>
          </div>
        </button>
        <button
          @click="withdrawalMethod = 'ewallet'"
          :class="[
            withdrawalMethod === 'ewallet'
              ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500'
              : 'border-gray-300 hover:border-gray-400',
            'p-4 border-2 rounded-lg transition-all duration-200',
          ]"
        >
          <div class="text-center">
            <div class="text-2xl mb-2">📱</div>
            <div class="font-medium text-gray-900">E-Wallet</div>
          </div>
        </button>
      </div>
    </div>

    <!-- Account Details -->
    <div class="space-y-4">
      <div v-if="withdrawalMethod === 'bank'">
        <label for="bankName" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
        <select
          id="bankName"
          v-model="bankName"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
        >
          <option value="">Pilih Bank</option>
          <option value="BCA">BCA</option>
          <option value="Mandiri">Mandiri</option>
          <option value="BNI">BNI</option>
          <option value="BRI">BRI</option>
          <option value="Danamon">Danamon</option>
          <option value="Permata">Permata</option>
          <option value="CIMB Niaga">CIMB Niaga</option>
          <option value="BSI">BSI</option>
        </select>
      </div>

      <div v-else>
        <label for="ewalletName" class="block text-sm font-medium text-gray-700 mb-2">Provider E-Wallet</label>
        <select
          id="ewalletName"
          v-model="ewalletName"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
        >
          <option value="">Pilih E-Wallet</option>
          <option value="GoPay">GoPay</option>
          <option value="OVO">OVO</option>
          <option value="DANA">DANA</option>
          <option value="LinkAja">LinkAja</option>
          <option value="ShopeePay">ShopeePay</option>
        </select>
      </div>

      <div>
        <label for="accountNumber" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening / E-Wallet</label>
        <input
          id="accountNumber"
          v-model="accountNumber"
          type="text"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          placeholder="Masukkan nomor"
        />
      </div>

      <div>
        <label for="accountName" class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Rekening</label>
        <input
          id="accountName"
          v-model="accountName"
          type="text"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          placeholder="Sesuai dengan rekening"
        />
      </div>
    </div>

    <!-- Summary -->
    <div v-if="amount > 0" class="bg-gray-50 rounded-lg p-4 space-y-2">
      <div class="flex justify-between text-sm">
        <span class="text-gray-600">Jumlah Penarikan</span>
        <span class="font-medium text-gray-900">{{ formatCurrency(amount) }}</span>
      </div>
      <div class="flex justify-between text-sm">
        <span class="text-gray-600">Biaya Admin (1%)</span>
        <span class="font-medium text-gray-900">{{ formatCurrency(fee) }}</span>
      </div>
      <div class="border-t border-gray-200 pt-2 flex justify-between">
        <span class="font-semibold text-gray-900">Diterima</span>
        <span class="font-bold text-green-600">{{ formatCurrency(netAmount) }}</span>
      </div>
    </div>

    <!-- Submit Button -->
    <button
      @click="handleSubmit"
      :disabled="!isValid"
      :class="[
        isValid
          ? 'bg-indigo-600 hover:bg-indigo-700'
          : 'bg-gray-300 cursor-not-allowed',
        'w-full py-3 px-4 rounded-lg text-white font-medium transition-colors duration-200',
      ]"
    >
      Ajukan Penarikan
    </button>
  </div>
</template>
