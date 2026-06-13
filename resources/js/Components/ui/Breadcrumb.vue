<script setup lang="ts">
interface BreadcrumbItem {
    label: string;
    href?: string;
}

interface Props {
    items: BreadcrumbItem[];
}

defineProps<Props>();
</script>

<template>
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li v-for="(item, index) in items" :key="index" class="inline-flex items-center">
                <template v-if="index > 0">
                    <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </template>
                <component
                    :is="item.href ? 'a' : 'span'"
                    :href="item.href"
                    :class="[
                        'text-sm font-medium',
                        index === items.length - 1 
                            ? 'text-gray-900 dark:text-white' 
                            : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    ]"
                >
                    {{ item.label }}
                </component>
            </li>
        </ol>
    </nav>
</template>
