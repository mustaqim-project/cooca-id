<!-- PAGE LOADER -->
<div class="page-loader" id="pageLoader">
  <div class="loader-logo">
    <div class="logo-icon-large">C</div>
    <div class="logo-text">{{ $setting->company_name ?? 'COOCA' }}</div>
  </div>
</div>

<!-- FLOATING WHATSAPP -->
<a
  href="{{ $setting->whatsapp_link ?? 'https://wa.me/6281234567890' }}"
  class="whatsapp-float"
  target="_blank"
  rel="noopener"
  aria-label="Chat on WhatsApp"
>
  <span class="pulse-ring"></span>
  <i class="bi bi-whatsapp"></i>
</a>

@include('partials.navbar')
@include('partials.mobile-menu')
