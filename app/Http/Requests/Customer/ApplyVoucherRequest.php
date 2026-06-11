<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

final class ApplyVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'voucher_code' => ['required', 'string', 'max:50'],
            'purchase_amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'voucher_code.required' => 'Voucher code is required.',
            'purchase_amount.required' => 'Purchase amount is required.',
            'purchase_amount.numeric' => 'Purchase amount must be a number.',
            'purchase_amount.min' => 'Purchase amount cannot be negative.',
        ];
    }
}
