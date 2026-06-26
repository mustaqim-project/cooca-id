<?php

$file = 'resources/views/pages/solutions/index.blade.php';
$content = file_get_contents($file);

// 1. Inject Swiper CSS
if (strpos($content, 'swiper-bundle.min.css') === false) {
    $content = str_replace(
        "@push('styles')",
        "@push('styles')\n<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css\" />",
        $content
    );
}

// 2. Inject Swiper CSS styles
$swiper_styles = <<<EOT
      /* Swiper Styling */
      .pricing-swiper {
          padding: 20px 10px 60px;
          overflow: hidden;
      }
      .swiper-slide {
          height: auto;
      }
      .swiper-pagination-bullet {
          background: var(--text-muted);
          width: 10px;
          height: 10px;
          transition: all var(--transition);
      }
      .swiper-pagination-bullet-active {
          background: var(--accent);
          width: 28px;
          border-radius: 5px;
      }
      .swiper-button-next, .swiper-button-prev {
          width: 48px;
          height: 48px;
          border-radius: 50%;
          background: var(--card);
          border: 1px solid var(--border);
          color: var(--accent);
          box-shadow: var(--shadow);
          transition: all var(--transition);
      }
      .swiper-button-next:hover, .swiper-button-prev:hover {
          background: var(--primary);
          color: #fff;
          border-color: var(--primary);
          transform: scale(1.1);
      }
      /* Category Tabs Styling */
      .category-tabs-wrapper {
          display: flex;
          justify-content: center;
          margin-bottom: 48px;
          padding: 0 16px;
      }
      .category-tabs-container {
          display: flex;
          gap: 8px;
          background: var(--card);
          border: 1px solid var(--border);
          padding: 8px;
          border-radius: 50px;
          box-shadow: var(--shadow);
          overflow-x: auto;
          max-width: 100%;
          scrollbar-width: none;
      }
      .category-tabs-container::-webkit-scrollbar {
          display: none;
      }
      .cat-tab-btn {
          padding: 12px 28px;
          border-radius: 50px;
          border: none;
          background: transparent;
          color: var(--text-muted);
          font-weight: 700;
          font-size: 0.95rem;
          white-space: nowrap;
          transition: all var(--transition);
          cursor: pointer;
      }
      .cat-tab-btn:hover {
          color: var(--text);
          background: rgba(56, 189, 248, 0.08);
      }
      .cat-tab-btn.active {
          background: linear-gradient(135deg, var(--primary), var(--accent));
          color: #fff;
          box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
      }
EOT;

if (strpos($content, '.pricing-swiper') === false) {
    $content = str_replace('/* ---- Solution Cards (Page-Specific) ---- */', $swiper_styles . "\n    /* ---- Solution Cards (Page-Specific) ---- */", $content);
}

// 3. Inject Swiper JS
if (strpos($content, 'swiper-bundle.min.js') === false) {
    $swiper_js_push = <<<EOT
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset(\$products) && \$products->count() > 0)
            @php
                \$groupedProducts = \$products->groupBy(function(\$item) {
                    return \$item->category ? \$item->category->name : __('General Suite');
                });
            @endphp
            @foreach(\$groupedProducts as \$catName => \$catProducts)
                @php \$tabId = \Illuminate\Support\Str::slug(\$catName); @endphp
                if(document.getElementById('swiper-{{ \$tabId }}')) {
                    new Swiper('#swiper-{{ \$tabId }}', {
                        slidesPerView: 1,
                        spaceBetween: 24,
                        observer: true,
                        observeParents: true,
                        watchSlidesProgress: true,
                        pagination: {
                            el: '#swiper-{{ \$tabId }} .swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '#next-{{ \$tabId }}',
                            prevEl: '#prev-{{ \$tabId }}',
                        },
                        breakpoints: {
                            768: { slidesPerView: 2 },
                            1024: { slidesPerView: 3 }
                        },
                    });
                }
            @endforeach
        @endif
    });
</script>
@endpush
EOT;
    $content = str_replace('@endsection', $swiper_js_push . "\n@endsection", $content);
}

// 4. Replace static content
$dynamic_content = <<<EOT
<!-- =====================================
     DYNAMIC PRODUCTS LISTING
     ===================================== -->
<section class="section-padding">
    <div class="container">
        @php
            \$groupedProducts = collect([]);
            if (isset(\$products) && count(\$products) > 0) {
                \$groupedProducts = \$products->groupBy(function(\$item) {
                    return \$item->category ? \$item->category->name : __('General Suite');
                });
            }
        @endphp

        @if(\$groupedProducts->count() > 0)
            <div class="category-tabs-wrapper reveal reveal-delay-2">
                <div class="category-tabs-container" role="tablist">
                    @foreach(\$groupedProducts as \$catName => \$catProducts)
                        @php \$tabId = \Illuminate\Support\Str::slug(\$catName); @endphp
                        <button class="cat-tab-btn {{ \$loop->first ? 'active' : '' }}" id="tab-sol-{{ \$tabId }}" data-bs-toggle="tab" data-bs-target="#pane-sol-{{ \$tabId }}" type="button" role="tab">
                            {{ \$catName }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="tab-content reveal reveal-delay-3">
                @foreach(\$groupedProducts as \$catName => \$catProducts)
                    @php \$tabId = \Illuminate\Support\Str::slug(\$catName); @endphp
                    <div class="tab-pane fade {{ \$loop->first ? 'show active' : '' }}" id="pane-sol-{{ \$tabId }}" role="tabpanel">
                        <div class="position-relative">
                            <div class="swiper pricing-swiper" id="swiper-{{ \$tabId }}">
                                <div class="swiper-wrapper">
                                    @foreach(\$catProducts as \$product)
                                        <div class="swiper-slide">
                                            <div class="solution-card" style="height: 100%; display: flex; flex-direction: column;">
                                                <div class="solution-card-header">
                                                    <div class="solution-icon"><i class="{{ \$product->icon ?? 'bi bi-box' }}"></i></div>
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="solution-title">{{ \$product->name }}</div>
                                                        <span class="solution-tag" style="background:rgba(37,99,235,0.15);color:var(--primary);">
                                                            {{ \$product->category ? \$product->category->name : 'Solution' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="solution-card-body" style="flex-grow: 1;">
                                                    <p style="font-size:0.88rem;color:var(--text-muted);margin-bottom:16px;">
                                                        {{ \$product->short_description ?? Str::limit(\$product->description, 90) }}
                                                    </p>
                                                    @if(\$product->features && is_array(\$product->features))
                                                        @foreach(array_slice(\$product->features, 0, 5) as \$feature)
                                                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>{{ \$feature }}</span></div>
                                                        @endforeach
                                                    @endif
                                                    <div class="mt-4 pt-3" style="border-top: 1px solid var(--border);">
                                                        <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-outline w-100 mb-2">{{ __('View Pricing') }}</a>
                                                        <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary w-100">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="swiper-button-prev d-none d-xl-flex" id="prev-{{ \$tabId }}" style="left: -25px;"></div>
                            <div class="swiper-button-next d-none d-xl-flex" id="next-{{ \$tabId }}" style="right: -25px;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">{{ __('No products available at the moment.') }}</p>
            </div>
        @endif
    </div>
</section>
EOT;

// Extract CTA
preg_match('/<!-- =====================================\s+CTA\s+===================================== -->.*?<\/section>/s', $content, $cta_match);
$cta_section = $cta_match[0] ?? '';

// Replace everything from <!-- COMMERCE & RETAIL --> to CTA
$content = preg_replace(
    '/<!-- =====================================\s+COMMERCE & RETAIL\s+===================================== -->.*<!-- =====================================\s+CTA\s+===================================== -->/s', 
    $dynamic_content . "\n\n" . "<!-- =====================================\n     CTA\n     ===================================== -->", 
    $content
);

file_put_contents($file, $content);
echo "solutions/index.blade.php patched successfully.\n";
