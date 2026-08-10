<?php
// boots the app and dumps runtime config('services.midtrans') and api_integrations record
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ApiIntegration;

$config = config('services.midtrans');
$api = ApiIntegration::where('name', 'midtrans')->first();

$result = [
    'config_services_midtrans' => $config,
    'api_integration_db' => $api ? ['id' => $api->id, 'name' => $api->name, 'config' => $api->config] : null,
];

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
