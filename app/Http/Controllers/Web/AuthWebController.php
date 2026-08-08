<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginAdminRequest;
use App\Http\Requests\Affiliator\LoginAffiliatorRequest;
use App\Http\Requests\Affiliator\RegisterAffiliatorRequest;
use App\Http\Requests\Customer\LoginCustomerRequest;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * Auth Web Controller
 *
 * Handles Web authentication for Customer, Affiliator, and Admin users,
 * including OAuth (Google Socialite), email verification, and password resets.
 */
class AuthWebController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /* ==================== CUSTOMER AUTH ==================== */

    public function showCustomerLogin()
    {
        return view('public.auth.customer.login');
    }

    public function showCustomerRegister()
    {
        return view('public.auth.customer.register');
    }

    public function customerLogin(LoginCustomerRequest $request): RedirectResponse
    {
        if (!\App\Helpers\CaptchaHelper::verify($request->input('captcha'))) {
            return back()
                ->withErrors(['captcha' => 'Jawaban Captcha tidak valid atau telah kadaluwarsa. Silakan coba lagi.'])
                ->withInput($request->only('email'));
        }

        if (!Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'));
    }

    public function customerRegister(RegisterCustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/logos');
            File::ensureDirectoryExists($uploadPath);
            $file->move($uploadPath, $filename);
            $data['logo_path'] = '/uploads/logos/' . $filename;
        }

        $customer = $this->authService->registerCustomer($data);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'))
            ->with('success', 'Registrasi berhasil! Selamat datang di Cooca.id');
    }

    public function customerLogout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }

    public function showCustomerVerificationNotice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('customer.dashboard'))
            : view('public.auth.customer.verify-email');
    }

    public function verifyCustomerEmail(Request $request, $id, $hash)
    {
        $customer = \App\Models\Customer::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($customer->getEmailForVerification()))) {
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
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('customer.dashboard'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda.');
    }

    /* ==================== GOOGLE OAUTH ==================== */

    public function redirectToGoogleCustomer()
    {
        return Socialite::driver('google')->redirect();
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

    public function redirectToGoogleAffiliator()
    {
        return Socialite::driver('google')
            ->redirectUrl(route('affiliator.auth.google.callback', [], true))
            ->redirect();
    }

    public function handleGoogleCallbackAffiliator()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('affiliator.auth.google.callback', [], true))
                ->user();

            $affiliator = $this->authService->handleGoogleCallback('affiliator', $googleUser);
            Auth::guard('affiliator')->login($affiliator);

            return redirect()->intended(route('affiliator.dashboard'))
                ->with('success', 'Login dengan Google berhasil!');
        } catch (\Exception $e) {
            return redirect()->route('affiliator.login')
                ->withErrors(['email' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }
    }

    /* ==================== AFFILIATOR AUTH ==================== */

    public function showAffiliatorLogin()
    {
        return view('public.auth.affiliator.login');
    }

    public function showAffiliatorRegister()
    {
        return view('public.auth.affiliator.register');
    }

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
        if (!\App\Helpers\CaptchaHelper::verify($request->input('captcha'))) {
            return back()
                ->withErrors(['captcha' => 'Jawaban Captcha tidak valid atau telah kadaluwarsa. Silakan coba lagi.'])
                ->withInput($request->only('email'));
        }

        if (!Auth::guard('affiliator')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Kredensial tidak valid.'])
                ->withInput($request->only('email'));
        }

        $affiliator = Auth::guard('affiliator')->user();
        if ($affiliator && $affiliator->status === 'suspended') {
            Auth::guard('affiliator')->logout();
            return back()
                ->withErrors(['email' => 'Akun Anda telah ditangguhkan. Hubungi admin untuk informasi lebih lanjut.'])
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

    public function showAdminLogin()
    {
        return view('public.auth.admin.login');
    }

    public function adminLogin(LoginAdminRequest $request): RedirectResponse
    {
        if (!\App\Helpers\CaptchaHelper::verify($request->input('captcha'))) {
            return back()
                ->withErrors(['captcha' => 'Jawaban Captcha tidak valid atau telah kadaluwarsa. Silakan coba lagi.'])
                ->withInput($request->only('email'));
        }

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

    /* ==================== PASSWORD RESETS ==================== */

    public function showCustomerForgotPassword()
    {
        return view('public.auth.customer.forgot-password');
    }

    public function sendCustomerResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem kami.',
        ]);

        $status = Password::broker()->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }

    public function showCustomerReset(Request $request, string $token)
    {
        return view('public.auth.customer.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

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

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.')
            : back()->withErrors(['email' => ['Gagal mereset password. Link mungkin sudah kadaluarsa.']]);
    }

    public function showAffiliatorForgotPassword()
    {
        return view('public.auth.affiliator.forgot-password');
    }

    public function sendAffiliatorResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:affiliators,email',
        ], [
            'email.exists' => 'Email affiliator tidak terdaftar.',
        ]);

        $status = Password::broker()->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }

    public function showAffiliatorReset(Request $request, string $token)
    {
        return view('public.auth.affiliator.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

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

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('affiliator.login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.')
            : back()->withErrors(['email' => ['Gagal mereset password. Link mungkin sudah kadaluarsa.']]);
    }

    public function showAdminForgotPassword()
    {
        return view('public.auth.admin.forgot-password');
    }

    public function sendAdminResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ], [
            'email.exists' => 'Email admin tidak terdaftar.',
        ]);

        $status = Password::broker()->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }

    public function showAdminReset(Request $request, string $token)
    {
        return view('public.auth.admin.reset-password', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

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

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
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
