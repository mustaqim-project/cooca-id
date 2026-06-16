<?php
$htmlPath = 'c:/laragon/www/cooca-id/resources/views/html/home.html';
$content = file_get_contents($htmlPath);

// Extract CSS
if (preg_match('/<style>(.*?)<\/style>/is', $content, $cssMatch)) {
    $css = trim($cssMatch[1]);
    file_put_contents('c:/laragon/www/cooca-id/resources/css/landing.css', $css);
    echo "Extracted landing.css\n";
}

// Extract JS
// The last script tag has the custom JS. The first script tag has bootstrap JS.
// We should match the script that contains "SHARED SYSTEM"
if (preg_match('/<script>([\s\S]*?\/\/\s*=======\s*SHARED SYSTEM[\s\S]*?)<\/script>/i', $content, $jsMatch)) {
    $js = trim($jsMatch[1]);
    file_put_contents('c:/laragon/www/cooca-id/resources/js/landing.js', $js);
    echo "Extracted landing.js\n";
} else {
    // Try to find the block manually
    preg_match_all('/<script>([\s\S]*?)<\/script>/i', $content, $matches);
    if (count($matches[1]) > 0) {
        $js = trim(end($matches[1])); // take the last one
        file_put_contents('c:/laragon/www/cooca-id/resources/js/landing.js', $js);
        echo "Extracted landing.js (fallback)\n";
    }
}
