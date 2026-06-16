<?php

$file = 'c:/laragon/www/cooca-id/resources/views/pages/solutions/index.blade.php';
$content = file_get_contents($file);

$replacements = [
    'Industry Solutions' => "{{ setting('solutions.hero.badge', 'Industry Solutions') }}",
    'Purpose-Built for <span class="text-gradient">Every Industry</span>' => "{!! setting('solutions.hero.title', 'Purpose-Built for <span class=\"text-gradient\">Every Industry</span>') !!}",
    'Nine specialized systems — each engineered to replace the fragmented tools that drain your time, cash, and sanity. One license. One infrastructure. Yours forever.' => "{!! setting('solutions.hero.subtitle', 'Nine specialized systems — each engineered to replace the fragmented tools that drain your time, cash, and sanity. One license. One infrastructure. Yours forever.') !!}",
    
    'Commerce & Retail' => "{{ setting('solutions.retail.badge', 'Commerce & Retail') }}",
    'Sell More. Manage Less. <span class="text-gradient">Own Everything.</span>' => "{!! setting('solutions.retail.title', 'Sell More. Manage Less. <span class=\"text-gradient\">Own Everything.</span>') !!}",
    'From multi-outlet POS to smart inventory — retail solutions that scale with your ambition.' => "{!! setting('solutions.retail.subtitle', 'From multi-outlet POS to smart inventory — retail solutions that scale with your ambition.') !!}",
    
    'Hospitality & Services' => "{{ setting('solutions.hospitality.badge', 'Hospitality & Services') }}",
    'Every Guest. Every Room. <span class="text-gradient">Every Revenue Source.</span>' => "{!! setting('solutions.hospitality.title', 'Every Guest. Every Room. <span class=\"text-gradient\">Every Revenue Source.</span>') !!}",
    'From table to hotel to rental — service industry systems that maximize occupancy and revenue.' => "{!! setting('solutions.hospitality.subtitle', 'From table to hotel to rental — service industry systems that maximize occupancy and revenue.') !!}",
    
    'Health & Professional' => "{{ setting('solutions.health.badge', 'Health & Professional') }}",
    'Compliant. Integrated. <span class="text-gradient">Finally Stress-Free.</span>' => "{!! setting('solutions.health.title', 'Compliant. Integrated. <span class=\"text-gradient\">Finally Stress-Free.</span>') !!}",
    'EMR, workshop, education — professional systems designed for compliance and operational excellence.' => "{!! setting('solutions.health.subtitle', 'EMR, workshop, education — professional systems designed for compliance and operational excellence.') !!}",
    
    'Not Sure Which Solution <span class="text-gradient">Fits Your Business?</span>' => "{!! setting('solutions.cta.title', 'Not Sure Which Solution <span class=\"text-gradient\">Fits Your Business?</span>') !!}",
    'Start your free 30-day trial and explore all nine industry systems. Or talk to our team — we\'ll match you in 15 minutes.' => "{!! setting('solutions.cta.subtitle', 'Start your free 30-day trial and explore all nine industry systems. Or talk to our team — we\'ll match you in 15 minutes.') !!}"
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced static strings in solutions/index.blade.php\n";
