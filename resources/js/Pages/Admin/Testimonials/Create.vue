<script setup>
import AuthenticatedLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    testimonial: Object,
});

const form = useForm({
    name: props.testimonial?.name || "",
    role: props.testimonial?.role || "",
    company: props.testimonial?.company || "",
    content: props.testimonial?.content || "",
    image_url: props.testimonial?.image_url || "",
    order: props.testimonial?.order || 0,
    is_featured: props.testimonial?.is_featured ?? false,
    is_active: props.testimonial?.is_active ?? true,
});

const submit = () => {
    if (props.testimonial) {
        form.put(route("admin.testimonials.update", props.testimonial.id));
    } else {
        form.post(route("admin.testimonials.store"));
    }
};
</script>

<template>
    <Head :title="testimonial ? 'Edit Testimonial' : 'Create Testimonial'" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{
                    testimonial ? "Edit Testimonial" : "Create New Testimonial"
                }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="Customer Name" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <!-- Role & Company -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="role" value="Role/Title" />
                                    <TextInput
                                        id="role"
                                        v-model="form.role"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., CEO, Manager"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.role"
                                    />
                                </div>
                                <div>
                                    <InputLabel for="company" value="Company" />
                                    <TextInput
                                        id="company"
                                        v-model="form.company"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="e.g., Acme Corp"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.company"
                                    />
                                </div>
                            </div>

                            <!-- Content -->
                            <div>
                                <InputLabel
                                    for="content"
                                    value="Testimonial Content"
                                />
                                <TextArea
                                    id="content"
                                    v-model="form.content"
                                    rows="6"
                                    class="mt-1 block w-full"
                                    required
                                    placeholder="Customer's testimonial text..."
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.content"
                                />
                            </div>

                            <!-- Image URL -->
                            <div>
                                <InputLabel
                                    for="image_url"
                                    value="Customer Photo URL (Optional)"
                                />
                                <TextInput
                                    id="image_url"
                                    v-model="form.image_url"
                                    type="url"
                                    class="mt-1 block w-full"
                                    placeholder="https://example.com/photo.jpg"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.image_url"
                                />
                                <p class="text-xs text-gray-500 mt-1">
                                    Leave empty for default avatar
                                </p>
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
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.order"
                                />
                                <p class="text-xs text-gray-500 mt-1">
                                    Lower numbers appear first
                                </p>
                            </div>

                            <!-- Is Featured -->
                            <div class="flex items-center gap-2">
                                <input
                                    id="is_featured"
                                    v-model="form.is_featured"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <label
                                    for="is_featured"
                                    class="text-sm text-gray-700"
                                    >Featured (highlighted on homepage)</label
                                >
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.is_featured"
                            />

                            <!-- Is Active -->
                            <div class="flex items-center gap-2">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <label
                                    for="is_active"
                                    class="text-sm text-gray-700"
                                    >Active (visible on website)</label
                                >
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.is_active"
                            />

                            <!-- Actions -->
                            <div class="flex items-center gap-4 pt-4">
                                <PrimaryButton :disabled="form.processing">
                                    {{ form.processing ? 'Saving...' : (testimonial ? 'Update Testimonial' : 'Create Testimonial") }}
                                </PrimaryButton>
                                <Link
                                    :href="route('admin.testimonials.index')"
                                    class="text-sm text-gray-600 hover:text-gray-900"
                                >
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
