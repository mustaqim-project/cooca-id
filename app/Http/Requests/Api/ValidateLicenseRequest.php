<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class ValidateLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_code' => ['required', 'string'],
            'license_key' => ['nullable', 'string'],
            'product' => ['required', 'string'],
            'domain' => ['required', 'string', 'max:255'],
            'installation_id' => ['required', 'string'],
            'application_version' => ['nullable', 'string'],
            'ip_address' => ['nullable', 'string'],
            'timestamp' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'domain.required' => 'Domain is required.',
            'license_code.required' => 'License code is required.',
            'product.required' => 'Product code is required.',
            'installation_id.required' => 'Installation ID is required.',
        ];
    }
}
