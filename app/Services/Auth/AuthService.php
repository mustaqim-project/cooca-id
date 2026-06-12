<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

final class AuthService
{
    /**
     * Register a new admin
     */
    public function registerAdmin(array $data): Admin
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'permissions' => $data['permissions'] ?? [],
        ]);
    }

    /**
     * Login admin and return Sanctum token
     */
    public function loginAdmin(array $data): string
    {
        if (!Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        return $admin->createToken('admin-token')->plainTextToken;
    }

    /**
     * Logout admin
     */
    public function logoutAdmin(): void
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        $admin->currentAccessToken()->delete();
        Auth::guard('admin')->logout();
    }

    /**
     * Register a new customer
     */
    public function registerCustomer(array $data): Customer
    {
        $customerData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'business_name' => $data['business_name'] ?? null,
        ];

        // Link to affiliator if referral code provided
        if (isset($data['referral_code'])) {
            $affiliator = Affiliator::where('referral_code', $data['referral_code'])->first();
            if ($affiliator) {
                $customerData['affiliator_id'] = $affiliator->id;
            }
        }

        return Customer::create($customerData);
    }

    /**
     * Login customer and return Sanctum token
     */
    public function loginCustomer(array $data): string
    {
        if (!Auth::guard('customer')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        return $customer->createToken('customer-token')->plainTextToken;
    }

    /**
     * Logout customer
     */
    public function logoutCustomer(): void
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();
        $customer->currentAccessToken()->delete();
        Auth::guard('customer')->logout();
    }

    /**
     * Handle Google callback for customer or affiliator
     */
    public function handleGoogleCallback(string $guard): Customer|Affiliator
    {
        $googleUser = Socialite::guard($guard)->user();

        if ($guard === 'customer') {
            return $this->loginCustomerWithGoogle($googleUser);
        }

        return $this->loginAffiliatorWithGoogle($googleUser);
    }

    /**
     * Login customer with Google
     */
    private function loginCustomerWithGoogle(object $googleUser): Customer
    {
        $customer = Customer::where('google_id', $googleUser->getId())->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(bin2hex(random_bytes(16))),
            ]);
        }

        Auth::guard('customer')->login($customer);

        return $customer;
    }

    /**
     * Register a new affiliator
     */
    public function registerAffiliator(array $data): Affiliator
    {
        $affiliatorData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'bank_account' => $data['bank_account'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'referral_code' => strtoupper(\Illuminate\Support\Str::random(10)),
        ];

        // Link to parent affiliator if provided
        if (isset($data['parent_referral_code'])) {
            $parentAffiliator = Affiliator::where('referral_code', $data['parent_referral_code'])->first();
            if ($parentAffiliator) {
                $affiliatorData['parent_affiliator_id'] = $parentAffiliator->id;
            }
        }

        return Affiliator::create($affiliatorData);
    }

    /**
     * Login affiliator and return Sanctum token
     */
    public function loginAffiliator(array $data): string
    {
        if (!Auth::guard('affiliator')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        /** @var Affiliator $affiliator */
        $affiliator = Auth::guard('affiliator')->user();

        return $affiliator->createToken('affiliator-token')->plainTextToken;
    }

    /**
     * Logout affiliator
     */
    public function logoutAffiliator(): void
    {
        /** @var Affiliator $affiliator */
        $affiliator = Auth::guard('affiliator')->user();
        $affiliator->currentAccessToken()->delete();
        Auth::guard('affiliator')->logout();
    }

    /**
     * Login affiliator with Google
     */
    private function loginAffiliatorWithGoogle(object $googleUser): Affiliator
    {
        $affiliator = Affiliator::where('google_id', $googleUser->getId())->first();

        if (!$affiliator) {
            $affiliator = Affiliator::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(bin2hex(random_bytes(16))),
                'referral_code' => strtoupper(\Illuminate\Support\Str::random(10)),
            ]);
        }

        Auth::guard('affiliator')->login($affiliator);

        return $affiliator;
    }

    /**
     * Logout current user from specified guard
     */
    public function logout(string $guard): void
    {
        Auth::guard($guard)->logout();
        session()->forget("{$guard}_session");
    }

    /**
     * Get current authenticated user for guard
     */
    public function getCurrentUser(string $guard): Admin|Customer|Affiliator|null
    {
        return Auth::guard($guard)->user();
    }
}
