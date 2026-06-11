<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    modelValue?: boolean;
    label: string;
    error?: string;
    disabled?: boolean;
    helpText?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    disabled: false
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const isChecked = ref(props.modelValue);

const handleChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    isChecked.value = target.checked;
    emit('update:modelValue', target.checked);
};
</script>

<template>
    <div class="mb-4">
        <label class="flex items-start space-x-3 cursor-pointer" :class="{ 'opacity-50 cursor-not-allowed': disabled }">
            <input
                type="checkbox"
                :checked="modelValue"
                :disabled="disabled"
                @change="handleChange"
                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1"
            />
            <div>
                <span class="text-sm font-medium text-gray-700">{{ label }}</span>
                <p v-if="helpText && !error" class="mt-1 text-sm text-gray-500">
                    {{ helpText }}
                </p>
                <p v-if="error" class="mt-1 text-sm text-red-600">
                    {{ error }}
                </p>
            </div>
        </label>
    </div>
</template>
