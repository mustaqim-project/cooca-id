<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('customer.password.email'));
};
</script>

<template>
    <AuthLayout title="Lupa Password?">
        <Head title="Lupa Password - Cooca.id" />

        <div class="mb-4 text-sm text-gray-600">
            Lupa password Anda? Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan link reset password yang memungkinkan Anda memilih password baru.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required
                    autofocus
                />
                <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('customer.login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Kembali ke login
                </Link>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                >
                    Kirim Link Reset Password
                </button>
            </div>
        </form>
    </AuthLayout>
</template>
