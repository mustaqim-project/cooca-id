@extends('layouts.guest')

@section('title', 'About COOCA - The Story Behind the System')
@section('meta_description', 'Learn about COOCA\'s mission to give every business true ownership over their digital infrastructure.')

@section('content')
<!-- Page Hero Section -->
<section class="page-hero">
    <div class="page-hero-orb" style="width:600px;height:600px;background:var(--primary);top:-200px;right:-100px;"></div>
    <div class="page-hero-orb" style="width:400px;height:400px;background:var(--accent);bottom:-100px;left:-100px;"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-pill reveal mb-4"><i class="bi bi-building-fill"></i> Our Story</div>
        <h1 style="font-size:clamp(2.4rem,5vw,4rem);" class="reveal rv1">Built Because We Were <span class="text-gradient">Tired of Renting.</span></h1>
        <p style="font-size:1.15rem;max-width:600px;margin:20px auto 0;" class="reveal rv2">{{ setting('about.hero_description', 'COOCA was born from frustration — with subscription traps, fragmented tools, and software that grows your vendor\'s business more than yours.') }}</p>
    </div>
</section>

<!-- Mission Section -->
<section class="sec">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <div class="section-label"><i class="bi bi-bullseye"></i> Mission</div>
                <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="mb-4">Every Business Deserves to <span class="text-gradient">Own Its Infrastructure</span></h2>
                <p class="mb-4">{{ setting('about.mission_text_1', 'The SaaS model created a world where businesses rent the tools they depend on — indefinitely. Every month, cash flows out. Every year, the dependency deepens. And if you ever stop paying, you lose everything you built on top of it.') }}</p>
                <p class="mb-4">{{ setting('about.mission_text_2', 'COOCA flips this model. We believe business software should be an asset that appreciates, not a liability that bleeds. Our lifetime license model gives you permanent ownership with one investment — and our isolated infrastructure ensures your system belongs to you alone.') }}</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('register') }}" class="btn-cooca btn-primary-c">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                    <a href="{{ route('solution') }}" class="btn-cooca btn-outline-c">View Solutions</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach($counters as $counter)
                    <div class="col-6 reveal {{ $loop->first ? '' : 'rv' . $loop->index }}">
                        <div class="card-c" style="text-align:center;">
                            <div class="stat-val">{{ $counter['value'] }}</div>
                            <div class="stat-label">{{ $counter['label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="sec sec-alt">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-gem"></i> Core Values</div>
            <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="reveal rv1">The Principles We <span class="text-gradient">Never Compromise</span></h2>
        </div>
        <div class="row g-4">
            @foreach($values as $value)
            <div class="col-lg-4 col-md-6 reveal {{ $loop->first ? '' : 'rv' . $loop->index }}">
                <div class="card-c h-100">
                    <div class="value-icon"><i class="bi {{ $value['icon'] }}"></i></div>
                    <h4 class="mb-3">{{ $value['title'] }}</h4>
                    <p>{{ $value['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="sec">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-4 reveal">
                <div class="section-label"><i class="bi bi-clock-history"></i> Our Journey</div>
                <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="mb-4">From a <span class="text-gradient">Frustrated Founder</span> to {{ setting('about.businesses_count', '10,000+') }} Businesses</h2>
                <p>{{ setting('about.journey_intro', 'COOCA started as a solution to a problem we lived. As the system matured, so did our conviction: businesses deserved better than the SaaS status quo.') }}</p>
            </div>
            <div class="col-lg-8">
                <div class="timeline-v">
                    @foreach($timeline as $item)
                    <div class="tv-item reveal {{ $loop->first ? '' : 'rv' . $loop->index }}">
                        <div class="tv-dot">{{ substr($item['year'], -2) }}</div>
                        <div class="tv-year">{{ $item['year'] }}</div>
                        <div class="tv-title">{{ $item['title'] }}</div>
                        <div class="tv-desc">{{ $item['description'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="sec sec-alt">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-person-badge-fill"></i> Leadership</div>
            <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="reveal rv1">The Team Behind <span class="text-gradient">the System</span></h2>
            <p class="reveal rv2" style="max-width:500px;margin:16px auto 0;">{{ setting('about.team_intro', 'Operators, engineers, and designers who\'ve built and run businesses — and built the tools they wished they had.') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($team as $member)
            <div class="col-lg-3 col-md-6 reveal {{ $loop->first ? '' : 'rv' . $loop->index }}">
                <div class="card-c team-card h-100">
                    <div class="team-avatar">{{ $member['initials'] }}</div>
                    <div class="team-name">{{ $member['name'] }}</div>
                    <div class="team-role">{{ $member['role'] }}</div>
                    <p class="team-bio">{{ $member['bio'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="sec" style="background:linear-gradient(160deg,var(--bg) 0%,#0F172A 50%,var(--bg) 100%);">
    <div class="container text-center">
        <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="reveal">Ready to Own Your <span class="text-gradient">Business Infrastructure?</span></h2>
        <p class="reveal rv1" style="max-width:480px;margin:16px auto 36px;">{{ setting('about.cta_description', 'Join 10,000+ businesses that chose ownership over renting. Start your 30-day free trial — no credit card required.') }}</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap reveal rv2">
            <a href="{{ route('register') }}" class="btn-cooca btn-primary-c" style="padding:16px 40px;">Start Free Trial <i class="bi bi-arrow-right"></i></a>
            <a href="{{ route('contact') }}" class="btn-cooca btn-outline-c" style="padding:16px 40px;">Talk to Sales</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// About page specific animations handled by global reveal system
</script>
@endpush
