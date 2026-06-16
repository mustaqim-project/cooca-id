@extends('layouts.guest')

@section('title', 'Tentang Cooca.id')
@section('description', 'Platform SaaS ERP multi-tenant terpercaya untuk bisnis Indonesia')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-indigo-600 to-purple-700 py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
      Tentang Cooca.id
    </h1>
    <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
      Platform SaaS ERP pertama di Indonesia yang menyediakan berbagai sistem siap pakai 
      dengan model subscription terjangkau.
    </p>
  </div>
</section>

<!-- Story Section -->
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Cerita Kami</h2>
        <p class="text-lg text-gray-600 mb-4">
          Cooca.id didirikan pada tahun 2024 dengan misi sederhana: membuat teknologi ERP 
          yang sebelumnya hanya bisa diakses perusahaan besar, kini dapat dinikmati oleh 
          UMKM dan bisnis menengah di Indonesia.
        </p>
        <p class="text-lg text-gray-600 mb-4">
          Kami percaya bahwa setiap bisnis berhak mendapatkan akses ke sistem manajemen 
          yang profesional, terjangkau, dan mudah digunakan. Dengan model multi-tenant 
          SaaS, kami menghadirkan solusi lengkap tanpa biaya investasi infrastruktur 
          yang mahal.
        </p>
        <p class="text-lg text-gray-600">
          Hingga kini, Cooca.id telah melayani ratusan bisnis di berbagai sektor, mulai 
          dari restoran, klinik, bengkel, hingga firma hukum.
        </p>
      </div>
      <div class="bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl p-8 flex items-center justify-center">
        <div class="text-9xl">🚀</div>
      </div>
    </div>
  </div>
</section>

<!-- Values Section -->
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Nilai-Nilai Kami</h2>
      <p class="text-lg text-gray-600">Prinsip yang menjadi fondasi setiap keputusan kami</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-4xl mb-4">🎯</div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Fokus pada Pelanggan</h3>
        <p class="text-gray-600">Kepuasan pelanggan adalah prioritas utama dalam setiap produk dan layanan kami.</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-4xl mb-4">💡</div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Inovasi Berkelanjutan</h3>
        <p class="text-gray-600">Kami terus berinovasi untuk menghadirkan fitur-fitur terbaru yang relevan.</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-4xl mb-4">🤝</div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Integritas</h3>
        <p class="text-gray-600">Kejujuran dan transparansi dalam setiap interaksi dengan pelanggan dan partner.</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <div class="text-4xl mb-4">⚡</div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Kecepatan & Kualitas</h3>
        <p class="text-gray-600">Solusi cepat tanpa mengorbankan kualitas dan keamanan data.</p>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
      <div>
        <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">500+</div>
        <div class="text-gray-600">Pelanggan Aktif</div>
      </div>
      <div>
        <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">10+</div>
        <div class="text-gray-600">Sistem ERP</div>
      </div>
      <div>
        <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">99.9%</div>
        <div class="text-gray-600">Uptime SLA</div>
      </div>
      <div>
        <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">24/7</div>
        <div class="text-gray-600">Support</div>
      </div>
    </div>
  </div>
</section>

<!-- Team Section -->
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">Tim Kami</h2>
      <p class="text-lg text-gray-600">Dibalik Cooca.id ada tim profesional yang berdedikasi tinggi</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
          <div class="text-6xl">👨‍💼</div>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-900">Founder & CEO</h3>
          <p class="text-indigo-600 mb-4">Visioner Teknologi</p>
          <p class="text-gray-600 text-sm">Berpengalaman lebih dari 15 tahun di industri software enterprise.</p>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
          <div class="text-6xl">👩‍💻</div>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-900">CTO</h3>
          <p class="text-indigo-600 mb-4">Ahli Arsitektur Cloud</p>
          <p class="text-gray-600 text-sm">Spesialis dalam membangun sistem scalable dan secure.</p>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
          <div class="text-6xl">👨‍🎨</div>
        </div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-900">Head of Product</h3>
          <p class="text-indigo-600 mb-4">UX Enthusiast</p>
          <p class="text-gray-600 text-sm">Fokus pada pengalaman pengguna yang intuitif dan menyenangkan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-700">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl font-bold text-white mb-4">
      Siap Bergabung dengan Ribuan Bisnis Lainnya?
    </h2>
    <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
      Mulai gunakan Cooca.id hari juga dan rasakan perbedaan dalam mengelola bisnis Anda.
    </p>
    <div class="flex flex-col sm:flex-row justify-center gap-4">
      <a
        href="{{ route('customer.register') }}"
        class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition-colors duration-200"
      >
        Daftar Gratis
      </a>
      <a
        href="{{ route('landing.pricing') }}"
        class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:bg-opacity-10 transition-colors duration-200"
      >
        Lihat Harga
      </a>
    </div>
  </div>
</section>
@endsection
