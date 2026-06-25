<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Props {
    title?: string;
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

const navigationGroups = computed(() => [
    {
        title: 'Main',
        items: [
            { name: 'Dashboard', href: route('admin.dashboard'), icon: 'home' }
        ]
    },
    {
        title: 'Users',
        items: [
            { name: 'Customers', href: route('admin.customers.index'), icon: 'users' },
            { name: 'Affiliators', href: route('admin.affiliators.index'), icon: 'user-group' }
        ]
    },
    {
        title: 'Catalog',
        items: [
            { name: 'Products', href: route('admin.products.index'), icon: 'box' },
            { name: 'Categories', href: route('admin.product-categories.index'), icon: 'tag' },
            { name: 'Subscriptions', href: route('admin.subscriptions.index'), icon: 'calendar' },
            { name: 'Licenses', href: route('admin.licenses.index'), icon: 'key' }
        ]
    },
    {
        title: 'Sales & Finance',
        items: [
            { name: 'Transactions', href: route('admin.transactions.index'), icon: 'currency-dollar' },
            { name: 'Settlements', href: route('admin.settlements.index'), icon: 'banknotes' },
            { name: 'Vouchers', href: route('admin.vouchers.index'), icon: 'ticket' },
            { name: 'ERP Requests', href: route('admin.erp-requests.index'), icon: 'server' }
        ]
    },
    {
        title: 'Content',
        items: [
            { name: 'CMS Pages', href: route('admin.cms.pages.index'), icon: 'document-text' },
            { name: 'Blog', href: route('admin.blog.index'), icon: 'newspaper' },
            { name: 'FAQs', href: route('admin.faqs.index'), icon: 'question-mark-circle' },
            { name: 'Testimonials', href: route('admin.testimonials.index'), icon: 'chat-bubble' },
            { name: 'Reviews', href: route('admin.reviews.index'), icon: 'star' }
        ]
    },
    {
        title: 'Communication',
        items: [
            { name: 'Email Campaigns', href: route('admin.email-campaigns.index'), icon: 'envelope' },
            { name: 'Email Templates', href: route('admin.email-templates.index'), icon: 'document-duplicate' },
            { name: 'Tickets', href: route('admin.tickets.index'), icon: 'inbox' }
        ]
    },
    {
        title: 'System',
        items: [
            { name: 'Settings', href: route('admin.settings.index'), icon: 'cog' }
        ]
    }
]);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <Head :title="title || 'Admin Panel'" />

        <!-- Mobile sidebar backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity md:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- Sidebar -->
        <div
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out flex flex-col md:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-gray-100">
                <Link :href="route('admin.dashboard')" class="text-2xl font-bold text-gray-900 tracking-tight">
                    Cooca<span class="text-indigo-600">.id</span>
                </Link>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
                <div v-for="group in navigationGroups" :key="group.title">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ group.title }}</h3>
                    <div class="space-y-1">
                        <Link
                            v-for="item in group.items"
                            :key="item.name"
                            :href="item.href"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors"
                            :class="
                                $page.url === new URL(item.href).pathname || $page.url.startsWith(new URL(item.href).pathname + '/')
                                    ? 'bg-gray-900 text-white shadow-sm'
                                    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'
                            "
                        >
                            <svg 
                                class="mr-3 h-5 w-5 flex-shrink-0" 
                                :class="$page.url === new URL(item.href).pathname || $page.url.startsWith(new URL(item.href).pathname + '/') ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-500'" 
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getIconPath(item.icon)" />
                            </svg>
                            {{ item.name }}
                        </Link>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 md:pl-64 flex flex-col min-h-screen min-w-0">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <button
                        type="button"
                        class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none"
                        @click="toggleSidebar"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1 flex px-4 md:px-0">
                        <div class="relative w-full max-w-md hidden sm:block">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search anywhere..."
                                class="w-full pl-10 pr-4 py-2 border-0 bg-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors"
                            />
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center gap-3">
                            <div class="hidden md:block text-right">
                                <div class="text-sm font-medium text-gray-900">{{ $page.props.auth?.user?.name || user?.name || 'Admin' }}</div>
                                <div class="text-xs text-gray-500">{{ $page.props.auth?.user?.email || user?.email || 'admin@cooca.id' }}</div>
                            </div>
                            <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                                {{ ($page.props.auth?.user?.name || user?.name || 'A').charAt(0) }}
                            </div>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <Link
                            :href="route('admin.logout')"
                            method="post"
                            as="button"
                            class="text-sm font-medium text-red-600 hover:text-red-700"
                        >
                            Logout
                        </Link>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 py-8">
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
                banknotes: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                tag: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                calendar: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                server: 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01',
                'document-text': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                newspaper: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
                'question-mark-circle': 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'chat-bubble': 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                star: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                envelope: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'document-duplicate': 'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z',
                inbox: 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
                cog: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
            };
            return icons[icon] || icons.home;
        }
    }
};
</script>
