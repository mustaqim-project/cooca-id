@extends('layouts.app')

@section('title', 'Daftar Akun Baru')
@section('description', 'Buat akun Cooca.id gratis dan mulai kelola bisnis Anda')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-xl p-8">
    <!-- Header -->
    <div class="text-center">
      <div class="flex justify-center">
        <div class="text-5xl">🏢</div>
      </div>
      <h2 class="mt-4 text-3xl font-bold text-gray-900">
        Buat Akun Baru
      </h2>
      <p class="mt-2 text-sm text-gray-600">
        Sudah punya akun? 
        <a href="{{ route('customer.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
          Login di sini
        </a>
      </p>
    </div>

    <!-- Registration Form -->
    <form class="mt-8 space-y-6" action="{{ route('customer.register') }}" method="POST">
      @csrf
      
      <input type="hidden" name="referral_code" value="{{ request('ref') }}">

      <div class="space-y-4">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
          <input
            id="name"
            name="name"
            type="text"
            required
            value="{{ old('name') }}"
            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
            placeholder="Masukkan nama lengkap"
          />
          @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            id="email"
            name="email"
            type="email"
            required
            value="{{ old('email') }}"
            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror"
            placeholder="nama@email.com"
          />
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Business Name -->
        <div>
          <label for="business_name" class="block text-sm font-medium text-gray-700">Nama Bisnis</label>
          <input
            id="business_name"
            name="business_name"
            type="text"
            required
            value="{{ old('business_name') }}"
            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('business_name') border-red-500 @enderror"
            placeholder="PT / CV / UD Nama Bisnis"
          />
          @error('business_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            id="password"
            name="password"
            type="password"
            required
            minlength="8"
            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-500 @enderror"
            placeholder="Minimal 8 karakter"
          />
          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password Confirmation -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            required
            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Ulangi password"
          />
        </div>
      </div>

      <!-- Terms & Conditions -->
      <div class="flex items-start">
        <input
          id="terms"
          name="terms"
          type="checkbox"
          required
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-1"
        />
        <label for="terms" class="ml-2 block text-sm text-gray-600">
          Saya setuju dengan 
          <a href="#" class="text-indigo-600 hover:text-indigo-500">Syarat & Ketentuan</a>
          dan 
          <a href="#" class="text-indigo-600 hover:text-indigo-500">Kebijakan Privasi</a>
        </label>
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
      >
        Daftar Sekarang
      </button>
    </form>

    <!-- Divider -->
    <div class="relative">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300"></div>
      </div>
      <div class="relative flex justify-center text-sm">
        <span class="px-2 bg-white text-gray-500">Atau daftar dengan</span>
      </div>
    </div>

    <!-- Google OAuth -->
    <a
      href="{{ route('customer.auth.google') }}"
      class="w-full flex justify-center items-center gap-3 py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
    >
      <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
      </svg>
      Google
    </a>

    <!-- Benefits -->
    <div class="mt-6 space-y-3">
      <div class="flex items-center text-sm text-gray-600">
        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        Gratis trial 14 hari
      </div>
      <div class="flex items-center text-sm text-gray-600">
        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        Tidak perlu kartu kredit
      </div>
      <div class="flex items-center text-sm text-gray-600">
        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        Akses ke semua fitur
      </div>
    </div>
  </div>
</div>
@endsection
