<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        
        return [
            'id' => (string) Str::uuid(),
            'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()?->id ?? \App\Models\ProductCategory::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(),
            'base_price' => fake()->randomElement([1500000, 2500000, 3500000, 5000000, 7500000, 12500000]),
            'features' => [
                'Advanced Real-time Analytics & Automated Dashboard',
                'Multi-user Roles & Custom Permissions Management',
                'Priority 24/7 Support & VIP Account Manager',
                'Complete API Access & Webhooks Integration',
                '99.9% Uptime Guarantee SLA & Enterprise Security'
            ],
            'specifications' => [
                'System Architecture' => 'Cloud Native Microservices',
                'Deployment Model' => 'Multi-tenant SaaS & Dedicated Instance',
                'Security Compliance' => 'ISO 27001 & SOC 2 Type II Certified',
                'Backup Frequency' => 'Hourly Automated Snapshots',
                'Data Hosting' => 'Google Cloud Platform (Jakarta Region)'
            ],
            'demo_url' => fake()->optional(0.7)->url(),
            'thumbnail' => fake()->optional(0.8)->imageUrl(640, 480, 'business', true),
            'screenshots' => [
                fake()->imageUrl(1280, 720, 'business', true),
                fake()->imageUrl(1280, 720, 'technics', true),
            ],
            'is_active' => true,
            'is_featured' => fake()->boolean(30),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
