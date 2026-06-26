<?php
$routes = [
    '/',
    '/about',
    '/pricing',
    '/contact',
    '/affiliate',
    '/solutions',
    '/faq',
    '/docs',
    '/terms',
    '/privacy',
    '/products',
    '/blog',
    '/customer/login',
    '/customer/register',
    '/affiliator/login',
    '/affiliator/register',
    '/admin/login',
];

$baseUrl = 'http://localhost:3178';
$errors = [];

foreach ($routes as $route) {
    $url = $baseUrl . $route;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ignore SSL if any, though localhost
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Timeout
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode !== 200) {
        $errors[] = "Route: $route returned HTTP $httpCode";
    } else {
        echo "Route: $route OK\n";
    }
    curl_close($ch);
}

// Test a product detail page if products exist
// Actually, I can't be sure what product slug exists, let's grab one from DB
// Not grabbing DB now, just testing main routes.

if (empty($errors)) {
    echo "\nAll main routes returned 200 OK!\n";
} else {
    echo "\nErrors found:\n";
    foreach ($errors as $e) {
        echo "- $e\n";
    }
}
