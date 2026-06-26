@php
    $routeName = Route::currentRouteName();
    
    // Default fallback
    $platformName = setting('site.name', 'COOCA');
    $defaultTitle = $platformName . ' - Enterprise Business Infrastructure';
    $defaultDesc = 'The business system that works like an asset.';
    
    // Check if there is specific SEO setting for this route
    $seoTitle = setting("seo.{$routeName}.title", $defaultTitle);
    $seoDesc = setting("seo.{$routeName}.description", $defaultDesc);
    $seoKeywords = setting("seo.{$routeName}.keywords", 'Business System, ERP, COOCA');
    
    // If the view provides its own title/desc via @section (e.g. dynamic blog/product page), use it.
    // Otherwise fallback to SEO Setting, then Default.
@endphp

<title>@yield('title', $seoTitle)</title>
<meta name="description" content="@yield('meta_description', $seoDesc)" />
<meta name="keywords" content="@yield('meta_keywords', $seoKeywords)" />
<link rel="canonical" href="{{ url()->current() }}" />

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:title" content="@yield('title', $seoTitle)" />
<meta property="og:description" content="@yield('meta_description', $seoDesc)" />
<meta property="og:image" content="{{ setting('site.logo') ? asset(setting('site.logo')) : asset('assets/images/og-image.jpg') }}" />

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:url" content="{{ url()->current() }}" />
<meta property="twitter:title" content="@yield('title', $seoTitle)" />
<meta property="twitter:description" content="@yield('meta_description', $seoDesc)" />
<meta property="twitter:image" content="{{ setting('site.logo') ? asset(setting('site.logo')) : asset('assets/images/og-image.jpg') }}" />
