@extends('layouts.admin')

@section('title', 'Landing Page CMS')
@section('subtitle', 'Manage all landing page content dynamically.')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <form action="{{ route('admin.cms.landing.update') }}" method="POST" enctype="multipart/form-data" class="form-confirm-submit">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar Tabs -->
            <div class="col-span-1">
                <div class="corporate-card p-2 flex flex-col space-y-1">
                    <button type="button" class="tab-button active flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-colors bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400" data-target="tab-identity">
                        <i data-lucide="image" class="w-5 h-5 mr-3"></i> Site Identity
                    </button>
                    <button type="button" class="tab-button flex items-center px-4 py-3 rounded-lg text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors" data-target="tab-hero">
                        <i data-lucide="layout-template" class="w-5 h-5 mr-3"></i> Hero Section
                    </button>
                    <button type="button" class="tab-button flex items-center px-4 py-3 rounded-lg text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors" data-target="tab-stats">
                        <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i> Statistics
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="col-span-1 md:col-span-3">
                <div class="corporate-card p-6">
                    <!-- Tab: Identity -->
                    <div id="tab-identity" class="tab-pane block space-y-6">
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-white border-b border-surface-200 dark:border-surface-700 pb-3">Site Identity</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="form-label">Main Logo</label>
                                @if(isset($settings['site.logo']) && $settings['site.logo'])
                                    <div class="p-3 bg-surface-100 dark:bg-surface-800 rounded-md border border-surface-200 dark:border-surface-700">
                                        <img src="{{ asset($settings['site.logo']) }}" alt="Logo" class="h-12 object-contain">
                                    </div>
                                @endif
                                <input type="file" name="site_logo" class="form-input" accept="image/*">
                                <p class="text-xs text-surface-500">Leave blank to keep current. Format: PNG, SVG, WebP.</p>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="form-label">Favicon</label>
                                @if(isset($settings['site.favicon']) && $settings['site.favicon'])
                                    <div class="p-3 bg-surface-100 dark:bg-surface-800 rounded-md border border-surface-200 dark:border-surface-700 inline-block">
                                        <img src="{{ asset($settings['site.favicon']) }}" alt="Favicon" class="h-8 object-contain">
                                    </div>
                                @endif
                                <input type="file" name="site_favicon" class="form-input" accept=".ico,.png,.svg">
                                <p class="text-xs text-surface-500">Leave blank to keep current. Format: ICO, PNG, SVG.</p>
                            </div>
                        </div>

                        <div class="space-y-2 pt-4">
                            <label class="form-label">Preloader Text</label>
                            <input type="text" name="site_preloader_text" value="{{ old('site_preloader_text', $settings['site.preloader_text'] ?? '') }}" class="form-input" placeholder="e.g. COOCA">
                        </div>
                    </div>

                    <!-- Tab: Hero Section -->
                    <div id="tab-hero" class="tab-pane hidden space-y-6">
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-white border-b border-surface-200 dark:border-surface-700 pb-3">Hero Section</h3>
                        
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="form-label">Hero Badge Text</label>
                                <input type="text" name="home_hero_badge" value="{{ old('home_hero_badge', $settings['home.hero.badge'] ?? '') }}" class="form-input">
                            </div>

                            <div class="space-y-2">
                                <label class="form-label">Hero Title (Allows HTML)</label>
                                <textarea name="home_hero_title" rows="3" class="form-input font-mono text-sm">{{ old('home_hero_title', $settings['home.hero.title'] ?? '') }}</textarea>
                                <p class="text-xs text-surface-500">You can use &lt;br&gt; for line breaks and &lt;span class="text-gradient"&gt; for gradient text.</p>
                            </div>

                            <div class="space-y-2">
                                <label class="form-label">Hero Subtitle (Allows HTML)</label>
                                <textarea name="home_hero_subtitle" rows="3" class="form-input font-mono text-sm">{{ old('home_hero_subtitle', $settings['home.hero.subtitle'] ?? '') }}</textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="form-label">Hero Description (Allows HTML)</label>
                                <textarea name="home_hero_description" rows="3" class="form-input font-mono text-sm">{{ old('home_hero_description', $settings['home.hero.description'] ?? '') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="form-label">Primary Button Text</label>
                                    <input type="text" name="home_hero_btn_primary" value="{{ old('home_hero_btn_primary', $settings['home.hero.btn_primary'] ?? '') }}" class="form-input">
                                </div>
                                <div class="space-y-2">
                                    <label class="form-label">Outline Button Text</label>
                                    <input type="text" name="home_hero_btn_outline" value="{{ old('home_hero_btn_outline', $settings['home.hero.btn_outline'] ?? '') }}" class="form-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Statistics -->
                    <div id="tab-stats" class="tab-pane hidden space-y-6">
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-white border-b border-surface-200 dark:border-surface-700 pb-3">Statistics Section</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4 p-4 border border-surface-200 dark:border-surface-700 rounded-lg">
                                <h4 class="font-medium text-surface-800 dark:text-surface-200">Statistic 1</h4>
                                <div class="space-y-2">
                                    <label class="form-label">Value</label>
                                    <input type="text" name="home_stats_1_value" value="{{ old('home_stats_1_value', $settings['home.stats.1.value'] ?? '') }}" class="form-input" placeholder="e.g. 10,000+">
                                </div>
                                <div class="space-y-2">
                                    <label class="form-label">Label</label>
                                    <input type="text" name="home_stats_1_label" value="{{ old('home_stats_1_label', $settings['home.stats.1.label'] ?? '') }}" class="form-input" placeholder="e.g. Businesses">
                                </div>
                            </div>

                            <div class="space-y-4 p-4 border border-surface-200 dark:border-surface-700 rounded-lg">
                                <h4 class="font-medium text-surface-800 dark:text-surface-200">Statistic 2</h4>
                                <div class="space-y-2">
                                    <label class="form-label">Value</label>
                                    <input type="text" name="home_stats_2_value" value="{{ old('home_stats_2_value', $settings['home.stats.2.value'] ?? '') }}" class="form-input" placeholder="e.g. 99.9%">
                                </div>
                                <div class="space-y-2">
                                    <label class="form-label">Label</label>
                                    <input type="text" name="home_stats_2_label" value="{{ old('home_stats_2_label', $settings['home.stats.2.label'] ?? '') }}" class="form-input" placeholder="e.g. Uptime">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save CMS Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-button');
    const panes = document.querySelectorAll('.tab-pane');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active state from all tabs
            tabs.forEach(t => {
                t.classList.remove('active', 'bg-primary-50', 'text-primary-700', 'dark:bg-primary-900/30', 'dark:text-primary-400');
                t.classList.add('text-surface-600', 'dark:text-surface-400', 'hover:bg-surface-50', 'dark:hover:bg-surface-800');
            });
            
            // Add active state to clicked tab
            tab.classList.add('active', 'bg-primary-50', 'text-primary-700', 'dark:bg-primary-900/30', 'dark:text-primary-400');
            tab.classList.remove('text-surface-600', 'dark:text-surface-400', 'hover:bg-surface-50', 'dark:hover:bg-surface-800');

            // Hide all panes
            panes.forEach(p => {
                p.classList.remove('block');
                p.classList.add('hidden');
            });

            // Show selected pane
            const targetId = tab.getAttribute('data-target');
            document.getElementById(targetId).classList.remove('hidden');
            document.getElementById(targetId).classList.add('block');
        });
    });
});
</script>
@endpush
