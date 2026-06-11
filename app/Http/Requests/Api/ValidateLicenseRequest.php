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
            'domain' => ['required', 'string', 'max:255'],
            'license_code' => ['required', 'string', 'size:16'],
            'token_code' => ['required', 'string', 'size:16'],
        ];
    }

    public function messages(): array
    {
        return [
            'domain.required' => 'Domain is required.',
            'license_code.required' => 'License code is required.',
            'license_code.size' => 'License code must be exactly 16 characters.',
            'token_code.required' => 'Token code is required.',
            'token_code.size' => 'Token code must be exactly 16 characters.',
        ];
    }
}
