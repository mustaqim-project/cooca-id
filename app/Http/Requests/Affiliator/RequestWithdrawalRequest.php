<?php

declare(strict_types=1);

namespace App\Http\Requests\Affiliator;

use Illuminate\Foundation\Http\FormRequest;

final class RequestWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:10000'],
            'withdrawal_method' => ['required', Rule::in(['bank', 'ewallet'])],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Withdrawal amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Minimum withdrawal amount is Rp 10,000.',
            'withdrawal_method.required' => 'Withdrawal method is required.',
            'withdrawal_method.in' => 'Withdrawal method must be either "bank" or "ewallet".',
            'account_number.required' => 'Account number is required.',
            'account_name.required' => 'Account name is required.',
        ];
    }
}
