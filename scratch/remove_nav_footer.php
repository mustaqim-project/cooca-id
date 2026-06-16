<?php

$dir = 'c:/laragon/www/cooca-id/resources/views/pages/';

$files = [
    'about/index.blade.php',
    'affiliate/index.blade.php',
    'contact/index.blade.php',
    'pricing/index.blade.php',
    'solutions/index.blade.php',
];

foreach ($files as $file) {
    $path = $dir . $file;
    $content = file_get_contents($path);
    
    // Remove navbar
    $content = preg_replace('/<nav class="navbar-cooca"[^>]*>.*?<\/nav>/is', '', $content);
    
    // Remove offcanvas
    $content = preg_replace('/<!-- Mobile Offcanvas Menu -->\s*<div class="offcanvas offcanvas-end offcanvas-cooca".*?<\/div>\s*<\/div>\s*<\/div>/is', '', $content);
    // There are some variations
    $content = preg_replace('/<!-- MOBILE OFFCANVAS[^\n]*\s*<div class="offcanvas offcanvas-end offcanvas-cooca".*?<\/div>\s*<\/div>\s*<\/div>/is', '', $content);
    $content = preg_replace('/<div class="offcanvas offcanvas-end offcanvas-cooca"[^>]*>.*?<\/div>\s*<\/div>\s*<\/div>/is', '', $content);
    
    // Remove footer
    $content = preg_replace('/<footer class="footer">.*?<\/footer>/is', '', $content);
    
    // Remove unified navbar comment
    $content = preg_replace('/<!-- ======================== UNIFIED NAVBAR ======================== -->/is', '', $content);
    
    // Remove other nav comments
    $content = preg_replace('/<!-- NAVBAR \(STANDARD\) -->/is', '', $content);
    $content = preg_replace('/<!-- =====================================\s*NAVBAR — Standardized across all pages\s*===================================== -->/is', '', $content);
    
    file_put_contents($path, $content);
    echo "Removed nav/footer from $file\n";
}
