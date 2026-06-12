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
use Inertia\Inertia;
use Inertia\Response;

final class PasswordResetController extends Controller
{
    /**
     * Display the password reset link request view for customers.
     */
    public function showCustomerForgotPassword(): Response
    {
        return Inertia::render('Auth/Customer/ForgotPassword');
    }

    /**
     * Handle sending password reset email for customers.
     */
    public function sendCustomerResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset view for customers.
     */
    public function showCustomerReset(Request $request): Response
    {
        return Inertia::render('Auth/Customer/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle password reset for customers.
     */
    public function resetCustomerPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
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
            ? redirect()->route('customer.login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Display the password reset link request view for affiliators.
     */
    public function showAffiliatorForgotPassword(): Response
    {
        return Inertia::render('Auth/Affiliator/ForgotPassword');
    }

    /**
     * Handle sending password reset email for affiliators.
     */
    public function sendAffiliatorResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('affiliators')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset view for affiliators.
     */
    public function showAffiliatorReset(Request $request): Response
    {
        return Inertia::render('Auth/Affiliator/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle password reset for affiliators.
     */
    public function resetAffiliatorPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
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
            ? redirect()->route('affiliator.login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Display the password reset link request view for admins.
     */
    public function showAdminForgotPassword(): Response
    {
        return Inertia::render('Auth/Admin/ForgotPassword');
    }

    /**
     * Handle sending password reset email for admins.
     */
    public function sendAdminResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset view for admins.
     */
    public function showAdminReset(Request $request): Response
    {
        return Inertia::render('Auth/Admin/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle password reset for admins.
     */
    public function resetAdminPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
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
            ? redirect()->route('admin.login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
