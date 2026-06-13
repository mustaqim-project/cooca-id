<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface NavItem {
    name: string;
    href: string;
    icon: string;
}

const props = defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const navigation = computed<NavItem[]>(() => [
    { name: 'Dashboard', href: route('admin.dashboard'), icon: 'home' },
    { name: 'Products', href: route('admin.products.index'), icon: 'box' },
    { name: 'Customers', href: route('admin.customers.index'), icon: 'users' },
    { name: 'Affiliators', href: route('admin.affiliators.index'), icon: 'user-group' },
    { name: 'Licenses', href: route('admin.licenses.index'), icon: 'key' },
    { name: 'Subscriptions', href: route('admin.subscriptions.index'), icon: 'refresh' },
    { name: 'Transactions', href: route('admin.transactions.index'), icon: 'currency-dollar' },
    { name: 'Vouchers', href: route('admin.vouchers.index'), icon: 'ticket' },
    { name: 'Settlements', href: route('admin.settlements.index'), icon: 'banknotes' },
    { name: 'Tickets', href: route('admin.tickets.index'), icon: 'chat' },
    { name: 'Reviews', href: route('admin.reviews.index'), icon: 'star' },
    { name: 'Blog', href: route('admin.blog.index'), icon: 'document-text' },
    { name: 'CMS Pages', href: route('admin.cms.pages.index'), icon: 'document' },
    { name: 'Email Campaigns', href: route('admin.email-campaigns.index'), icon: 'mail' },
    { name: 'Settings', href: route('admin.settings.index'), icon: 'cog' },
]);

const getIconPath = (icon: string): string => {
    const icons: Record<string, string> = {
        home: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        box: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'user-group': 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        key: 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
        refresh: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'currency-dollar': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ticket: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
        banknotes: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        chat: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        star: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        'document-text': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        document: 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        mail: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        cog: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    };
    return icons[icon] || icons.home;
};

const isActive = (href: string): boolean => {
    return typeof window !== 'undefined' && window.location.pathname.startsWith(href.replace(/\/\d+$/, ''));
};
</script>

<template>
    <!-- Mobile sidebar backdrop -->
    <div
        v-if="isOpen"
        class="fixed inset-0 z-40 flex md:hidden bg-gray-900/50 backdrop-blur-sm transition-opacity"
        @click="emit('close')"
    />

    <!-- Sidebar -->
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-900 shadow-xl transform transition-transform duration-300 ease-in-out md:translate-x-0 border-r border-gray-200 dark:border-gray-800',
            isOpen ? 'translate-x-0' : '-translate-x-full'
        ]"
    >
        <!-- Logo -->
        <div class="flex h-16 items-center justify-between px-6 border-b border-gray-200 dark:border-gray-800">
            <Link :href="route('admin.dashboard')" class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Cooca.id
            </Link>
            <button
                type="button"
                class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                @click="emit('close')"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-4 px-3 space-y-1 overflow-y-auto max-h-[calc(100vh-4rem)] pb-4">
            <Link
                v-for="item in navigation"
                :key="item.name"
                :href="item.href"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200"
                :class="[
                    isActive(item.href)
                        ? 'bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-900/30 text-indigo-700 dark:text-indigo-400 border-l-4 border-indigo-600'
                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white border-l-4 border-transparent'
                ]"
            >
                <svg
                    class="mr-3 h-5 w-5 flex-shrink-0 transition-colors"
                    :class="isActive(item.href) ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300'"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getIconPath(item.icon)" />
                </svg>
                {{ item.name }}
            </Link>
        </nav>
    </aside>
</template>
