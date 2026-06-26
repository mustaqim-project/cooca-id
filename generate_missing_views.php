<?php

$controllersDir = __DIR__ . '/app/Http/Controllers';
$viewsDir = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllersDir));
$phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$missingViews = [];

foreach ($phpFiles as $file) {
    $content = file_get_contents($file[0]);
    if (preg_match_all("/view\(['\"]([^'\"]+)['\"]/", $content, $matches)) {
        foreach ($matches[1] as $viewName) {
            // Ignore auth views or mail views
            if (str_contains($viewName, 'auth.') || str_contains($viewName, 'emails.')) {
                continue;
            }
            
            $viewPath = $viewsDir . '/' . str_replace('.', '/', $viewName) . '.blade.php';
            if (!file_exists($viewPath)) {
                $missingViews[$viewName] = $viewPath;
            }
        }
    }
}

foreach ($missingViews as $viewName => $viewPath) {
    $dir = dirname($viewPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Determine layout based on view name prefix
    $layout = 'admin';
    if (str_starts_with($viewName, 'customer.')) {
        $layout = 'customer';
    } elseif (str_starts_with($viewName, 'affiliator.')) {
        $layout = 'affiliator';
    }
    
    // Generate simple title from view name
    $parts = explode('.', $viewName);
    $title = ucfirst(end($parts)) . ' ' . ucfirst($parts[count($parts) - 2] ?? '');
    
    $stub = <<<BLADE
@extends('layouts.{$layout}')

@section('title', '{$title}')
@section('subtitle', 'Manage ' . '{$title}')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h2 class="text-xl font-semibold mb-4">{$title} View</h2>
        <p class="mb-4">This view has been automatically generated during the Blade migration.</p>
        
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 dark:text-yellow-500">
                        Content for <strong>{$viewName}</strong> is pending manual implementation.
                    </p>
                </div>
            </div>
        </div>
        
        @if(isset(\$items) || isset(\$data))
        <div class="mt-6">
            <h3 class="text-lg font-medium">Available Data</h3>
            <pre class="mt-2 p-4 bg-gray-100 dark:bg-gray-900 rounded overflow-x-auto text-sm">{{ json_encode(get_defined_vars(), JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
</div>
@endsection
BLADE;

    file_put_contents($viewPath, $stub);
    echo "Created view: {$viewName}\n";
}

echo "Done generating missing views.\n";
