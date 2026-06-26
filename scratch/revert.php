<?php

// 1. Read home.html
$homeHtml = file_get_contents('resources/views/html/home.html');

// Extract sections
preg_match('/<!-- PRODUCT ECOSYSTEM — 3 CORE BUSINESS TABS -->.*?<\/section>/s', $homeHtml, $productsMatch);
preg_match('/<!-- CORE CAPABILITIES — 3 GROUPED TABS -->.*?<\/section>/s', $homeHtml, $modulesMatch);
preg_match('/<!-- PRICING -->.*?<\/section>/s', $homeHtml, $pricingMatch);

$productsSection = $productsMatch[0] ?? '';
$modulesSection = $modulesMatch[0] ?? '';
$pricingSection = $pricingMatch[0] ?? '';

// Replace buttons in the extracted sections
$replacements = [
    '<a href="#" class="btn-cooca btn-cooca-outline btn-cooca-sm">Learn More</a>' => '<a href="{{ route(\'product.show\', \'demo-product\') }}" class="btn-cooca btn-cooca-outline btn-cooca-sm">Product Detail</a>',
    '<a href="#" class="btn-cooca btn-cooca-primary btn-cooca-sm">Live Demo</a>' => '<a href="javascript:void(0)" class="btn-cooca btn-cooca-primary btn-cooca-sm">Live Demo</a>',
    '<a href="pricing.html" class="btn-cooca btn-cooca-primary"' => '<a href="{{ route(\'pricing\') }}" class="btn-cooca btn-cooca-primary"',
    'href="affiliate.html"' => 'href="{{ route(\'affiliate\') }}"',
    '<a href="#" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center">Start Free Trial</a>' => '<a href="{{ route(\'customer.register\') }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center">Start Free Trial</a>',
    '<a href="#" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center">Start Monthly</a>' => '<a href="{{ route(\'customer.register\') }}" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center">Start Monthly</a>',
    '<a href="#" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center">Start 3 Months</a>' => '<a href="{{ route(\'customer.register\') }}" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center">Start 3 Months</a>',
    '<a href="#" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center">Start Annual</a>' => '<a href="{{ route(\'customer.register\') }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center">Start Annual</a>',
    '<a href="#" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center">Own It Forever</a>' => '<a href="{{ route(\'customer.register\') }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center">Own It Forever</a>',
    '<a href="#" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center">Contact Us <i class="bi bi-chat-dots"></i></a>' => '<a href="{{ route(\'contact\') }}" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center">Contact Us <i class="bi bi-chat-dots"></i></a>',
];

foreach ($replacements as $search => $replace) {
    $productsSection = str_replace($search, $replace, $productsSection);
    $modulesSection = str_replace($search, $replace, $modulesSection);
    $pricingSection = str_replace($search, $replace, $pricingSection);
}

// Write to files

// --- HOME ---
$homePhp = file_get_contents('resources/views/pages/home/index.blade.php');
// Remove the Swiper JS & CSS blocks if possible, or just leave them. We'll leave them.
// Replace #products
$homePhp = preg_replace('/<!-- PRODUCT ECOSYSTEM(.*?)<\/section>/s', $productsSection, $homePhp, 1);
// Ensure #pricing is present. The home page currently doesn't have it. We will insert it before the final CTA.
if (strpos($homePhp, 'id="pricing"') === false) {
    $homePhp = preg_replace('/<!-- AFFILIATE PROGRAM -->(.*?)<\/section>/s', "<!-- AFFILIATE PROGRAM -->$1</section>\n\n$pricingSection", $homePhp, 1);
}
file_put_contents('resources/views/pages/home/index.blade.php', $homePhp);
echo "home/index.blade.php updated\n";

// --- SOLUTIONS ---
$solPhp = file_get_contents('resources/views/pages/solutions/index.blade.php');
// Replace the dynamic products section we added with the static products & modules
$solPhp = preg_replace(
    '/<!-- =====================================\s+DYNAMIC PRODUCTS LISTING\s+===================================== -->.*?<\/section>/s',
    $productsSection . "\n\n" . $modulesSection,
    $solPhp
);
file_put_contents('resources/views/pages/solutions/index.blade.php', $solPhp);
echo "solutions/index.blade.php updated\n";

// --- PRICING ---
$pricePhp = file_get_contents('resources/views/pages/pricing/index.blade.php');
// Replace the #pricing section (which has Swiper) with the static pricing
$pricePhp = preg_replace(
    '/<!-- DYNAMIC PRICING SLIDER -->.*?<\/section>/s',
    $pricingSection,
    $pricePhp
);
// In case the marker was different:
if (strpos($pricePhp, '<!-- COMPREHENSIVE COMPARISON TABLE -->') !== false) {
    $pricePhp = preg_replace(
        '/<section class="section-padding" id="pricing".*?<!-- COMPREHENSIVE COMPARISON TABLE -->/s',
        $pricingSection . "\n\n<!-- COMPREHENSIVE COMPARISON TABLE -->",
        $pricePhp
    );
}
file_put_contents('resources/views/pages/pricing/index.blade.php', $pricePhp);
echo "pricing/index.blade.php updated\n";

