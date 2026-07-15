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
    public function index()
    {
        $customers = $this->customerRepository->paginate(15);

        return view('admin.customers.index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Display the specified customer.
     */
    public function show(string $id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Load relationships for the view
        $customer->load([
            'subscriptions' => fn($q) => $q->with('product', 'subscriptionPlan')->latest(),
            'licenses' => fn($q) => $q->with('product')->latest()
        ]);

        return view('admin.customers.show', [
            'customer' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(string $id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        return view('admin.customers.edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Store a newly created customer.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:8',
            'phone' => 'nullable|string',
            'business_name' => 'nullable|string',
        ]);
        
        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);

        $this->customerRepository->create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Update the specified customer.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return redirect()->route('admin.customers.index')->with('error', 'Customer not found.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string',
            'business_name' => 'nullable|string',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8';
        }

        $data = $request->validate($rules);
        
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->customerRepository->update($id, $data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(string $id)
    {
        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return redirect()->route('admin.customers.index')->with('error', 'Customer not found.');
        }

        $this->customerRepository->delete($id);

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
