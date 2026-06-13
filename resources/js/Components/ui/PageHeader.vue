<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
    actions?: any[];
}

defineProps<Props>();
</script>

<template>
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ title }}</h1>
                <p v-if="description" class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ description }}
                </p>
            </div>
            <div v-if="actions && actions.length > 0" class="flex items-center gap-2">
                <slot name="actions">
                    <component
                        v-for="(action, index) in actions"
                        :is="action.component || 'button'"
                        :key="index"
                        v-bind="action.props"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg v-if="action.icon" class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="action.icon" />
                        </svg>
                        {{ action.label }}
                    </component>
                </slot>
            </div>
        </div>
    </div>
</template>
