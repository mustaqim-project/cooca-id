@extends('layouts.guest')
@push('styles')
<style>
    /* BLOG DETAIL SPECIFIC */
    .blog-detail-hero {
        padding: 140px 0 40px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
        border-bottom: 1px solid var(--border);
    }
    .blog-cat-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 6px 16px;
        border-radius: 50px;
        display: inline-block;
        margin-bottom: 20px;
        background: rgba(37, 99, 235, 0.12);
        border: 1px solid rgba(37, 99, 235, 0.2);
        color: var(--accent);
    }
    .blog-post-title {
        font-size: clamp(2rem, 4vw, 3.2rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.2;
        color: var(--text);
        margin-bottom: 20px;
    }
    .author-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .share-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 12px;
    }
    .share-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--card);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition);
        font-size: 1.1rem;
    }
    .share-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--card-alt);
        transform: translateY(-2px);
    }
    .share-label {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-right: 8px;
    }

    /* FEATURED IMAGE */
    .featured-image-section {
        margin: 40px 0 60px;
        border-radius: 28px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.4);
        border: 1px solid var(--border);
        background: var(--card);
    }
    .featured-image-section img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
        max-height: 600px;
    }

    /* ARTICLE TYPOGRAPHY */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.85;
        color: var(--text-muted);
    }
    .article-content h2 {
        font-size: 1.7rem;
        margin-top: 54px;
        margin-bottom: 24px;
        color: var(--text);
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .article-content h3 {
        font-size: 1.35rem;
        margin-top: 40px;
        margin-bottom: 18px;
        color: var(--text);
        font-weight: 600;
    }
    .article-content p {
        margin-bottom: 24px;
    }
    .article-content ul,
    .article-content ol {
        margin-bottom: 24px;
        padding-left: 24px;
        color: var(--text-muted);
    }
    .article-content li {
        margin-bottom: 10px;
    }
    .article-content blockquote {
        background: rgba(37, 99, 235, 0.06);
        border-left: 4px solid var(--primary);
        padding: 24px 28px;
        margin: 36px 0;
        border-radius: 0 16px 16px 0;
        font-style: italic;
        color: var(--text);
    }
    .article-content blockquote p {
        margin-bottom: 0;
        color: var(--text);
    }
    .article-content strong {
        color: var(--text);
        font-weight: 600;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        margin: 28px 0;
        border: 1px solid var(--border);
    }

    /* RELATED POSTS */
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
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
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
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-title-sm {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.4;
        color: var(--text);
    }
    .blog-title-sm a {
        color: var(--text);
        text-decoration: none;
        transition: color var(--transition);
    }
    .blog-title-sm a:hover {
        color: var(--accent);
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

    @media (max-width: 767px) {
        .newsletter-input { flex-direction: column; }
        .blog-detail-hero { padding: 120px 0 30px; }
        .article-content h2 { font-size: 1.4rem; margin-top: 36px; }
        .article-content { font-size: 1rem; }
        .featured-image-section { border-radius: 16px; margin: 30px 0 40px; }
        .featured-image-section img { max-height: 280px; }
    }
</style>
@endpush
@section('content')
<!-- HERO -->
<section class="blog-detail-hero">
    <div class="blog-hero-orb blog-hero-orb-1"></div>
    <div class="blog-hero-orb blog-hero-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <!-- Category Badge -->
                <span class="blog-cat-badge reveal">{{ $post->category ?? __('Business Insight') }}</span>
                
                <!-- Title -->
                <h1 class="blog-post-title reveal reveal-delay-1">
                    {{ $post->title }}
                </h1>

                <!-- Meta -->
                <div class="d-flex align-items-center flex-wrap gap-3 reveal reveal-delay-2" style="margin-bottom:28px; border-bottom: 1px solid var(--border); padding-bottom: 24px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="author-avatar">{{ strtoupper(substr($post->author->name ?? 'A', 0, 2)) }}</div>
                        <span style="font-size:0.95rem;color:var(--text);font-weight:600;">{{ $post->author->name ?? __('Staff Writer') }}</span>
                    </div>
                    <span style="color:var(--border);">•</span>
                    <span style="font-size:0.9rem;color:var(--text-muted);"><i class="bi bi-calendar3 me-1"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</span>
                    <span style="color:var(--border);">•</span>
                    <span style="font-size:0.9rem;color:var(--text-muted);"><i class="bi bi-eye me-1"></i> {{ number_format($post->view_count ?? 0) }} {{ __('views') }}</span>
                </div>

                <!-- Share -->
                <div class="share-bar reveal reveal-delay-3">
                    <span class="share-label">{{ __('Share:') }}</span>
                    <button class="share-btn" title="Share on Twitter" onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(window.location.href),'_blank')"><i class="bi bi-twitter-x"></i></button>
                    <button class="share-btn" title="Share on LinkedIn" onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url='+encodeURIComponent(window.location.href),'_blank')"><i class="bi bi-linkedin"></i></button>
                    <button class="share-btn" title="Share on Facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href),'_blank')"><i class="bi bi-facebook"></i></button>
                    <button class="share-btn" title="Copy link" onclick="navigator.clipboard.writeText(window.location.href);this.innerHTML='<i class=\'bi bi-check-lg\'></i>';setTimeout(()=>this.innerHTML='<i class=\'bi bi-link-45deg\'></i>',2000)"><i class="bi bi-link-45deg"></i></button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED IMAGE SECTION -->
@if($post->cover_image)
<div class="container reveal">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="featured-image-section">
                <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}">
            </div>
        </div>
    </div>
</div>
@endif

<!-- ARTICLE BODY -->
<section style="padding: 60px 0 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-8">
                <article class="article-content reveal" id="articleBody">
                    {!! $post->content !!}
                </article>
            </div>
        </div>
    </div>
</section>

<!-- RELATED POSTS -->
@if(isset($relatedPosts) && count($relatedPosts) > 0)
<section class="section-padding" style="background: var(--card-alt); border-top: 1px solid var(--border);">
    <div class="container">
        <div class="reveal mb-5 text-center">
            <h2 style="font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 800; color: var(--text);">{{ __('Related Articles') }}</h2>
            <p style="color: var(--text-muted);">{{ __('Keep exploring more insights and operational strategies.') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($relatedPosts as $idx => $relPost)
            <div class="col-lg-4 col-md-6 reveal reveal-delay-{{ ($idx % 3) + 1 }}">
                <div class="blog-card card-c">
                    <div class="blog-thumb">
                        @if($relPost->cover_image)
                            <img src="{{ Storage::url($relPost->cover_image) }}" alt="{{ $relPost->title }}">
                        @else
                            <div style="font-size: 3rem; color: var(--text-muted);">
                                <i class="bi bi-card-text"></i>
                            </div>
                        @endif
                    </div>
                    <div class="blog-body">
                        <span class="blog-cat-badge" style="margin-bottom:12px;padding:4px 12px;font-size:0.7rem;">{{ $relPost->category ?? __('Insight') }}</span>
                        <div class="blog-title-sm">
                            <a href="{{ route('blog.show', $relPost->slug) }}">{{ $relPost->title }}</a>
                        </div>
                        <p style="font-size:0.88rem;color:var(--text-muted);margin-bottom:16px;flex-grow:1;">
                            {{ $relPost->excerpt ?? Str::limit(strip_tags($relPost->content), 100) }}
                        </p>
                        <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.8rem;color:var(--text-muted);padding-top:12px;border-top:1px solid var(--border);">
                            <span>{{ $relPost->author->name ?? __('Staff Writer') }}</span>
                            <span>{{ $relPost->published_at ? $relPost->published_at->format('M d, Y') : '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- NEWSLETTER -->
<section class="section-padding" style="background: var(--bg); border-top: 1px solid var(--border);">
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
