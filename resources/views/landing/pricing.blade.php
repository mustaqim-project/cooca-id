@extends('layouts.app')

@section('title', 'Pricing - Cooca.id')
@section('meta_description', 'Harga transparan untuk berbagai paket ERP. Mulai dari trial gratis hingga enterprise.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Simple, Transparent Pricing
        </h1>
        <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
            Pilih paket yang sesuai dengan kebutuhan bisnis Anda. Semua paket termasuk 7 hari trial gratis.
        </p>
    </div>
</section>

<!-- Products & Pricing Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @forelse($products ?? [] as $product)
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                <p class="text-lg text-gray-600">{{ $product->description }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($product->subscriptionPlans ?? [] as $plan)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border @if($plan->is_popular) border-indigo-500 ring-2 ring-indigo-500 @endif border-gray-200">
                    @if($plan->is_popular)
                    <div class="bg-indigo-500 text-white text-center py-2 text-sm font-semibold">
                        MOST POPULAR
                    </div>
                    @endif
                    
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-gray-900">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                            <span class="text-gray-600">/{{ $plan->billing_cycle === 'monthly' ? 'bulan' : 'tahun' }}</span>
                        </div>
                        
                        @if($plan->billing_cycle === 'yearly')
                        <div class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full inline-block mb-4">
                            Hemat 20% vs bulanan
                        </div>
                        @endif

                        <ul class="space-y-3 mb-8">
                            @if($plan->features && is_array($plan->features))
                                @foreach($plan->features as $feature)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </li>
                                @endforeach
                            @else
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Semua fitur dasar {{ $product->name }}</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Support via Email & WhatsApp</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Update otomatis</span>
                                </li>
                            @endif
                        </ul>

                        <a href="{{ route('customer.register') }}" 
                           class="block w-full text-center py-3 px-6 rounded-lg font-semibold transition-colors @if($plan->is_popular) bg-indigo-600 text-white hover:bg-indigo-700 @else bg-gray-100 text-gray-900 hover:bg-gray-200 @endif">
                            Start Free Trial
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <!-- Default Pricing if no products found -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">ERP Restoran</h2>
            <p class="text-lg text-gray-600">Solusi lengkap untuk manajemen restoran Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Starter Plan -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-gray-900">Rp 299.000</span>
                        <span class="text-gray-600">/bulan</span>
                    </div>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Hingga 50 transaksi/hari</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">POS System</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Basic Inventory</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">1 User Account</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Email Support</span>
                        </li>
                    </ul>

                    <a href="{{ route('customer.register') }}" class="block w-full text-center py-3 px-6 rounded-lg font-semibold bg-gray-100 text-gray-900 hover:bg-gray-200 transition-colors">
                        Start Free Trial
                    </a>
                </div>
            </div>

            <!-- Professional Plan -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-indigo-500 ring-2 ring-indigo-500 relative">
                <div class="bg-indigo-500 text-white text-center py-2 text-sm font-semibold">
                    MOST POPULAR
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Professional</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-gray-900">Rp 599.000</span>
                        <span class="text-gray-600">/bulan</span>
                    </div>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Transaksi Unlimited</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">POS + Kitchen Display</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Advanced Inventory</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">5 User Accounts</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Priority Support 24/7</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Laporan & Analytics</span>
                        </li>
                    </ul>

                    <a href="{{ route('customer.register') }}" class="block w-full text-center py-3 px-6 rounded-lg font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                        Start Free Trial
                    </a>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-gray-900">Rp 1.299.000</span>
                        <span class="text-gray-600">/bulan</span>
                    </div>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Semua fitur Professional</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Unlimited Users</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Multi-outlet Support</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Custom Integration</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Dedicated Account Manager</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">SLA 99.9%</span>
                        </li>
                    </ul>

                    <a href="{{ route('customer.register') }}" class="block w-full text-center py-3 px-6 rounded-lg font-semibold bg-gray-100 text-gray-900 hover:bg-gray-200 transition-colors">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-gray-600">Pertanyaan yang sering diajukan tentang pricing dan layanan kami</p>
        </div>

        <div class="space-y-4">
            @forelse($faq ?? [] as $index => $item)
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-answer-{{ $index }}').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">{{ $item['question'] }}</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-answer-{{ $index }}" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">{{ $item['answer'] }}</p>
                </div>
            </div>
            @empty
            <!-- Default FAQ -->
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-answer-1').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Bagaimana cara memulai trial?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-answer-1" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Pilih produk ERP yang diinginkan, klik "Coba Gratis", isi data bisnis Anda, dan sistem akan aktif dalam 30 menit - 1 jam setelah verifikasi admin.</p>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-answer-2').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Apakah ada biaya tersembunyi?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-answer-2" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Tidak sama sekali. Harga yang tertera adalah harga final. Tidak ada biaya setup, instalasi, atau maintenance tambahan.</p>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-answer-3').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Bisakah upgrade/downgrade paket kapan saja?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-answer-3" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Ya, Anda dapat upgrade atau downgrade paket subscription kapan saja. Perubahan akan berlaku pada periode billing berikutnya.</p>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-answer-4').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Apa yang terjadi setelah trial berakhir?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-answer-4" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Setelah trial 7 hari, Anda perlu berlangganan untuk terus menggunakan sistem. Data Anda tetap tersimpan dan dapat diakses segera setelah berlangganan.</p>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-answer-5').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Apakah tersedia diskon untuk pembayaran tahunan?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-answer-5" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Ya, pembayaran tahunan mendapatkan diskon hingga 20% dibandingkan pembayaran bulanan.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Siap Memulai?
        </h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            Coba gratis selama 7 hari. Tidak perlu kartu kredit. Batal kapan saja.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('customer.register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                Start Free Trial Now
            </a>
            <a href="{{ route('contact') }}" class="inline-block bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors text-lg">
                Contact Sales
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Toggle FAQ answers
document.querySelectorAll('[onclick*="faq-answer"]').forEach(button => {
    button.addEventListener('click', function() {
        const icon = this.querySelector('svg');
        if (icon) {
            icon.classList.toggle('rotate-180');
        }
    });
});
</script>
@endpush
