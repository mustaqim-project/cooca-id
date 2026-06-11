<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\LicenseResource;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class LicenseController extends Controller
{
    public function __construct(
        private readonly LicenseService $licenseService
    ) {}

    /**
     * Display listing of customer licenses.
     */
    public function index(): Response
    {
        $customer = Auth::guard('customer')->user();
        $licenses = $this->licenseService->getByCustomer($customer->getKey());

        return Inertia::render('Customer/Licenses/Index', [
            'licenses' => LicenseResource::collection($licenses),
        ]);
    }

    /**
     * Display the specified license details.
     */
    public function show(string $id): Response
    {
        $customer = Auth::guard('customer')->user();
        $license = $this->licenseService->findByIdAndCustomer($id, $customer->getKey());

        if (!$license) {
            abort(404, 'License not found');
        }

        return Inertia::render('Customer/Licenses/Show', [
            'license' => new LicenseResource($license),
        ]);
    }
}
