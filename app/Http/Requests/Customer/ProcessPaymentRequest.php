<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

final class ProcessPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invoice_id' => ['nullable', 'string', 'exists:invoices,id'],
            'subscription_id' => ['required_without:invoice_id', 'nullable', 'string', 'exists:subscriptions,id'],
            'gross_amount' => ['required_without:invoice_id', 'nullable', 'numeric', 'min:0'],
            'voucher_discount' => ['nullable', 'numeric', 'min:0'],
            'voucher_id' => ['nullable', 'string', 'exists:vouchers,id'],
            'payment_method' => ['nullable', 'string', 'in:bank_transfer,credit_card,gopay,shopeepay'],
        ];
    }
}
