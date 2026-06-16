<?php

$pagesDir = 'c:/laragon/www/cooca-id/resources/views/pages';

$missingFiles = [
    "features/index.blade.php",
    "faq/index.blade.php",
    "docs/index.blade.php",
    "products/index.blade.php",
    "products/detail.blade.php",
    "auth/affiliator/login.blade.php",
    "auth/affiliator/register.blade.php",
    "auth/admin/login.blade.php",
];

foreach ($missingFiles as $file) {
    $bladePath = "$pagesDir/$file";
    
    // Create directories
    $dir = dirname($bladePath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Only create if it doesn't exist
    if (!file_exists($bladePath)) {
        // Build title based on path
        $titleName = ucwords(str_replace(['/', '-', '.blade.php', 'index'], [' ', ' ', '', ''], $file));
        $titleName = trim($titleName);
        if (empty($titleName)) $titleName = 'Page';
        
        $content = "@extends('layouts.guest')\n\n";
        $content .= "@section('title', \$page->title ?? ('$titleName - ' . (\$setting->company_name ?? config('app.name'))))\n\n";
        $content .= "@section('content')\n";
        $content .= "<section class=\"section-padding\">\n";
        $content .= "    <div class=\"container\">\n";
        $content .= "        <h1>{{ \$page->title ?? '$titleName' }}</h1>\n";
        $content .= "        <p>{{ \$page->description ?? 'Content for $titleName goes here.' }}</p>\n";
        $content .= "    </div>\n";
        $content .= "</section>\n";
        $content .= "@endsection\n";
        
        file_put_contents($bladePath, $content);
        echo "Created missing file: $file\n";
    }
}
echo "Done.\n";
