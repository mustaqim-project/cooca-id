<script setup lang="ts">
defineProps<{
    value?: string;
    label?: string;
    required?: boolean;
}>();

defineEmits(['update:value']);
</script>

<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
        </label>
        <input
            :value="value"
            @input="$emit('update:value', ($event.target as HTMLInputElement).value)"
            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            :required="required"
        />
    </div>
</template>
