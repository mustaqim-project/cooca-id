@extends('layouts.guest')

@section('title', 'Cooca.id - SaaS ERP Platform')
@section('meta_description', 'Platform SaaS ERP multi-tenant untuk berbagai industri dengan sistem lisensi dan affiliate 2-tier')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                All-in-One ERP Platform
            </h1>
            <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                Solusi ERP siap pakai untuk Restoran, Klinik, Legal, Bengkel, dan berbagai industri lainnya dengan sistem lisensi fleksibel.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/customer/register" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                    Start Free Trial
                </a>
                <a href="/pricing" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors text-lg">
                    View Pricing
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Powerful Features for Your Business
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Everything you need to manage your business efficiently
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">License Management</h3>
                <p class="text-gray-600">Sistem lisensi domain-bound dengan validasi otomatis dan aktivasi instant.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Affiliate 2-Tier</h3>
                <p class="text-gray-600">Earn 25% L1 + 5% L2 lifetime commissions dari setiap transaksi.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Multi-Tenant</h3>
                <p class="text-gray-600">Isolasi data lengkap dengan multi-guard authentication untuk Admin, Customer, dan Affiliator.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Voucher System</h3>
                <p class="text-gray-600">Flexible voucher system dengan support percent/nominal discount dan usage limits.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Payment Gateway</h3>
                <p class="text-gray-600">Integrasi Midtrans untuk pembayaran aman dengan auto-commission calculation.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Notifications</h3>
                <p class="text-gray-600">Multi-channel notifications via Email, WhatsApp (Fonnte), dan in-app.</p>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Ready-to-Use ERP Solutions
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Pilih solusi ERP yang sesuai dengan industri Anda
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="/products/restaurant" class="group block bg-gray-50 rounded-xl p-6 hover:bg-indigo-50 transition-colors">
                <div class="text-4xl mb-4">🍽️</div>
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">ERP Restoran</h3>
                <p class="text-sm text-gray-600 mt-2">POS, Inventory, Table Management, Kitchen Display</p>
            </a>

            <a href="/products/clinic" class="group block bg-gray-50 rounded-xl p-6 hover:bg-indigo-50 transition-colors">
                <div class="text-4xl mb-4">🏥</div>
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">ERP Klinik</h3>
                <p class="text-sm text-gray-600 mt-2">Patient Records, Appointments, Pharmacy, Billing</p>
            </a>

            <a href="/products/legal" class="group block bg-gray-50 rounded-xl p-6 hover:bg-indigo-50 transition-colors">
                <div class="text-4xl mb-4">⚖️</div>
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">Legal Management</h3>
                <p class="text-sm text-gray-600 mt-2">Case Management, Document Tracking, Client Portal</p>
            </a>

            <a href="/products/workshop" class="group block bg-gray-50 rounded-xl p-6 hover:bg-indigo-50 transition-colors">
                <div class="text-4xl mb-4">🔧</div>
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">Bengkel System</h3>
                <p class="text-sm text-gray-600 mt-2">Job Orders, Spare Parts, Service History, Invoicing</p>
            </a>
        </div>
    </div>
</section>

<!-- Affiliate CTA -->
<section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Join Our Affiliate Program
        </h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            Earn 25% commission Level 1 + 5% Level 2 untuk setiap transaksi yang dihasilkan. Passive income seumur hidup!
        </p>
        <a href="/affiliator/register" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
            Become an Affiliator Now
        </a>
    </div>
</section>
@endsection
