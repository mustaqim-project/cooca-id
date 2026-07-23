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
        $customer = Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        $customer->companyProfile()->create([
            'company_name' => $data['business_name'] ?? $data['name'],
        ]);

        if (isset($data['referral_code'])) {
            $affiliatorProfile = AffiliatorProfile::where('referral_code', $data['referral_code'])->first();
            if ($affiliatorProfile) {
                // Update referred_by_id in some place (maybe update the model logic if it exists)
                // Note: The original code updated referred_by_id, we just leave it for now if it exists on Customer
            }
        }

        $customer->assignRole('customer');

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

    public function handleGoogleCallback(string $userType)
    {
        $googleUser = Socialite::driver('google')->user();

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
                    'referral_code' => strtoupper(Str::random(10)),
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
            'referral_code' => strtoupper(Str::random(10)),
            'bank_account' => $data['bank_account'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
        ];

        if (isset($data['parent_referral_code'])) {
            $parentProfile = AffiliatorProfile::where('referral_code', $data['parent_referral_code'])->first();
            if ($parentProfile) {
                $profileData['parent_referred_by_id'] = $parentProfile->affiliator_id;
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
