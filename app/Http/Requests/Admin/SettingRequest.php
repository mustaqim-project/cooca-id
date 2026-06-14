<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin middleware handles authorization
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255', Rule::unique('settings')->ignore($this->id)],
            'value' => ['nullable', 'string'],
            'type' => ['required', 'in:string,boolean,integer,float,json,array'],
            'group' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_public' => ['boolean'],
            'metadata' => ['nullable', 'json'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'key.required' => 'Setting key is required',
            'key.unique' => 'This setting key already exists',
            'type.required' => 'Setting type is required',
            'type.in' => 'Invalid setting type',
            'group.required' => 'Setting group is required',
            'metadata.json' => 'Metadata must be valid JSON',
        ];
    }
}
