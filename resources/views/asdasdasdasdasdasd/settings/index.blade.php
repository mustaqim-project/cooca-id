@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
    <div class="page-toolbar">
        <div class="page-toolbar-left">
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle">Platform configuration and preferences</p>
        </div>
    </div>

    @include('components.swal-alert')

    <div class="card-saas">
        <div class="card-saas-header" style="border-bottom:none;padding-bottom:0;">
            <nav id="settingsTabs" class="d-flex gap-1 flex-wrap">
                <button class="tab-btn active" data-tab="general"><i class="bi bi-gear me-1"></i>General</button>
                <button class="tab-btn" data-tab="landing"><i class="bi bi-layout-text-window me-1"></i>Landing
                    Page</button>
                <button class="tab-btn" data-tab="contact"><i class="bi bi-envelope me-1"></i>Contact & Footer</button>
                <button class="tab-btn" data-tab="social"><i class="bi bi-share me-1"></i>Social Media</button>
                <button class="tab-btn" data-tab="seo"><i class="bi bi-search me-1"></i>SEO Meta</button>
                <button class="tab-btn" data-tab="affiliate"><i class="bi bi-people me-1"></i>Affiliate</button>
            </nav>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
            class="form-confirm-submit">
            @csrf
            @method('PUT')

            {{-- GENERAL --}}
            <div class="tab-panel" id="tab-general">
                <div class="card-saas-body">
                    <h6 class="fw-semibold mb-4" style="color:var(--primary);">Platform Identity</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Platform Name</label>
                                <input type="text" name="platform_name"
                                    class="form-saas-input @error('platform_name') is-invalid @enderror"
                                    value="{{ old('platform_name', $settings['platform_name'] ?? '') }}">
                                @error('platform_name')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Preloader Text</label>
                                <input type="text" name="preloader_text"
                                    class="form-saas-input @error('preloader_text') is-invalid @enderror"
                                    value="{{ old('preloader_text', $settings['preloader_text'] ?? '') }}">
                                @error('preloader_text')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Logo Light</label>
                                @if (!empty($settings['logo_light']))
                                    <div class="mb-2 p-2 rounded" style="background:#1a1a2e;">
                                        <img src="{{ asset('storage/' . $settings['logo_light']) }}" alt="Logo Light"
                                            style="height:40px;">
                                    </div>
                                @endif
                                <input type="file" name="logo_light"
                                    class="form-saas-input @error('logo_light') is-invalid @enderror" accept="image/*">
                                @error('logo_light')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                                <div class="form-saas-hint">For dark backgrounds. PNG recommended.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Logo Dark</label>
                                @if (!empty($settings['logo_dark']))
                                    <div class="mb-2 p-2 rounded"
                                        style="background:#f8f9fa;border:1px solid var(--border);">
                                        <img src="{{ asset('storage/' . $settings['logo_dark']) }}" alt="Logo Dark"
                                            style="height:40px;">
                                    </div>
                                @endif
                                <input type="file" name="logo_dark"
                                    class="form-saas-input @error('logo_dark') is-invalid @enderror" accept="image/*">
                                @error('logo_dark')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                                <div class="form-saas-hint">For light backgrounds. PNG recommended.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Favicon</label>
                                @if (!empty($settings['favicon']))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon"
                                            style="height:32px;width:32px;object-fit:contain;">
                                    </div>
                                @endif
                                <input type="file" name="favicon"
                                    class="form-saas-input @error('favicon') is-invalid @enderror" accept="image/*">
                                @error('favicon')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                                <div class="form-saas-hint">32×32 or 64×64 ICO/PNG.</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr style="border-color:var(--border);">
                            <h6 class="fw-semibold mb-3" style="color:var(--primary);">Maintenance</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label d-flex align-items-center gap-3">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="maintenance_mode"
                                            value="1"
                                            {{ old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                                    </div>
                                    Maintenance Mode
                                </label>
                                <div class="form-saas-hint">Visitors see maintenance page when enabled.</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Maintenance Message</label>
                                <textarea name="maintenance_message" class="form-saas-textarea @error('maintenance_message') is-invalid @enderror"
                                    rows="3">{{ old('maintenance_message', $settings['maintenance_message'] ?? '') }}</textarea>
                                @error('maintenance_message')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LANDING PAGE --}}
            <div class="tab-panel d-none" id="tab-landing">
                <div class="card-saas-body">
                    <h6 class="fw-semibold mb-4" style="color:var(--primary);">Hero Section</h6>
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Hero Title</label>
                                <input type="text" name="hero_title"
                                    class="form-saas-input @error('hero_title') is-invalid @enderror"
                                    value="{{ old('hero_title', $settings['hero_title'] ?? '') }}">
                                @error('hero_title')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Hero Subtitle</label>
                                <textarea name="hero_subtitle" class="form-saas-textarea @error('hero_subtitle') is-invalid @enderror"
                                    rows="2">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}</textarea>
                                @error('hero_subtitle')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">CTA Button Text</label>
                                <input type="text" name="hero_cta_text"
                                    class="form-saas-input @error('hero_cta_text') is-invalid @enderror"
                                    value="{{ old('hero_cta_text', $settings['hero_cta_text'] ?? '') }}">
                                @error('hero_cta_text')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">CTA Button URL</label>
                                <input type="url" name="hero_cta_url"
                                    class="form-saas-input @error('hero_cta_url') is-invalid @enderror"
                                    value="{{ old('hero_cta_url', $settings['hero_cta_url'] ?? '') }}">
                                @error('hero_cta_url')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <hr style="border-color:var(--border);">
                            <h6 class="fw-semibold mb-3" style="color:var(--primary);">Features Section</h6>
                        </div>
                        @for ($i = 1; $i <= 3; $i++)
                            <div class="col-md-4">
                                <div class="card-saas" style="border:1px solid var(--border);">
                                    <div class="card-saas-body">
                                        <p class="fw-semibold mb-3">Feature {{ $i }}</p>
                                        <div class="form-saas-group">
                                            <label class="form-saas-label">Icon Class</label>
                                            <input type="text" name="feature_{{ $i }}_icon"
                                                class="form-saas-input"
                                                value="{{ old('feature_' . $i . '_icon', $settings['feature_' . $i . '_icon'] ?? '') }}"
                                                placeholder="bi bi-star">
                                        </div>
                                        <div class="form-saas-group">
                                            <label class="form-saas-label">Title</label>
                                            <input type="text" name="feature_{{ $i }}_title"
                                                class="form-saas-input"
                                                value="{{ old('feature_' . $i . '_title', $settings['feature_' . $i . '_title'] ?? '') }}">
                                        </div>
                                        <div class="form-saas-group mb-0">
                                            <label class="form-saas-label">Description</label>
                                            <textarea name="feature_{{ $i }}_description" class="form-saas-textarea" rows="2">{{ old('feature_' . $i . '_description', $settings['feature_' . $i . '_description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- CONTACT & FOOTER --}}
            <div class="tab-panel d-none" id="tab-contact">
                <div class="card-saas-body">
                    <h6 class="fw-semibold mb-4" style="color:var(--primary);">Contact Information</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Contact Email</label>
                                <input type="email" name="contact_email"
                                    class="form-saas-input @error('contact_email') is-invalid @enderror"
                                    value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
                                @error('contact_email')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Contact Phone</label>
                                <input type="text" name="contact_phone"
                                    class="form-saas-input @error('contact_phone') is-invalid @enderror"
                                    value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                                @error('contact_phone')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Contact Address</label>
                                <textarea name="contact_address" class="form-saas-textarea @error('contact_address') is-invalid @enderror"
                                    rows="3">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                                @error('contact_address')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <hr style="border-color:var(--border);">
                            <h6 class="fw-semibold mb-3" style="color:var(--primary);">Footer</h6>
                        </div>
                        <div class="col-12">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Footer Text</label>
                                <textarea name="footer_text" class="form-saas-textarea @error('footer_text') is-invalid @enderror" rows="3">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
                                @error('footer_text')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Copyright Text</label>
                                <input type="text" name="copyright_text"
                                    class="form-saas-input @error('copyright_text') is-invalid @enderror"
                                    value="{{ old('copyright_text', $settings['copyright_text'] ?? '') }}">
                                @error('copyright_text')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SOCIAL MEDIA --}}
            <div class="tab-panel d-none" id="tab-social">
                <div class="card-saas-body">
                    <h6 class="fw-semibold mb-4" style="color:var(--primary);">Social Media Links</h6>
                    <div class="row g-4">
                        @php
                            $socials = [
                                'social_facebook' => ['Facebook', 'bi bi-facebook', 'https://facebook.com/yourpage'],
                                'social_twitter' => ['Twitter/X', 'bi bi-twitter-x', 'https://x.com/yourhandle'],
                                'social_instagram' => [
                                    'Instagram',
                                    'bi bi-instagram',
                                    'https://instagram.com/yourpage',
                                ],
                                'social_linkedin' => [
                                    'LinkedIn',
                                    'bi bi-linkedin',
                                    'https://linkedin.com/company/yourco',
                                ],
                                'social_youtube' => ['YouTube', 'bi bi-youtube', 'https://youtube.com/@yourchannel'],
                            ];
                        @endphp
                        @foreach ($socials as $key => [$label, $icon, $placeholder])
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label">
                                        <i class="{{ $icon }} me-1"></i>{{ $label }}
                                    </label>
                                    <input type="url" name="{{ $key }}"
                                        class="form-saas-input @error($key) is-invalid @enderror"
                                        value="{{ old($key, $settings[$key] ?? '') }}"
                                        placeholder="{{ $placeholder }}">
                                    @error($key)
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- SEO META --}}
            <div class="tab-panel d-none" id="tab-seo">
                <div class="card-saas-body">
                    <h6 class="fw-semibold mb-1" style="color:var(--primary);">SEO Meta Tags</h6>
                    <p class="text-muted mb-4" style="font-size:.85rem;">Configure title, description, and keywords per
                        page.</p>
                    <div class="accordion" id="seoAccordion">
                        @foreach ($seoPages as $index => $page)
                            <div class="accordion-item"
                                style="border:1px solid var(--border);border-radius:var(--radius-md);margin-bottom:.5rem;overflow:hidden;">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#seo-{{ $page }}"
                                        style="font-weight:600;font-size:.9rem;background:var(--surface-raised);">
                                        <i class="bi bi-file-text me-2" style="color:var(--primary);"></i>
                                        {{ ucfirst(str_replace('-', ' ', $page)) }}
                                    </button>
                                </h2>
                                <div id="seo-{{ $page }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#seoAccordion">
                                    <div class="accordion-body" style="background:var(--surface-base);">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-saas-group">
                                                    <label class="form-saas-label">SEO Title</label>
                                                    <input type="text" name="seo_title_{{ $page }}"
                                                        class="form-saas-input"
                                                        value="{{ old('seo_title_' . $page, $settings['seo_title_' . $page] ?? '') }}"
                                                        placeholder="Page title for search engines">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-saas-group">
                                                    <label class="form-saas-label">Meta Description</label>
                                                    <textarea name="seo_description_{{ $page }}" class="form-saas-textarea" rows="2"
                                                        placeholder="150-160 characters recommended">{{ old('seo_description_' . $page, $settings['seo_description_' . $page] ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-saas-group mb-0">
                                                    <label class="form-saas-label">Keywords</label>
                                                    <input type="text" name="seo_keywords_{{ $page }}"
                                                        class="form-saas-input"
                                                        value="{{ old('seo_keywords_' . $page, $settings['seo_keywords_' . $page] ?? '') }}"
                                                        placeholder="keyword1, keyword2, keyword3">
                                                    <div class="form-saas-hint">Comma-separated.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- AFFILIATE --}}
            <div class="tab-panel d-none" id="tab-affiliate">
                <div class="card-saas-body">
                    <h6 class="fw-semibold mb-4" style="color:var(--primary);">Affiliate Program Settings</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Commission Percent (%)</label>
                                <input type="number" name="affiliate_commission_percent"
                                    class="form-saas-input @error('affiliate_commission_percent') is-invalid @enderror"
                                    value="{{ old('affiliate_commission_percent', $settings['affiliate_commission_percent'] ?? '') }}"
                                    min="0" max="100" step="0.01">
                                @error('affiliate_commission_percent')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                                <div class="form-saas-hint">Percentage of sale paid to affiliator.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Cookie Duration (days)</label>
                                <input type="number" name="affiliate_cookie_days"
                                    class="form-saas-input @error('affiliate_cookie_days') is-invalid @enderror"
                                    value="{{ old('affiliate_cookie_days', $settings['affiliate_cookie_days'] ?? '') }}"
                                    min="1">
                                @error('affiliate_cookie_days')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                                <div class="form-saas-hint">Referral tracking cookie lifetime.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Minimum Withdrawal (Rp)</label>
                                <input type="number" name="affiliate_min_withdrawal"
                                    class="form-saas-input @error('affiliate_min_withdrawal') is-invalid @enderror"
                                    value="{{ old('affiliate_min_withdrawal', $settings['affiliate_min_withdrawal'] ?? '') }}"
                                    min="0">
                                @error('affiliate_min_withdrawal')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-saas-group">
                                <label class="form-saas-label">Payment Method Options</label>
                                <textarea name="affiliate_payment_method_options"
                                    class="form-saas-textarea @error('affiliate_payment_method_options') is-invalid @enderror" rows="4"
                                    placeholder="One option per line, e.g.:&#10;BCA&#10;Mandiri&#10;GoPay">{{ old('affiliate_payment_method_options', $settings['affiliate_payment_method_options'] ?? '') }}</textarea>
                                @error('affiliate_payment_method_options')
                                    <div class="form-saas-error">{{ $message }}</div>
                                @enderror
                                <div class="form-saas-hint">One payment method per line.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-saas-footer d-flex justify-content-end gap-2">
                <button type="submit" class="btn-saas btn-saas-primary">
                    <i class="bi bi-check2 me-1"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('#settingsTabs .tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#settingsTabs .tab-btn').forEach(b => b.classList.remove(
                    'active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('d-none'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.remove('d-none');
            });
        });
    </script>
@endpush
