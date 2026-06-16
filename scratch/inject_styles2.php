<?php
$htmlDir = 'c:/laragon/www/cooca-id/resources/views/html';
$bladeDir = 'c:/laragon/www/cooca-id/resources/views/pages';

$mapping = [
    'about.html' => 'about/index.blade.php',
    'affiliate.html' => 'affiliate/index.blade.php',
    'blog.html' => 'blog/index.blade.php',
    'blog detail.html' => 'blog/detail.blade.php',
    'contact.html' => 'contact/index.blade.php',
    'home.html' => 'home/index.blade.php',
    'Privacy Policy.html' => 'legal/privacy.blade.php',
    'Terms of Service.html' => 'legal/terms.blade.php',
    'pricing.html' => 'pricing/index.blade.php',
    'solution.html' => 'solutions/index.blade.php',
];

foreach ($mapping as $htmlFile => $bladeFile) {
    $htmlPath = "$htmlDir/$htmlFile";
    $bladePath = "$bladeDir/$bladeFile";
    
    if (file_exists($htmlPath) && file_exists($bladePath)) {
        $htmlContent = file_get_contents($htmlPath);
        if (preg_match('/<style>(.*?)<\/style>/s', $htmlContent, $matches)) {
            $styleContent = $matches[1];
            
            $bladeContent = file_get_contents($bladePath);
            
            if (strpos($bladeContent, "@push('styles')") === false) {
                // Append the style push to the end of the blade file
                $pushBlock = "\n@push('styles')\n<style>\n" . trim($styleContent) . "\n</style>\n@endpush\n";
                file_put_contents($bladePath, $bladeContent . $pushBlock);
                echo "Injected styles into $bladeFile\n";
            } else {
                echo "Styles already exist in $bladeFile\n";
            }
        }
    }
}
