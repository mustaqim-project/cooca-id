<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { Head } from '@inertiajs/vue3';

interface Props {
    errors?: Record<string, string>;
}

defineProps<Props>();

const form = useForm({
    name: '',
    subject: '',
    target: 'all_customers',
    body: '',
    schedule_type: 'now',
    scheduled_at: '',
});

const submit = () => {
    form.post(route('admin.email-campaigns.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Buat Email Campaign" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">Buat Campaign Baru</h2>
                            <p class="mt-1 text-sm text-gray-600">Kirim email marketing ke customer atau affiliator.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Nama Campaign" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    placeholder="Contoh: Promo Ramadan 2025"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="subject" value="Subject Email" />
                                <TextInput
                                    id="subject"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.subject"
                                    required
                                    placeholder="Subjek email yang akan diterima recipient"
                                />
                                <InputError class="mt-2" :message="form.errors.subject" />
                            </div>

                            <div>
                                <InputLabel for="target" value="Target Audience" />
                                <select
                                    id="target"
                                    v-model="form.target"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="all_customers">Semua Customer</option>
                                    <option value="all_affiliators">Semua Affiliator</option>
                                    <option value="custom">Custom Segment</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.target" />
                            </div>

                            <div>
                                <InputLabel for="body" value="Isi Email" />
                                <Textarea
                                    id="body"
                                    class="mt-1 block w-full min-h-[300px]"
                                    v-model="form.body"
                                    required
                                    placeholder="Tulis konten email Anda di sini..."
                                />
                                <InputError class="mt-2" :message="form.errors.body" />
                            </div>

                            <div>
                                <InputLabel value="Jadwal Pengiriman" />
                                <div class="mt-2 space-y-3">
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            value="now"
                                            v-model="form.schedule_type"
                                            class="text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Kirim Sekarang</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            value="scheduled"
                                            v-model="form.schedule_type"
                                            class="text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Jadwalkan</span>
                                    </label>
                                    <div v-if="form.schedule_type === 'scheduled'" class="ml-6 mt-2">
                                        <TextInput
                                            type="datetime-local"
                                            v-model="form.scheduled_at"
                                            class="block w-full"
                                        />
                                        <InputError class="mt-2" :message="form.errors.scheduled_at" />
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.schedule_type" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Simpan Campaign
                                </PrimaryButton>
                                <SecondaryButton @click="router.get(route('admin.email-campaigns.index'))">
                                    Batal
                                </SecondaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
