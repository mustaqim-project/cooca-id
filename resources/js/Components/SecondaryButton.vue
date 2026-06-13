<script setup lang="ts">
defineProps<{
    type?: string;
}>();

defineEmits(['click']);
</script>

<template>
    <button
        :type="type || 'button'"
        @click="$emit('click')"
        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
