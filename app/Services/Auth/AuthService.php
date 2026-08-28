<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\CompanyProfile;
use App\Models\AffiliatorProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

final class AuthService
{
    public function registerAdmin(array $data): Admin
    {
        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        if (isset($data['permissions'])) {
            $admin->syncPermissions($data['permissions']);
        }
        
        return $admin;
    }

    public function loginAdmin(array $data): string
    {
        if (!Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials or not an admin'],
            ]);
        }

        return Auth::guard('admin')->user()->createToken('admin-token')->plainTextToken;
    }

    public function registerCustomer(array $data): Customer
    {
        $logoPath = $data['logo_path'] ?? null;

        $phoneVerifiedAt = null;
        if (! (bool) \App\Models\Setting::get('whatsapp.notifications_active', true)) {
            $phoneVerifiedAt = now();
        }

        $customer = Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'business_name' => $data['business_name'] ?? null,
            'logo_path' => $logoPath,
            'phone_verified_at' => $phoneVerifiedAt,
        ]);
        
        $customer->companyProfile()->create([
            'company_name' => $data['business_name'] ?? $data['name'],
            'logo_path' => $logoPath,
        ]);

        if (!empty($data['referral_code'])) {
            $affiliator = Affiliator::where('referral_code', $data['referral_code'])->first();
            if (!$affiliator) {
                $affiliatorProfile = AffiliatorProfile::where('referral_code', $data['referral_code'])->first();
                if ($affiliatorProfile) {
                    $affiliator = $affiliatorProfile->affiliator ?? Affiliator::find($affiliatorProfile->affiliator_id);
                }
            }

            if ($affiliator) {
                $customer->update(['affiliator_id' => $affiliator->id]);
            }
        }

        $customer->assignRole('customer');

        // Generate and send WA OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $customer->update([
            'wa_otp_code' => $otp,
            'wa_otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            app(\App\Services\Notification\WhatsAppService::class)->sendMessage(
                $customer->phone, 
                "Halo {$customer->name}, kode verifikasi Cooca.id Anda adalah: *{$otp}*\n\nKode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun."
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send WA OTP', ['error' => $e->getMessage()]);
        }

        // Send Email Verification
        $customer->sendEmailVerificationNotification();

        return $customer;
    }

    public function loginCustomer(array $data): string
    {
        if (!Auth::guard('customer')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        return Auth::guard('customer')->user()->createToken('customer-token')->plainTextToken;
    }

    public function handleGoogleCallback(string $userType, $googleUser = null)
    {
        $googleUser = $googleUser ?? Socialite::driver('google')->user();

        if ($userType === 'customer') {
            $user = Customer::where('google_id', $googleUser->getId())->orWhere('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = Customer::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(bin2hex(random_bytes(16))),
                    'email_verified_at' => now(),
                ]);

                $user->companyProfile()->create([
                    'company_name' => $user->name,
                ]);
            }
            Auth::guard('customer')->login($user);
            return $user;

        } elseif ($userType === 'affiliator') {
            $user = Affiliator::where('google_id', $googleUser->getId())->orWhere('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = Affiliator::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(bin2hex(random_bytes(16))),
                    'email_verified_at' => now(),
                ]);

                $user->profile()->create([
                    'referral_code' => $user->referral_code ?? strtoupper(Str::random(8)),
                ]);
            }
            Auth::guard('affiliator')->login($user);
            return $user;
        }

        throw new \Exception('Invalid user type for Google Login');
    }

    public function registerAffiliator(array $data): Affiliator
    {
        $affiliator = Affiliator::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        $profileData = [
            'referral_code' => $affiliator->referral_code ?? strtoupper(Str::random(8)),
            'bank_account' => $data['bank_account'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
        ];

        if (isset($data['parent_referral_code'])) {
            $parent = Affiliator::where('referral_code', $data['parent_referral_code'])->first();
            if (!$parent) {
                $parentProfile = AffiliatorProfile::where('referral_code', $data['parent_referral_code'])->first();
                if ($parentProfile) {
                    $parent = $parentProfile->affiliator;
                }
            }
            if ($parent) {
                $affiliator->parent_affiliator_id = $parent->id;
                $affiliator->save();

                if ($parent->profile) {
                    $profileData['parent_referred_by_id'] = $parent->profile->id;
                }
            }
        }
        
        $affiliator->profile()->create($profileData);

        $affiliator->assignRole('affiliator');

        return $affiliator;
    }

    public function loginAffiliator(array $data): string
    {
        if (!Auth::guard('affiliator')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        return Auth::guard('affiliator')->user()->createToken('affiliator-token')->plainTextToken;
    }
}
