<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

final class CreateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_slug' => ['required', 'string', 'exists:products,slug'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
            'domain' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_slug.required' => 'Product selection is required.',
            'product_slug.exists' => 'Selected product does not exist.',
            'subscription_plan_id.required' => 'Subscription plan is required.',
            'subscription_plan_id.exists' => 'Selected subscription plan does not exist.',
            'voucher_code.max' => 'Voucher code is too long.',
            'domain.required' => 'Domain is required for license activation.',
        ];
    }
}
