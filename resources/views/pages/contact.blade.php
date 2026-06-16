@extends('layouts.guest')

@section('title', 'Contact - Talk to the COOCA Team')
@section('meta_description', 'Get in touch with the COOCA team. Sales, support, partnerships — we respond fast because business can\'t wait.')

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="hero-bg-orb hero-bg-orb-1"></div>
    <div class="hero-bg-orb hero-bg-orb-2"></div>
    <div class="hero-bg-orb hero-bg-orb-3"></div>
    <div class="grid-bg"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" style="position:relative;z-index:2;">
                <div class="badge-glow reveal mb-4">
                    <i class="bi bi-chat-dots-fill"></i> {{ setting('contact.badge', 'Get In Touch') }}
                </div>
                <h1 class="reveal reveal-delay-1" style="font-size:clamp(2.4rem,5vw,4rem);">
                    {!! setting('contact.hero_title', 'Talk to the <span class="text-gradient">COOCA Team</span>') !!}
                </h1>
                <p class="reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
                    {{ setting('contact.hero_description', 'Sales, support, partnerships — we respond fast because we know business can\'t wait. Choose your preferred channel and we\'ll connect you immediately.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Channels Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-send-fill"></i> {{ setting('contact.channels_title', 'Choose Your Channel') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! setting('contact.channels_subtitle', 'Fast Response. <span class="text-gradient">Real Humans.</span>') !!}</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($channels as $index => $channel)
            <div class="col-lg-3 col-md-6 reveal {{ $index > 0 ? 'rv' . ($index % 4) : '' }}">
                <div class="channel-card">
                    <div class="channel-icon"><i class="bi {{ $channel['icon'] }}"></i></div>
                    <div class="channel-title">{{ $channel['title'] }}</div>
                    <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ $channel['description'] }}</p>
                    <a href="{{ $channel['link'] }}" class="btn-cooca btn-outline-c btn-sm-c" style="justify-content:center;width:100%;">
                        {{ $channel['button_text'] }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container">
        <div class="row g-5 align-items-start">
            <!-- Contact Form -->
            <div class="col-lg-7 reveal">
                <div style="background:var(--card);border:1px solid var(--border);border-radius:24px;padding:40px;transition:background var(--transition);">
                    <h3 style="font-size:1.4rem;margin-bottom:8px;">{{ setting('contact.form_title', 'Send Us a Message') }}</h3>
                    <p style="font-size:.9rem;margin-bottom:28px;">{{ setting('contact.form_description', 'Fill out the form and we\'ll route it to the right person immediately.') }}</p>
                    
                    <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ setting('contact.label_name', 'Full Name') }} *</label>
                                    <input type="text" name="name" class="form-control-c" placeholder="{{ setting('contact.placeholder_name', 'Ahmad Kurniawan') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ setting('contact.label_email', 'Email Address') }} *</label>
                                    <input type="email" name="email" class="form-control-c" placeholder="{{ setting('contact.placeholder_email', 'ahmad@company.com') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ setting('contact.label_phone', 'Phone / WhatsApp') }}</label>
                                    <input type="tel" name="phone" class="form-control-c" placeholder="{{ setting('contact.placeholder_phone', '+62 812 3456 7890') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ setting('contact.label_company', 'Company Name') }}</label>
                                    <input type="text" name="company" class="form-control-c" placeholder="{{ setting('contact.placeholder_company', 'RetailMax Indonesia') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label-c">{{ setting('contact.label_industry', 'Industry') }} *</label>
                                    <select name="industry" class="form-control-c form-select-c" required>
                                        <option value="" disabled selected>{{ setting('contact.select_industry', 'Select your industry') }}</option>
                                        @foreach(setting('contact.industries', ['Retail', 'Restaurant & F&B', 'Hotel & Hospitality', 'Clinic & Healthcare', 'Education', 'Salon & Beauty', 'Laundry', 'Workshop & Automotive', 'Rental', 'Other']) as $industry)
                                        <option>{{ $industry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label-c">{{ setting('contact.label_topic', 'How can we help?') }} *</label>
                                    <select name="topic" class="form-control-c form-select-c" required>
                                        <option value="" disabled selected>{{ setting('contact.select_topic', 'Select topic') }}</option>
                                        @foreach(setting('contact.topics', ['Sales & Pricing', 'Book a Demo', 'Technical Support', 'Migration Assistance', 'Partnership / Affiliate', 'Enterprise / Custom', 'Other']) as $topic)
                                        <option>{{ $topic }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label-c">{{ setting('contact.label_message', 'Your Message') }} *</label>
                                    <textarea name="message" rows="5" class="form-control-c" placeholder="{{ setting('contact.placeholder_message', 'Tell us about your business, current challenges, and what you\'re looking for...') }}" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-cooca btn-primary-c" style="width:100%;justify-content:center;padding:16px;">
                                    {{ setting('contact.button_submit', 'Send Message') }} <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Success Message (shown after submission) -->
                    <div class="form-success" id="formSuccess" style="display:none;text-align:center;padding:40px;">
                        <i class="bi bi-check-circle-fill" style="font-size:2.5rem;color:#10B981;margin-bottom:12px;display:block;"></i>
                        <h5 style="font-weight:700;margin-bottom:6px;color:var(--text);">{{ setting('contact.success_title', 'Message Sent!') }}</h5>
                        <p style="margin:0;font-size:.9rem;">{{ setting('contact.success_description', 'We\'ve received your inquiry and will respond within 8 business hours.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5 reveal rv2">
                <div class="section-label mb-4"><i class="bi bi-geo-alt-fill"></i> {{ setting('contact.info_title', 'Find Us') }}</div>
                
                <div class="d-flex flex-column gap-3 mb-5">
                    @foreach($contactInfo as $info)
                    <div class="ci-card">
                        <div class="ci-icon"><i class="bi {{ $info['icon'] }}"></i></div>
                        <div>
                            <div class="ci-title">{{ $info['title'] }}</div>
                            <div class="ci-detail">{!! nl2br(e($info['detail'])) !!}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="section-label mb-3"><i class="bi bi-share-fill"></i> {{ setting('contact.social_title', 'Follow Us') }}</div>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($socialLinks as $social)
                    <a href="{{ $social['url'] }}" class="btn-cooca btn-outline-c btn-sm-c">
                        <i class="bi {{ $social['icon'] }}"></i> {{ $social['name'] }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const success = document.getElementById('formSuccess');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#EF4444';
                    field.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.15)';
                    setTimeout(function() {
                        field.style.borderColor = '';
                        field.style.boxShadow = '';
                    }, 2000);
                }
            });
            
            if (!valid) return;

            const btn = form.querySelector('button[type="submit"]');
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:16px;height:16px;border-width:2px;"></span> Sending...';

            // Simulate form submission (replace with actual AJAX call)
            setTimeout(function() {
                form.style.display = 'none';
                if (success) success.style.display = 'block';
            }, 1500);
        });

        form.querySelectorAll('input, select, textarea').forEach(function(field) {
            field.addEventListener('input', function() {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            });
        });
    }
});
</script>
@endpush
