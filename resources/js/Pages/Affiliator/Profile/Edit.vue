<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import AffiliatorLayout from '@/Layouts/AffiliatorLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { Head } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    google_id: string | null;
}

interface BankAccount {
    id: number;
    bank_name: string | null;
    account_number: string | null;
    account_holder: string | null;
}

interface Props {
    user: User;
    bank_account: BankAccount | null;
    errors?: Record<string, string>;
}

const props = defineProps<Props>();

// Profile Form
const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone || '',
});

// Bank Account Form
const bankForm = useForm({
    bank_name: props.bank_account?.bank_name || '',
    account_number: props.bank_account?.account_number || '',
    account_holder: props.bank_account?.account_holder || '',
});

// Password Form
const passwordForm = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const submitProfile = () => {
    profileForm.put(route('affiliator.profile.update'), {
        onSuccess: () => {},
    });
};

const submitBank = () => {
    bankForm.put(route('affiliator.profile.bank_account.update'), {
        onSuccess: () => {},
    });
};

const submitPassword = () => {
    passwordForm.put(route('affiliator.profile.password.update'), {
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};
</script>

<template>
    <Head title="Edit Profil" />

    <AffiliatorLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- Section 1: Data Diri -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Data Diri & Akun</h2>
                        <form @submit.prevent="submitProfile" class="space-y-4">
                            <div>
                                <InputLabel for="name" value="Nama Lengkap" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="profileForm.name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="profileForm.errors.name" />
                            </div>
                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="profileForm.email"
                                    :disabled="!!user.google_id"
                                    required
                                />
                                <InputError class="mt-2" :message="profileForm.errors.email" />
                                <p v-if="user.google_id" class="mt-1 text-xs text-green-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Login via Google - Email tidak dapat diubah
                                </p>
                            </div>
                            <div>
                                <InputLabel for="phone" value="Nomor HP / WhatsApp" />
                                <TextInput
                                    id="phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="profileForm.phone"
                                    placeholder="081234567890"
                                />
                                <InputError class="mt-2" :message="profileForm.errors.phone" />
                            </div>
                            <div>
                                <PrimaryButton :class="{ 'opacity-25': profileForm.processing }" :disabled="profileForm.processing">
                                    Update Data Diri
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Section 2: Rekening Bank/E-Wallet -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Rekening Bank / E-Wallet</h2>
                        <p class="text-sm text-gray-600 mb-4">Informasi ini digunakan untuk penarikan komisi (withdrawal).</p>
                        <form @submit.prevent="submitBank" class="space-y-4">
                            <div>
                                <InputLabel for="bank_name" value="Nama Bank / E-Wallet" />
                                <TextInput
                                    id="bank_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="bankForm.bank_name"
                                    placeholder="Contoh: BCA, GoPay, OVO"
                                    required
                                />
                                <InputError class="mt-2" :message="bankForm.errors.bank_name" />
                            </div>
                            <div>
                                <InputLabel for="account_number" value="Nomor Rekening / Nomor E-Wallet" />
                                <TextInput
                                    id="account_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="bankForm.account_number"
                                    required
                                />
                                <InputError class="mt-2" :message="bankForm.errors.account_number" />
                            </div>
                            <div>
                                <InputLabel for="account_holder" value="Atas Nama" />
                                <TextInput
                                    id="account_holder"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="bankForm.account_holder"
                                    required
                                />
                                <InputError class="mt-2" :message="bankForm.errors.account_holder" />
                            </div>
                            <div>
                                <PrimaryButton :class="{ 'opacity-25': bankForm.processing }" :disabled="bankForm.processing">
                                    Simpan Informasi Bank
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Section 3: Ganti Password -->
                <div v-if="!user.google_id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ganti Password</h2>
                        <form @submit.prevent="submitPassword" class="space-y-4">
                            <div>
                                <InputLabel for="current_password" value="Password Saat Ini" />
                                <TextInput
                                    id="current_password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="passwordForm.current_password"
                                    required
                                />
                                <InputError class="mt-2" :message="passwordForm.errors.current_password" />
                            </div>
                            <div>
                                <InputLabel for="new_password" value="Password Baru" />
                                <TextInput
                                    id="new_password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="passwordForm.new_password"
                                    required
                                />
                                <InputError class="mt-2" :message="passwordForm.errors.new_password" />
                            </div>
                            <div>
                                <InputLabel for="new_password_confirmation" value="Konfirmasi Password Baru" />
                                <TextInput
                                    id="new_password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="passwordForm.new_password_confirmation"
                                    required
                                />
                                <InputError class="mt-2" :message="passwordForm.errors.new_password_confirmation" />
                            </div>
                            <div>
                                <PrimaryButton :class="{ 'opacity-25': passwordForm.processing }" :disabled="passwordForm.processing">
                                    Update Password
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AffiliatorLayout>
</template>
