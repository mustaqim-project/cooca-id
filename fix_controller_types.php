<?php

$dir = __DIR__ . '/app/Http/Controllers';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($phpFiles as $file) {
    $filePath = $file[0];
    $content = file_get_contents($filePath);
    
    $original = $content;
    
    // Pattern matches: public function name(args): Type
    // The type can include backslashes.
    // e.g. public function index(): \Illuminate\View\View
    // or public function index(): Response
    $content = preg_replace('/(public\s+function\s+[a-zA-Z0-9_]+\s*\([^)]*\))\s*:\s*[a-zA-Z0-9_\\\\]+/', '$1', $content);
    
    if ($content !== $original) {
        file_put_contents($filePath, $content);
        echo "Fixed return types in: " . str_replace(__DIR__, '', $filePath) . "\n";
    }
}

echo "Done fixing controller return types.\n";
