<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  value: number;
  label?: string;
  color?: 'blue' | 'green' | 'red' | 'yellow' | 'purple' | 'indigo';
  size?: 'sm' | 'md' | 'lg';
  showValue?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  label: '',
  color: 'blue',
  size: 'md',
  showValue: true,
});

const colorClasses = {
  blue: 'bg-blue-500',
  green: 'bg-green-500',
  red: 'bg-red-500',
  yellow: 'bg-yellow-500',
  purple: 'bg-purple-500',
  indigo: 'bg-indigo-500',
};

const sizeClasses = {
  sm: 'h-2',
  md: 'h-3',
  lg: 'h-4',
};

const normalizedValue = computed(() => {
  return Math.min(Math.max(props.value, 0), 100);
});
</script>

<template>
  <div class="w-full">
    <div v-if="label || showValue" class="flex justify-between items-center mb-2">
      <span v-if="label" class="text-sm font-medium text-gray-700">{{ label }}</span>
      <span v-if="showValue" class="text-sm font-semibold text-gray-900">{{ value }}%</span>
    </div>
    <div :class="['w-full bg-gray-200 rounded-full overflow-hidden', sizeClasses[size]]">
      <div
        :class="[colorClasses[color], sizeClasses[size], 'rounded-full transition-all duration-500 ease-out']"
        :style="{ width: `${normalizedValue}%` }"
      />
    </div>
  </div>
</template>
