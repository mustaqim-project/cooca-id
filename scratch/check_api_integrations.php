<?php

use Illuminate\Support\Facades\DB;

$rows = DB::table('api_integrations')->get(['provider', 'is_active', DB::raw('LENGTH(config) as clen')]);

if ($rows->isEmpty()) {
    echo "NO ROWS in api_integrations table\n";
} else {
    foreach ($rows as $r) {
        echo "provider={$r->provider} active={$r->is_active} config_len={$r->clen}\n";
    }
}

// Also test the model accessor
echo "\n--- Model test ---\n";
$models = \App\Models\ApiIntegration::all();
foreach ($models as $m) {
    $cfg = $m->config;
    $type = gettype($cfg);
    echo "provider={$m->provider} config_type={$type} is_array=" . (is_array($cfg) ? 'YES' : 'NO') . "\n";
    if (is_array($cfg)) {
        echo "  keys: " . implode(',', array_keys($cfg)) . "\n";
    }
}
