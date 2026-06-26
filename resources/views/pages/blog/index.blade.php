@extends('layouts.guest')
@push('styles')
<style>
    /* HERO */
    .blog-hero {
        padding: 160px 0 80px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
        border-bottom: 1px solid var(--border);
    }
    .blog-hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.12;
        pointer-events: none;
    }
    .blog-hero-orb-1 {
        width: 500px;
        height: 500px;
        background: var(--primary);
        top: -150px;
        right: -100px;
    }
    .blog-hero-orb-2 {
        width: 300px;
        height: 300px;
        background: var(--accent);
        bottom: -80px;
        left: -60px;
    }

    /* CARDS */
    .card-c {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: all var(--transition);
    }
    .card-c:hover {
        border-color: rgba(56, 189, 248, 0.3);
        box-shadow: 0 20px 60px rgba(56, 189, 248, 0.12);
    }
    .blog-card {
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .blog-card:hover {
        transform: translateY(-6px);
    }
    .blog-thumb {
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: var(--card-alt);
    }
    .blog-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .blog-card:hover .blog-thumb img {
        transform: scale(1.05);
    }
    .blog-body {
        padding: 28px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-cat {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 6px 14px;
        border-radius: 50px;
        display: inline-block;
        margin-bottom: 16px;
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: var(--accent);
        align-self: flex-start;
    }
    .blog-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
        color: var(--text);
    }
    .blog-title a {
        color: var(--text);
        text-decoration: none;
        transition: color var(--transition);
    }
    .blog-title a:hover {
        color: var(--accent);
    }
    .blog-excerpt {
        font-size: 0.95rem;
        color: var(--text-muted);
        flex-grow: 1;
        margin-bottom: 24px;
        line-height: 1.7;
    }
    .blog-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        color: var(--text-muted);
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }
    .blog-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .author-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    /* FEATURED CARD */
    .featured-card {
        border-radius: 28px;
        overflow: hidden;
    }
    .featured-thumb {
        height: 100%;
        min-height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: var(--card-alt);
        overflow: hidden;
    }
    .featured-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .featured-body {
        padding: 48px;
    }

    /* NEWSLETTER FORM */
    .form-control-c {
        padding: 16px 22px;
        border-radius: 16px;
        border: 1px solid var(--border);
        background: var(--card);
        color: var(--text);
        font-family: var(--font);
        font-size: 1rem;
        outline: none;
        transition: border-color var(--transition);
        width: 100%;
    }
    .form-control-c:focus {
        border-color: var(--accent);
    }
    .form-control-c::placeholder {
        color: var(--text-muted);
    }
    .newsletter-box {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(56, 189, 248, 0.06));
        border: 1px solid rgba(56, 189, 248, 0.15);
        border-radius: 32px;
        padding: 64px 32px;
        text-align: center;
    }
    .newsletter-input {
        display: flex;
        gap: 16px;
        max-width: 520px;
        margin: 32px auto 0;
    }

    /* SIDEBAR */
    .sidebar-widget {
        padding: 32px;
        margin-bottom: 32px;
    }
    .widget-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    .tag-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .tag {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid var(--border);
        color: var(--text-muted);
        transition: all var(--transition);
        cursor: pointer;
        text-decoration: none;
    }
    .tag:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: rgba(56, 189, 248, 0.05);
    }

    /* CATEGORY FILTER */
    .cat-filter {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 48px;
    }
    .cat-btn {
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition);
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        text-decoration: none;
    }
    .cat-btn:hover, .cat-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    @media (max-width: 767px) {
        .blog-hero { padding: 120px 0 60px; }
        .newsletter-input { flex-direction: column; }
        .featured-body { padding: 28px; }
    }
</style>
@endpush
@section('content')
<!-- HERO -->
<section class="blog-hero">
    <div class="blog-hero-orb blog-hero-orb-1"></div>
    <div class="blog-hero-orb blog-hero-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-glow reveal mb-4">
            <i class="bi bi-journal-richtext"></i> {{ __(setting('blog.hero.badge', 'Business Insights')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {!! __(setting('blog.hero.title', 'Guides for Businesses That <span class="text-gradient">Play to Win</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:620px;margin:20px auto 0;">
            {{ __(setting('blog.hero.subtitle', 'Practical strategies, industry benchmarks, and operational playbooks — written for operators, not consultants.')) }}
        </p>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="section-padding" style="background: var(--bg);">
    <div class="container">
        <!-- FEATURED POST -->
        @if(isset($featuredPosts) && count($featuredPosts) > 0)
            @php $featured = $featuredPosts->first(); @endphp
            <div class="row mb-5">
                <div class="col-12 reveal">
                    <div class="featured-card card-c">
                        <div class="row g-0 align-items-center">
                            <div class="col-lg-6">
                                <div class="featured-thumb">
                                    @if($featured->cover_image)
                                        <img src="{{ Storage::url($featured->cover_image) }}" alt="{{ $featured->title }}">
                                    @else
                                        <div style="font-size: 5rem; color: var(--text-muted);">
                                            <i class="bi bi-file-earmark-richtext"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="featured-body">
                                    <span class="blog-cat">{{ $featured->category ?? __('Featured') }}</span>
                                    <h2 style="font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 800; margin-bottom: 16px;">
                                        <a href="{{ route('blog.show', $featured->slug) }}" style="color: var(--text); text-decoration: none;">
                                            {{ $featured->title }}
                                        </a>
                                    </h2>
                                    <p style="color: var(--text-muted); font-size: 1.05rem; margin-bottom: 28px; line-height: 1.7;">
                                        {{ $featured->excerpt ?? Str::limit(strip_tags($featured->content), 200) }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                        <div class="blog-author">
                                            <div class="author-avatar">{{ strtoupper(substr($featured->author->name ?? 'A', 0, 2)) }}</div>
                                            <span style="font-size: 0.9rem; color: var(--text-muted);">{{ $featured->author->name ?? __('Staff Writer') }} &nbsp;·&nbsp; {{ $featured->published_at ? $featured->published_at->format('M d, Y') : '' }}</span>
                                        </div>
                                        <a href="{{ route('blog.show', $featured->slug) }}" class="btn-cooca btn-cooca-primary">
                                            {{ __('Read Article') }} <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- CATEGORIES FILTER -->
        @if(isset($categories) && count($categories) > 0)
            <div class="cat-filter reveal">
                <a href="{{ route('blog.index') }}" class="cat-btn {{ !request('category') ? 'active' : '' }}">{{ __('All') }}</a>
                @foreach($categories as $cat)
                <a href="{{ route('blog.index', ['category' => $cat]) }}" class="cat-btn {{ request('category') == $cat ? 'active' : '' }}">{{ $cat }}</a>
                @endforeach
            </div>
        @endif

        <div class="row g-5">
            <!-- MAIN POSTS GRID -->
            <div class="col-lg-8">
                @if(isset($posts) && count($posts) > 0)
                    <div class="row g-4">
                        @foreach($posts as $index => $post)
                        <div class="col-md-6 reveal reveal-delay-{{ ($index % 2) + 1 }}">
                            <div class="blog-card card-c">
                                <div class="blog-thumb">
                                    @if($post->cover_image)
                                        <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}">
                                    @else
                                        <div style="font-size: 3rem; color: var(--text-muted);">
                                            <i class="bi bi-card-text"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="blog-body">
                                    <span class="blog-cat">{{ $post->category ?? __('Insight') }}</span>
                                    <div class="blog-title">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </div>
                                    <p class="blog-excerpt">
                                        {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    <div class="blog-meta">
                                        <div class="blog-author">
                                            <div class="author-avatar">{{ strtoupper(substr($post->author->name ?? 'A', 0, 2)) }}</div>
                                            <span>{{ $post->author->name ?? __('Staff Writer') }}</span>
                                        </div>
                                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-5 reveal">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5 reveal">
                        <div style="font-size: 4rem; color: var(--text-muted); margin-bottom: 20px;">
                            <i class="bi bi-journal-x"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text); margin-bottom: 10px;">{{ __('No Articles Found') }}</h3>
                        <p style="color: var(--text-muted);">{{ __('We are currently preparing new business insights. Please check back soon.') }}</p>
                    </div>
                @endif
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                @if(isset($featuredPosts) && count($featuredPosts) > 1)
                <div class="sidebar-widget card-c reveal">
                    <div class="widget-title"><i class="bi bi-star-fill me-2" style="color: var(--accent);"></i> {{ __('Popular This Week') }}</div>
                    <div class="d-flex flex-column gap-3">
                        @foreach($featuredPosts->skip(1) as $idx => $sidePost)
                        <div style="display: flex; gap: 16px; align-items: flex-start; padding-bottom: 16px; border-bottom: {{ $loop->last ? 'none' : '1px solid var(--border)' }};">
                            <div style="font-size: 1.6rem; font-weight: 800; color: var(--border); width: 28px; flex-shrink: 0;">{{ $idx + 2 }}</div>
                            <div>
                                <a href="{{ route('blog.show', $sidePost->slug) }}" style="font-size: 0.95rem; font-weight: 700; color: var(--text); line-height: 1.4; text-decoration: none;">
                                    {{ $sidePost->title }}
                                </a>
                                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">{{ $sidePost->published_at ? $sidePost->published_at->format('M d, Y') : '' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="sidebar-widget card-c reveal reveal-delay-1">
                    <div class="widget-title"><i class="bi bi-tags-fill me-2" style="color: var(--accent);"></i> {{ __('Key Topics') }}</div>
                    <div class="tag-cloud">
                        <a href="{{ route('blog.index', ['tag' => 'Retail']) }}" class="tag">{{ __('Retail') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'Restaurant']) }}" class="tag">{{ __('Restaurant') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'Hotel']) }}" class="tag">{{ __('Hotel') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'Clinic']) }}" class="tag">{{ __('Clinic') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'Revenue']) }}" class="tag">{{ __('Revenue') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'Inventory']) }}" class="tag">{{ __('Inventory') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'AI']) }}" class="tag">{{ __('AI') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'Finance']) }}" class="tag">{{ __('Finance') }}</a>
                        <a href="{{ route('blog.index', ['tag' => 'Automation']) }}" class="tag">{{ __('Automation') }}</a>
                    </div>
                </div>

                <div class="sidebar-widget card-c reveal reveal-delay-2" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(56, 189, 248, 0.05)); border-color: rgba(56, 189, 248, 0.2);">
                    <div class="widget-title"><i class="bi bi-rocket-takeoff-fill me-2" style="color: var(--primary);"></i> {{ __('Try COOCA Free') }}</div>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 24px;">{{ __('30-day full system access. No credit card. No commitment.') }}</p>
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center;">
                        {{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER -->
<section class="section-padding" style="background: var(--card-alt); border-top: 1px solid var(--border);">
    <div class="container">
        <div class="newsletter-box reveal">
            <div class="badge-glow mb-4" style="margin: 0 auto;">
                <i class="bi bi-envelope-heart-fill"></i> {{ __('Newsletter') }}
            </div>
            <h3 style="font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 800; color: var(--text); margin-bottom: 16px;">{{ __('Business Insights, Every Tuesday') }}</h3>
            <p style="max-width: 500px; font-size: 1.1rem; color: var(--text-muted); margin: 0 auto;">{{ __('One actionable idea for business owners. No noise, no filler. Just what matters for growing and operating a profitable business.') }}</p>
            
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-input">
                @csrf
                <input type="email" name="email" class="form-control-c" placeholder="{{ __('Your email address') }}" required>
                <button type="submit" class="btn-cooca btn-cooca-primary" style="white-space: nowrap;">
                    {{ __('Subscribe') }} <i class="bi bi-send"></i>
                </button>
            </form>
            <p style="font-size: 0.85rem; margin-top: 16px; color: var(--text-muted);">{{ __('No spam. Unsubscribe anytime.') }}</p>
        </div>
    </div>
</section>
@endsection
