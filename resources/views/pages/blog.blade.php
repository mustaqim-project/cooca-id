@extends('layouts.guest')

@section('title', 'Blog - COOCA Business Insights')
@section('meta_description', 'Practical guides, industry insights, and growth strategies for serious business owners.')

@section('content')
<!-- Hero Section -->
<section class="blog-hero">
    <div class="hero-bg-orb hero-bg-orb-1"></div>
    <div class="hero-bg-orb hero-bg-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" style="position:relative;z-index:2;">
                <div class="badge-pill reveal mb-4">
                    <i class="bi bi-envelope-heart-fill"></i> {{ setting('blog.badge', 'Business Insights') }}
                </div>
                <h1 class="reveal reveal-delay-1" style="font-size:clamp(2.4rem,5vw,4rem);">
                    {!! setting('blog.hero_title', 'Guides & Insights for <span class="text-gradient">Growth-Minded Owners</span>') !!}
                </h1>
                <p class="reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
                    {{ setting('blog.hero_description', 'Practical strategies, industry deep-dives, and operational wisdom from businesses that scaled — not theorists.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Category Filter -->
<section class="section-padding" style="padding-top:60px;">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5 reveal">
            @foreach($categories as $index => $category)
            <button class="btn-cooca btn-outline-c btn-sm-c cat-btn {{ $index === 0 ? 'active' : '' }}" 
                    data-category="{{ $category['slug'] }}">
                {{ $category['name'] }}
            </button>
            @endforeach
        </div>

        <div class="row g-4">
            @foreach($posts as $index => $post)
            <div class="col-md-6 col-lg-4 reveal {{ $index > 0 ? 'rv' . ($index % 3) : '' }}">
                <div class="blog-card card-c">
                    <div class="blog-thumb" style="background:{{ $post['thumb_bg'] }};">{{ $post['emoji'] }}</div>
                    <div class="blog-body">
                        <span class="blog-cat" style="background:{{ $post['category_bg'] }};color:{{ $post['category_color'] }};">
                            {{ $post['category'] }}
                        </span>
                        <div class="blog-title">
                            <a href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a>
                        </div>
                        <p class="blog-excerpt">{{ $post['excerpt'] }}</p>
                        <div class="blog-meta">
                            <div class="blog-author">
                                <div class="author-avatar">{{ $post['author_initials'] }}</div>
                                <span>{{ $post['author_name'] }}</span>
                            </div>
                            <span>{{ $post['read_time'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($showLoadMore)
        <div class="text-center mt-5 reveal">
            <button class="btn-cooca btn-outline-c" style="padding:14px 40px;" id="loadMoreBtn">
                Load More Articles <i class="bi bi-arrow-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>

<!-- Sidebar Section (Popular + Topics) -->
@if($showSidebar)
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container">
        <div class="row g-5">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <!-- Additional posts can be loaded here -->
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Popular This Week -->
                <div class="sidebar-widget card-c reveal">
                    <div class="widget-title">{{ setting('blog.popular_title', 'Popular This Week') }}</div>
                    <div class="d-flex flex-column gap-3">
                        @foreach($popularPosts as $index => $post)
                        <div style="display:flex;gap:12px;align-items:flex-start;{{ $index < count($popularPosts) - 1 ? 'padding-bottom:12px;border-bottom:1px solid var(--border);' : '' }}">
                            <div style="font-size:1.5rem;font-weight:800;color:var(--border);width:28px;flex-shrink:0;">{{ $index + 1 }}</div>
                            <a href="{{ route('blog.show', $post['slug']) }}" style="font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4;">{{ $post['title'] }}</a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Topics -->
                <div class="sidebar-widget card-c reveal rv1">
                    <div class="widget-title">{{ setting('blog.topics_title', 'Topics') }}</div>
                    <div class="tag-cloud">
                        @foreach($topics as $topic)
                        <span class="tag">{{ $topic }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- CTA Widget -->
                <div class="sidebar-widget card-c reveal rv2" style="background:linear-gradient(135deg,rgba(37,99,235,.08),rgba(56,189,248,.04));border-color:rgba(56,189,248,.15);">
                    <div class="widget-title">{{ setting('blog.cta_widget_title', 'Try COOCA Free') }}</div>
                    <p style="font-size:.85rem;margin-bottom:16px;">{{ setting('blog.cta_widget_description', '30-day full system access. No credit card. No commitment.') }}</p>
                    <a href="{{ route('register') }}" class="btn-cooca btn-primary-c btn-sm-c" style="width:100%;justify-content:center;">
                        Start Free Trial <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="sec sec-alt">
    <div class="container">
        <div class="newsletter-box reveal">
            <div class="badge-pill mb-3" style="margin:0 auto;"><i class="bi bi-envelope-heart-fill"></i> {{ setting('blog.newsletter_badge', 'Newsletter') }}</div>
            <h3 style="font-size:1.8rem;margin-bottom:8px;">{{ setting('blog.newsletter_title', 'Business Insights, Every Tuesday') }}</h3>
            <p style="max-width:400px;margin:0 auto;">{{ setting('blog.newsletter_description', 'One actionable idea for business owners. No noise, no filler. Just what matters for growing and operating a profitable business.') }}</p>
            <form action="{{ route('blog.subscribe') }}" method="POST" class="newsletter-input">
                @csrf
                <input type="email" name="email" class="form-control-c" placeholder="{{ setting('blog.newsletter_placeholder', 'Your email address') }}" required>
                <button type="submit" class="btn-cooca btn-primary-c" style="white-space:nowrap;padding:14px 24px;">
                    {{ setting('blog.newsletter_button', 'Subscribe') }} <i class="bi bi-send"></i>
                </button>
            </form>
            <p style="font-size:.78rem;margin-top:12px;color:var(--text-muted);">{{ setting('blog.newsletter_disclaimer', 'No spam. Unsubscribe anytime.') }}</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Category filter functionality
document.querySelectorAll('.cat-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.cat-btn').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
        
        const category = this.dataset.category;
        // Add your filtering logic here or use AJAX to load filtered posts
        console.log('Filtering by category:', category);
    });
});

// Load more functionality
const loadMoreBtn = document.getElementById('loadMoreBtn');
if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
        // Add your load more logic here (AJAX or pagination)
        console.log('Loading more articles...');
    });
}
</script>
@endpush
