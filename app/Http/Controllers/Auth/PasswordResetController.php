<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Password Reset Controller
 * 
 * Handles password reset functionality for Admin, Customer, and Affiliator guards.
 * All methods use Blade views as per web route architecture.
 */
final class PasswordResetController extends Controller
{
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
