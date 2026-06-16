<footer class="footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand">
          <div class="logo-icon">C</div>
          {{ $setting->company_name ?? 'COOCA' }}
        </div>
        <p class="footer-desc">
          {{ $setting->footer_description ?? 'The business system that works like an asset. Lifetime license, modular ERP, and complete digital infrastructure for serious businesses.' }}
        </p>
        <div class="footer-socials">
          <a href="{{ $setting->social_twitter ?? '#' }}" aria-label="Twitter"
            ><i class="bi bi-twitter-x"></i
          ></a>
          <a href="{{ $setting->social_linkedin ?? '#' }}" aria-label="LinkedIn"
            ><i class="bi bi-linkedin"></i
          ></a>
          <a href="{{ $setting->social_github ?? '#' }}" aria-label="GitHub"><i class="bi bi-github"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">Solutions</div>
        <ul class="footer-links">
          <li><a href="{{ route('solutions') }}#retail">Retail</a></li>
          <li><a href="{{ route('solutions') }}#klinik">Klinik</a></li>
          <li><a href="{{ route('solutions') }}#hotel">Hotel</a></li>
          <li><a href="{{ route('solutions') }}#education">Education</a></li>
          <li><a href="{{ route('solutions') }}#restaurant">Restaurant</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">Company</div>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}">About</a></li>
          <li><a href="{{ route('about') }}#careers">Careers</a></li>
          <li><a href="{{ route('blog.index') }}">Blog</a></li>
          <li><a href="{{ route('affiliate') }}">Partners</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">Resources</div>
        <ul class="footer-links">
          <li><a href="{{ route('docs') }}">Documentation</a></li>
          <li><a href="{{ route('docs') }}#api">API Reference</a></li>
          <li><a href="{{ route('contact') }}">Status</a></li>
          <li><a href="{{ route('blog.index') }}">Community</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">Support</div>
        <ul class="footer-links">
          <li><a href="{{ route('faq') }}">Help Center</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
          <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
          <li><a href="{{ route('terms') }}">Terms</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; {{ date('Y') }} {{ $setting->company_name ?? 'COOCA' }}. All rights reserved.</p>
      <p>Enterprise Business Infrastructure — Built for Ownership.</p>
    </div>
  </div>
</footer>
