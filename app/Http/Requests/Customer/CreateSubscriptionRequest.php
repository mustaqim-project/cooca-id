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
        $rules = [
            'product_slug' => ['required', 'string', 'exists:products,slug'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
            'domain' => [
                'required', 
                'string', 
                'max:255',
                function ($attribute, $value, $fail) {
                    $domainStr = str_contains($value, '.') ? $value : $value . '.cooca.id';
                    
                    if (!preg_match('/^[a-zA-Z0-9.-]+$/', $domainStr)) {
                        $fail('Format domain tidak valid.');
                        return;
                    }

                    $existsInLicenses = \App\Models\License::where('domain', $domainStr)
                        ->where('customer_id', '!=', auth('customer')->id())
                        ->exists();

                    $subdomainOnly = str_replace('.cooca.id', '', $domainStr);
                    $existsInRequests = \App\Models\ErpRequest::where('requested_subdomain', $subdomainOnly)
                        ->where('customer_id', '!=', auth('customer')->id())
                        ->whereNotIn('status', [\App\Models\ErpRequest::STATUS_REJECTED, \App\Models\ErpRequest::STATUS_TRIAL_EXPIRED])
                        ->exists();

                    if ($existsInLicenses || $existsInRequests) {
                        $fail('Domain sudah digunakan oleh pengguna lain.');
                    }
                }
            ],
        ];

        $customer = auth('customer')->user();
        if ($customer && !$customer->isCompanyProfileComplete()) {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['industry'] = ['required', 'string', 'max:255'];
            $rules['company_size'] = ['required', 'in:1-10,11-50,51-200,201-500,500+'];
            $rules['phone'] = ['required', 'string', 'max:20'];
            $rules['address'] = ['required', 'string'];
            $rules['city'] = ['required', 'string', 'max:100'];
            $rules['province'] = ['required', 'string', 'max:100'];
            $rules['postal_code'] = ['required', 'string', 'max:10'];
            $rules['npwp'] = ['nullable', 'string', 'max:30'];
            $rules['website'] = ['nullable', 'url', 'max:255'];
        }

        return $rules;
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
