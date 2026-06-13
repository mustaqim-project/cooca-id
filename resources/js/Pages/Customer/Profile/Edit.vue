<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

interface Props {
    user: {
        name: string;
        email: string;
        business_name?: string;
        domain?: string;
        google_id?: string;
    };
}

const props = defineProps<Props>();

// Form untuk update profil
const profileForm = useForm({
    name: props.user.name,
    business_name: props.user.business_name || '',
    domain: props.user.domain || '',
});

// Form untuk ganti password
const passwordForm = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const updateProfile = () => {
    profileForm.put(route('customer.profile.update'), {
        preserveScroll: true,
    });
};

const updatePassword = () => {
    passwordForm.put(route('customer.profile.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};

const isGoogleLogin = !!props.user.google_id;
</script>

<template>
    <Head title="Edit Profil" />

    <CustomerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-800">
                            Edit Profil
                        </h2>

                        <!-- Section 1: Data Bisnis & Akun -->
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Data Bisnis & Akun
                            </h3>
                            <form @submit.prevent="updateProfile">
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Lengkap
                                        </label>
                                        <input
                                            id="name"
                                            v-model="profileForm.name"
                                            type="text"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            required
                                        />
                                        <span v-if="profileForm.errors.name" class="text-red-500 text-xs mt-1">{{ profileForm.errors.name }}</span>
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                            Email
                                        </label>
                                        <div class="relative">
                                            <input
                                                id="email"
                                                v-model="props.user.email"
                                                type="email"
                                                :disabled="isGoogleLogin"
                                                :class="[
                                                    'w-full border-gray-300 rounded-md shadow-sm',
                                                    isGoogleLogin ? 'bg-gray-100 cursor-not-allowed' : 'focus:border-indigo-500 focus:ring-indigo-500'
                                                ]"
                                            />
                                            <span v-if="isGoogleLogin" class="absolute right-3 top-1/2 transform -translate-y-1/2 inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Login via Google
                                            </span>
                                        </div>
                                        <span v-if="profileForm.errors.email" class="text-red-500 text-xs mt-1">{{ profileForm.errors.email }}</span>
                                        <p v-if="isGoogleLogin" class="text-xs text-gray-500 mt-1">Email tidak dapat diubah karena Anda login menggunakan Google.</p>
                                    </div>

                                    <div>
                                        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Bisnis
                                        </label>
                                        <input
                                            id="business_name"
                                            v-model="profileForm.business_name"
                                            type="text"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            placeholder="Nama perusahaan/bisnis Anda"
                                        />
                                        <span v-if="profileForm.errors.business_name" class="text-red-500 text-xs mt-1">{{ profileForm.errors.business_name }}</span>
                                    </div>

                                    <div>
                                        <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">
                                            Domain ERP
                                        </label>
                                        <div class="flex">
                                            <input
                                                id="domain"
                                                v-model="profileForm.domain"
                                                type="text"
                                                class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-l-md shadow-sm"
                                                placeholder="subdomain-anda"
                                            />
                                            <span class="inline-flex items-center px-3 border border-l-0 border-gray-300 bg-gray-50 rounded-r-md text-gray-500 text-sm">
                                                .cooca.id
                                            </span>
                                        </div>
                                        <span v-if="profileForm.errors.domain" class="text-red-500 text-xs mt-1">{{ profileForm.errors.domain }}</span>
                                        <p class="text-xs text-gray-500 mt-1">Domain ini akan menjadi URL akses ERP Anda (contoh: subdomain-anda.cooca.id)</p>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button
                                        type="submit"
                                        :disabled="profileForm.processing"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                    >
                                        {{ profileForm.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Section 2: Ganti Password -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Ganti Password
                            </h3>
                            <form @submit.prevent="updatePassword">
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                            Password Saat Ini
                                        </label>
                                        <input
                                            id="current_password"
                                            v-model="passwordForm.current_password"
                                            type="password"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            :required="!isGoogleLogin"
                                        />
                                        <span v-if="passwordForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.current_password }}</span>
                                    </div>

                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                                            Password Baru
                                        </label>
                                        <input
                                            id="new_password"
                                            v-model="passwordForm.new_password"
                                            type="password"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            minlength="8"
                                            required
                                        />
                                        <span v-if="passwordForm.errors.new_password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.new_password }}</span>
                                    </div>

                                    <div>
                                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                            Konfirmasi Password Baru
                                        </label>
                                        <input
                                            id="new_password_confirmation"
                                            v-model="passwordForm.new_password_confirmation"
                                            type="password"
                                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            minlength="8"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button
                                        type="submit"
                                        :disabled="passwordForm.processing"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                    >
                                        {{ passwordForm.processing ? 'Menyimpan...' : 'Ganti Password' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
