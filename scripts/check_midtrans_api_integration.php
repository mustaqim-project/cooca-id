<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('api_integrations')) {
    echo "NO_TABLE\n";
    exit(0);
}

$integration = ApiIntegration::where('provider', 'midtrans')->first();
if (!$integration) {
    echo "NO_RECORD\n";
    exit(0);
}

echo json_encode([
    'id' => $integration->id,
    'provider' => $integration->provider,
    'name' => $integration->name,
    'is_active' => $integration->is_active,
    'config' => $integration->config,
], JSON_PRETTY_PRINT);
