@extends('layouts.guest')
@section('content')
    <section class="page-hero">
        <div class="page-hero-orb page-hero-orb-1"></div>
        <div class="page-hero-orb page-hero-orb-2"></div>
        <div class="grid-bg"></div>
        <div class="container text-center position-relative" style="z-index:2;">
            <div class="badge-glow reveal mb-4">
                <i class="bi bi-star-fill"></i> {{ __('Contact Us') }}
            </div>
            <h1 class="hero-title reveal rv-delay-1">{!! __('Let\'s Talk <span class="text-gradient">Business.</span>') !!}</h1>
            <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
                {{ __('Sales questions, technical support, partnership inquiries — our team responds within hours, not days.') }}
            </p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-lg-3 col-md-6 reveal">
                    <div class="channel-card">
                        <div class="channel-icon"><i class="bi bi-whatsapp" style="color:#25D366;"></i></div>
                        <div class="channel-title">WhatsApp</div>
                        <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">
                            {{ __('Fastest response during business hours.') }}</p>
                        <a href="{{ setting('contact.whatsapp_link', 'https://wa.me/6281234567890') }}" target="_blank"
                            rel="noopener" class="btn btn-outline btn-sm btn-block">{{ __('Open WhatsApp') }}</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 reveal rv-delay-1">
                    <div class="channel-card">
                        <div class="channel-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div class="channel-title">Email</div>
                        <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">
                            {{ __('We respond within 8 business hours.') }}</p>
                        <a href="mailto:{{ setting('contact.email', 'support@cooca.io') }}"
                            class="btn btn-outline btn-sm btn-block">{{ setting('contact.email', 'support@cooca.io') }}</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 reveal rv-delay-2">
                    <div class="channel-card">
                        <div class="channel-icon"><i class="bi bi-calendar-check"></i></div>
                        <div class="channel-title">{{ __('Book a Demo') }}</div>
                        <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">
                            {{ __('30-minute live walkthrough of your solution.') }}</p>
                        <a href="{{ route('customer.register') }}"
                            class="btn btn-outline btn-sm btn-block">{{ __('Book Demo') }}</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 reveal rv-delay-3">
                    <div class="channel-card">
                        <div class="channel-icon"><i class="bi bi-headset"></i></div>
                        <div class="channel-title">{{ __('Support') }}</div>
                        <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">
                            {{ __('Knowledge base and ticket system.') }}</p>
                        <a href="{{ route('faq') }}" class="btn btn-outline btn-sm btn-block">{{ __('Help Center') }}</a>
                    </div>
                </div>
            </div>

            <div class="row g-5 align-items-start">
                <div class="col-lg-7 reveal">
                    <div class="card" style="border-radius:var(--radius-lg);padding:40px;">
                        <h3 style="font-size:1.4rem;margin-bottom:8px;">{{ __('Send Us a Message') }}</h3>
                        <p style="font-size:.9rem;margin-bottom:28px;">
                            {{ __('Fill out the form and we\'ll route it to the right person.') }}</p>
                        <form id="contactForm" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group"><label class="form-label">{{ __('Full Name') }} *</label><input
                                            type="text" class="form-control" placeholder="Ahmad Kurniawan" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label class="form-label">{{ __('Email Address') }}
                                            *</label><input type="email" class="form-control"
                                            placeholder="ahmad@company.com" required></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label
                                            class="form-label">{{ __('Phone / WhatsApp') }}</label><input type="tel"
                                            class="form-control" placeholder="+62 812 3456 7890"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label
                                            class="form-label">{{ __('Company Name') }}</label><input type="text"
                                            class="form-control" placeholder="RetailMax Indonesia"></div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group"><label class="form-label">{{ __('Industry') }} *</label><select
                                            class="form-select" required>
                                            <option value="" disabled selected>{{ __('Select your industry') }}
                                            </option>
                                            <option>{{ __('Retail') }}</option>
                                            <option>{{ __('Restaurant & F&B') }}</option>
                                            <option>{{ __('Hotel & Hospitality') }}</option>
                                            <option>{{ __('Clinic & Healthcare') }}</option>
                                            <option>{{ __('Education') }}</option>
                                            <option>{{ __('Other') }}</option>
                                        </select></div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group"><label class="form-label">{{ __('Your Message') }} *</label>
                                        <textarea class="form-control" placeholder="{{ __('Tell us about your business and what you\'re looking for...') }}"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit"
                                        class="btn btn-primary btn-block btn-lg">{{ __('Send Message') }} <i
                                            class="bi bi-send-fill"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5 reveal rv-delay-2">
                    <div class="section-label mb-4"><i class="bi bi-geo-alt-fill"></i> {{ __('Find Us') }}</div>
                    <div class="d-flex flex-column gap-3">
                        <div class="contact-info-item">
                            <div class="ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <div class="ci-title">{{ __('Headquarters') }}</div>
                                <div class="ci-text">{!! __(setting('contact.address', 'Jl. Jend. Sudirman Kav. 52–53<br>Jakarta Selatan 12190, Indonesia')) !!}</div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="ci-icon"><i class="bi bi-envelope-fill"></i></div>
                            <div>
                                <div class="ci-title">{{ __('Email') }}</div>
                                <div class="ci-text">{{ setting('contact.email', 'support@cooca.io') }}<br>sales@cooca.io
                                </div>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="ci-icon"><i class="bi bi-clock"></i></div>
                            <div>
                                <div class="ci-title">{{ __('Business Hours') }}</div>
                                <div class="ci-text">
                                    {{ __('Mon–Fri: 09:00–18:00 WIB') }}<br>{{ __('Sat: 09:00–14:00 WIB') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
