<?php
$htmlDir = 'c:/laragon/www/cooca-id/resources/views/html';
$authDir = 'c:/laragon/www/cooca-id/resources/views/auth';

$mappings = [
    'customer login.html' => [
        "$authDir/customer/login.blade.php",
        "$authDir/admin/login.blade.php",
        "$authDir/affiliator/login.blade.php"
    ],
    'customer_register.html' => [
        "$authDir/customer/register.blade.php",
        "$authDir/affiliator/register.blade.php"
    ]
];

foreach ($mappings as $htmlFile => $bladeFiles) {
    $htmlPath = "$htmlDir/$htmlFile";
    
    if (file_exists($htmlPath)) {
        $htmlContent = file_get_contents($htmlPath);
        if (preg_match('/<style>(.*?)<\/style>/s', $htmlContent, $matches)) {
            $styleContent = $matches[1];
            
            foreach ($bladeFiles as $bladePath) {
                if (file_exists($bladePath)) {
                    $bladeContent = file_get_contents($bladePath);
                    
                    if (strpos($bladeContent, "@push('styles')") === false) {
                        $pushBlock = "\n@push('styles')\n<style>\n" . trim($styleContent) . "\n</style>\n@endpush\n";
                        file_put_contents($bladePath, $bladeContent . $pushBlock);
                        echo "Injected styles into " . basename(dirname($bladePath)) . "/" . basename($bladePath) . "\n";
                    } else {
                        echo "Styles already exist in " . basename(dirname($bladePath)) . "/" . basename($bladePath) . "\n";
                    }
                } else {
                    echo "File not found: $bladePath\n";
                }
            }
        }
    }
}
