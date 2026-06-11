<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class GenerateLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'product_id' => ['required', 'exists:products,id'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'domain' => ['required', 'string', 'max:255', Rule::unique('licenses', 'domain')->whereNull('deleted_at')],
            'expires_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required.',
            'customer_id.exists' => 'Selected customer does not exist.',
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Selected product does not exist.',
            'subscription_plan_id.required' => 'Subscription plan is required.',
            'subscription_plan_id.exists' => 'Selected subscription plan does not exist.',
            'domain.required' => 'Domain is required.',
            'domain.unique' => 'This domain is already registered to another license.',
            'expires_at.date' => 'Expiry date must be a valid date.',
        ];
    }
}
