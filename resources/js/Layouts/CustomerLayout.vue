<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Props {
    title: string;
    user?: {
        id: string;
        name: string;
        email: string;
        business_name?: string;
        domain?: string;
        avatar?: string;
    };
}

const props = defineProps<Props>();

const sidebarOpen = ref(false);

const navigation = computed(() => [
    { name: 'Dashboard', href: route('customer.dashboard'), icon: 'home' },
    { name: 'Produk', href: route('customer.products.index'), icon: 'box' },
    { name: 'Subscripsi Saya', href: route('customer.subscriptions.index'), icon: 'refresh' },
    { name: 'Lisensi', href: route('customer.licenses.index'), icon: 'key' },
    { name: 'Invoice', href: route('customer.invoices.index'), icon: 'document-text' },
]);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const getIconPath = (icon: string): string => {
    const icons: Record<string, string> = {
        home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        box: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        refresh: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        key: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
        'document-text': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
    };
    return icons[icon] || icons.home;
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
                <Link :href="route('customer.dashboard')" class="text-2xl font-bold text-indigo-600">
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

                    <div class="flex-1"></div>

                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ user?.name }}</p>
                            <p class="text-xs text-gray-500">{{ user?.business_name || '-' }}</p>
                        </div>
                        <Link
                            :href="route('customer.logout')"
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