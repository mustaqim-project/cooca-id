<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Http\Requests\Customer\LoginCustomerRequest;
use App\Http\Requests\Affiliator\RegisterAffiliatorRequest;
use App\Http\Requests\Affiliator\LoginAffiliatorRequest;
use App\Http\Requests\Admin\LoginAdminRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

final class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /* ==================== CUSTOMER AUTH ==================== */

    public function customerRegister(RegisterCustomerRequest $request): JsonResponse
    {
        $customer = $this->authService->registerCustomer($request->validated());

        return response()->json([
            'message' => 'Registration successful',
            'customer' => $customer,
        ], 201);
    }

    public function customerLogin(LoginCustomerRequest $request): JsonResponse
    {
        $token = $this->authService->loginCustomer($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
        ]);
    }

    public function customerLogout(): JsonResponse
    {
        $this->authService->logoutCustomer();

        return response()->json(['message' => 'Logout successful']);
    }

    public function redirectToGoogleCustomer(): RedirectResponse
    {
        return Socialite::guard('customer')->redirect();
    }

    public function handleGoogleCallbackCustomer(): JsonResponse
    {
        $customer = $this->authService->handleGoogleCallback('customer');

        return response()->json([
            'message' => 'Google login successful',
            'customer' => $customer,
        ]);
    }

    /* ==================== AFFILIATOR AUTH ==================== */

    public function affiliatorRegister(RegisterAffiliatorRequest $request): JsonResponse
    {
        $affiliator = $this->authService->registerAffiliator($request->validated());

        return response()->json([
            'message' => 'Registration successful',
            'affiliator' => $affiliator,
        ], 201);
    }

    public function affiliatorLogin(LoginAffiliatorRequest $request): JsonResponse
    {
        $token = $this->authService->loginAffiliator($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
        ]);
    }

    public function affiliatorLogout(): JsonResponse
    {
        $this->authService->logoutAffiliator();

        return response()->json(['message' => 'Logout successful']);
    }

    /* ==================== ADMIN AUTH ==================== */

    public function adminLogin(LoginAdminRequest $request): JsonResponse
    {
        $token = $this->authService->loginAdmin($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
        ]);
    }

    public function adminLogout(): JsonResponse
    {
        $this->authService->logoutAdmin();

        return response()->json(['message' => 'Logout successful']);
    }
}
