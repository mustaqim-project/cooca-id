@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('Blog') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Insights & <span class="text-gradient">Resources.</span>') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('Business tips, product updates, industry insights, and guides to help you get the most out of COOCA.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    @if(isset($posts) && count($posts))
    <div class="row g-4">
      @foreach($posts as $post)
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card card-3d">
          @if($post->featured_image)
          <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:200px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:16px;">
          @endif
          @if($post->category)
          <div class="badge-glow mb-2">{{ $post->category }}</div>
          @endif
          <h3 class="card-title" style="font-size:1.05rem;"><a href="{{ route('blog.show', $post->slug) }}" style="color:var(--text);">{{ $post->title }}</a></h3>
          <p class="card-desc">{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 100) }}</p>
          <div class="d-flex justify-content-between align-items-center" style="font-size:0.8rem;color:var(--text-muted);">
            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</span>
            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline btn-sm">{{ __('Read More') }}</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @if(method_exists($posts, 'links'))
    <div class="pagination-c mt-5">{{ $posts->links() }}</div>
    @endif
    @else
    <div class="text-center py-5 reveal">
      <div class="empty-state-icon"><i class="bi bi-journal-text"></i></div>
      <h4>{{ __('No Posts Yet') }}</h4>
      <p>{{ __('Blog posts are coming soon. Check back for business insights and product updates.') }}</p>
    </div>
    @endif
  </div>
</section>
@endsection