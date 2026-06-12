<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cooca.id - SaaS ERP Platform')</title>
    <meta name="description" content="@yield('meta_description', 'Platform SaaS ERP multi-tenant untuk berbagai industri dengan sistem lisensi dan affiliate')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @inertiaHead --}}
</head>

<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600">
                        Cooca.id
                    </a>
                    <div class="hidden md:flex ml-10 space-x-8">
                        <a href="/#features" class="text-gray-700 hover:text-indigo-600 transition-colors">
                            Features
                        </a>
                        <a href="/pricing" class="text-gray-700 hover:text-indigo-600 transition-colors">
                            Pricing
                        </a>
                        <a href="/blog" class="text-gray-700 hover:text-indigo-600 transition-colors">
                            Blog
                        </a>
                        <a href="/about" class="text-gray-700 hover:text-indigo-600 transition-colors">
                            About
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/customer/login" class="text-gray-700 hover:text-indigo-600 transition-colors">
                        Login
                    </a>
                    <a href="/customer/register"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Start Free Trial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
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
                        <li><a href="/products/restaurant" class="hover:text-white">ERP Restoran</a></li>
                        <li><a href="/products/clinic" class="hover:text-white">ERP Klinik</a></li>
                        <li><a href="/products/legal" class="hover:text-white">Legal Management</a></li>
                        <li><a href="/products/workshop" class="hover:text-white">Bengkel System</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="/about" class="hover:text-white">About Us</a></li>
                        <li><a href="/blog" class="hover:text-white">Blog</a></li>
                        <li><a href="/pricing" class="hover:text-white">Pricing</a></li>
                        <li><a href="/contact" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Affiliate Program</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="/affiliator/register" class="hover:text-white">Become Affiliator</a></li>
                        <li>Earn 25% L1 + 5% L2</li>
                        <li>Lifetime commissions</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Cooca.id. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
