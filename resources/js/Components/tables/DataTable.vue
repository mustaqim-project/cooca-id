<script setup lang="ts">
import { ref, watch } from 'vue';

interface Column {
    key: string;
    label: string;
    sortable?: boolean;
    format?: (value: any) => string;
}

interface Props {
    columns: Column[];
    data: any[];
    loading?: boolean;
    emptyMessage?: string;
    actions?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    loading: false,
    emptyMessage: 'No data available',
    actions: true
});

const emit = defineEmits<{
    (e: 'sort', column: string): void;
    (e: 'edit', item: any): void;
    (e: 'delete', item: any): void;
    (e: 'view', item: any): void;
}>();

const sortColumn = ref<string | null>(null);
const sortDirection = ref<'asc' | 'desc'>('asc');

const handleSort = (column: string) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
    emit('sort', column);
};

const getCellValue = (row: any, column: Column): any => {
    const value = row[column.key];
    if (column.format) {
        return column.format(value);
    }
    return value;
};
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            :class="{ 'cursor-pointer hover:bg-gray-100': column.sortable }"
                            @click="column.sortable && handleSort(column.key)"
                        >
                            <div class="flex items-center space-x-1">
                                <span>{{ column.label }}</span>
                                <svg
                                    v-if="sortColumn === column.key"
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        v-if="sortDirection === 'asc'"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 15l7-7 7 7"
                                    />
                                    <path
                                        v-else
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </div>
                        </th>
                        <th
                            v-if="actions"
                            scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading">
                        <td
                            :colspan="columns.length + (actions ? 1 : 0)"
                            class="px-6 py-8 text-center"
                        >
                            <div class="flex justify-center items-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                </svg>
                                <span class="text-gray-500">Loading...</span>
                            </div>
                        </td>
                    </tr>
                    <tr v-else-if="data.length === 0">
                        <td
                            :colspan="columns.length + (actions ? 1 : 0)"
                            class="px-6 py-8 text-center text-gray-500"
                        >
                            {{ emptyMessage }}
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="(row, index) in data"
                        :key="row.id || index"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td
                            v-for="column in columns"
                            :key="column.key"
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                        >
                            {{ getCellValue(row, column) }}
                        </td>
                        <td
                            v-if="actions"
                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                        >
                            <div class="flex justify-end space-x-2">
                                <button
                                    @click="emit('view', row)"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    View
                                </button>
                                <button
                                    @click="emit('edit', row)"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="emit('delete', row)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
