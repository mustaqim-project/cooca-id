<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$routes = app('router')->getRoutes()->getRoutesByName();
$registeredRouteNames = array_keys($routes);

$viewsPath = __DIR__.'/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsPath));
$bladeFiles = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$errors = [];
$missingRoutes = [];

foreach ($bladeFiles as $file) {
    $filePath = $file[0];
    $content = file_get_contents($filePath);
    
    // Check for route('xxx'
    preg_match_all('/route\([\'"]([^\'"]+)[\'"]/', $content, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $routeName) {
            if (!in_array($routeName, $registeredRouteNames)) {
                $missingRoutes[] = [
                    'file' => str_replace(__DIR__, '', $filePath),
                    'route' => $routeName
                ];
            }
        }
    }
}

if (empty($missingRoutes)) {
    echo "SUCCESS: All route() calls in Blade views match registered routes!\n";
} else {
    echo "ERROR: Found missing routes:\n";
    foreach ($missingRoutes as $error) {
        echo "- {$error['route']} in {$error['file']}\n";
    }
}
