<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Category {
    id: string;
    name: string;
    slug: string;
    description: string | null;
    icon: string | null;
    is_active: boolean;
    sort_order: number;
}

interface Props {
    category: Category;
    errors?: Record<string, string>;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.category.name,
    slug: props.category.slug,
    description: props.category.description || '',
    icon: props.category.icon || '',
    is_active: props.category.is_active,
    sort_order: props.category.sort_order,
});

const submit = () => {
    form.put(route('admin.product-categories.update', props.category.id), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <Head title="Edit Kategori Produk" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link :href="route('admin.product-categories.index')" class="text-indigo-600 hover:text-indigo-900">
                        &larr; Kembali ke Daftar Kategori
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Edit Kategori Produk</h2>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="name" value="Nama Kategori" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                        autofocus
                                    />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div>
                                    <InputLabel for="slug" value="Slug (URL Friendly)" />
                                    <TextInput
                                        id="slug"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.slug"
                                    />
                                    <InputError class="mt-2" :message="form.errors.slug" />
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel for="description" value="Deskripsi" />
                                    <textarea
                                        id="description"
                                        v-model="form.description"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors.description" />
                                </div>

                                <div>
                                    <InputLabel for="icon" value="Icon (Class CSS atau URL)" />
                                    <TextInput
                                        id="icon"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.icon"
                                    />
                                    <InputError class="mt-2" :message="form.errors.icon" />
                                </div>

                                <div>
                                    <InputLabel for="sort_order" value="Urutan Tampil" />
                                    <TextInput
                                        id="sort_order"
                                        type="number"
                                        min="0"
                                        class="mt-1 block w-full"
                                        v-model.number="form.sort_order"
                                    />
                                    <InputError class="mt-2" :message="form.errors.sort_order" />
                                </div>

                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            v-model="form.is_active"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        />
                                        <span class="ml-2 text-sm text-gray-600">Aktif</span>
                                    </label>
                                    <InputError class="mt-2" :message="form.errors.is_active" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update Kategori
                                </PrimaryButton>
                                <Link :href="route('admin.product-categories.index')" class="text-gray-600 hover:text-gray-900">
                                    Batal
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
