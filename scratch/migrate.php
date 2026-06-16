<?php

$htmlDir = 'c:/laragon/www/cooca-id/resources/views/html';
$pagesDir = 'c:/laragon/www/cooca-id/resources/views/pages';

$fileMap = [
    "home.html" => "home/index.blade.php",
    "about.html" => "about/index.blade.php",
    "pricing.html" => "pricing/index.blade.php",
    "contact.html" => "contact/index.blade.php",
    "affiliate.html" => "affiliate/index.blade.php",
    "solution.html" => "solutions/index.blade.php",
    "blog.html" => "blog/index.blade.php",
    "blog detail.html" => "blog/detail.blade.php",
    "Privacy Policy.html" => "legal/privacy.blade.php",
    "Terms of Service.html" => "legal/terms.blade.php",
    "customer login.html" => "auth/customer/login.blade.php",
    "customer_register.html" => "auth/customer/register.blade.php",
];

$routeMap = [
    "index" => "home",
    "home" => "home",
    "about" => "about",
    "pricing" => "pricing",
    "contact" => "contact",
    "affiliate" => "affiliate",
    "solution" => "solutions",
    "solutions" => "solutions",
    "blog" => "blog.index",
    "blog detail" => "blog.show",
    "Privacy Policy" => "privacy",
    "Terms of Service" => "terms",
    "customer login" => "customer.login",
    "customer_register" => "customer.register",
    "login" => "customer.login",
    "register" => "customer.register"
];

function fixRoutes($content) {
    global $routeMap;
    return preg_replace_callback('/href="([^"]+)"/', function($matches) use ($routeMap) {
        $orig = $matches[1];
        if (strpos($orig, '?') !== false) {
            list($base, $qs) = explode('?', $orig, 2);
            $base = str_replace('.html', '', $base);
            if (strpos($qs, 'affiliate') !== false) {
                return 'href="{{ route(\'affiliator.login\') }}"';
            }
        }
        $base = str_replace('.html', '', $orig);
        if ($base == '#' || strpos($orig, '#') === 0) {
            return 'href="'.$orig.'"';
        }
        if (isset($routeMap[$base])) {
            return 'href="{{ route(\''.$routeMap[$base].'\') }}"';
        }
        return $matches[0];
    }, $content);
}

function fixAssets($content) {
    return preg_replace('/src="(assets\/[^"]+)"/', 'src="{{ asset(\'$1\') }}"', $content);
}

foreach ($fileMap as $htmlFile => $bladeFile) {
    $htmlPath = "$htmlDir/$htmlFile";
    $bladePath = "$pagesDir/$bladeFile";
    
    if (!file_exists($htmlPath)) {
        echo "File not found: $htmlPath\n";
        continue;
    }
    
    $content = file_get_contents($htmlPath);
    
    if (preg_match('/<body>(.*?)<\/body>/is', $content, $matches)) {
        $bodyContent = $matches[1];
    } else {
        $bodyContent = $content;
    }
    
    // Remove scripts at the end of body
    $bodyContent = preg_replace('/<script.*?<\/script>/is', '', $bodyContent);
    // Remove standard includes
    $bodyContent = preg_replace('/<!-- PAGE LOADER -->.*?<\/div>\s*<\/div>\s*<\/div>/is', '', $bodyContent);
    $bodyContent = preg_replace('/<!-- FLOATING WHATSAPP -->.*?<\/a>/is', '', $bodyContent);
    $bodyContent = preg_replace('/<!-- UNIFIED NAVBAR -->.*?<\/nav>/is', '', $bodyContent);
    $bodyContent = preg_replace('/<!-- MOBILE OFFCANVAS -->.*?<\/div>\s*<\/div>\s*<\/div>/is', '', $bodyContent);
    // Remove footer
    $bodyContent = preg_replace('/<!-- UNIFIED FOOTER -->.*?<\/footer>/is', '', $bodyContent);
    // Replace final CTA
    if (strpos($bodyContent, '<!-- FINAL CTA -->') !== false) {
        $bodyContent = preg_replace('/<!-- FINAL CTA -->.*?<\/section>/is', "@include('partials.cta')", $bodyContent);
    }
    
    // For blog detail and others that might have a newsletter
    if (strpos($bodyContent, '<!-- NEWSLETTER -->') !== false) {
        $bodyContent = preg_replace('/<!-- NEWSLETTER -->.*?<\/section>/is', "@include('partials.newsletter')", $bodyContent);
    }
    
    $bodyContent = fixRoutes($bodyContent);
    $bodyContent = fixAssets($bodyContent);
    
    // Trim extra spaces
    $bodyContent = preg_replace('/\n\s*\n/', "\n\n", $bodyContent);
    $bodyContent = trim($bodyContent);
    
    $title = ucwords(str_replace('.html', '', $htmlFile));
    
    $bladeContent = "@extends('layouts.guest')\n\n";
    $bladeContent .= "@section('title', '$title - ' . (\$setting->company_name ?? config('app.name')))\n\n";
    $bladeContent .= "@section('content')\n";
    $bladeContent .= $bodyContent . "\n";
    $bladeContent .= "@endsection\n";
    
    $dir = dirname($bladePath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($bladePath, $bladeContent);
    echo "Migrated $htmlFile -> $bladeFile\n";
}
echo "Done.\n";
