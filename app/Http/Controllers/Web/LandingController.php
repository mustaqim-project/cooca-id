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
            ->ordered()
            ->get();
        return view('pages.home.index', compact('products'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('pages.about.index');
    }

    /**
     * Display the pricing page.
     */
    public function pricing()
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'subscriptionPlans' => function ($query) {
                $query->where('is_active', true)->orderBy('price');
            }])
            ->get();
        return view('pages.pricing.index', compact('products'));
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('pages.contact.index');
    }

    /**
     * Display the affiliate program page.
     */
    public function affiliate()
    {
        return view('pages.affiliate.index');
    }

    /**
     * Display the solutions page.
     */
    public function solution()
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'subscriptionPlans' => function ($query) {
                $query->where('is_active', true)->orderBy('price');
            }])
            ->get();
        return view('pages.solutions.index', compact('products'));
    }

    /**
     * Switch session language/locale.
     */
    public function switchLang(string $locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session()->put('locale', $locale);
        }
        return Redirect::back();
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
            ->with(['author'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.blog.index', compact('posts', 'categories', 'featuredPosts'));
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
        $post->incrementViews();

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

        return view('pages.blog.detail', compact('post', 'relatedPosts'));
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

        return view('pages.products.detail', compact('product', 'canonical'));
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

    public function showCustomerVerificationNotice(Request $request)
    {
        return $request->user('customer')->hasVerifiedEmail()
            ? redirect()->intended(route('customer.dashboard'))
            : view('auth.customer.verify-email');
    }

    public function verifyCustomerEmail(Request $request, $id, $hash)
    {
        $customer = Customer::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($customer->getEmailForVerification()))) {
            abort(403);
        }

        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }

        if ($customer->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($customer));
        }

        return redirect()->intended(route('customer.dashboard'))
            ->with('success', 'Email berhasil diverifikasi.');
    }

    public function resendCustomerVerificationEmail(Request $request)
    {
        if ($request->user('customer')->hasVerifiedEmail()) {
            return redirect()->intended(route('customer.dashboard'));
        }

        $request->user('customer')->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda.');
    }

    public function customerRegister(RegisterCustomerRequest $request)
    {
        $customer = $this->authService->registerCustomer($request->validated());

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'))
            ->with('success', 'Registrasi berhasil! Selamat datang di Cooca.id');
    }

    public function customerLogin(LoginCustomerRequest $request)
    {
        if (!Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'));
    }

    public function customerLogout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }

    public function redirectToGoogleCustomer()
    {
        return Socialite::guard('customer')->redirect();
    }

    public function handleGoogleCallbackCustomer()
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

    public function affiliatorRegister(RegisterAffiliatorRequest $request)
    {
        $affiliator = $this->authService->registerAffiliator($request->validated());

        Auth::guard('affiliator')->login($affiliator);
        $request->session()->regenerate();

        return redirect()->intended(route('affiliator.dashboard'))
            ->with('success', 'Registrasi affiliator berhasil!');
    }

    public function affiliatorLogin(LoginAffiliatorRequest $request)
    {
        if (!Auth::guard('affiliator')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('affiliator.dashboard'));
    }

    public function affiliatorLogout(Request $request)
    {
        Auth::guard('affiliator')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }

    /* ==================== ADMIN AUTH ==================== */

    public function adminLogin(LoginAdminRequest $request)
    {
        if (!Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Admin telah logout.');
    }


    public function showAdminLogin()
    {
        return view('auth.admin.login');
    }

    public function showCustomerLogin()
    {
        return view('auth.customer.login');
    }

    public function showCustomerRegister()
    {
        return view('auth.customer.register');
    }

    public function showAffiliatorLogin()
    {
        return view('auth.affiliator.login');
    }

    public function showAffiliatorRegister()
    {
        return view('auth.affiliator.register');
    }

    /* ========================================== */

    /**
     * Display the password reset link request view for customers.
     */
    public function showCustomerForgotPassword()
    {
        return view('auth.customer.forgot-password');
    }

    /**
     * Handle sending password reset email for customers.
     */
    public function sendCustomerResetLink(Request $request)
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
    public function showCustomerReset(Request $request, string $token)
    {
        return view('auth.customer.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Handle password reset for customers.
     */
    public function resetCustomerPassword(Request $request)
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
    public function showAffiliatorForgotPassword()
    {
        return view('auth.affiliator.forgot-password');
    }

    /**
     * Handle sending password reset email for affiliators.
     */
    public function sendAffiliatorResetLink(Request $request)
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
    public function showAffiliatorReset(Request $request, string $token)
    {
        return view('auth.affiliator.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Handle password reset for affiliators.
     */
    public function resetAffiliatorPassword(Request $request)
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
    public function showAdminForgotPassword()
    {
        return view('auth.admin.forgot-password');
    }

    /**
     * Handle sending password reset email for admins.
     */
    public function sendAdminResetLink(Request $request)
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
    public function showAdminReset(Request $request, string $token)
    {
        return view('auth.admin.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Handle password reset for admins.
     */
    public function resetAdminPassword(Request $request)
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
