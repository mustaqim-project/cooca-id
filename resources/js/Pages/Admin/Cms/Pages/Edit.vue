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

interface Page {
    id: number;
    title: string;
    slug: string;
    content: string;
    meta_title: string | null;
    meta_description: string | null;
    status: 'draft' | 'published';
    created_at: string;
    updated_at: string;
}

interface Props {
    page: Page;
    errors?: Record<string, string>;
}

const props = defineProps<Props>();

const form = useForm({
    title: props.page.title,
    slug: props.page.slug,
    content: props.page.content,
    meta_title: props.page.meta_title || '',
    meta_description: props.page.meta_description || '',
    status: props.page.status,
});

const generateSlug = () => {
    if (form.title) {
        form.slug = form.title
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
};

const submit = () => {
    form.put(route('admin.cms.pages.update', props.page.id), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <Head title="Edit Halaman CMS" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">Edit Halaman</h2>
                            <p class="mt-1 text-sm text-gray-600">Edit halaman statis "{{ page.title }}".</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="title" value="Judul Halaman" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    @input="generateSlug"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="slug" value="Slug (URL)" />
                                <TextInput
                                    id="slug"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.slug"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.slug" />
                            </div>

                            <div>
                                <InputLabel for="content" value="Konten" />
                                <Textarea
                                    id="content"
                                    class="mt-1 block w-full min-h-[400px]"
                                    v-model="form.content"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.content" />
                            </div>

                            <div>
                                <InputLabel for="meta_title" value="Meta Title (SEO)" />
                                <TextInput
                                    id="meta_title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.meta_title"
                                />
                                <InputError class="mt-2" :message="form.errors.meta_title" />
                            </div>

                            <div>
                                <InputLabel for="meta_description" value="Meta Description (SEO)" />
                                <Textarea
                                    id="meta_description"
                                    class="mt-1 block w-full"
                                    v-model="form.meta_description"
                                    rows="3"
                                />
                                <InputError class="mt-2" :message="form.errors.meta_description" />
                            </div>

                            <div>
                                <InputLabel for="status" value="Status" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update Halaman
                                </PrimaryButton>
                                <SecondaryButton @click="router.get(route('admin.cms.pages.index'))">
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
