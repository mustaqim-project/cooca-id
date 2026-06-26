@extends('layouts.admin')

@section('title', 'Settings & CMS')
@section('subtitle', 'Manage your platform settings and landing page content.')

@section('content')
<div x-data="{ activeTab: 'general' }">
    <!-- Tabs Navigation -->
    <div class="border-b border-surface-200 dark:border-surface-700 mb-6 flex overflow-x-auto">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'general'" :class="{'border-primary-500 text-primary-600': activeTab === 'general', 'border-transparent text-surface-500 hover:text-surface-700 hover:border-surface-300': activeTab !== 'general'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" type="button">
                General
            </button>
            <button @click="activeTab = 'landing'" :class="{'border-primary-500 text-primary-600': activeTab === 'landing', 'border-transparent text-surface-500 hover:text-surface-700 hover:border-surface-300': activeTab !== 'landing'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" type="button">
                Landing Page
            </button>
            <button @click="activeTab = 'contact'" :class="{'border-primary-500 text-primary-600': activeTab === 'contact', 'border-transparent text-surface-500 hover:text-surface-700 hover:border-surface-300': activeTab !== 'contact'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" type="button">
                Contact & Footer
            </button>
            <button @click="activeTab = 'social'" :class="{'border-primary-500 text-primary-600': activeTab === 'social', 'border-transparent text-surface-500 hover:text-surface-700 hover:border-surface-300': activeTab !== 'social'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" type="button">
                Social Media
            </button>
            <button @click="activeTab = 'seo'" :class="{'border-primary-500 text-primary-600': activeTab === 'seo', 'border-transparent text-surface-500 hover:text-surface-700 hover:border-surface-300': activeTab !== 'seo'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" type="button">
                SEO Meta
            </button>
            <button @click="activeTab = 'affiliate'" :class="{'border-primary-500 text-primary-600': activeTab === 'affiliate', 'border-transparent text-surface-500 hover:text-surface-700 hover:border-surface-300': activeTab !== 'affiliate'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" type="button">
                Affiliate
            </button>
        </nav>
    </div>

    <form class="form-confirm-submit" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Tab: General -->
        <div x-show="activeTab === 'general'" class="corporate-card overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">General Settings</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="platform_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Platform Name</label>
                        <input type="text" name="platform_name" id="platform_name" value="{{ old('platform_name', $settings['platform_name'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="preloader_text" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Preloader Text</label>
                        <input type="text" name="preloader_text" id="preloader_text" value="{{ old('preloader_text', $settings['preloader_text'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="preloader_image_light" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Preloader Image (Light Theme)</label>
                        @if(!empty($settings['preloader_image_light_url']))
                            <img src="{{ asset($settings['preloader_image_light_url']) }}" alt="Preloader Light" class="h-12 mt-2 mb-2 object-contain bg-surface-100 dark:bg-surface-800 rounded p-1">
                        @endif
                        <input type="file" name="preloader_image_light" id="preloader_image_light" accept="image/*" class="mt-1 block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                    <div>
                        <label for="preloader_image_dark" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Preloader Image (Dark Theme)</label>
                        @if(!empty($settings['preloader_image_dark_url']))
                            <img src="{{ asset($settings['preloader_image_dark_url']) }}" alt="Preloader Dark" class="h-12 mt-2 mb-2 object-contain bg-surface-100 dark:bg-surface-800 rounded p-1">
                        @endif
                        <input type="file" name="preloader_image_dark" id="preloader_image_dark" accept="image/*" class="mt-1 block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                    <div>
                        <label for="logo_light" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Site Logo (Light Theme)</label>
                        @if(!empty($settings['logo_light_url']))
                            <img src="{{ asset($settings['logo_light_url']) }}" alt="Logo Light" class="h-12 mt-2 mb-2 object-contain bg-surface-100 dark:bg-surface-800 rounded p-1">
                        @endif
                        <input type="file" name="logo_light" id="logo_light" accept="image/*" class="mt-1 block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                    <div>
                        <label for="logo_dark" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Site Logo (Dark Theme)</label>
                        @if(!empty($settings['logo_dark_url']))
                            <img src="{{ asset($settings['logo_dark_url']) }}" alt="Logo Dark" class="h-12 mt-2 mb-2 object-contain bg-surface-100 dark:bg-surface-800 rounded p-1">
                        @endif
                        <input type="file" name="logo_dark" id="logo_dark" accept="image/*" class="mt-1 block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                    <div>
                        <label for="favicon" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Site Favicon</label>
                        @if(!empty($settings['favicon_url']))
                            <img src="{{ asset($settings['favicon_url']) }}" alt="Favicon" class="h-8 mt-2 mb-2 object-contain bg-surface-100 dark:bg-surface-800 rounded p-1">
                        @endif
                        <input type="file" name="favicon" id="favicon" accept="image/*" class="mt-1 block w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Landing Page -->
        <div x-show="activeTab === 'landing'" class="corporate-card overflow-hidden mb-6" style="display: none;">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Landing Page Hero Section</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="landing_hero_title" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Hero Title</label>
                    <input type="text" name="landing_hero_title" id="landing_hero_title" value="{{ old('landing_hero_title', $settings['landing_hero_title'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                </div>
                <div>
                    <label for="landing_hero_subtitle" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Hero Subtitle</label>
                    <textarea name="landing_hero_subtitle" id="landing_hero_subtitle" rows="3" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">{{ old('landing_hero_subtitle', $settings['landing_hero_subtitle'] ?? '') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="landing_hero_cta_text" class="block text-sm font-medium text-surface-700 dark:text-surface-300">CTA Button Text</label>
                        <input type="text" name="landing_hero_cta_text" id="landing_hero_cta_text" value="{{ old('landing_hero_cta_text', $settings['landing_hero_cta_text'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="landing_hero_cta_link" class="block text-sm font-medium text-surface-700 dark:text-surface-300">CTA Button Link</label>
                        <input type="text" name="landing_hero_cta_link" id="landing_hero_cta_link" value="{{ old('landing_hero_cta_link', $settings['landing_hero_cta_link'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Contact & Footer -->
        <div x-show="activeTab === 'contact'" class="corporate-card overflow-hidden mb-6" style="display: none;">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Contact & Footer Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email_support" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Support Email</label>
                        <input type="email" name="email_support" id="email_support" value="{{ old('email_support', $settings['email_support'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-surface-700 dark:text-surface-300">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                </div>
                <div>
                    <label for="whatsapp_link" class="block text-sm font-medium text-surface-700 dark:text-surface-300">WhatsApp Link (Floating Button)</label>
                    <input type="text" name="whatsapp_link" id="whatsapp_link" value="{{ old('whatsapp_link', $settings['whatsapp_link'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                </div>
                <div>
                    <label for="footer_description" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Footer Description</label>
                    <textarea name="footer_description" id="footer_description" rows="3" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">{{ old('footer_description', $settings['footer_description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Tab: Social Media -->
        <div x-show="activeTab === 'social'" class="corporate-card overflow-hidden mb-6" style="display: none;">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Social Media Links</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="social_instagram" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Instagram</label>
                        <input type="text" name="social_instagram" id="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="social_twitter" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Twitter (X)</label>
                        <input type="text" name="social_twitter" id="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="social_linkedin" class="block text-sm font-medium text-surface-700 dark:text-surface-300">LinkedIn</label>
                        <input type="text" name="social_linkedin" id="social_linkedin" value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="social_github" class="block text-sm font-medium text-surface-700 dark:text-surface-300">GitHub</label>
                        <input type="text" name="social_github" id="social_github" value="{{ old('social_github', $settings['social_github'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: SEO Meta -->
        <div x-show="activeTab === 'seo'" class="corporate-card overflow-hidden mb-6" style="display: none;">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">SEO Meta Data (Guest Pages)</h3>
            </div>
            <div class="p-6 space-y-8">
                @php
                    $seoPages = [
                        ['id' => 'home', 'name' => 'Home Page'],
                        ['id' => 'about', 'name' => 'About Page'],
                        ['id' => 'pricing', 'name' => 'Pricing Page'],
                        ['id' => 'contact', 'name' => 'Contact Page'],
                        ['id' => 'solutions', 'name' => 'Solutions Page'],
                        ['id' => 'features', 'name' => 'Features Page'],
                        ['id' => 'affiliate', 'name' => 'Affiliate Page'],
                        ['id' => 'faq', 'name' => 'FAQ Page'],
                        ['id' => 'docs', 'name' => 'Docs Page'],
                        ['id' => 'terms', 'name' => 'Terms of Service'],
                        ['id' => 'privacy', 'name' => 'Privacy Policy'],
                    ];
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($seoPages as $page)
                        <div class="p-4 border border-surface-200 dark:border-surface-700 rounded-lg bg-surface-50 dark:bg-surface-800/50">
                            <h4 class="font-medium text-surface-900 dark:text-white mb-3">{{ $page['name'] }}</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs text-surface-500 dark:text-surface-400">Meta Title</label>
                                    <input type="text" name="seo_{{ $page['id'] }}_title" value="{{ old('seo_'.$page['id'].'_title', $settings['seo_'.$page['id'].'_title'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-1.5 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm bg-white dark:bg-surface-800">
                                </div>
                                <div>
                                    <label class="block text-xs text-surface-500 dark:text-surface-400">Meta Description</label>
                                    <textarea name="seo_{{ $page['id'] }}_description" rows="2" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-1.5 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm bg-white dark:bg-surface-800">{{ old('seo_'.$page['id'].'_description', $settings['seo_'.$page['id'].'_description'] ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs text-surface-500 dark:text-surface-400">Meta Keywords</label>
                                    <input type="text" name="seo_{{ $page['id'] }}_keywords" value="{{ old('seo_'.$page['id'].'_keywords', $settings['seo_'.$page['id'].'_keywords'] ?? '') }}" placeholder="comma, separated, keywords" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-1.5 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm bg-white dark:bg-surface-800">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tab: Affiliate -->
        <div x-show="activeTab === 'affiliate'" class="corporate-card overflow-hidden mb-6" style="display: none;">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
                <h3 class="text-lg font-medium text-surface-900 dark:text-white">Affiliate Settings</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="affiliate_commission_l1" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Level 1 Commission (%)</label>
                        <input type="number" step="0.01" name="affiliate_commission_l1" id="affiliate_commission_l1" value="{{ old('affiliate_commission_l1', $settings['affiliate_commission_l1'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                    <div>
                        <label for="affiliate_commission_l2" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Level 2 Commission (%)</label>
                        <input type="number" step="0.01" name="affiliate_commission_l2" id="affiliate_commission_l2" value="{{ old('affiliate_commission_l2', $settings['affiliate_commission_l2'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="minimum_withdrawal" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Minimum Withdrawal (Rp)</label>
                        <input type="number" name="minimum_withdrawal" id="minimum_withdrawal" value="{{ old('minimum_withdrawal', $settings['minimum_withdrawal'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm text-sm">
                Save Settings
            </button>
        </div>
    </form>
</div>

@endsection
