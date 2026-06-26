<footer class="footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand">
          @if(setting('site.logo'))
              <img src="{{ setting('site.logo') }}" alt="{{ setting('site.name','COOCA') }}" style="height:28px;object-fit:contain;" />
          @else
              <div class="logo-icon">C</div>
              {{ setting('site.name', 'COOCA') }}
          @endif
        </div>
        <p class="footer-desc">{{ __(setting('footer.description', 'The business system that works like an asset. Lifetime license, modular ERP, and complete digital infrastructure for serious businesses.')) }}</p>
        <div class="footer-socials">
          @if(setting('social.twitter'))
          <a href="{{ setting('social.twitter','#') }}" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
          @endif
          @if(setting('social.linkedin'))
          <a href="{{ setting('social.linkedin','#') }}" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          @endif
          @if(setting('social.github'))
          <a href="{{ setting('social.github','#') }}" aria-label="GitHub"><i class="bi bi-github"></i></a>
          @endif
          @if(setting('social.instagram'))
          <a href="{{ setting('social.instagram','#') }}" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          @endif
        </div>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __(setting('footer.col1_title', 'Solutions')) }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('solutions') }}">{{ __('Retail') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('Klinik') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('Hotel') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('Education') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('Restaurant') }}</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __(setting('footer.col2_title', 'Company')) }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}">{{ __('About') }}</a></li>
          <li><a href="{{ route('blog.index') }}">{{ __('Blog') }}</a></li>
          <li><a href="{{ route('affiliate') }}">{{ __('Partners') }}</a></li>
          <li><a href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __(setting('footer.col3_title', 'Resources')) }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('docs') }}">{{ __('Documentation') }}</a></li>
          <li><a href="{{ route('faq') }}">{{ __('FAQ') }}</a></li>
          <li><a href="{{ route('pricing') }}">{{ __('Pricing') }}</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __(setting('footer.col4_title', 'Legal')) }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('terms') }}">{{ __('Terms of Service') }}</a></li>
          <li><a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a></li>
          <li><a href="{{ route('contact') }}">{{ __('Help Center') }}</a></li>
        </ul>
        <!-- Newsletter -->
        <div class="mt-3">
          <div class="footer-title" style="font-size:0.8rem;">{{ __('Newsletter') }}</div>
          <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex gap-1 mt-1">
            @csrf
            <input type="email" name="email" class="form-control form-control-sm" placeholder="your@email.com"
              style="background:var(--card);border-color:var(--border);color:var(--text);border-radius:8px;font-size:0.8rem;" required>
            <button type="submit" class="btn-cooca btn-cooca-primary btn-cooca-sm" style="white-space:nowrap;border-radius:8px;padding:6px 10px;font-size:0.75rem;">
              <i class="bi bi-send-fill"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; {{ date('Y') }} {{ setting('site.name','COOCA') }}. {{ __(setting('footer.copyright_text','All rights reserved.')) }}</p>
      <p>{{ __(setting('footer.tagline', 'Enterprise Business Infrastructure — Built for Ownership.')) }}</p>
    </div>
  </div>
</footer>
