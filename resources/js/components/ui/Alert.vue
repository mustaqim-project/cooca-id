<script setup lang="ts">
interface Props {
    type?: 'success' | 'error' | 'warning' | 'info';
    title?: string;
    dismissible?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'info',
    dismissible: true
});

const emit = defineEmits<{
    (e: 'dismiss'): void;
}>();

const typeClasses = {
    success: 'bg-green-50 border-green-200 text-green-800',
    error: 'bg-red-50 border-red-200 text-red-800',
    warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
    info: 'bg-blue-50 border-blue-200 text-blue-800'
};

const iconPaths = {
    success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    error: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
};
</script>

<template>
    <div
        :class="[
            'border rounded-lg p-4 flex items-start justify-between',
            typeClasses[type]
        ]"
    >
        <div class="flex items-start space-x-3">
            <svg
                class="h-5 w-5 flex-shrink-0 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="iconPaths[type]"
                />
            </svg>
            <div>
                <p v-if="title" class="font-semibold mb-1">
                    {{ title }}
                </p>
                <slot />
            </div>
        </div>
        <button
            v-if="dismissible"
            @click="emit('dismiss')"
            class="flex-shrink-0 ml-4 -mr-1 inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
            :class="{
                'hover:bg-green-100 focus:ring-green-600': type === 'success',
                'hover:bg-red-100 focus:ring-red-600': type === 'error',
                'hover:bg-yellow-100 focus:ring-yellow-600': type === 'warning',
                'hover:bg-blue-100 focus:ring-blue-600': type === 'info'
            }"
        >
            <span class="sr-only">Dismiss</span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</template>
