<?php

$file = 'c:/laragon/www/cooca-id/resources/views/pages/home/index.blade.php';
$content = file_get_contents($file);

$replacements = [
    'High-Ticket Business
              Infrastructure' => "{!! setting('home.hero.badge', 'High-Ticket Business Infrastructure') !!}",
    
    'Your Business System<br />Deserves to Work<br /><span
                class="text-gradient"
                >Like an Asset, Not a Liability.</span
              >' => "{!! setting('home.hero.title', 'Your Business System<br />Deserves to Work<br /><span class=\"text-gradient\">Like an Asset, Not a Liability.</span>') !!}",
              
    '<strong
                >Most businesses lose revenue through fragmented tools.</strong
              >
              Disconnected systems, recurring fees that never stop, and software
              that owns you — not the other way around. There\'s a better way.' => "{!! setting('home.hero.subtitle', '<strong>Most businesses lose revenue through fragmented tools.</strong> Disconnected systems, recurring fees that never stop, and software that owns you — not the other way around. There\'s a better way.') !!}",
              
    'COOCA replaces the chaos with
              <strong>one integrated system</strong> — lifetime license, modular
              ERP, and full control over your digital business infrastructure.' => "{!! setting('home.hero.description', 'COOCA replaces the chaos with <strong>one integrated system</strong> — lifetime license, modular ERP, and full control over your digital business infrastructure.') !!}",

    'View Pricing' => "{{ setting('home.hero.btn_primary', 'View Pricing') }}",
    'How It Works' => "{{ setting('home.hero.btn_outline', 'How It Works') }}",
    
    '10,000+' => "{{ setting('home.stats.1.value', '10,000+') }}",
    'Businesses Running on COOCA' => "{{ setting('home.stats.1.label', 'Businesses Running on COOCA') }}",
    '99.9%' => "{{ setting('home.stats.2.value', '99.9%') }}",
    'System Uptime' => "{{ setting('home.stats.2.label', 'System Uptime') }}",
    '500M+' => "{{ setting('home.stats.3.value', '500M+') }}",
    'Secure Transactions' => "{{ setting('home.stats.3.label', 'Secure Transactions') }}",
    
    'Revenue This Month' => "{{ setting('home.dash.1.title', 'Revenue This Month') }}",
    '$284,500' => "{{ setting('home.dash.1.value', '$284,500') }}",
    '+24.5% growth' => "{{ setting('home.dash.1.change', '+24.5% growth') }}",
    
    'Active Licenses' => "{{ setting('home.dash.2.title', 'Active Licenses') }}",
    '12,847' => "{{ setting('home.dash.2.value', '12,847') }}",
    'All protected' => "{{ setting('home.dash.2.change', 'All protected') }}",
    
    'System Status' => "{{ setting('home.card.1.title', 'System Status') }}",
    'Protected ✓' => "{{ setting('home.card.1.value', 'Protected ✓') }}",
    
    'Monthly Growth' => "{{ setting('home.card.2.title', 'Monthly Growth') }}",
    '+Rp48jt MRR' => "{{ setting('home.card.2.value', '+Rp48jt MRR') }}",
    
    'Businesses Trust COOCA' => "{{ setting('home.counter.1.label', 'Businesses Trust COOCA') }}",
    'Guaranteed Uptime SLA' => "{{ setting('home.counter.2.label', 'Guaranteed Uptime SLA') }}",
    'Transactions Processed' => "{{ setting('home.counter.3.label', 'Transactions Processed') }}",
    
    '1 Customer =' => "{!! setting('home.trust.title.prefix', '1 Customer =') !!}",
    '<span class="text-gradient">1 Isolated System</span>' => "{!! setting('home.trust.title.gradient', '<span class=\"text-gradient\">1 Isolated System</span>') !!}",
    
    'Your own dedicated infrastructure. Fully separated. Independent
                security. Not shared — <strong>yours alone</strong>.' => "{!! setting('home.trust.subtitle', 'Your own dedicated infrastructure. Fully separated. Independent security. Not shared — <strong>yours alone</strong>.') !!}",
                
    'Industry Solutions' => "{{ setting('home.products.badge', 'Industry Solutions') }}",
    'Built for <span class="text-gradient">Every Industry</span>' => "{!! setting('home.products.title', 'Built for <span class=\"text-gradient\">Every Industry</span>') !!}",
    'Nine specialized business systems — each engineered to replace
            fragmented tools that drain your time, revenue, and peace of mind.' => "{!! setting('home.products.subtitle', 'Nine specialized business systems — each engineered to replace fragmented tools that drain your time, revenue, and peace of mind.') !!}",
            
    'Business Capabilities' => "{{ setting('home.modules.badge', 'Business Capabilities') }}",
    'Everything Your Business Needs to
            <span class="text-gradient">Scale</span>' => "{!! setting('home.modules.title', 'Everything Your Business Needs to <span class=\"text-gradient\">Scale</span>') !!}",
    'Ten integrated capabilities replacing dozens of separate
            subscriptions. Each one works with the others — because they were
            built to.' => "{!! setting('home.modules.subtitle', 'Ten integrated capabilities replacing dozens of separate subscriptions. Each one works with the others — because they were built to.') !!}",
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced static strings in home/index.blade.php\n";
