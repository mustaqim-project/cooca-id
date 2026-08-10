<?php
// scripts/check_routes.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

/** @var \Illuminate\Routing\Router $router */
$router = $app['router'];
$routes = $router->getRoutes();

$missing = [];
$total = 0;

foreach ($routes as $route) {
    $action = $route->getAction();
    $uri = $route->uri();
    $methods = implode('|', $route->methods());
    $name = $route->getName() ?? '';
    $total++;

    if (isset($action['controller'])) {
        $controllerAction = $action['controller'];
        if (is_string($controllerAction) && strpos($controllerAction, '@') !== false) {
            [$class, $method] = explode('@', $controllerAction);
            if (!class_exists($class)) {
                $missing[] = [
                    'uri' => $uri,
                    'name' => $name,
                    'problem' => 'missing_class',
                    'class' => $class,
                    'method' => $method,
                ];
                continue;
            }
            if (!method_exists($class, $method)) {
                $missing[] = [
                    'uri' => $uri,
                    'name' => $name,
                    'problem' => 'missing_method',
                    'class' => $class,
                    'method' => $method,
                ];
                continue;
            }
        }
    }
}

echo "Total routes: {$total}\n";
if (count($missing) === 0) {
    echo "All routes controllers and methods exist.\n";
    exit(0);
}

echo "Found " . count($missing) . " problematic routes:\n";
foreach ($missing as $m) {
    echo "URI: {$m['uri']} | Name: {$m['name']} | Problem: {$m['problem']} | Class: {$m['class']} | Method: {$m['method']}\n";
}

exit(1);
