<?php
$htmlFiles = glob('c:/laragon/www/cooca-id/resources/views/html/*.html');
$allSpecificStyles = "/* ==================================================================\n   PAGE-SPECIFIC STYLES (Extracted from HTML files)\n================================================================== */\n\n";

foreach ($htmlFiles as $file) {
    $content = file_get_contents($file);
    if (preg_match('/<style>(.*?)<\/style>/s', $content, $matches)) {
        $styleContent = $matches[1];
        
        // Split by the "PAGE-SPECIFIC STYLES" comment if it exists, or just try to find specific classes
        // Usually I can just look for a comment like /* ================= PAGE-SPECIFIC STYLES
        if (preg_match('/(?:\/\* ================= PAGE-SPECIFIC STYLES.*?\*\/)(.*)/s', $styleContent, $styleMatches)) {
            $specific = trim($styleMatches[1]);
            if (!empty($specific)) {
                $basename = basename($file);
                $allSpecificStyles .= "/* --- Styles from {$basename} --- */\n";
                $allSpecificStyles .= $specific . "\n\n";
            }
        }
    }
}

file_put_contents('c:/laragon/www/cooca-id/resources/css/pages.css', $allSpecificStyles);
echo "Extracted styles to resources/css/pages.css\n";
