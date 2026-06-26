@extends('layouts.guest')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .hero-section {
        min-height: 70vh;
        display: flex;
        align-items: center;
        padding-top: 120px;
        padding-bottom: 80px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
    }
    .hero-bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.12;
        pointer-events: none;
    }
    .hero-bg-orb-1 {
        width: 600px;
        height: 600px;
        background: var(--primary);
        top: -200px;
        right: -100px;
    }
    .hero-bg-orb-2 {
        width: 400px;
        height: 400px;
        background: var(--accent);
        bottom: -100px;
        left: -100px;
    }
    .hero-bg-orb-3 {
        width: 300px;
        height: 300px;
        background: var(--secondary);
        top: 50%;
        left: 40%;
        animation: float 8s ease-in-out infinite;
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }
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
    /* ---- Solution Cards (Page-Specific) ---- */
    .solution-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: all var(--transition);
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .solution-card:hover {
        transform: translateY(-8px);
        border-color: rgba(56, 189, 248, 0.3);
        box-shadow: 0 24px 64px rgba(56, 189, 248, 0.08);
    }
    .solution-card-header {
        padding: 28px 28px 20px;
        border-bottom: 1px solid var(--border);
    }
    .solution-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(56, 189, 248, 0.15));
        border: 1px solid rgba(56, 189, 248, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--accent);
        margin-bottom: 16px;
    }
    .solution-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .solution-tag {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    .solution-card-body {
        padding: 20px 28px 28px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .solution-feature {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.88rem;
    }
    .solution-feature:last-child {
        border-bottom: none;
    }
    .solution-feature i {
        color: var(--accent);
        flex-shrink: 0;
        margin-top: 3px;
    }
    .solution-card .btn-cooca {
        margin-top: auto;
        justify-content: center;
    }

    @media (max-width: 991.98px) {
        .solution-card-header .d-flex.align-items-center.justify-content-between {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
    }
    @media (max-width: 767.98px) {
        .hero-section {
            padding-top: 100px;
            padding-bottom: 60px;
        }
    }
</style>
@endpush
@section('content')
<section class="blog-hero">
    <div class="blog-hero-orb blog-hero-orb-1"></div>
    <div class="blog-hero-orb blog-hero-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-glow reveal mb-4">
            <i class="bi bi-grid-3x3-gap-fill"></i> {{ __(setting('solutions.badge', 'Industry Solutions')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {!! __(setting('solutions.title', 'Purpose-Built for <span class="text-gradient">Every Industry</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 36px;">
            {{ __(setting('solutions.subtitle', 'Nine specialized systems — each engineered to replace the fragmented tools that drain your time, cash, and sanity. One license. One infrastructure. Yours forever.')) }}
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap reveal reveal-delay-3">
            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
            <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-outline">{{ __('View Pricing') }}</a>
        </div>
    </div>
</section>

<!-- PRODUCT ECOSYSTEM — 3 CORE BUSINESS TABS -->
    <section class="section-padding" id="products">
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-grid-3x3-gap-fill"></i> Industry Solutions
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Built for <span class="text-gradient">Every Industry</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            Nine specialized business systems — each engineered to replace
            fragmented tools that drain your time, revenue, and peace of mind.
          </p>
        </div>

        @php
            $groupedProducts = collect([]);
            if (isset($products) && count($products) > 0) {
                $groupedProducts = $products->groupBy(function($item) {
                    return $item->category ? $item->category->name : __('General Suite');
                });
            }
        @endphp

        @if($groupedProducts->count() > 0)
            <ul class="nav nav-tabs" id="productsTab" role="tablist">
              @foreach($groupedProducts as $catName => $catProducts)
                @php 
                    $tabId = \Illuminate\Support\Str::slug($catName); 
                    $catIcon = $catProducts->first()->category->icon ?? 'bi-grid-3x3-gap-fill';
                @endphp
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $tabId }}"
                    data-bs-toggle="tab"
                    data-bs-target="#products-{{ $tabId }}"
                    type="button"
                    role="tab"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                  >
                    <i class="bi {{ $catIcon }} me-2"></i>{{ $catName }}
                  </button>
                </li>
              @endforeach
            </ul>

            <div class="tab-content" id="productsTabContent">
              @foreach($groupedProducts as $catName => $catProducts)
                @php $tabId = \Illuminate\Support\Str::slug($catName); @endphp
                <div
                  class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                  id="products-{{ $tabId }}"
                  role="tabpanel"
                >
                  <div class="row g-4">
                    @foreach($catProducts as $index => $product)
                      <div class="col-lg-4 col-md-6 reveal {{ $index > 0 ? 'reveal-delay-' . $index : '' }}">
                        <div class="card-3d product-card" style="height: 100%">
                          <div class="card-glow"></div>
                          <div class="card-icon"><i class="bi {{ $product->icon ?? 'bi-box' }}"></i></div>
                          <div class="card-title">{{ $product->name }}</div>
                          <div class="card-desc">
                            {{ $product->short_description ?? \Illuminate\Support\Str::limit($product->description, 120) }}
                          </div>
                          <div class="card-actions">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn-cooca btn-cooca-outline btn-cooca-sm"
                              >{{ __('Learn More') }}</a>
                            @if($product->demo_url)
                              <a
                                href="{{ $product->demo_url }}"
                                target="_blank"
                                class="btn-cooca btn-cooca-primary btn-cooca-sm"
                                >{{ __('Live Demo') }}</a
                              >
                            @endif
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
        @else
            <div class="glow-card text-center py-5 reveal">
                <i class="bi bi-inbox-fill" style="font-size: 3rem; color: var(--border);"></i>
                <p class="text-muted mt-3">{{ __('No products available at the moment.') }}</p>
            </div>
        @endif
      </div>
    </section>

<!-- CORE CAPABILITIES — 3 GROUPED TABS -->
    <section
      class="section-padding"
      id="modules"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-puzzle-fill"></i> Business Capabilities
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Everything Your Business Needs to
            <span class="text-gradient">Scale</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            Ten integrated capabilities replacing dozens of separate
            subscriptions. Each one works with the others — because they were
            built to.
          </p>
        </div>

        <ul class="nav nav-tabs" id="modulesTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="tab-people"
              data-bs-toggle="tab"
              data-bs-target="#modules-people"
              type="button"
              role="tab"
              aria-selected="true"
            >
              <i class="bi bi-people me-2"></i>People &amp; Revenue
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-operations"
              data-bs-toggle="tab"
              data-bs-target="#modules-operations"
              type="button"
              role="tab"
              aria-selected="false"
            >
              <i class="bi bi-gear me-2"></i>Operations &amp; Finance
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-intelligence"
              data-bs-toggle="tab"
              data-bs-target="#modules-intelligence"
              type="button"
              role="tab"
              aria-selected="false"
            >
              <i class="bi bi-graph-up-arrow me-2"></i>Intelligence &amp; Growth
            </button>
          </li>
        </ul>

        <div class="tab-content" id="modulesTabContent">
          <!-- Tab 1: People & Revenue -->
          <div
            class="tab-pane fade show active"
            id="modules-people"
            role="tabpanel"
          >
            <div class="row g-4">
              <div class="col-lg-4 col-md-6 reveal">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="module-title">CRM</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Turn leads into loyal customers with automated relationship
                    management. Full pipeline visibility from first contact to
                    closed deal.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Contact &amp; lead management
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Sales pipeline tracking
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Loyalty &amp; retention programs
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-person-badge"></i>
                  </div>
                  <div class="module-title">HRM</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Manage your entire team — from recruitment and onboarding to
                    payroll and performance — all in one place.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Attendance &amp; shift scheduling
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Automated payroll calculation
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Performance &amp; leave management
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-whatsapp"></i>
                  </div>
                  <div class="module-title">WhatsApp Integration</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Reach customers where they already are — instantly and
                    automatically. Blast notifications, confirmations, and
                    campaigns without leaving the system.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Broadcast &amp; bulk messaging
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Automated order &amp; payment alerts
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Two-way customer chat
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 2: Operations & Finance -->
          <div class="tab-pane fade" id="modules-operations" role="tabpanel">
            <div class="row g-4">
              <div class="col-lg-3 col-md-6 reveal">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-calculator"></i>
                  </div>
                  <div class="module-title">Accounting</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Real-time financial clarity with automated bookkeeping,
                    journal entries, and balance sheets — no accountant
                    dependency.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Auto journal &amp; GL
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      P&amp;L &amp; balance sheets
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 reveal reveal-delay-1">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-bank"></i>
                  </div>
                  <div class="module-title">Finance</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Budgeting, forecasting, and cash flow management that
                    empowers confident business decisions — not reactive panic.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Budget planning
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Cash flow forecasting
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 reveal reveal-delay-2">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-box-seam"></i>
                  </div>
                  <div class="module-title">Inventory</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Never run out of stock — or over-purchase. Smart tracking
                    across multiple warehouses with automated reorder alerts.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Multi-warehouse tracking
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Auto reorder points
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 reveal reveal-delay-3">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-credit-card"></i>
                  </div>
                  <div class="module-title">Payments</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Accept money from anywhere with fully integrated payment
                    processing — QRIS, transfer, cards, and e-wallets.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      QRIS &amp; multi-gateway
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Auto reconciliation
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 3: Intelligence & Growth -->
          <div class="tab-pane fade" id="modules-intelligence" role="tabpanel">
            <div class="row g-4">
              <div class="col-lg-4 col-md-6 reveal">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-bar-chart-line"></i>
                  </div>
                  <div class="module-title">Reporting &amp; Analytics</div>
                  <div class="module-desc" style="margin-top: 8px">
                    See exactly where your money comes from and where it goes.
                    Real-time dashboards that surface the insights that actually
                    matter.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Custom dashboard builder
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Revenue &amp; cost breakdown
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Exportable PDF &amp; Excel reports
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-gear-wide-connected"></i>
                  </div>
                  <div class="module-title">Workflow Automation</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Eliminate repetitive work so your team focuses on growth.
                    Set rules once, let the system execute thousands of tasks
                    daily.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Trigger-based rule engine
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Cross-module automation
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Scheduled &amp; event-driven tasks
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-robot"></i>
                  </div>
                  <div class="module-title">AI Assistant</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Get actionable insights and smart recommendations that drive
                    better business decisions — before problems become
                    expensive.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Predictive revenue analytics
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Anomaly &amp; fraud detection
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Natural language business Q&amp;A
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

<!-- =====================================
     CTA
     ===================================== -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container text-center">
        <h2 class="reveal" style="font-size:clamp(1.8rem,3.5vw,2.8rem);">{!! __('Not Sure Which Solution <span class="text-gradient">Fits Your Business?</span>') !!}</h2>
        <p class="reveal reveal-delay-1" style="max-width:480px;margin:16px auto 36px;">{{ __('Start your free 30-day trial and explore all nine industry systems. Or talk to our team — we\'ll match you in 15 minutes.') }}</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap reveal reveal-delay-2">
            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" style="padding:16px 40px;">{{ __('Start 30-Day Free Trial') }} <i class="bi bi-arrow-right"></i></a>
            <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline" style="padding:16px 40px;">{{ __('Talk to Sales') }}</a>
        </div>
    </div>
</section>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($products) && $products->count() > 0)
            @php
                $groupedProducts = $products->groupBy(function($item) {
                    return $item->category ? $item->category->name : __('General Suite');
                });
            @endphp
            @foreach($groupedProducts as $catName => $catProducts)
                @php $tabId = \Illuminate\Support\Str::slug($catName); @endphp
                if(document.getElementById('swiper-{{ $tabId }}')) {
                    new Swiper('#swiper-{{ $tabId }}', {
                        slidesPerView: 1,
                        spaceBetween: 24,
                        observer: true,
                        observeParents: true,
                        watchSlidesProgress: true,
                        pagination: {
                            el: '#swiper-{{ $tabId }} .swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '#next-{{ $tabId }}',
                            prevEl: '#prev-{{ $tabId }}',
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
@endsection
