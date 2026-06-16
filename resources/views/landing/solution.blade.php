@extends('layouts.guest')

@section('title', 'Solutions')
@section('subtitle', 'Industry-Specific ERP Systems')
@section('meta_description', 'Explore COOCA\'s purpose-built systems for Retail, Restaurant, Hotel, Clinic, Education, and more.')

@section('content')
{{-- Hero Section --}}
<section class="section-padding" style="position:relative;overflow:hidden;background:var(--hero-bg);">
    <div style="position:absolute;border-radius:50%;filter:blur(80px);opacity:var(--hero-orb-opacity);pointer-events:none;width:400px;height:400px;background:var(--primary);top:-100px;right:-80px;"></div>
    <div style="position:absolute;border-radius:50%;filter:blur(60px);opacity:var(--hero-orb-opacity);pointer-events:none;width:250px;height:250px;background:var(--accent);bottom:-60px;left:-40px;"></div>
    <div style="position:absolute;inset:0;background-image:linear-gradient(var(--hero-grid-color) 1px,transparent 1px),linear-gradient(90deg,var(--hero-grid-color) 1px,transparent 1px);background-size:60px 60px;pointer-events:none;"></div>
    
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge-glow mb-3"><i class="bi bi-layers-fill"></i> Solutions</span>
                <h1 class="mb-3">Purpose-Built Systems for <span class="text-gradient">Every Industry</span></h1>
                <p class="lead mb-4" style="color:var(--text-muted);">One platform, multiple solutions. Each ERP is tailored to your industry's unique workflows, compliance needs, and growth goals.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">
                        <i class="bi bi-rocket-takeoff"></i> Start Free Trial
                    </a>
                    <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-outline">
                        <i class="bi bi-tag"></i> View Pricing
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass p-4 reveal" style="animation:fade-in-scale 0.8s ease-out;">
                    <img src="https://placehold.co/600x400/0F172A/38BDF8?text=Industry+Solutions" alt="Solutions Dashboard" class="img-fluid rounded" style="border:1px solid var(--border);">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Solutions Grid --}}
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label"><i class="bi bi-grid-3x3-gap-fill"></i> Industries</span>
            <h2 class="section-title">Choose Your <span class="text-gradient">Industry</span></h2>
            <p class="section-subtitle">Each solution comes with industry-specific features, reports, and automation out of the box.</p>
        </div>

        <div class="row g-4">
            @php
                $solutions = [
                    ['icon' => 'bi-cart4', 'title' => 'Retail & POS', 'desc' => 'Inventory, multi-outlet, loyalty programs', 'slug' => 'retail'],
                    ['icon' => 'bi-cup-hot', 'title' => 'Restaurant & F&B', 'desc' => 'Table management, KDS, recipe costing', 'slug' => 'restoran'],
                    ['icon' => 'bi-hotel', 'title' => 'Hotel & Hospitality', 'desc' => 'Booking, housekeeping, guest portal', 'slug' => 'hotel'],
                    ['icon' => 'bi-heart-pulse', 'title' => 'Clinic & Healthcare', 'desc' => 'EMR, pharmacy, insurance billing', 'slug' => 'klinik'],
                    ['icon' => 'bi-mortarboard', 'title' => 'Education', 'desc' => 'Student info, LMS, fee management', 'slug' => 'education'],
                    ['icon' => 'bi-tools', 'title' => 'Automotive & Workshop', 'desc' => 'Job cards, parts inventory, service tracking', 'slug' => 'bengkel'],
                    ['icon' => 'bi-briefcase', 'title' => 'Legal & Law Firm', 'desc' => 'Case management, time tracking, client portal', 'slug' => 'legal'],
                    ['icon' => 'bi-building', 'title' => 'Professional Services', 'desc' => 'Project billing, resource planning, CRM', 'slug' => 'services'],
                    ['icon' => 'bi-factory', 'title' => 'Manufacturing', 'desc' => 'BOM, production planning, QC', 'slug' => 'manufacturing'],
                    ['icon' => 'bi-truck', 'title' => 'Logistics & Distribution', 'desc' => 'Fleet tracking, warehouse, route optimization', 'slug' => 'logistics'],
                    ['icon' => 'bi-person-workspace', 'title' => 'Co-working Space', 'desc' => 'Desk booking, member management, access control', 'slug' => 'coworking'],
                    ['icon' => 'bi-droplet-half', 'title' => 'Laundry & Dry Cleaning', 'desc' => 'Order tracking, garment tagging, delivery', 'slug' => 'laundry'],
                ];
            @endphp

            @foreach($solutions as $index => $solution)
            <div class="col-md-6 col-lg-4">
                <div class="card-3d h-100 reveal" style="transition-delay:{{ $index * 0.05 }}s;">
                    <div class="card-glow"></div>
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="trust-icon" style="width:48px;height:48px;font-size:1.4rem;">
                            <i class="bi {{ $solution['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 style="font-size:1.1rem;margin-bottom:4px;">{{ $solution['title'] }}</h3>
                            <p style="font-size:0.85rem;margin:0;color:var(--text-muted);">{{ $solution['desc'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}#{{ $solution['slug'] }}" class="btn-cooca btn-cooca-outline btn-cooca-sm mt-3" style="width:100%;justify-content:center;">
                        Learn More <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Common Features --}}
<section class="section-padding" style="background:var(--card);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label"><i class="bi bi-star-fill"></i> Unified Platform</span>
            <h2 class="section-title">One System, <span class="text-gradient">Endless Possibilities</span></h2>
            <p class="section-subtitle">All solutions share the same core infrastructure with industry-specific modules layered on top.</p>
        </div>

        <div class="row g-4">
            @php
                $features = [
                    ['icon' => 'bi-shield-check', 'title' => 'Isolated System', 'desc' => 'Your data never mixes with other clients'],
                    ['icon' => 'bi-cloud-check', 'title' => 'Cloud-Native', 'desc' => 'Access from anywhere, anytime'],
                    ['icon' => 'bi-phone', 'title' => 'Mobile Ready', 'desc' => 'iOS and Android apps included'],
                    ['icon' => 'bi-graph-up', 'title' => 'Real-time Analytics', 'desc' => 'Dashboards and reports that matter'],
                    ['icon' => 'bi-chat-dots', 'title' => 'WhatsApp Integration', 'desc' => 'Automated notifications and reminders'],
                    ['icon' => 'bi-credit-card', 'title' => 'Payment Gateway', 'desc' => 'Accept payments seamlessly'],
                ];
            @endphp

            @foreach($features as $index => $feature)
            <div class="col-md-6 col-lg-4">
                <div class="d-flex gap-3 reveal" style="transition-delay:{{ $index * 0.1 }}s;">
                    <div class="trust-icon" style="flex-shrink:0;">
                        <i class="bi {{ $feature['icon'] }}"></i>
                    </div>
                    <div>
                        <h4 style="font-size:1rem;margin-bottom:6px;">{{ $feature['title'] }}</h4>
                        <p style="font-size:0.85rem;margin:0;color:var(--text-muted);">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="section-padding">
    <div class="container">
        <div class="glass p-5 text-center reveal" style="background:linear-gradient(135deg,rgba(37,99,235,0.1),rgba(56,189,248,0.05));border:1px solid var(--border);">
            <h2 class="mb-3">Ready to Transform Your Business?</h2>
            <p class="mb-4" style="max-width:600px;margin:0 auto 24px;color:var(--text-muted);">Start your 7-day free trial. No credit card required. Full access to all features.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">
                    <i class="bi bi-rocket-takeoff"></i> Start Free Trial
                </a>
                <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline">
                    <i class="bi bi-calendar-event"></i> Schedule Demo
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reveal animations
    const reveals = document.querySelectorAll('.reveal');
    const revealOnScroll = () => {
        reveals.forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight - 100) {
                el.classList.add('revealed');
            }
        });
    };
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
});
</script>
@endpush
