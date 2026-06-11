<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Props {
    title: string;
    user?: {
        id: string;
        name: string;
        email: string;
        avatar?: string;
    };
}

const props = defineProps<Props>();

const sidebarOpen = ref(false);
const searchQuery = ref('');

const navigation = computed(() => [
    { name: 'Dashboard', href: route('admin.dashboard'), icon: 'home' },
    { name: 'Products', href: route('admin.products.index'), icon: 'box' },
    { name: 'Customers', href: route('admin.customers.index'), icon: 'users' },
    { name: 'Affiliators', href: route('admin.affiliators.index'), icon: 'user-group' },
    { name: 'Licenses', href: route('admin.licenses.index'), icon: 'key' },
    { name: 'Transactions', href: route('admin.transactions.index'), icon: 'currency-dollar' },
    { name: 'Vouchers', href: route('admin.vouchers.index'), icon: 'ticket' },
    { name: 'Settlements', href: route('admin.settlements.index'), icon: 'banknotes' },
]);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <Head :title="title" />

        <!-- Mobile sidebar backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 flex md:hidden"
            @click="sidebarOpen = false"
        >
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" />
        </div>

        <!-- Sidebar -->
        <div
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="flex h-16 items-center justify-center border-b border-gray-200">
                <Link :href="route('admin.dashboard')" class="text-2xl font-bold text-indigo-600">
                    Cooca.id
                </Link>
            </div>

            <nav class="mt-5 px-4 space-y-2">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors"
                    :class="$page.url.startsWith(item.href) ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'"
                >
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getIconPath(item.icon)" />
                    </svg>
                    {{ item.name }}
                </Link>
            </nav>
        </div>

        <!-- Main content -->
        <div class="md:pl-64">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 bg-white shadow-sm border-b border-gray-200">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <button
                        type="button"
                        class="md:hidden text-gray-500 hover:text-gray-700"
                        @click="toggleSidebar"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1 px-4 md:px-0">
                        <div class="relative max-w-md">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700">{{ user?.name }}</span>
                        <Link
                            :href="route('admin.logout')"
                            method="post"
                            as="button"
                            class="text-sm text-red-600 hover:text-red-700"
                        >
                            Logout
                        </Link>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="py-6">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script lang="ts">
export default {
    methods: {
        getIconPath(icon: string): string {
            const icons: Record<string, string> = {
                home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                box: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'user-group': 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                key: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
                'currency-dollar': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                ticket: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
                banknotes: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'
            };
            return icons[icon] || icons.home;
        }
    }
};
</script>
