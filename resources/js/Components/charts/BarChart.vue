<script setup lang="ts">
import { computed } from 'vue';

interface DataPoint {
  label: string;
  value: number;
}

interface Props {
  data: DataPoint[];
  title?: string;
  color?: 'blue' | 'green' | 'red' | 'yellow' | 'purple' | 'indigo';
  height?: number;
  showValues?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  title: '',
  color: 'blue',
  height: 200,
  showValues: true,
});

const colorClasses = {
  blue: 'text-blue-500',
  green: 'text-green-500',
  red: 'text-red-500',
  yellow: 'text-yellow-500',
  purple: 'text-purple-500',
  indigo: 'text-indigo-500',
};

const bgColors = {
  blue: 'bg-blue-500',
  green: 'bg-green-500',
  red: 'bg-red-500',
  yellow: 'bg-yellow-500',
  purple: 'bg-purple-500',
  indigo: 'bg-indigo-500',
};

const maxValue = computed(() => {
  return Math.max(...props.data.map((d) => d.value), 1);
});
</script>

<template>
  <div class="w-full">
    <h3 v-if="title" class="text-lg font-semibold text-gray-900 mb-4">{{ title }}</h3>
    
    <div class="flex items-end space-x-2" :style="{ height: `${height}px` }">
      <div
        v-for="(point, index) in data"
        :key="index"
        class="flex-1 flex flex-col items-center justify-end group"
      >
        <div v-if="showValues" class="mb-2 opacity-0 group-hover:opacity-100 transition-opacity">
          <span class="text-xs font-semibold text-gray-700 bg-white px-2 py-1 rounded shadow">
            {{ point.value }}
          </span>
        </div>
        <div
          :class="[bgColors[color], 'w-full rounded-t transition-all duration-300 hover:opacity-80']"
          :style="{ height: `${(point.value / maxValue) * 100}%` }"
        />
        <div class="mt-2 text-xs text-gray-600 text-center truncate w-full">{{ point.label }}</div>
      </div>
    </div>
  </div>
</template>
