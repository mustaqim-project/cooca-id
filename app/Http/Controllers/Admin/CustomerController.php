<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CustomerResource;
use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository
    ) {}

    /**
     * Display listing of customers.
     */
    public function index(): View
    {
        $customers = $this->customerRepository->paginate(15);

        return view('admin.customers.index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Display the specified customer.
     */
    public function show(string $id): View
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        return view('admin.customers.show', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified customer.
     */
    public function update(string $id, array $data): JsonResponse
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->customerRepository->update($id, $data);

        return response()->json([
            'message' => 'Customer updated successfully',
            'data' => new CustomerResource($customer->fresh()),
        ]);
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(string $id): JsonResponse
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $this->customerRepository->delete($id);

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
