<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('vouchers', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['percent', 'nominal'])],
            'value' => ['required', 'numeric', 'min:0'],
            'min_purchase' => ['required', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'max_usage' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['boolean'],
            'applicable_products' => ['nullable', 'array'],
            'applicable_products.*' => ['exists:products,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Voucher code is required.',
            'code.unique' => 'This voucher code already exists.',
            'name.required' => 'Voucher name is required.',
            'type.required' => 'Voucher type is required.',
            'type.in' => 'Voucher type must be either "percent" or "nominal".',
            'value.required' => 'Voucher value is required.',
            'value.min' => 'Voucher value cannot be negative.',
            'min_purchase.required' => 'Minimum purchase amount is required.',
            'min_purchase.min' => 'Minimum purchase cannot be negative.',
            'max_discount.min' => 'Maximum discount cannot be negative.',
            'max_usage.min' => 'Maximum usage must be at least 1.',
            'per_user_limit.min' => 'Per-user limit must be at least 1.',
            'valid_until.after_or_equal' => 'Valid until date must be after or equal to valid from date.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
