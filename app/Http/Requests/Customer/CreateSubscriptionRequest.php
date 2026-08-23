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
                    $domainStr = trim((string) $value);
                    if (!str_contains($domainStr, '.')) {
                        $domainStr .= '.cooca.id';
                    }
                    
                    if (!preg_match('/^[a-zA-Z0-9.-]+$/', $domainStr)) {
                        $fail('Format domain tidak valid. Hanya huruf, angka, dan tanda hubung yang diperbolehkan.');
                        return;
                    }

                    // 1. Cek lisensi aktif/suspended global
                    $activeLicenseExists = \App\Models\License::where('domain', $domainStr)
                        ->whereIn('status', [\App\Models\License::STATUS_ACTIVE, \App\Models\License::STATUS_SUSPENDED])
                        ->exists();

                    if ($activeLicenseExists) {
                        $fail("Domain '{$domainStr}' sudah aktif digunakan. Silakan gunakan nama domain/subdomain lain.");
                        return;
                    }

                    // 2. Cek ERP Request aktif
                    $subdomainOnly = str_replace('.cooca.id', '', $domainStr);
                    $existsInRequests = \App\Models\ErpRequest::where('requested_subdomain', $subdomainOnly)
                        ->whereNotIn('status', [\App\Models\ErpRequest::STATUS_REJECTED, \App\Models\ErpRequest::STATUS_TRIAL_EXPIRED])
                        ->exists();

                    if ($existsInRequests) {
                        $fail("Subdomain '{$subdomainOnly}' sedang aktif dalam proses uji coba / setup ERP.");
                        return;
                    }

                    // 3. Cek Pending Payment Transaction (< 1 jam)
                    $pendingLicense = \App\Models\License::where('domain', $domainStr)
                        ->where('status', \App\Models\License::STATUS_INACTIVE)
                        ->whereHas('subscription.transactions', function ($query) {
                            $query->where('status', 'pending')
                                ->where('created_at', '>=', now()->subHours(1));
                        })
                        ->exists();

                    if ($pendingLicense) {
                        $fail("Domain '{$domainStr}' sedang dalam proses pemesanan dan menunggu pembayaran (Batas waktu 1 jam).");
                        return;
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
