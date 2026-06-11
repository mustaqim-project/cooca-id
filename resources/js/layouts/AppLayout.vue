<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface Props {
    title?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="min-h-screen bg-white">
        <Head :title="title || 'Cooca.id - SaaS ERP Platform'" />

        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-2xl font-bold text-indigo-600">
                            Cooca.id
                        </Link>
                        <div class="hidden md:flex ml-10 space-x-8">
                            <Link href="/#features" class="text-gray-700 hover:text-indigo-600 transition-colors">
                                Features
                            </Link>
                            <Link href="/pricing" class="text-gray-700 hover:text-indigo-600 transition-colors">
                                Pricing
                            </Link>
                            <Link href="/blog" class="text-gray-700 hover:text-indigo-600 transition-colors">
                                Blog
                            </Link>
                            <Link href="/about" class="text-gray-700 hover:text-indigo-600 transition-colors">
                                About
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link
                            href="/customer/login"
                            class="text-gray-700 hover:text-indigo-600 transition-colors"
                        >
                            Login
                        </Link>
                        <Link
                            href="/customer/register"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                            Start Free Trial
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Cooca.id</h3>
                        <p class="text-gray-400 text-sm">
                            Platform SaaS ERP multi-tenant untuk berbagai industri dengan sistem lisensi dan affiliate.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Products</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><Link href="/products/restaurant" class="hover:text-white">ERP Restoran</Link></li>
                            <li><Link href="/products/clinic" class="hover:text-white">ERP Klinik</Link></li>
                            <li><Link href="/products/legal" class="hover:text-white">Legal Management</Link></li>
                            <li><Link href="/products/workshop" class="hover:text-white">Bengkel System</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Company</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><Link href="/about" class="hover:text-white">About Us</Link></li>
                            <li><Link href="/blog" class="hover:text-white">Blog</Link></li>
                            <li><Link href="/pricing" class="hover:text-white">Pricing</Link></li>
                            <li><Link href="/contact" class="hover:text-white">Contact</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Affiliate Program</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><Link href="/affiliator/register" class="hover:text-white">Become Affiliator</Link></li>
                            <li>Earn 25% L1 + 5% L2</li>
                            <li>Lifetime commissions</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                    <p>&copy; {{ new Date().getFullYear() }} Cooca.id. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
