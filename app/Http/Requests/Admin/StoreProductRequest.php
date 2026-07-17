<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $typeKeys = implode(',', array_keys(\App\Models\Product::TYPES));
        $licenseKeys = implode(',', array_keys(\App\Models\Product::LICENSE_TYPES));

        return [
            'category_id'       => ['nullable', 'exists:product_categories,id'],
            'product_type'      => ['required', 'string', "in:{$typeKeys}"],
            'license_type'      => ['nullable', 'string', "in:{$licenseKeys}"],
            'name'              => ['required', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'base_price'        => ['nullable', 'numeric', 'min:0'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'setup_fee'         => ['nullable', 'numeric', 'min:0'],
            'maintenance_fee'   => ['nullable', 'numeric', 'min:0'],
            'type'              => ['nullable', 'string', 'in:one_time,subscription'],
            'version'           => ['nullable', 'string', 'max:20'],
            'max_domains'       => ['nullable', 'integer', 'min:1'],
            'is_bundleable'     => ['boolean'],
            'requirements'      => ['nullable', 'string', 'max:2000'],
            'features'          => ['nullable', 'array'],
            'specifications'    => ['nullable', 'array'],
            'demo_url'          => ['nullable', 'url', 'max:255'],
            'webhook_url'       => ['nullable', 'url', 'max:255'],
            'thumbnail'         => ['nullable', 'image', 'max:2048'],
            'screenshots'       => ['nullable', 'array'],
            'is_active'         => ['boolean'],
            'is_featured'       => ['boolean'],
            'sort_order'        => ['integer', 'min:0'],
            // Pricing plans submitted alongside create form
            'plans'                          => ['nullable', 'array'],
            'plans.*.name'                   => ['required_with:plans', 'string', 'max:100'],
            'plans.*.duration_months'        => ['required_with:plans', 'integer', 'min:1'],
            'plans.*.price'                  => ['required_with:plans', 'numeric', 'min:0'],
            'plans.*.discount_percent'       => ['nullable', 'numeric', 'min:0', 'max:100'],
            'plans.*.sort_order'             => ['nullable', 'integer', 'min:0'],
            'plans.*.is_active'              => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Product category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'name.required' => 'Product name is required.',
            'slug.required' => 'Product slug is required.',
            'slug.unique' => 'This slug is already in use.',
            'description.required' => 'Product description is required.',
            'short_description.required' => 'Short description is required.',
            'base_price.required' => 'Base price is required.',
            'base_price.numeric' => 'Base price must be a number.',
            'base_price.min' => 'Base price cannot be negative.',
            'demo_url.url' => 'Demo URL must be a valid URL.',
            'thumbnail.image' => 'Thumbnail must be an image.',
            'thumbnail.max' => 'Thumbnail size cannot exceed 2MB.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active'     => $this->boolean('is_active'),
            'is_featured'   => $this->boolean('is_featured'),
            'is_bundleable' => $this->boolean('is_bundleable'),
            'sort_order'    => $this->integer('sort_order', 0),
            // Auto-generate slug from name if not provided
            'slug' => $this->input('slug') ?: \Illuminate\Support\Str::slug($this->input('name', '')),
        ]);
    }
}
