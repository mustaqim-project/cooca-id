<?php
$htmlDir = 'c:/laragon/www/cooca-id/resources/views/html';
$htmlFiles = glob("$htmlDir/*.html");
$allSpecificStyles = "/* ==================================================================\n   PAGE-SPECIFIC STYLES (Extracted from HTML files)\n================================================================== */\n\n";

foreach ($htmlFiles as $file) {
    $content = file_get_contents($file);
    if (preg_match('/<style>(.*?)<\/style>/s', $content, $matches)) {
        $styleContent = $matches[1];
        
        // Remove the unified block. 
        // The unified block usually starts at /* ----- DESIGN SYSTEM and ends around /* ----- FOOTER or /* ----- REVEAL ANIMATION
        // We can just remove known unified blocks by string replacement or regex.
        // Actually, let's just find where page-specific sections start. They usually have comments like /* ----- PAGE HERO or /* ----- TEAM or /* ----- PRICING
        
        // Let's strip the common unified header which is usually from the start up to the first page-specific comment
        // A simpler way: The unified block is everything up to /* ----- PAGE HERO or /* ----- PRICING HERO etc.
        // But what if we just strip the known common CSS classes?
        
        // Let's just strip everything between /* ----- DESIGN SYSTEM ROOT VARIABLES and /* ----- PAGE HERO (exclusive)
        // If not found, try to strip up to /* ----- STATS
        
        // A very safe way: split by "/* ----- " and keep only the blocks we want.
        $blocks = preg_split('/(?=\/\* ----- )/', $styleContent);
        
        $keptBlocks = [];
        $unifiedHeaders = [
            'DESIGN SYSTEM ROOT',
            'NAVBAR',
            'BUTTONS',
            'CARDS',
            'TYPOGRAPHY',
            'FOOTER',
            'REVEAL ANIMATION',
            'MOBILE OFF CANVAS',
            'RESPONSIVE RULES'
        ];
        
        foreach ($blocks as $block) {
            $isUnified = false;
            foreach ($unifiedHeaders as $header) {
                if (stripos($block, $header) !== false && stripos($block, '(UNIFIED)') !== false) {
                    $isUnified = true;
                    break;
                }
            }
            if (!$isUnified && trim($block) !== '') {
                $keptBlocks[] = trim($block);
            }
        }
        
        if (!empty($keptBlocks)) {
            $basename = basename($file);
            $allSpecificStyles .= "/* ================= Styles from {$basename} ================= */\n";
            $allSpecificStyles .= implode("\n\n", $keptBlocks) . "\n\n";
        }
    }
}

file_put_contents('c:/laragon/www/cooca-id/resources/css/pages.css', $allSpecificStyles);
echo "Extracted cleanly to resources/css/pages.css\n";
