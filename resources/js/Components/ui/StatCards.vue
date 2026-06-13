<script setup lang="ts">
interface StatCard {
    title: string;
    value: string | number;
    change?: number;
    icon?: string;
    color?: 'indigo' | 'green' | 'yellow' | 'red' | 'blue' | 'purple' | 'pink' | 'cyan';
}

interface Props {
    stats: StatCard[];
    columns?: 1 | 2 | 3 | 4;
}

withDefaults(defineProps<Props>(), {
    columns: 4
});

const getColorClasses = (color: string = 'indigo') => {
    const classes: Record<string, string> = {
        indigo: 'bg-indigo-500 dark:bg-indigo-600',
        green: 'bg-green-500 dark:bg-green-600',
        yellow: 'bg-yellow-500 dark:bg-yellow-600',
        red: 'bg-red-500 dark:bg-red-600',
        blue: 'bg-blue-500 dark:bg-blue-600',
        purple: 'bg-purple-500 dark:bg-purple-600',
        pink: 'bg-pink-500 dark:bg-pink-600',
        cyan: 'bg-cyan-500 dark:bg-cyan-600'
    };
    return classes[color] || classes.indigo;
};

const getIconPath = (icon: string): string => {
    const icons: Record<string, string> = {
        users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'user-plus': 'M18 9v3m0 0l-3-3m3 3l3-3m-2 0h-1m-2 0a9 9 0 01-9-9m9 9a9 9 0 01-9 9m9-9V3m0 0L9 6m3-3L6 6m3 3a9 9 0 01-9 9m9-9a9 9 0 019 9',
        'currency-dollar': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'document-text': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'shopping-cart': 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        chart: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        key: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
        'credit-card': 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'check-circle': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock': 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'trending-up': 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'trending-down': 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6',
        'exclamation': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
    };
    return icons[icon] || icons.chart;
};

const getGridClass = (columns: number) => {
    const classes: Record<number, string> = {
        1: 'grid-cols-1',
        2: 'grid-cols-1 md:grid-cols-2',
        3: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'
    };
    return classes[columns] || classes[4];
};
</script>

<template>
    <div :class="['grid gap-6', getGridClass(columns)]">
        <div
            v-for="(stat, index) in stats"
            :key="index"
            class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200"
        >
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">
                        {{ stat.title }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ stat.value }}
                    </p>
                    <div v-if="stat.change !== undefined" class="mt-2 flex items-center">
                        <svg
                            :class="stat.change >= 0 ? 'text-green-500' : 'text-red-500'"
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="stat.change >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6'"
                            />
                        </svg>
                        <span
                            :class="stat.change >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                            class="ml-1 text-sm font-medium"
                        >
                            {{ Math.abs(stat.change) }}%
                        </span>
                    </div>
                </div>
                <div
                    v-if="stat.icon"
                    :class="[getColorClasses(stat.color), 'p-3 rounded-lg']"
                >
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            :d="getIconPath(stat.icon)"
                        />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>
