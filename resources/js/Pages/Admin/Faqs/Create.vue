<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    faq: Object,
});

const form = useForm({
    question: props.faq?.question || '',
    answer: props.faq?.answer || '',
    order: props.faq?.order || 0,
    is_active: props.faq?.is_active ?? true,
    category: props.faq?.category || '',
});

const submit = () => {
    if (props.faq) {
        form.put(route('admin.faqs.update', props.faq.id));
    } else {
        form.post(route('admin.faqs.store'));
    }
};
</script>

<template>
    <Head :title="faq ? 'Edit FAQ' : 'Create FAQ'" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ faq ? 'Edit FAQ' : 'Create New FAQ' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Question -->
                            <div>
                                <InputLabel for="question" value="Question" />
                                <TextInput
                                    id="question"
                                    v-model="form.question"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.question" />
                            </div>

                            <!-- Answer -->
                            <div>
                                <InputLabel for="answer" value="Answer" />
                                <TextArea
                                    id="answer"
                                    v-model="form.answer"
                                    rows="6"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.answer" />
                            </div>

                            <!-- Category -->
                            <div>
                                <InputLabel for="category" value="Category (Optional)" />
                                <TextInput
                                    id="category"
                                    v-model="form.category"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g., Billing, Technical, General"
                                />
                                <InputError class="mt-2" :message="form.errors.category" />
                            </div>

                            <!-- Order -->
                            <div>
                                <InputLabel for="order" value="Display Order" />
                                <TextInput
                                    id="order"
                                    v-model="form.order"
                                    type="number"
                                    class="mt-1 block w-full"
                                    min="0"
                                />
                                <InputError class="mt-2" :message="form.errors.order" />
                                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                            </div>

                            <!-- Is Active -->
                            <div class="flex items-center gap-2">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <label for="is_active" class="text-sm text-gray-700">Active (visible on website)</label>
                            </div>
                            <InputError class="mt-2" :message="form.errors.is_active" />

                            <!-- Actions -->
                            <div class="flex items-center gap-4 pt-4">
                                <PrimaryButton :disabled="form.processing">
                                    {{ form.processing ? 'Saving...' : (faq ? 'Update FAQ' : 'Create FAQ') }}
                                </PrimaryButton>
                                <Link :href="route('admin.faqs.index')" class="text-sm text-gray-600 hover:text-gray-900">
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
