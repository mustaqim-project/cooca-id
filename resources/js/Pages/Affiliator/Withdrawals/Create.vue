<script setup lang="ts">
import { computed, ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AffiliatorLayout from "@/Layouts/AffiliatorLayout.vue";

interface Props {
    availableBalance: number;
    withdrawalFee: {
        bank: number;
        ewallet: number;
    };
    minimumWithdrawal: number;
    bankAccount?: {
        bank_name: string;
        account_number: string;
        account_holder: string;
        type: "bank" | "ewallet";
    };
}

const props = defineProps<Props>();

const form = useForm({
    amount: 0,
    method: props.bankAccount?.type || "bank",
});

const calculatedFee = computed(() => {
    if (form.method === "bank") {
        return props.withdrawalFee.bank;
    }
    return props.withdrawalFee.ewallet;
});

const netAmount = computed(() => {
    return Math.max(0, form.amount - calculatedFee.value);
});

const maxAmount = computed(() => {
    return props.availableBalance;
});

const minAmount = computed(() => props.minimumWithdrawal);

const submitWithdrawal = () => {
    form.post(route("affiliator.withdrawals.store"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Ajukan Penarikan" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800">
                            Ajukan Penarikan Dana
                        </h2>

                        <!-- Info Saldo -->
                        <div
                            class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-indigo-700"
                                    >Saldo Tersedia</span
                                >
                                <span class="text-xl font-bold text-indigo-900">
                                    Rp
                                    {{ availableBalance.toLocaleString('id-ID") }}
                                </span>
                            </div>
                        </div>

                        <!-- Info Rekening -->
                        <div
                            v-if="bankAccount"
                            class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg"
                        >
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                Rekening Tujuan
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-900">
                                        {{ bankAccount.account_holder }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ bankAccount.bank_name }} -
                                        {{ bankAccount.account_number }}
                                    </p>
                                </div>
                                <Link
                                    :href="route('affiliator.profile.edit')"
                                    class="text-indigo-600 hover:text-indigo-900 text-xs font-medium"
                                >
                                    Ubah
                                </Link>
                            </div>
                        </div>

                        <div
                            v-else
                            class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg"
                        >
                            <p class="text-sm text-yellow-800">
                                ⚠️ Anda belum mengatur rekening bank/e-wallet.
                                Silakan lengkapi data rekening di
                                <Link
                                    :href="route('affiliator.profile.edit')"
                                    class="underline font-medium"
                                >
                                    halaman profil
                                </Link>
                                .
                            </p>
                        </div>

                        <form
                            @submit.prevent="submitWithdrawal"
                            v-if="bankAccount"
                        >
                            <!-- Jumlah Penarikan -->
                            <div class="mb-4">
                                <label
                                    for="amount"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Jumlah Penarikan (Rp)
                                </label>
                                <input
                                    id="amount"
                                    v-model.number="form.amount"
                                    type="number"
                                    :min="minAmount"
                                    :max="maxAmount"
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    :placeholder="`Minimum Rp ${minAmount.toLocaleString('id-ID')}`"
                                    required
                                />
                                <span
                                    v-if="form.errors.amount"
                                    class="text-red-500 text-xs mt-1"
                                    >{{ form.errors.amount }}</span
                                >
                                <p class="text-xs text-gray-500 mt-1">
                                    Minimum: Rp
                                    {{ minAmount.toLocaleString('id-ID") }} |
                                    Maksimum: Rp
                                    {{ maxAmount.toLocaleString('id-ID") }}
                                </p>
                            </div>

                            <!-- Metode Penarikan -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Metode Penarikan
                                </label>
                                <div class="flex gap-3">
                                    <label class="flex items-center">
                                        <input
                                            v-model="form.method"
                                            type="radio"
                                            value="bank"
                                            class="text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700"
                                            >Transfer Bank (Rp
                                            {{ withdrawalFee.bank.toLocaleString('id-ID")
                                            }})</span
                                        >
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            v-model="form.method"
                                            type="radio"
                                            value="ewallet"
                                            class="text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700"
                                            >E-Wallet (Rp
                                            {{ withdrawalFee.ewallet.toLocaleString('id-ID")
                                            }})</span
                                        >
                                    </label>
                                </div>
                                <span
                                    v-if="form.errors.method"
                                    class="text-red-500 text-xs mt-1"
                                    >{{ form.errors.method }}</span
                                >
                            </div>

                            <!-- Preview Perhitungan -->
                            <div
                                class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg"
                            >
                                <h3
                                    class="text-sm font-medium text-gray-700 mb-3"
                                >
                                    Rincian Penarikan
                                </h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"
                                            >Jumlah Penarikan</span
                                        >
                                        <span class="text-gray-900"
                                            >Rp
                                            {{ form.amount.toLocaleString('id-ID") }}</span
                                        >
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600"
                                            >Biaya Admin</span
                                        >
                                        <span class="text-gray-900"
                                            >- Rp
                                            {{ calculatedFee.toLocaleString('id-ID") }}</span
                                        >
                                    </div>
                                    <div
                                        class="border-t border-gray-200 pt-2 flex justify-between font-semibold"
                                    >
                                        <span class="text-gray-900"
                                            >Total Diterima</span
                                        >
                                        <span class="text-indigo-600"
                                            >Rp
                                            {{ netAmount.toLocaleString('id-ID") }}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex gap-3">
                                <button
                                    type="submit"
                                    :disabled="
                                        form.processing ||
                                        !form.amount ||
                                        form.amount < minAmount ||
                                        form.amount > maxAmount
                                    "
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                >
                                    {{
                                        form.processing
                                            ? "Memproses..."
                                            : "Ajukan Penarikan"
                                    }}
                                </button>
                                <Link
                                    :href="
                                        route('affiliator.withdrawals.history')
                                    "
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150"
                                >
                                    Riwayat
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>
