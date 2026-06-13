@extends('layouts.app')

@section('title', 'Contact Us - Cooca.id')
@section('meta_description', 'Hubungi tim Cooca.id untuk pertanyaan, dukungan, atau demo produk.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Get in Touch
        </h1>
        <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
            Punya pertanyaan? Kami siap membantu Anda menemukan solusi ERP yang tepat untuk bisnis Anda.
        </p>
    </div>
</section>

<!-- Contact Info Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Contact Information</h2>
                
                <div class="space-y-6">
                    @forelse($contactInfo ?? [] as $info)
                    <div class="flex items-start space-x-4">
                        <div class="text-3xl">{{ $info['icon'] }}</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $info['label'] }}</h3>
                            <p class="text-gray-600">{{ $info['value'] }}</p>
                        </div>
                    </div>
                    @empty
                    <!-- Default Contact Info -->
                    <div class="flex items-start space-x-4">
                        <div class="text-3xl">📧</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email Support</h3>
                            <p class="text-gray-600">support@cooca.id</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="text-3xl">💼</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email Sales</h3>
                            <p class="text-gray-600">marketing@cooca.id</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="text-3xl">💬</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">WhatsApp</h3>
                            <p class="text-gray-600">+62 812-3456-7890</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="text-3xl">📍</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Office</h3>
                            <p class="text-gray-600">Jakarta, Indonesia</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Business Hours -->
                <div class="mt-10 bg-white rounded-xl shadow p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Business Hours</h3>
                    <div class="space-y-2 text-gray-600">
                        <div class="flex justify-between">
                            <span>Monday - Friday</span>
                            <span class="font-semibold">08:00 - 17:00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Saturday</span>
                            <span class="font-semibold">09:00 - 13:00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sunday</span>
                            <span class="font-semibold text-red-600">Closed</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-4">
                        * Support darurat tersedia 24/7 untuk pelanggan Enterprise
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                
                <form action="{{ route('contact') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="John Doe">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="john@example.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                               placeholder="+62 812-3456-7890">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name
                        </label>
                        <input type="text" 
                               id="company" 
                               name="company" 
                               value="{{ old('company') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('company') border-red-500 @enderror"
                               placeholder="PT. Your Company">
                        @error('company')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject *
                        </label>
                        <select id="subject" 
                                name="subject" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject') border-red-500 @enderror">
                            <option value="">Select a subject</option>
                            <option value="general" {{ old('subject') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="sales" {{ old('subject') === 'sales' ? 'selected' : '' }}>Sales & Pricing</option>
                            <option value="support" {{ old('subject') === 'support' ? 'selected' : '' }}>Technical Support</option>
                            <option value="demo" {{ old('subject') === 'demo' ? 'selected' : '' }}>Request Demo</option>
                            <option value="partnership" {{ old('subject') === 'partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="affiliate" {{ old('subject') === 'affiliate' ? 'selected' : '' }}>Affiliate Program</option>
                        </select>
                        @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message *
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="5" 
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('message') border-red-500 @enderror"
                                  placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Send Message
                    </button>

                    <p class="text-xs text-gray-500 text-center">
                        By submitting this form, you agree to our Privacy Policy and Terms of Service.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section (Placeholder) -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Location</h2>
            <p class="text-lg text-gray-600">Visit our office in Jakarta</p>
        </div>
        
        <div class="bg-gray-200 rounded-2xl h-96 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <div class="text-6xl mb-4">🗺️</div>
                <p class="text-lg">Interactive Map Placeholder</p>
                <p class="text-sm">Jakarta, Indonesia</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Quick Answers</h2>
            <p class="text-lg text-gray-600">Common questions before contacting us</p>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Berapa lama响应时间 untuk email support?</h3>
                <p class="text-gray-600">Kami berkomitmen untuk membalas semua email dalam waktu 24 jam pada hari kerja. Untuk prioritas tinggi, kami biasanya merespons dalam 2-4 jam.</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Apakah bisa request demo produk?</h3>
                <p class="text-gray-600">Tentu! Anda bisa request demo gratis melalui form di atas atau langsung menghubungi tim sales kami. Demo dapat dilakukan online via Zoom/Google Meet atau onsite untuk area Jakarta.</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Apa saja yang perlu disiapkan sebelum implementasi?</h3>
                <p class="text-gray-600">Tim kami akan membantu Anda dalam proses onboarding. Umumnya yang需要准备 adalah data master (produk, customer, supplier), struktur organisasi, dan kebutuhan spesifik bisnis Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Ready to Get Started?
        </h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            Jangan ragu untuk menghubungi kami. Tim kami siap membantu Anda 24/7.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('customer.register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-lg">
                Start Free Trial
            </a>
            <a href="https://wa.me/6281234567890" target="_blank" class="inline-block bg-green-500 text-white px-8 py-4 rounded-lg font-semibold hover:bg-green-600 transition-colors text-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                Chat on WhatsApp
            </a>
        </div>
    </div>
</section>
@endsection
