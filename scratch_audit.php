<?php

$controllersDir = __DIR__ . '/app/Http/Controllers/Admin';
$viewsDir = __DIR__ . '/resources/views/admin';

echo "--- CONTROLLERS AUDIT ---\n";
foreach (glob($controllersDir . '/*.php') as $file) {
    $content = file_get_contents($file);
    $name = basename($file);
    
    preg_match_all('/public function (\w+)\(/', $content, $matches);
    $methods = implode(', ', $matches[1]);
    
    // Check if it returns JSON but has no view return
    $isJson = strpos($content, 'response()->json(') !== false;
    $isView = strpos($content, 'return view(') !== false || strpos($content, 'return redirect(') !== false;
    
    $warning = ($isJson && !$isView && $name !== 'DashboardController.php' && $name !== 'AuthController.php') 
        ? "  [WARNING: Might be returning JSON instead of redirects/views for web]" 
        : "";
        
    echo "{$name}: {$methods}{$warning}\n";
}

echo "\n--- VIEWS AUDIT ---\n";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        $relPath = str_replace($viewsDir . '\\', '', $path);
        
        if (stripos($content, 'coming soon') !== false) {
            echo "Coming Soon found in: {$relPath}\n";
        }
    }
}
