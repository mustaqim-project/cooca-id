<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;

final class AuthController extends Controller
{
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
}
