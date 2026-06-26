<?php

$file = 'resources/views/pages/home/index.blade.php';
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
    $content = str_replace('/* Utility Classes */', $swiper_styles . "\n      /* Utility Classes */", $content);
}

// 3. Inject Swiper JS
if (strpos($content, 'swiper-bundle.min.js') === false) {
    $content = str_replace(
        "@push('scripts')",
        "@push('scripts')\n<script src=\"https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js\"></script>",
        $content
    );
}

$swiper_js = <<<EOT
        // Initialize Swiper for each category tab
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
EOT;

if (strpos($content, 'Swiper for each category tab') === false) {
    $content = str_replace(
        "document.addEventListener('DOMContentLoaded', function() {",
        "document.addEventListener('DOMContentLoaded', function() {\n" . $swiper_js,
        $content
    );
}

// 4. Replace #products section
$products_replacement = <<<EOT
    <!-- PRODUCT ECOSYSTEM -->
    <section class="section-padding" id="products">
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-grid-3x3-gap-fill"></i> {{ __('Industry Solutions') }}
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            {!! __('Built for <span class="text-gradient">Every Industry</span>') !!}
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            {{ __('Nine specialized business systems engineered to replace fragmented tools. Pick your core foundation.') }}
          </p>
        </div>

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
                        <button class="cat-tab-btn {{ \$loop->first ? 'active' : '' }}" id="tab-home-{{ \$tabId }}" data-bs-toggle="tab" data-bs-target="#pane-home-{{ \$tabId }}" type="button" role="tab">
                            {{ \$catName }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="tab-content reveal reveal-delay-3">
                @foreach(\$groupedProducts as \$catName => \$catProducts)
                    @php \$tabId = \Illuminate\Support\Str::slug(\$catName); @endphp
                    <div class="tab-pane fade {{ \$loop->first ? 'show active' : '' }}" id="pane-home-{{ \$tabId }}" role="tabpanel">
                        <div class="position-relative">
                            <div class="swiper pricing-swiper" id="swiper-{{ \$tabId }}">
                                <div class="swiper-wrapper">
                                    @foreach(\$catProducts as \$product)
                                        <div class="swiper-slide">
                                            <div class="card-3d product-card" style="height: 100%; display: flex; flex-direction: column;">
                                                <div class="card-glow"></div>
                                                <div class="card-icon"><i class="{{ \$product->icon ?? 'bi bi-box' }}"></i></div>
                                                <div class="card-title">{{ \$product->name }}</div>
                                                <div class="card-desc" style="flex-grow: 1;">
                                                    {{ \$product->short_description ?? Str::limit(\$product->description, 90) }}
                                                </div>
                                                <div class="card-actions mt-4">
                                                    <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-outline btn-cooca-sm">{{ __('View Pricing') }}</a>
                                                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary btn-cooca-sm">{{ __('Live Demo') }}</a>
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

$content = preg_replace('/<!-- PRODUCT ECOSYSTEM(.*?)<\/section>/s', $products_replacement, $content, 1);

// 5. Remove #pricing section
$content = preg_replace('/<!-- PRICING -->(.*?)<\/section>/s', '', $content, 1);

// 6. Replace href="#" in remaining file
$content = str_replace('href="#"', 'href="javascript:void(0)"', $content);

file_put_contents($file, $content);
echo "home/index.blade.php patched successfully.\n";
