<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
     * Login admin
     */
    public function loginAdmin(string $email, string $password): Admin
    {
        if (!Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        return $admin;
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
     * Login customer
     */
    public function loginCustomer(string $email, string $password): Customer
    {
        if (!Auth::guard('customer')->attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        return $customer;
    }

    /**
     * Login customer with Google
     */
    public function loginCustomerWithGoogle(array $googleUser): Customer
    {
        $customer = Customer::where('google_id', $googleUser['id'])->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['id'],
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
            'referral_code' => strtoupper(substr(uniqid(), -8)),
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
     * Login affiliator
     */
    public function loginAffiliator(string $email, string $password): Affiliator
    {
        if (!Auth::guard('affiliator')->attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        /** @var Affiliator $affiliator */
        $affiliator = Auth::guard('affiliator')->user();

        return $affiliator;
    }

    /**
     * Login affiliator with Google
     */
    public function loginAffiliatorWithGoogle(array $googleUser): Affiliator
    {
        $affiliator = Affiliator::where('google_id', $googleUser['id'])->first();

        if (!$affiliator) {
            $affiliator = Affiliator::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['id'],
                'password' => Hash::make(bin2hex(random_bytes(16))),
                'referral_code' => strtoupper(substr(uniqid(), -8)),
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
