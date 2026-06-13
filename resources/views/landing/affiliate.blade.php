@extends('layouts.app')

@section('title', 'Affiliate Program - Cooca.id')
@section('meta_description', 'Bergabung dengan program affiliate Cooca.id dan dapatkan komisi 25% L1 + 5% L2 seumur hidup.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Affiliate Program
        </h1>
        <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
            Dapatkan passive income dengan merekomendasikan solusi ERP terbaik untuk bisnis Indonesia.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('affiliator.register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                Join Now - Free!
            </a>
            <a href="#how-it-works" class="inline-block bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors text-lg">
                Learn More
            </a>
        </div>
    </div>
</section>

<!-- Commission Stats Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-5xl mb-4">💰</div>
                <div class="text-4xl font-bold text-indigo-600 mb-2">25%</div>
                <div class="text-gray-600 font-semibold">Level 1 Commission</div>
                <p class="text-sm text-gray-500 mt-2">Dari setiap transaksi customer yang Anda referensikan langsung</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-5xl mb-4">🎁</div>
                <div class="text-4xl font-bold text-purple-600 mb-2">5%</div>
                <div class="text-gray-600 font-semibold">Level 2 Commission</div>
                <p class="text-sm text-gray-500 mt-2">Dari transaksi customer downline level 2 Anda</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-5xl mb-4">🔄</div>
                <div class="text-4xl font-bold text-green-600 mb-2">Lifetime</div>
                <div class="text-gray-600 font-semibold">Recurring Income</div>
                <p class="text-sm text-gray-500 mt-2">Komisi berjalan terus selama customer aktif berlangganan</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-5xl mb-4">⚡</div>
                <div class="text-4xl font-bold text-yellow-600 mb-2">Instant</div>
                <div class="text-gray-600 font-semibold">Withdrawal</div>
                <p class="text-sm text-gray-500 mt-2">Penarikan dana otomatis dengan fee minimal</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Why Join Our Affiliate Program?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Program affiliate terbaik dengan benefit yang tidak bisa Anda temukan di tempat lain
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($benefits ?? [] as $benefit)
            <div class="text-center">
                <div class="text-5xl mb-4">{{ $benefit['icon'] }}</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $benefit['title'] }}</h3>
                <p class="text-gray-600">{{ $benefit['description'] }}</p>
            </div>
            @empty
            <!-- Default Benefits -->
            <div class="text-center">
                <div class="text-5xl mb-4">💰</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Komisi 25%</h3>
                <p class="text-gray-600">Dapatkan 25% dari setiap pembayaran customer yang Anda referensikan.</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">🔄</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Recurring Income</h3>
                <p class="text-gray-600">Komisi berjalan terus selama customer aktif berlangganan.</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">👥</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Multi-Level</h3>
                <p class="text-gray-600">Dapatkan 5% dari downline level 2 yang Anda bangun.</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">⚡</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Withdraw Cepat</h3>
                <p class="text-gray-600">Penarikan dana otomatis dengan fee minimal.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                How It Works
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Mulai menghasilkan dalam 4 langkah mudah
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @forelse($howItWorks ?? [] as $step)
            <div class="relative">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center h-full">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        {{ $step['step'] }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $step['title'] }}</h3>
                    <p class="text-gray-600">{{ $step['description'] }}</p>
                </div>
                @if(!$loop->last)
                <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                    <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
                @endif
            </div>
            @empty
            <!-- Default Steps -->
            <div class="relative">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center h-full">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Daftar</h3>
                    <p class="text-gray-600">Registrasi menjadi affiliator secara gratis</p>
                </div>
                <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                    <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center h-full">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Promosi</h3>
                    <p class="text-gray-600">Bagikan link referral Anda ke calon customer</p>
                </div>
                <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                    <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center h-full">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Earn</h3>
                    <p class="text-gray-600">Dapatkan komisi saat customer berlangganan</p>
                </div>
                <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                    <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center h-full">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                        4
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Withdraw</h3>
                    <p class="text-gray-600">Tarik saldo komisi ke rekening Anda</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Commission Calculator Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Potential Earnings
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Lihat berapa yang bisa Anda hasilkan dengan program affiliate kami
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Monthly Billing Example -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Monthly Billing Example</h3>
                
                @php
                    $monthlyCustomers = $commissionExample['monthly']['customers'] ?? 10;
                    $monthlyAvgPrice = $commissionExample['monthly']['avgPrice'] ?? 500000;
                    $monthlyCommission = $commissionExample['monthly']['commission'] ?? 1250000;
                @endphp

                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Customer Referenced</span>
                        <span class="font-bold text-gray-900">{{ $monthlyCustomers }} customers</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Average Price/Month</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($monthlyAvgPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Total Transaction Value</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($monthlyCustomers * $monthlyAvgPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-lg font-semibold text-gray-900">Your Commission (25%)</span>
                        <span class="text-3xl font-bold text-green-600">Rp {{ number_format($monthlyCommission, 0, ',', '.') }}/month</span>
                    </div>
                </div>

                <div class="mt-6 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-center">
                    <strong>Yearly Potential:</strong> Rp {{ number_format($monthlyCommission * 12, 0, ',', '.') }}/tahun
                </div>
            </div>

            <!-- Yearly Billing Example -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Yearly Billing Example</h3>
                
                @php
                    $yearlyCustomers = $commissionExample['yearly']['customers'] ?? 10;
                    $yearlyAvgPrice = $commissionExample['yearly']['avgPrice'] ?? 5000000;
                    $yearlyCommission = $commissionExample['yearly']['commission'] ?? 12500000;
                @endphp

                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Customer Referenced</span>
                        <span class="font-bold text-gray-900">{{ $yearlyCustomers }} customers</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Average Price/Year</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($yearlyAvgPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Total Transaction Value</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($yearlyCustomers * $yearlyAvgPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-lg font-semibold text-gray-900">Your Commission (25%)</span>
                        <span class="text-3xl font-bold text-green-600">Rp {{ number_format($yearlyCommission, 0, ',', '.') }}/transaction</span>
                    </div>
                </div>

                <div class="mt-6 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-center">
                    <strong>One-time earning per customer</strong> for yearly subscription
                </div>
            </div>
        </div>

        <p class="text-center text-gray-500 mt-8 text-sm">
            * Perhitungan di atas adalah contoh. Actual earnings tergantung pada jumlah customer yang berhasil Anda referensikan.
        </p>
    </div>
</section>

<!-- Tools & Resources Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Marketing Tools & Resources
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Kami menyediakan semua tools yang Anda butuhkan untuk sukses
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="text-4xl mb-4">🔗</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Referral Link Generator</h3>
                <p class="text-gray-600 mb-4">Generate unique referral link dengan custom tracking parameters.</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li>✓ Custom campaign tags</li>
                    <li>✓ UTM parameters support</li>
                    <li>✓ Short link available</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="text-4xl mb-4">📱</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Marketing Materials</h3>
                <p class="text-gray-600 mb-4">Banner, social media posts, dan email templates siap pakai.</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li>✓ Social media kits</li>
                    <li>✓ Email templates</li>
                    <li>✓ Banner ads various sizes</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="text-4xl mb-4">📊</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Real-time Dashboard</h3>
                <p class="text-gray-600 mb-4">Track clicks, conversions, dan commissions secara real-time.</p>
                <ul class="text-sm text-gray-500 space-y-1">
                    <li>✓ Live statistics</li>
                    <li>✓ Conversion funnel</li>
                    <li>✓ Export reports</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-gray-600">Pertanyaan umum tentang program affiliate kami</p>
        </div>

        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-aff-1').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Apakah pendaftaran gratis?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-aff-1" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Ya, pendaftaran program affiliate 100% gratis. Tidak ada biaya bulanan atau komitmen minimum.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-aff-2').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Kapan komisi dibayarkan?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-aff-2" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Komisi dikreditkan ke akun Anda segera setelah customer melakukan pembayaran. Anda bisa withdraw kapan saja dengan minimum penarikan Rp 100.000.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-aff-3').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Metode withdrawal apa yang tersedia?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-aff-3" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Kami mendukung transfer bank lokal (BCA, Mandiri, BNI, BRI), e-wallet (GoPay, OVO, Dana), dan QRIS.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-aff-4').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Berapa lama komisi Level 2 berjalan?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-aff-4" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Komisi Level 2 (5%) berjalan seumur hidup selama downline Anda tetap aktif sebagai affiliator dan menghasilkan referral.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" 
                        onclick="document.getElementById('faq-aff-5').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Apakah ada batasan jumlah referral?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-aff-5" class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Tidak ada batasan! Anda bisa mereferensikan sebanyak mungkin customer. Semakin banyak referral, semakin besar pendapatan Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Ready to Start Earning?
        </h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            Bergabunglah dengan ratusan affiliator yang sudah menghasilkan passive income dari program kami.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('affiliator.register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                Join Affiliate Program Now
            </a>
            <a href="{{ route('login') }}" class="inline-block bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors text-lg">
                Login to Dashboard
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId !== '#') {
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    });
});
</script>
@endpush
