<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    modelValue?: string | number;
    label: string;
    type?: 'text' | 'email' | 'password' | 'number' | 'tel' | 'url';
    placeholder?: string;
    error?: string;
    required?: boolean;
    disabled?: boolean;
    readonly?: boolean;
    helpText?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    required: false,
    disabled: false,
    readonly: false
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void;
}>();

const inputValue = ref(props.modelValue);

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const value = props.type === 'number' ? parseFloat(target.value) : target.value;
    emit('update:modelValue', value);
};
</script>

<template>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <input
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :readonly="readonly"
            :required="required"
            @input="handleInput"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
            :class="{
                'border-gray-300': !error,
                'border-red-500': error,
                'bg-gray-100 cursor-not-allowed': disabled || readonly,
                'opacity-50': disabled
            }"
        />
        <p v-if="helpText && !error" class="mt-1 text-sm text-gray-500">
            {{ helpText }}
        </p>
        <p v-if="error" class="mt-1 text-sm text-red-600">
            {{ error }}
        </p>
    </div>
</template>
