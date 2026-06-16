{{-- Footer - Dynamic from Database --}}
@php
    $footerSettings = setting()->all();
    $footerMenu = \App\Models\Menu::where('location', 'footer')->where('active', true)->orderBy('order')->get();
    $socialLinks = \App\Models\SocialLink::where('active', true)->orderBy('order')->get();
    $logo = setting('branding.logo_footer', setting('branding.logo', null));
    $logoText = setting('branding.name', config('app.name', 'COOCA'));
    $footerDescription = setting('footer.description', 'The Business System That Works Like an Asset. Not Just Software.');
    $copyrightYear = date('Y');
@endphp

<footer class="footer">
    <div class="container">
        <div class="row g-4">
            {{-- Brand Column --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ $logoText }}" style="height: 32px;">
                    @else
                        <div class="footer-brand-logo-icon">{{ substr($logoText, 0, 1) }}</div>
                    @endif
                    {{ $logoText }}
                </div>
                <p class="footer-desc">{{ Str::limit($footerDescription, 150) }}</p>
                
                {{-- Social Links --}}
                @if($socialLinks->count() > 0)
                    <div class="footer-socials">
                        @foreach($socialLinks as $social)
                            <a href="{{ $social->url }}" target="_blank" rel="noopener" aria-label="{{ $social->platform }}">
                                <i class="bi bi-{{ $social->icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                @else
                    {{-- Default social links --}}
                    <div class="footer-socials">
                        <a href="#" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    </div>
                @endif
            </div>
            
            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-links">
                    @forelse($footerMenu->take(5) as $link)
                        <li><a href="{{ $link->url }}">{{ $link->title }}</a></li>
                    @empty
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/pricing') }}">Pricing</a></li>
                        <li><a href="{{ url('/blog') }}">Blog</a></li>
                        <li><a href="{{ url('/contact') }}">Contact</a></li>
                    @endforelse
                </ul>
            </div>
            
            {{-- Products --}}
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Products</h4>
                <ul class="footer-links">
                    @php
                        $products = \App\Models\Product::where('active', true)->orderBy('order')->limit(5)->get();
                    @endphp
                    @forelse($products as $product)
                        <li><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></li>
                    @empty
                        <li><a href="{{ url('/products/restaurant') }}">ERP Restoran</a></li>
                        <li><a href="{{ url('/products/clinic') }}">ERP Klinik</a></li>
                        <li><a href="{{ url('/products/retail') }}">Retail POS</a></li>
                        <li><a href="{{ url('/products/hotel') }}>Hotel Management</a></li>
                        <li><a href="{{ url('/products/workshop') }}>Workshop System</a></li>
                    @endforelse
                </ul>
            </div>
            
            {{-- Contact Info --}}
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Contact</h4>
                <ul class="footer-links">
                    @php
                        $contactEmail = setting('contact.email', 'hello@cooca.io');
                        $contactPhone = setting('contact.phone', '+62 821-1446-8467');
                        $contactAddress = setting('contact.address', 'Indonesia');
                    @endphp
                    <li><i class="bi bi-envelope me-2"></i> {{ $contactEmail }}</li>
                    <li><i class="bi bi-phone me-2"></i> {{ $contactPhone }}</li>
                    <li><i class="bi bi-geo-alt me-2"></i> {{ $contactAddress }}</li>
                </ul>
                
                {{-- Newsletter --}}
                <div class="mt-4">
                    <h5 class="footer-title" style="font-size: 0.75rem;">Subscribe to Newsletter</h5>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="Your email" required style="background: var(--card-alt); border-color: var(--border); color: var(--text);">
                        <button type="submit" class="btn-cooca btn-cooca-primary btn-cooca-sm">
                            <i class="bi bi-send"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- Footer Bottom --}}
        <div class="footer-bottom">
            <p>&copy; {{ $copyrightYear }} {{ $logoText }}. All rights reserved.</p>
            <div class="d-flex gap-3 flex-wrap">
                @php
                    $privacyPolicy = \App\Models\Page::where('slug', 'privacy-policy')->first();
                    $termsOfService = \App\Models\Page::where('slug', 'terms-of-service')->first();
                @endphp
                <a href="{{ $privacyPolicy ? route('pages.show', $privacyPolicy->slug) : url('/privacy-policy') }}" style="font-size: 0.82rem; color: var(--text-muted);">Privacy Policy</a>
                <a href="{{ $termsOfService ? route('pages.show', $termsOfService->slug) : url('/terms-of-service') }}" style="font-size: 0.82rem; color: var(--text-muted);">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer {
    padding: 60px 0 30px;
    border-top: 1px solid var(--border);
    background: var(--card);
}

.footer-brand {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-brand .footer-brand-logo-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.9rem;
    font-weight: 800;
}

.footer-desc {
    font-size: 0.88rem;
    color: var(--text-muted);
    margin-bottom: 20px;
    max-width: 300px;
}

.footer-title {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 16px;
    color: var(--text);
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: var(--text-muted);
    font-size: 0.88rem;
    transition: color var(--transition);
}

.footer-links a:hover {
    color: var(--accent);
}

.footer-bottom {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.footer-bottom p {
    font-size: 0.82rem;
    color: var(--text-muted);
    margin: 0;
}

.footer-socials {
    display: flex;
    gap: 12px;
}

.footer-socials a {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--card-alt);
    color: var(--text-muted);
    border: 1px solid var(--border);
    transition: all var(--transition);
    font-size: 1rem;
}

.footer-socials a:hover {
    color: var(--accent);
    border-color: var(--accent);
    transform: translateY(-2px);
}

@media (max-width: 767.98px) {
    .footer-bottom {
        justify-content: center;
        text-align: center;
        flex-direction: column;
    }
}
</style>
