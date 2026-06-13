<script setup lang="ts">
import { ref } from 'vue';

interface Option {
    value: string | number;
    label: string;
}

interface Props {
    modelValue?: string | number;
    label: string;
    options: Option[];
    placeholder?: string;
    error?: string;
    required?: boolean;
    disabled?: boolean;
    helpText?: string;
}

const props = withDefaults(defineProps<Props>(), {
    required: false,
    disabled: false
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void;
}>();

const selectedValue = ref(props.modelValue);

const handleChange = (event: Event) => {
    const target = event.target as HTMLSelectElement;
    const value = target.value;
    emit('update:modelValue', value);
};
</script>

<template>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <select
            :value="modelValue"
            :disabled="disabled"
            :required="required"
            @change="handleChange"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white"
            :class="{
                'border-gray-300': !error,
                'border-red-500': error,
                'bg-gray-100 cursor-not-allowed': disabled,
                'opacity-50': disabled
            }"
        >
            <option v-if="placeholder" value="" disabled>
                {{ placeholder }}
            </option>
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
        <p v-if="helpText && !error" class="mt-1 text-sm text-gray-500">
            {{ helpText }}
        </p>
        <p v-if="error" class="mt-1 text-sm text-red-600">
            {{ error }}
        </p>
    </div>
</template>
