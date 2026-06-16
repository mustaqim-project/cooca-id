<?php

$file = 'c:/laragon/www/cooca-id/resources/views/pages/pricing/index.blade.php';
$content = file_get_contents($file);

$replacements = [
    'Transparent Pricing' => "{{ setting('pricing.hero.badge', 'Transparent Pricing') }}",
    'Simple Pricing. <span class="text-gradient">Honest Value.</span>' => "{!! setting('pricing.hero.title', 'Simple Pricing. <span class=\"text-gradient\">Honest Value.</span>') !!}",
    'All plans include full access to all modules with unlimited users. No hidden tiers. No module paywalls. Choose how you want to invest — not whether you can access the system.' => "{!! setting('pricing.hero.subtitle', 'All plans include full access to all modules with unlimited users. No hidden tiers. No module paywalls. Choose how you want to invest — not whether you can access the system.') !!}",
    
    'Feature Comparison' => "{{ setting('pricing.compare.badge', 'Feature Comparison') }}",
    'Side-by-Side <span class="text-gradient">Plan Breakdown</span>' => "{!! setting('pricing.compare.title', 'Side-by-Side <span class=\"text-gradient\">Plan Breakdown</span>') !!}",
    
    'Pricing FAQ' => "{{ setting('pricing.faq.badge', 'Pricing FAQ') }}",
    'Questions About <span class="text-gradient">Investment?</span>' => "{!! setting('pricing.faq.title', 'Questions About <span class=\"text-gradient\">Investment?</span>') !!}",
    
    'Start Free. <span class="text-gradient">Upgrade When Ready.</span>' => "{!! setting('pricing.cta.title', 'Start Free. <span class=\"text-gradient\">Upgrade When Ready.</span>') !!}",
    '30 days. Full access. No credit card. The only risk is not finding out how much revenue you\'ve been leaving on the table.' => "{!! setting('pricing.cta.subtitle', '30 days. Full access. No credit card. The only risk is not finding out how much revenue you\'ve been leaving on the table.') !!}",
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced static strings in pricing/index.blade.php\n";
