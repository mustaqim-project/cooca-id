<script setup>
import AuthenticatedLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import SelectInput from '@/Components/forms/SelectInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    template: Object,
    categories: Array,
});

const form = useForm({
    name: props.template?.name || '',
    key: props.template?.key || '',
    subject: props.template?.subject || '',
    body_html: props.template?.body_html || '',
    body_text: props.template?.body_text || '',
    category: props.template?.category || '',
    variables: props.template?.variables || [],
    is_active: props.template?.is_active ?? true,
});

const submit = () => {
    if (props.template) {
        form.put(route('admin.email-templates.update', props.template.id));
    } else {
        form.post(route('admin.email-templates.store'));
    }
};

const addVariable = () => {
    form.variables.push('');
};

const removeVariable = (index) => {
    form.variables.splice(index, 1);
};
</script>

<template>
    <Head :title="template ? 'Edit Email Template' : 'Create Email Template'" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ template ? 'Edit Email Template' : 'Create New Email Template' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="Template Name" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    placeholder="e.g., Welcome Email"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Key -->
                            <div>
                                <InputLabel for="key" value="Template Key" />
                                <TextInput
                                    id="key"
                                    v-model="form.key"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    placeholder="e.g., welcome_email"
                                />
                                <InputError class="mt-2" :message="form.errors.key" />
                                <p class="text-xs text-gray-500 mt-1">Unique identifier for this template</p>
                            </div>

                            <!-- Category -->
                            <div>
                                <InputLabel for="category" value="Category" />
                                <select
                                    id="category"
                                    v-model="form.category"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >
                                    <option value="">Select Category</option>
                                    <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category" />
                            </div>

                            <!-- Subject -->
                            <div>
                                <InputLabel for="subject" value="Email Subject" />
                                <TextInput
                                    id="subject"
                                    v-model="form.subject"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    placeholder="You can use {{variable}} for dynamic content"
                                />
                                <InputError class="mt-2" :message="form.errors.subject" />
                            </div>

                            <!-- HTML Body -->
                            <div>
                                <InputLabel for="body_html" value="HTML Body" />
                                <TextArea
                                    id="body_html"
                                    v-model="form.body_html"
                                    rows="12"
                                    class="mt-1 block w-full font-mono text-sm"
                                    required
                                    placeholder="<html><body><h1>Hello {{name}}</h1></body></html>"
                                />
                                <InputError class="mt-2" :message="form.errors.body_html" />
                            </div>

                            <!-- Text Body -->
                            <div>
                                <InputLabel for="body_text" value="Plain Text Body (Optional)" />
                                <TextArea
                                    id="body_text"
                                    v-model="form.body_text"
                                    rows="8"
                                    class="mt-1 block w-full font-mono text-sm"
                                    placeholder="Hello {{name}},\n\nWelcome to our platform!"
                                />
                                <InputError class="mt-2" :message="form.errors.body_text" />
                            </div>

                            <!-- Variables -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <InputLabel value="Available Variables" />
                                    <button type="button" @click="addVariable" class="text-sm text-indigo-600 hover:text-indigo-900">
                                        + Add Variable
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <div v-for="(variable, index) in form.variables" :key="index" class="flex gap-2">
                                        <TextInput
                                            v-model="form.variables[index]"
                                            type="text"
                                            class="flex-1"
                                            placeholder="e.g., customer_name"
                                        />
                                        <button
                                            type="button"
                                            @click="removeVariable(index)"
                                            class="text-red-600 hover:text-red-900 px-2"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                    <p v-if="form.variables.length === 0" class="text-sm text-gray-500">No variables defined</p>
                                </div>
                                <InputError class="mt-2" :message="form.errors.variables" />
                                <p class="text-xs text-gray-500 mt-1">Use {{ variable_name }} in subject and body</p>
                            </div>

                            <!-- Is Active -->
                            <div class="flex items-center gap-2">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <label for="is_active" class="text-sm text-gray-700">Active (can be used by system)</label>
                            </div>
                            <InputError class="mt-2" :message="form.errors.is_active" />

                            <!-- Actions -->
                            <div class="flex items-center gap-4 pt-4">
                                <PrimaryButton :disabled="form.processing">
                                    {{ form.processing ? 'Saving...' : (template ? 'Update Template' : 'Create Template') }}
                                </PrimaryButton>
                                <Link :href="route('admin.email-templates.index')" class="text-sm text-gray-600 hover:text-gray-900">
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
