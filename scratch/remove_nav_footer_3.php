<?php

$files = [
    'c:/laragon/www/cooca-id/resources/views/pages/blog/detail.blade.php',
    'c:/laragon/www/cooca-id/resources/views/auth/customer/login.blade.php',
    'c:/laragon/www/cooca-id/resources/views/auth/customer/register.blade.php',
];

foreach ($files as $path) {
    if (!file_exists($path)) {
        echo "Skip missing: $path\n";
        continue;
    }
    $content = file_get_contents($path);
    
    // Remove navbar
    $content = preg_replace('/<!-- NAVBAR.*?-->\s*<nav[^>]*>.*?<\/nav>/is', '', $content);
    $content = preg_replace('/<nav class="navbar-cooca"[^>]*>.*?<\/nav>/is', '', $content);
    
    // Remove offcanvas
    $content = preg_replace('/<!-- MOBILE MENU.*?-->\s*<div class="offcanvas[^>]*>.*?<\/div>\s*<\/div>\s*<\/div>/is', '', $content);
    $content = preg_replace('/<div class="offcanvas offcanvas-end offcanvas-cooca"[^>]*>.*?<\/div>\s*<\/div>\s*<\/div>/is', '', $content);
    
    // Remove footer
    $content = preg_replace('/<footer class="footer">.*?<\/footer>/is', '', $content);
    
    file_put_contents($path, $content);
    echo "Removed nav/footer from " . basename($path) . "\n";
}
