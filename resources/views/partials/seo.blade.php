<title>@yield('title', $page->title ?? ($setting->company_name ?? config('app.name', 'COOCA')))</title>
<meta name="description" content="@yield('meta_description', $page->meta_description ?? ($setting->meta_description ?? 'COOCA Business System'))" />
<meta name="keywords" content="@yield('meta_keywords', $page->meta_keywords ?? ($setting->meta_keywords ?? 'Business System, ERP'))" />
<link rel="canonical" href="{{ url()->current() }}" />

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:title" content="@yield('title', $page->title ?? ($setting->company_name ?? 'COOCA'))" />
<meta property="og:description" content="@yield('meta_description', $page->meta_description ?? ($setting->meta_description ?? 'COOCA Business System'))" />
<meta property="og:image" content="{{ asset('assets/images/og-image.jpg') }}" />

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:url" content="{{ url()->current() }}" />
<meta property="twitter:title" content="@yield('title', $page->title ?? ($setting->company_name ?? 'COOCA'))" />
<meta property="twitter:description" content="@yield('meta_description', $page->meta_description ?? ($setting->meta_description ?? 'COOCA Business System'))" />
<meta property="twitter:image" content="{{ asset('assets/images/og-image.jpg') }}" />
