<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { Head } from '@inertiajs/vue3';

interface Settings {
    platform_name: string;
    logo_url: string | null;
    email_support: string;
    email_info: string;
    email_marketing: string;
    email_noreply: string;
    whatsapp_number: string;
    midtrans_server_key: string;
    midtrans_client_key: string;
    midtrans_sandbox: boolean;
    affiliate_commission_l1: number;
    affiliate_commission_l2: number;
    withdrawal_fee_bank: number;
    withdrawal_fee_ewallet: number;
}

interface Props {
    settings: Settings;
    errors?: Record<string, string>;
}

const props = defineProps<Props>();

// General Settings
const generalForm = useForm({
    platform_name: props.settings.platform_name,
    logo_url: props.settings.logo_url || '',
    email_support: props.settings.email_support,
    email_info: props.settings.email_info,
    email_marketing: props.settings.email_marketing,
    email_noreply: props.settings.email_noreply,
    whatsapp_number: props.settings.whatsapp_number,
});

// Payment Settings
const paymentForm = useForm({
    midtrans_server_key: props.settings.midtrans_server_key,
    midtrans_client_key: props.settings.midtrans_client_key,
    midtrans_sandbox: props.settings.midtrans_sandbox,
});

// Affiliate Settings
const affiliateForm = useForm({
    affiliate_commission_l1: props.settings.affiliate_commission_l1,
    affiliate_commission_l2: props.settings.affiliate_commission_l2,
    withdrawal_fee_bank: props.settings.withdrawal_fee_bank,
    withdrawal_fee_ewallet: props.settings.withdrawal_fee_ewallet,
});

const activeTab = ref('general');

const submitGeneral = () => {
    generalForm.put(route('admin.settings.update'), {
        onSuccess: () => {},
    });
};

const submitPayment = () => {
    paymentForm.put(route('admin.settings.update'), {
        onSuccess: () => {},
    });
};

const submitAffiliate = () => {
    affiliateForm.put(route('admin.settings.update'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <Head title="Pengaturan Platform" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Pengaturan Platform</h2>

                        <!-- Tabs -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button
                                    @click="activeTab = 'general'"
                                    :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                >
                                    Umum
                                </button>
                                <button
                                    @click="activeTab = 'payment'"
                                    :class="activeTab === 'payment' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                >
                                    Pembayaran
                                </button>
                                <button
                                    @click="activeTab = 'affiliate'"
                                    :class="activeTab === 'affiliate' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                >
                                    Affiliate
                                </button>
                            </nav>
                        </div>

                        <!-- General Settings Tab -->
                        <div v-if="activeTab === 'general'" class="space-y-6">
                            <form @submit.prevent="submitGeneral">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="platform_name" value="Nama Platform" />
                                        <TextInput
                                            id="platform_name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="generalForm.platform_name"
                                            required
                                        />
                                        <InputError class="mt-2" :message="generalForm.errors.platform_name" />
                                    </div>
                                    <div>
                                        <InputLabel for="logo_url" value="URL Logo" />
                                        <TextInput
                                            id="logo_url"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="generalForm.logo_url"
                                        />
                                        <InputError class="mt-2" :message="generalForm.errors.logo_url" />
                                    </div>
                                    <div>
                                        <InputLabel for="email_support" value="Email Support" />
                                        <TextInput
                                            id="email_support"
                                            type="email"
                                            class="mt-1 block w-full"
                                            v-model="generalForm.email_support"
                                            required
                                        />
                                        <InputError class="mt-2" :message="generalForm.errors.email_support" />
                                    </div>
                                    <div>
                                        <InputLabel for="email_info" value="Email Info" />
                                        <TextInput
                                            id="email_info"
                                            type="email"
                                            class="mt-1 block w-full"
                                            v-model="generalForm.email_info"
                                            required
                                        />
                                        <InputError class="mt-2" :message="generalForm.errors.email_info" />
                                    </div>
                                    <div>
                                        <InputLabel for="email_marketing" value="Email Marketing" />
                                        <TextInput
                                            id="email_marketing"
                                            type="email"
                                            class="mt-1 block w-full"
                                            v-model="generalForm.email_marketing"
                                            required
                                        />
                                        <InputError class="mt-2" :message="generalForm.errors.email_marketing" />
                                    </div>
                                    <div>
                                        <InputLabel for="email_noreply" value="Email No-Reply" />
                                        <TextInput
                                            id="email_noreply"
                                            type="email"
                                            class="mt-1 block w-full"
                                            v-model="generalForm.email_noreply"
                                            required
                                        />
                                        <InputError class="mt-2" :message="generalForm.errors.email_noreply" />
                                    </div>
                                    <div>
                                        <InputLabel for="whatsapp_number" value="Nomor WhatsApp (Fonnte)" />
                                        <TextInput
                                            id="whatsapp_number"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="generalForm.whatsapp_number"
                                        />
                                        <InputError class="mt-2" :message="generalForm.errors.whatsapp_number" />
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <PrimaryButton :class="{ 'opacity-25': generalForm.processing }" :disabled="generalForm.processing">
                                        Simpan Pengaturan Umum
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        <!-- Payment Settings Tab -->
                        <div v-if="activeTab === 'payment'" class="space-y-6">
                            <form @submit.prevent="submitPayment">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="flex items-center">
                                            <input
                                                type="checkbox"
                                                v-model="paymentForm.midtrans_sandbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            />
                                            <span class="ml-2 text-sm text-gray-600">Mode Sandbox (Testing)</span>
                                        </label>
                                        <InputError class="mt-2" :message="paymentForm.errors.midtrans_sandbox" />
                                    </div>
                                    <div>
                                        <InputLabel for="midtrans_server_key" value="Midtrans Server Key" />
                                        <TextInput
                                            id="midtrans_server_key"
                                            type="password"
                                            class="mt-1 block w-full"
                                            v-model="paymentForm.midtrans_server_key"
                                        />
                                        <InputError class="mt-2" :message="paymentForm.errors.midtrans_server_key" />
                                    </div>
                                    <div>
                                        <InputLabel for="midtrans_client_key" value="Midtrans Client Key" />
                                        <TextInput
                                            id="midtrans_client_key"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="paymentForm.midtrans_client_key"
                                        />
                                        <InputError class="mt-2" :message="paymentForm.errors.midtrans_client_key" />
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <PrimaryButton :class="{ 'opacity-25': paymentForm.processing }" :disabled="paymentForm.processing">
                                        Simpan Pengaturan Pembayaran
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        <!-- Affiliate Settings Tab -->
                        <div v-if="activeTab === 'affiliate'" class="space-y-6">
                            <form @submit.prevent="submitAffiliate">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="commission_l1" value="Komisi Affiliate Level 1 (%)" />
                                        <TextInput
                                            id="commission_l1"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            class="mt-1 block w-full"
                                            v-model="affiliateForm.affiliate_commission_l1"
                                            required
                                        />
                                        <InputError class="mt-2" :message="affiliateForm.errors.affiliate_commission_l1" />
                                    </div>
                                    <div>
                                        <InputLabel for="commission_l2" value="Komisi Affiliate Level 2 (%)" />
                                        <TextInput
                                            id="commission_l2"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            class="mt-1 block w-full"
                                            v-model="affiliateForm.affiliate_commission_l2"
                                            required
                                        />
                                        <InputError class="mt-2" :message="affiliateForm.errors.affiliate_commission_l2" />
                                    </div>
                                    <div>
                                        <InputLabel for="fee_bank" value="Withdrawal Fee Bank (Rp)" />
                                        <TextInput
                                            id="fee_bank"
                                            type="number"
                                            min="0"
                                            class="mt-1 block w-full"
                                            v-model="affiliateForm.withdrawal_fee_bank"
                                            required
                                        />
                                        <InputError class="mt-2" :message="affiliateForm.errors.withdrawal_fee_bank" />
                                    </div>
                                    <div>
                                        <InputLabel for="fee_ewallet" value="Withdrawal Fee E-Wallet (Rp)" />
                                        <TextInput
                                            id="fee_ewallet"
                                            type="number"
                                            min="0"
                                            class="mt-1 block w-full"
                                            v-model="affiliateForm.withdrawal_fee_ewallet"
                                            required
                                        />
                                        <InputError class="mt-2" :message="affiliateForm.errors.withdrawal_fee_ewallet" />
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <PrimaryButton :class="{ 'opacity-25': affiliateForm.processing }" :disabled="affiliateForm.processing">
                                        Simpan Pengaturan Affiliate
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
