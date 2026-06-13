<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Props {
    title: string;
    user?: {
        id: string;
        name: string;
        email: string;
        referral_code?: string;
        balance?: number;
        avatar?: string;
    };
}

const props = defineProps<Props>();

const sidebarOpen = ref(false);

const navigation = computed(() => [
    { name: 'Dashboard', href: route('affiliator.dashboard'), icon: 'home' },
    { name: 'Referrals', href: route('affiliator.referrals.index'), icon: 'user-plus' },
    { name: 'Commissions', href: route('affiliator.commissions.index'), icon: 'currency-dollar' },
    { name: 'Downlines', href: route('affiliator.downlines.index'), icon: 'users' },
    { name: 'Withdrawals', href: route('affiliator.withdrawals.index'), icon: 'banknotes' },
]);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const formatCurrency = (amount: number | undefined): string => {
    if (amount === undefined) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
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
                <Link :href="route('affiliator.dashboard')" class="text-2xl font-bold text-indigo-600">
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

                    <div class="flex items-center space-x-6">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ user?.name }}</p>
                            <p class="text-xs text-green-600 font-semibold">Balance: {{ formatCurrency(user?.balance) }}</p>
                        </div>
                        <Link
                            :href="route('affiliator.logout')"
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
                'user-plus': 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                'currency-dollar': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                users: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                banknotes: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'
            };
            return icons[icon] || icons.home;
        }
    }
};
</script>
