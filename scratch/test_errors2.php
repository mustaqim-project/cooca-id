<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

foreach (['/products', '/blog'] as $path) {
    $request = Illuminate\Http\Request::create($path, 'GET');
    try {
        $response = $kernel->handle($request);
        echo "=== $path ===\n";
        echo "Status: " . $response->getStatusCode() . "\n";
        if ($response->getStatusCode() !== 200) {
            // Extract the error text from HTML response
            $html = $response->getContent();
            preg_match('/<title>(.*?)<\/title>/s', $html, $titleMatch);
            preg_match('/<div class="exception-message">(.*?)<\/div>/s', $html, $msgMatch);
            preg_match('/\bException\b.*?<\/p>/s', $html, $exMatch);
            echo "Title: " . strip_tags($titleMatch[1] ?? 'N/A') . "\n";
            
            // Use php error log
            $lines = file(storage_path('logs/laravel.log'));
            $lastLines = array_slice($lines, -30);
            echo "Last 30 log lines:\n";
            echo implode('', $lastLines);
        }
    } catch (\Throwable $e) {
        echo "=== $path === EXCEPTION\n";
        echo $e->getMessage() . "\n";
        echo $e->getFile() . ":" . $e->getLine() . "\n";
    }
    echo "\n";
}
