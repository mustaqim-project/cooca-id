<?php
// Get all defined route names
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$router = app('router');
$routes = $router->getRoutes();
$names = [];
foreach ($routes as $route) {
    if ($route->getName()) {
        $names[] = $route->getName();
    }
}

// Now scan all pages blade views for route() calls
$viewDir = 'resources/views/pages';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));
$errors = [];
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
    $content = file_get_contents($file);
    preg_match_all("/route\('([^']+)'/", $content, $matches);
    foreach ($matches[1] as $routeName) {
        if (!in_array($routeName, $names)) {
            $errors[] = "File: " . str_replace('\\', '/', $file->getPathname()) . " -> Undefined route: '$routeName'";
        }
    }
}

if (empty($errors)) {
    echo "All route() calls in pages views are valid!\n";
} else {
    echo "Found " . count($errors) . " invalid route references:\n";
    foreach (array_unique($errors) as $e) {
        echo "- $e\n";
    }
}
