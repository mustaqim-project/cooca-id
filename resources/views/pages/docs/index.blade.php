<x-layouts.app title="Documentation - COOCA.ID ERP">
    <x-slot name="description">Dokumentasi lengkap penggunaan COOCA.ID ERP Platform. Panduan setup, fitur, API, dan troubleshooting.</x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 to-indigo-700 py-20 text-white">
        <div class="container mx-auto px-4 text-center">
            <h1 class="mb-6 text-4xl font-bold md:text-5xl">Dokumentasi COOCA.ID</h1>
            <p class="mx-auto mb-8 max-w-2xl text-lg text-blue-100">
                Panduan lengkap untuk memulai dan memaksimalkan penggunaan ERP COOCA.ID.
            </p>
            
            <!-- Search Box -->
            <div class="max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" placeholder="Cari dokumentasi..." class="w-full py-4 px-6 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <button class="absolute right-2 top-2 bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Documentation Categories -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                
                <!-- Getting Started -->
                <div class="border rounded-xl p-8 hover:shadow-lg transition">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Getting Started</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#register" class="hover:text-blue-600 hover:underline">→ Registrasi Akun</a></li>
                        <li><a href="#trial" class="hover:text-blue-600 hover:underline">→ Memulai Trial</a></li>
                        <li><a href="#setup" class="hover:text-blue-600 hover:underline">→ Setup Awal</a></li>
                        <li><a href="#domain" class="hover:text-blue-600 hover:underline">→ Konfigurasi Domain</a></li>
                    </ul>
                </div>

                <!-- User Guide -->
                <div class="border rounded-xl p-8 hover:shadow-lg transition">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">User Guide</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#dashboard" class="hover:text-blue-600 hover:underline">→ Dashboard Overview</a></li>
                        <li><a href="#inventory" class="hover:text-blue-600 hover:underline">→ Manajemen Inventaris</a></li>
                        <li><a href="#pos" class="hover:text-blue-600 hover:underline">→ Sistem POS</a></li>
                        <li><a href="#reporting" class="hover:text-blue-600 hover:underline">→ Laporan & Analitik</a></li>
                    </ul>
                </div>

                <!-- Industry Modules -->
                <div class="border rounded-xl p-8 hover:shadow-lg transition">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Industry Modules</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#restaurant" class="hover:text-blue-600 hover:underline">→ ERP Restoran</a></li>
                        <li><a href="#clinic" class="hover:text-blue-600 hover:underline">→ ERP Klinik</a></li>
                        <li><a href="#workshop" class="hover:text-blue-600 hover:underline">→ ERP Bengkel</a></li>
                        <li><a href="#legal" class="hover:text-blue-600 hover:underline">→ ERP Legal</a></li>
                    </ul>
                </div>

                <!-- Billing & Subscription -->
                <div class="border rounded-xl p-8 hover:shadow-lg transition">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Billing & Subscription</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#pricing" class="hover:text-blue-600 hover:underline">→ Paket & Harga</a></li>
                        <li><a href="#payment" class="hover:text-blue-600 hover:underline">→ Metode Pembayaran</a></li>
                        <li><a href="#invoice" class="hover:text-blue-600 hover:underline">→ Invoice & Receipt</a></li>
                        <li><a href="#voucher" class="hover:text-blue-600 hover:underline">→ Kode Voucher</a></li>
                    </ul>
                </div>

                <!-- API & Integration -->
                <div class="border rounded-xl p-8 hover:shadow-lg transition">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 text-red-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">API & Integration</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#api-overview" class="hover:text-blue-600 hover:underline">→ API Overview</a></li>
                        <li><a href="#authentication" class="hover:text-blue-600 hover:underline">→ Authentication</a></li>
                        <li><a href="#endpoints" class="hover:text-blue-600 hover:underline">→ API Endpoints</a></li>
                        <li><a href="#webhooks" class="hover:text-blue-600 hover:underline">→ Webhooks</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="border rounded-xl p-8 hover:shadow-lg transition">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#faq" class="hover:text-blue-600 hover:underline">→ FAQ</a></li>
                        <li><a href="#troubleshooting" class="hover:text-blue-600 hover:underline">→ Troubleshooting</a></li>
                        <li><a href="#contact" class="hover:text-blue-600 hover:underline">→ Hubungi Support</a></li>
                        <li><a href="#ticket" class="hover:text-blue-600 hover:underline">→ Submit Ticket</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- Quick Start Guide -->
    <section class="bg-gray-50 py-20">
        <div class="container mx-auto px-4 max-w-4xl">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Quick Start Guide</h2>
            
            <div class="space-y-6">
                <!-- Step 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">1</div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Registrasi Akun</h3>
                        <p class="text-gray-600">Daftar akun customer dengan email bisnis Anda. Verifikasi email untuk mengaktifkan akun.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">2</div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Lengkapi Data Bisnis</h3>
                        <p class="text-gray-600">Isi informasi bisnis: nama bisnis, pemilik, WhatsApp, alamat, dan kategori usaha.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">3</div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Pilih Produk ERP</h3>
                        <p class="text-gray-600">Pilih modul ERP yang sesuai: Restoran, Klinik, Bengkel, atau Legal.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">4</div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Konfigurasi Domain</h3>
                        <p class="text-gray-600">Pilih subdomain gratis (bisnisanda.cooca.id) atau gunakan custom domain sendiri.</p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">5</div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Trial Activation</h3>
                        <p class="text-gray-600">Tim admin akan memproses permintaan trial Anda dalam 1-3 hari kerja. Anda akan menerima URL, license, dan token akses.</p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">6</div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Mulai Menggunakan</h3>
                        <p class="text-gray-600">Login ke dashboard ERP dan mulai kelola bisnis Anda dengan fitur lengkap selama 14 hari trial.</p>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="/customer/register" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition">Mulai Trial Gratis</a>
            </div>
        </div>
    </section>

    <!-- Need Help CTA -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4 text-gray-900">Butuh Bantuan Lebih Lanjut?</h2>
            <p class="mb-8 text-gray-600">Tim support kami siap membantu Anda 24/7.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/contact" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition">Hubungi Kami</a>
                <a href="/faq" class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-full border border-blue-600 hover:bg-blue-50 transition">Lihat FAQ</a>
            </div>
        </div>
    </section>
</x-layouts.app>
