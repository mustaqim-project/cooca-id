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
        $isManual = in_array($this->input('payment_type') ?? $this->input('payment_method'), ['manual_transfer', 'bank_transfer_manual', 'manual']);

        return [
            'invoice_id' => ['nullable', 'string', 'exists:invoices,id'],
            'subscription_id' => ['required_without:invoice_id', 'nullable', 'string', 'exists:subscriptions,id'],
            'gross_amount' => ['required_without:invoice_id', 'nullable', 'numeric', 'min:0'],
            'voucher_discount' => ['nullable', 'numeric', 'min:0'],
            'voucher_id' => ['nullable', 'string', 'exists:vouchers,id'],
            'payment_type' => ['nullable', 'string', 'in:midtrans,manual_transfer,bank_transfer_manual,manual'],
            'payment_method' => ['nullable', 'string'],
            'sender_name' => [$isManual ? 'required' : 'nullable', 'string', 'max:255'],
            'payment_notes' => ['nullable', 'string', 'max:1000'],
            'payment_proof' => [
                $isManual ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120', // 5MB
            ],
        ];
    }
}
