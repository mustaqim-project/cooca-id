<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\License;
use App\Models\Subscription;
use App\Models\Domain;
use App\Models\LicenseLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class IntegrationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Customers with fixed UUID for GVB and Basic Project so we can compute identical signatures on client
        $customers = [
            'PT Demo Indonesia' => '22222222-3333-4444-5555-666666666666',
            'PT Gua Villa Bandung' => '11111111-2222-3333-4444-555555555555',
            'PT ABC' => Str::uuid()->toString(),
            'PT XYZ' => Str::uuid()->toString(),
        ];

        $customerModels = [];
        $i = 1;
        foreach ($customers as $name => $id) {
            $customerModels[$name] = Customer::firstOrCreate(
                ['email' => 'admin' . $i . '@' . Str::slug($name) . '.com'],
                [
                    'id' => $id,
                    'name' => 'Admin ' . $name,
                    'business_name' => $name,
                    'password' => bcrypt('password'),
                    'status' => 'active',
                ]
            );
            $i++;
        }

        // Generate API Tokens manually to avoid UUID error
        $clients = [
            'PT Gua Villa Bandung',
            'PT Demo Indonesia'
        ];

        foreach ($clients as $clientName) {
            $clientModel = $customerModels[$clientName];
            \Illuminate\Support\Facades\DB::table('personal_access_tokens')->where('tokenable_id', $clientModel->id)->delete();
            
            $tokenPlain = Str::random(40);
            $tokenId = Str::uuid()->toString();
            \Illuminate\Support\Facades\DB::table('personal_access_tokens')->insert([
                'id' => $tokenId,
                'tokenable_type' => 'customer',
                'tokenable_id' => $clientModel->id,
                'name' => 'Integration Token',
                'token' => hash('sha256', $tokenPlain),
                'abilities' => '["*"]',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info($clientName . " API Token: " . $tokenId . '|' . $tokenPlain);
        }

        // 2. Create Products with fixed UUID
        $products = [
            'Gua Villa Bandung' => ['id' => '66666666-7777-8888-9999-000000000000', 'type' => 'custom_dev'],
            'Basic-Project-Laravel' => ['id' => '88888888-7777-8888-9999-000000000000', 'type' => 'saas'],
            'COOCA ERP' => ['id' => Str::uuid()->toString(), 'type' => 'saas'],
        ];

        $productModels = [];
        foreach ($products as $name => $data) {
            $productModels[$name] = Product::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'id' => $data['id'],
                    'name' => $name,
                    'product_type' => $data['type'],
                    'base_price' => 1000000,
                    'setup_fee' => $data['type'] === 'custom_dev' ? 5000000 : 0,
                    'maintenance_fee' => 0,
                    'max_domains' => 1,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_bundleable' => false,
                    'sort_order' => 1,
                    'views' => 0,
                ]
            );
        }

        // 3. Create Subscription Plans
        $plans = [
            ['name' => 'Trial', 'months' => 1, 'price' => 0],
            ['name' => 'Monthly', 'months' => 1, 'price' => 100000],
            ['name' => 'Yearly', 'months' => 12, 'price' => 1000000],
            ['name' => 'Lifetime', 'months' => 1200, 'price' => 5000000], // 100 years
        ];

        $planModels = [];
        foreach (['Gua Villa Bandung', 'Basic-Project-Laravel'] as $prodName) {
            foreach ($plans as $plan) {
                $planModels[$prodName][$plan['name']] = SubscriptionPlan::firstOrCreate(
                    ['name' => $plan['name'], 'product_id' => $productModels[$prodName]->id],
                    [
                        'id' => Str::uuid()->toString(),
                        'duration_months' => $plan['months'],
                        'price' => $plan['price'],
                        'discount_percent' => 0,
                        'is_active' => true,
                        'sort_order' => 1,
                    ]
                );
            }
        }

        // 4. Create Licenses
        $licensesData = [
            [
                'code' => 'COOCA-PRODUCT-ACT',
                'status' => 'active',
                'plan' => 'Yearly',
                'starts' => now()->subDays(10),
                'expires' => now()->addDays(355),
                'domain' => 'act.localhost',
            ],
            [
                'code' => 'COOCA-PRODUCT-EXP',
                'status' => 'expired',
                'plan' => 'Yearly',
                'starts' => now()->subDays(370),
                'expires' => now()->subDays(5),
                'domain' => 'exp.localhost',
            ]
        ];

        $clientProductMap = [
            [
                'product' => 'Gua Villa Bandung',
                'customer' => 'PT Gua Villa Bandung',
                'code_prefix' => 'COOCA-GVB-',
                'domain_prefix' => 'gvb-'
            ],
            [
                'product' => 'Basic-Project-Laravel',
                'customer' => 'PT Demo Indonesia',
                'code_prefix' => 'COOCA-BAS-',
                'domain_prefix' => 'bas-'
            ]
        ];

        foreach ($clientProductMap as $map) {
            foreach ($licensesData as $data) {
                $domainName = $map['domain_prefix'] . $data['domain'];
                $code = str_replace('COOCA-PRODUCT-', $map['code_prefix'], $data['code']);

                $domainModel = Domain::firstOrCreate(
                    ['domain' => $domainName, 'customer_id' => $customerModels[$map['customer']]->id],
                    [
                        'id' => Str::uuid()->toString(),
                        'status' => 'active',
                    ]
                );

                // First delete existing if we want fresh
                $existingLicense = License::where('license_code', $code)->first();
                if ($existingLicense) {
                    Subscription::where('license_id', $existingLicense->id)->delete();
                    LicenseLog::where('license_id', $existingLicense->id)->delete();
                    $existingLicense->forceDelete();
                }

                $license = License::create([
                    'id' => Str::uuid()->toString(),
                    'customer_id' => $customerModels[$map['customer']]->id,
                    'product_id' => $productModels[$map['product']]->id,
                    'subscription_plan_id' => $planModels[$map['product']][$data['plan']]->id,
                    'domain_id' => $domainModel->id,
                    'domain' => $domainName,
                    'license_code' => $code,
                    'token_code' => 'TOKEN-' . explode('-', $code)[1] . '-' . explode('-', $code)[2],
                    'status' => $data['status'],
                    'is_trial' => $data['is_trial'] ?? false,
                    'activated_at' => clone $data['starts'],
                    'starts_at' => clone $data['starts'],
                    'expires_at' => $data['expires'] ? clone $data['expires'] : null,
                    'revoked_at' => $data['status'] === 'revoked' ? now() : null,
                ]);

                Subscription::create([
                    'id' => Str::uuid()->toString(),
                    'license_id' => $license->id,
                    'customer_id' => $customerModels[$map['customer']]->id,
                    'subscription_plan_id' => $planModels[$map['product']][$data['plan']]->id,
                    'status' => $data['status'] === 'active' ? 'active' : ($data['status'] === 'inactive' ? 'cancelled' : ($data['status'] === 'expired' ? 'expired' : 'cancelled')),
                    'started_at' => clone $data['starts'],
                    'expires_at' => $data['expires'] ? clone $data['expires'] : null,
                ]);

                // Create Activation History (License Logs)
                $actions = ['generated', 'activated', 'validated'];
                if ($data['status'] === 'expired') $actions[] = 'expired';

                foreach ($actions as $action) {
                    $instId = 'INST-' . explode('-', $code)[1] . '-' . explode('-', $code)[2];
                    LicenseLog::create([
                        'id' => Str::uuid()->toString(),
                        'license_id' => $license->id,
                        'action' => $action,
                        'description' => 'System ' . $action . ' license',
                        'ip_address' => '127.0.0.1',
                        'user_agent' => 'IntegrationSeeder',
                        'metadata' => ['installation_id' => $instId],
                        'created_at' => $action === 'generated' ? clone $data['starts'] : ($action === 'activated' ? clone $data['starts'] : now()),
                    ]);
                }
            }
        }
    }
}
