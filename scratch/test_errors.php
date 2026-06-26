<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

// Test /products
{
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::create('/products', 'GET');
    $response = $kernel->handle($request);
    echo "=== /products ===\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() !== 200) {
        // Print first 3000 chars of the response which may contain error msg
        echo substr($response->getContent(), 0, 3000) . "\n";
    }
}

// Test /blog
{
    $kernel2 = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request2 = Illuminate\Http\Request::create('/blog', 'GET');
    $response2 = $kernel2->handle($request2);
    echo "\n=== /blog ===\n";
    echo "Status: " . $response2->getStatusCode() . "\n";
    if ($response2->getStatusCode() !== 200) {
        echo substr($response2->getContent(), 0, 3000) . "\n";
    }
}
