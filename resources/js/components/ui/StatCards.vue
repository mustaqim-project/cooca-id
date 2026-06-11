<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface StatCardProps {
    title: string;
    value: string | number;
    change?: number;
    icon: string;
    color: 'indigo' | 'green' | 'yellow' | 'red' | 'blue';
}

const props = defineProps<{
    stats: StatCardProps[];
}>();

const getColorClasses = (color: string) => {
    const classes: Record<string, string> = {
        indigo: 'bg-indigo-500',
        green: 'bg-green-500',
        yellow: 'bg-yellow-500',
        red: 'bg-red-500',
        blue: 'bg-blue-500'
    };
    return classes[color] || classes.indigo;
};

const getIconPath = (icon: string): string => {
    const icons: Record<string, string> = {
        users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'currency-dollar': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'document-text': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'shopping-cart': 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        chart: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        key: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z'
    };
    return icons[icon] || icons.chart;
};
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
            v-for="(stat, index) in stats"
            :key="index"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ stat.title }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ stat.value }}</p>
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
                            :class="stat.change >= 0 ? 'text-green-600' : 'text-red-600'"
                            class="ml-1 text-sm font-medium"
                        >
                            {{ Math.abs(stat.change) }}%
                        </span>
                    </div>
                </div>
                <div
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
