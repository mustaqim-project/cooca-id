@extends('layouts.guest')
@section('content')
    @if (isset($post))
        <section class="page-hero">
            <div class="page-hero-orb page-hero-orb-1"></div>
            <div class="page-hero-orb page-hero-orb-2"></div>
            <div class="grid-bg"></div>
            <div class="container" style="position:relative;z-index:2;">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center reveal">
                        @if ($post->category)
                            <div class="badge-glow mb-4">{{ $post->category }}</div>
                        @endif
                        <h1 class="hero-title">{{ $post->title }}</h1>
                        <p class="hero-subtitle">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 150) }}</p>
                        <div style="font-size:0.85rem;color:var(--text-muted);">
                            <span>{{ __('Published') }}:
                                {{ $post->published_at ? $post->published_at->format('F j, Y') : '' }}</span>
                            @if ($post->author)
                                <span class="mx-2">·</span><span>{{ $post->author->name ?? '' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 reveal">
                        @if ($post->featured_image)
                            <div class="mb-4 text-center">
                                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}"
                                    style="max-width:100%;height:auto;border-radius:var(--radius-lg);box-shadow:var(--shadow-md);">
                            </div>
                        @endif
                        <div class="card"
                            style="border-radius:var(--radius-lg);padding:40px;background:var(--color-surface);box-shadow:var(--shadow-sm);border:1px solid var(--color-border);">
                            <div class="blog-content" style="font-size:1.05rem;line-height:1.8;color:var(--text-primary);">
                                {!! $post->content !!}
                            </div>
                        </div>

                        @if (isset($relatedPosts) && count($relatedPosts))
                            <div class="mt-5">
                                <h3 class="mb-4">{{ __('Related Articles') }}</h3>
                                <div class="row g-3">
                                    @foreach ($relatedPosts->take(3) as $related)
                                        <div class="col-md-4">
                                            <div class="card card-3d" style="padding:20px;">
                                                @if ($related->category)
                                                    <div class="badge-glow mb-2" style="font-size:0.65rem;">
                                                        {{ $related->category }}</div>
                                                @endif
                                                <h4 style="font-size:0.95rem;"><a
                                                        href="{{ route('blog.show', $related->slug) }}"
                                                        style="color:var(--text);">{{ $related->title }}</a></h4>
                                                <p style="font-size:0.78rem;margin:0;">
                                                    {{ $related->published_at ? $related->published_at->format('M d, Y') : '' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="text-center mt-5">
                            <a href="{{ route('blog.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i>
                                {{ __('Back to Blog') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="page-hero">
            <div class="container text-center">
                <h1>{{ __('Post Not Found') }}</h1>
                <p>{{ __("This blog post doesn't exist or has been removed.") }}</p><a href="{{ route('blog.index') }}"
                    class="btn btn-outline mt-3">{{ __('Back to Blog') }}</a>
            </div>
        </section>
    @endif
@endsection
