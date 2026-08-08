<?php

declare(strict_types=1);

namespace App\Http\Requests\Affiliator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RegisterAffiliatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'terms' => ['accepted'],
            'parent_referral_code' => ['nullable', 'string', 'exists:affiliator_profiles,referral_code'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered as an affiliator.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'parent_referral_code.exists' => 'Kode referral tidak valid.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan Partner Affiliasi serta Kebijakan Privasi.',
        ];
    }
}
