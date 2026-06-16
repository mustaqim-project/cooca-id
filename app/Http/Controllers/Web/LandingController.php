<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use function App\Helpers\setting;
use App\Models\Product;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Redirect;
use App\Services\Auth\AuthService;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Http\Requests\Customer\LoginCustomerRequest;
use App\Http\Requests\Affiliator\RegisterAffiliatorRequest;
use App\Http\Requests\Affiliator\LoginAffiliatorRequest;
use App\Http\Requests\Admin\LoginAdminRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\View\View;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Landing Controller (Unified)
 *
 * Handles public-facing landing pages, blog, product catalog, newsletter, auth, and password resets.
 */
class LandingController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(Request $request)
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->limit(6)
            ->get();

        if ($products->isEmpty()) {
            $products = collect(config('dummy.home.products'));
        }

        $features = [
            [
                'icon' => '⚡',
                'title' => 'Fast Deployment',
                'description' => 'Trial aktif dalam kurang dari 1 jam',
            ],
            [
                'icon' => '🧩',
                'title' => 'Multi ERP System',
                'description' => 'Satu platform untuk banyak solusi bisnis',
            ],
            [
                'icon' => '🔐',
                'title' => 'Secure License System',
                'description' => 'Domain dan token based protection',
            ],
            [
                'icon' => '💸',
                'title' => 'Recurring Affiliate',
                'description' => 'Monetisasi berkelanjutan untuk mitra',
            ],
            [
                'icon' => '📢',
                'title' => 'Integrated Notification',
                'description' => 'Otomatisasi WhatsApp dan Email',
            ],
            [
                'icon' => '🚀',
                'title' => 'Scalable Platform',
                'description' => 'Siap berkembang bersama bisnis Anda',
            ],
        ];

        $testimonials = [
            [
                'name' => 'Ahmad Rizki',
                'business' => 'Restoran Padang Sari Rasa',
                'avatar' => 'AR',
                'rating' => 5,
                'comment' => 'Sistem ERP yang sangat membantu operasional restoran kami. Trial langsung aktif dan tim support sangat responsif.',
            ],
            [
                'name' => 'Dr. Siti Nurhaliza',
                'business' => 'Klinik Sehat Mandiri',
                'avatar' => 'SN',
                'rating' => 5,
                'comment' => 'Management pasien dan rekam medis jadi lebih teratur. Worth it banget untuk klinik kecil seperti kami.',
            ],
            [
                'name' => 'Budi Santoso',
                'business' => 'Bengkel Auto Care',
                'avatar' => 'BS',
                'rating' => 4,
                'comment' => 'Fitur booking dan tracking servis sangat berguna. Customer juga senang bisa monitor progress online.',
            ],
        ];

        return view('pages.home.index', compact('products', 'features', 'testimonials'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        $stats = [
            ['label' => 'Active Customers', 'value' => '500+'],
            ['label' => 'ERP Systems', 'value' => '15+'],
            ['label' => 'Affiliators', 'value' => '200+'],
            ['label' => 'Uptime', 'value' => '99.9%'],
        ];

        $team = [
            [
                'name' => 'John Doe',
                'role' => 'CEO & Founder',
                'avatar' => 'JD',
            ],
            [
                'name' => 'Jane Smith',
                'role' => 'CTO',
                'avatar' => 'JS',
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Head of Product',
                'avatar' => 'MC',
            ],
        ];

        return view('pages.about.index', compact('stats', 'team'));
    }

    /**
     * Display the pricing page.
     */
    public function pricing()
    {
        $products = Product::where('is_active', true)
            ->with(['subscriptionPlans' => function ($query) {
                $query->where('is_active', true)->orderBy('price');
            }])
            ->get();

        $faq = [
            [
                'question' => 'Bagaimana cara memulai trial?',
                'answer' => 'Pilih produk ERP yang diinginkan, klik "Coba Gratis", isi data bisnis Anda, dan sistem akan aktif dalam 30 menit - 1 jam setelah verifikasi admin.',
            ],
            [
                'question' => 'Apakah ada biaya tersembunyi?',
                'answer' => 'Tidak sama sekali. Harga yang tertera adalah harga final. Tidak ada biaya setup, instalasi, atau maintenance tambahan.',
            ],
            [
                'question' => 'Bisakah upgrade/downgrade paket kapan saja?',
                'answer' => 'Ya, Anda dapat upgrade atau downgrade paket subscription kapan saja. Perubahan akan berlaku pada periode billing berikutnya.',
            ],
            [
                'question' => 'Apa yang terjadi setelah trial berakhir?',
                'answer' => 'Setelah trial 7 hari, Anda perlu berlangganan untuk terus menggunakan sistem. Data Anda tetap tersimpan dan dapat diakses segera setelah berlangganan.',
            ],
            [
                'question' => 'Apakah tersedia diskon untuk pembayaran tahunan?',
                'answer' => 'Ya, pembayaran tahunan mendapatkan diskon hingga 20% dibandingkan pembayaran bulanan.',
            ],
        ];

        return view('pages.pricing.index', compact('products', 'faq'));
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        $contactInfo = [
            [
                'type' => 'email',
                'label' => 'Email Support',
                'value' => 'support@cooca.id',
                'icon' => '📧',
            ],
            [
                'type' => 'email',
                'label' => 'Email Sales',
                'value' => 'marketing@cooca.id',
                'icon' => '💼',
            ],
            [
                'type' => 'whatsapp',
                'label' => 'WhatsApp',
                'value' => '+62 812-3456-7890',
                'icon' => '💬',
            ],
            [
                'type' => 'address',
                'label' => 'Office',
                'value' => 'Jakarta, Indonesia',
                'icon' => '📍',
            ],
        ];

        return view('pages.contact.index', compact('contactInfo'));
    }

    /**
     * Display the affiliate program page.
     */
    public function affiliate()
    {
        $benefits = [
            [
                'icon' => '💰',
                'title' => 'Komisi 25%',
                'description' => 'Dapatkan 25% dari setiap pembayaran customer yang Anda referensikan.',
            ],
            [
                'icon' => '🔄',
                'title' => 'Recurring Income',
                'description' => 'Komisi berjalan terus selama customer aktif berlangganan.',
            ],
            [
                'icon' => '👥',
                'title' => 'Multi-Level',
                'description' => 'Dapatkan 5% dari downline level 2 yang Anda bangun.',
            ],
            [
                'icon' => '⚡',
                'title' => 'Withdraw Cepat',
                'description' => 'Penarikan dana otomatis dengan fee minimal.',
            ],
        ];

        $howItWorks = [
            ['step' => 1, 'title' => '@yield(\'title\', (function_exists(\'setting\') ? setting(\'seo.title\', config(\'app.name\', \'COOCA\')) : config(\'app.name\', \'COOCA\'))) - @yield(\'subtitle\', (function_exists(\'setting\') ? setting(\'branding.tagline\', \'The Business System That Works Like an Asset\') : \'The Business System That Works Like an Asset\'))', 'description' => '<meta name="description" content="@yield(\'meta_description\', (function_exists(\'setting\') ? setting(\'seo.description\', \'Stop losing revenue to fragmented systems. COOCA is the integrated business infrastructure that gives you lifetime license protection, modular ERP, and a system that scales with your ambition.\') : \'Stop losing revenue to fragmented systems. COOCA is the integrated business infrastructure that gives you lifetime license protection, modular ERP, and a system that scales with your ambition.\'))"><meta name="keywords" content="@yield(\'meta_keywords\', \'ERP, Business System, SaaS, COOCA, Enterprise Resource Planning\')"><meta name="author" content="@yield(\'meta_author\', (function_exists(\'setting\') ? setting(\'branding.name\', config(\'app.name\')) : config(\'app.name\')))">'],
        ];

        $commissionExample = [
            'monthly' => [
                'customers' => 10,
                'avgPrice' => 500000,
                'commission' => 1250000,
            ],
            'yearly' => [
                'customers' => 10,
                'avgPrice' => 5000000,
                'commission' => 12500000,
            ],
        ];

        return view('pages.affiliate.index', compact('benefits', 'howItWorks', 'commissionExample'));
    }

    /**
     * Display the solutions page.
     */
    public function solution()
    {
        return view('pages.solutions.index');
    }

    /**
     * Display the features page.
     */
    public function features()
    {
        return view('pages.features.index');
    }

    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        return view('pages.faq.index');
    }

    /**
     * Display the documentation page.
     */
    public function docs()
    {
        return view('pages.docs.index');
    }

    /**
     * Display the Terms of Service page.
     */
    public function terms()
    {
        return view('pages.legal.terms');
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacy()
    {
        return view('pages.legal.privacy');
    }

    /**
     * Display the products catalog page.
     */
    public function products()
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->get();

        return view('pages.products.index', compact('products'));
    }

    /* ========================================== */

    /**
     * Display a listing of blog posts.
     */
    public function blogIndex(Request $request)
    {
        $posts = BlogPost::where('is_published', true)
            ->with(['author'])
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogPost::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('is_published', true)
            ->pluck('category');

        $featuredPosts = BlogPost::where('is_published', true)
            ->where('is_featured', true)
            ->with(['author'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    /**
     * Display the specified blog post.
     */
    public function blogShow(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->with(['author'])
            ->firstOrFail();

        // Increment view count
        $post->increment('view_count');

        // Get related posts
        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category', $post->category)
                    ->orWhereJsonContains('tags', $post->tags);
            })
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /* ========================================== */

    /**
     * Show a single product.
     *
     * URL: /products/{slug}
     */
    public function productShow(string $slug, Request $request)
    {
        // Load product with related data (category & subscription plans)
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->firstOrFail();

        // Optional view counter – you can add a column `views` to the products table if desired.
        if (method_exists($product, 'increment')) {
            $product->increment('views');
        }

        $canonical = url()->current();

        return view('products.show', compact('product', 'canonical'));
    }

    /* ========================================== */

    /**
     * Handle newsletter subscription form submission.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:newsletter_subscribers,email'],
        ]);

        NewsletterSubscriber::create([
            'email' => $validated['email'],
        ]);

        return Redirect::back()->with('status', 'Terima kasih! Anda telah berlangganan newsletter.');
    }

    /* ========================================== */

    public function __construct(
        private readonly AuthService $authService
    ) {}

    /* ==================== CUSTOMER AUTH ==================== */

    public function customerRegister(RegisterCustomerRequest $request): RedirectResponse
    {
        $customer = $this->authService->registerCustomer($request->validated());

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'))
            ->with('success', 'Registrasi berhasil! Selamat datang di Cooca.id');
    }

    public function customerLogin(LoginCustomerRequest $request): RedirectResponse
    {
        if (!Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'));
    }

    public function customerLogout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }

    public function redirectToGoogleCustomer(): RedirectResponse
    {
        return Socialite::guard('customer')->redirect();
    }

    public function handleGoogleCallbackCustomer(): RedirectResponse
    {
        try {
            $customer = $this->authService->handleGoogleCallback('customer');
            Auth::guard('customer')->login($customer);
            
            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Login dengan Google berhasil!');
        } catch (\Exception $e) {
            return redirect()->route('customer.login')
                ->withErrors(['email' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }
    }

    /* ==================== AFFILIATOR AUTH ==================== */

    public function affiliatorRegister(RegisterAffiliatorRequest $request): RedirectResponse
    {
        $affiliator = $this->authService->registerAffiliator($request->validated());

        Auth::guard('affiliator')->login($affiliator);
        $request->session()->regenerate();

        return redirect()->intended(route('affiliator.dashboard'))
            ->with('success', 'Registrasi affiliator berhasil!');
    }

    public function affiliatorLogin(LoginAffiliatorRequest $request): RedirectResponse
    {
        if (!Auth::guard('affiliator')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('affiliator.dashboard'));
    }

    public function affiliatorLogout(Request $request): RedirectResponse
    {
        Auth::guard('affiliator')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }

    /* ==================== ADMIN AUTH ==================== */

    public function adminLogin(LoginAdminRequest $request): RedirectResponse
    {
        if (!Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Admin telah logout.');
    }


    public function showAdminLogin(): View
    {
        return view('auth.admin.login');
    }

    public function showCustomerLogin(): View
    {
        return view('auth.customer.login');
    }

    public function showCustomerRegister(): View
    {
        return view('auth.customer.register');
    }

    public function showAffiliatorLogin(): View
    {
        return view('auth.affiliator.login');
    }

    public function showAffiliatorRegister(): View
    {
        return view('auth.affiliator.register');
    }

    /* ========================================== */

    /**
     * Display the password reset link request view for customers.
     */
    public function showCustomerForgotPassword(): View
    {
        return view('auth.customer.forgot-password');
    }

    /**
     * Handle sending password reset email for customers.
     */
    public function sendCustomerResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem kami.',
        ]);

        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }

    /**
     * Display the password reset view for customers.
     */
    public function showCustomerReset(Request $request, string $token): View
    {
        return view('auth.customer.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Handle password reset for customers.
     */
    public function resetCustomerPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
            'email.exists' => 'Email tidak terdaftar.',
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Customer $customer, string $password) {
                $customer->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.')
            : back()->withErrors(['email' => ['Gagal mereset password. Link mungkin sudah kadaluarsa.']]);
    }

    /**
     * Display the password reset link request view for affiliators.
     */
    public function showAffiliatorForgotPassword(): View
    {
        return view('auth.affiliator.forgot-password');
    }

    /**
     * Handle sending password reset email for affiliators.
     */
    public function sendAffiliatorResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:affiliators,email',
        ], [
            'email.exists' => 'Email affiliator tidak terdaftar.',
        ]);

        $status = Password::broker('affiliators')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }

    /**
     * Display the password reset view for affiliators.
     */
    public function showAffiliatorReset(Request $request, string $token): View
    {
        return view('auth.affiliator.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Handle password reset for affiliators.
     */
    public function resetAffiliatorPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:affiliators,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
            'email.exists' => 'Email affiliator tidak terdaftar.',
        ]);

        $status = Password::broker('affiliators')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Affiliator $affiliator, string $password) {
                $affiliator->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('affiliator.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.')
            : back()->withErrors(['email' => ['Gagal mereset password. Link mungkin sudah kadaluarsa.']]);
    }

    /**
     * Display the password reset link request view for admins.
     */
    public function showAdminForgotPassword(): View
    {
        return view('auth.admin.forgot-password');
    }

    /**
     * Handle sending password reset email for admins.
     */
    public function sendAdminResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ], [
            'email.exists' => 'Email admin tidak terdaftar.',
        ]);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }

    /**
     * Display the password reset view for admins.
     */
    public function showAdminReset(Request $request, string $token): View
    {
        return view('auth.admin.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Handle password reset for admins.
     */
    public function resetAdminPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
            'email.exists' => 'Email admin tidak terdaftar.',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, string $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.')
            : back()->withErrors(['email' => ['Gagal mereset password. Link mungkin sudah kadaluarsa.']]);
    }
}