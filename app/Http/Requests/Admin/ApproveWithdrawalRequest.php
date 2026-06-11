<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class ApproveWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'withdrawal_method' => ['required', Rule::in(['bank', 'ewallet'])],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:255'],
            'fee' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'withdrawal_method.required' => 'Withdrawal method is required.',
            'withdrawal_method.in' => 'Withdrawal method must be either "bank" or "ewallet".',
            'account_number.required' => 'Account number is required.',
            'account_name.required' => 'Account name is required.',
            'fee.min' => 'Fee cannot be negative.',
        ];
    }
}
