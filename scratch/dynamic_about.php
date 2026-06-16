<?php

$file = 'c:/laragon/www/cooca-id/resources/views/pages/about/index.blade.php';
$content = file_get_contents($file);

$replacements = [
    'Our Story' => "{{ setting('about.hero.badge', 'Our Story') }}",
    'Built Because We Were <span class="text-gradient">Tired of Renting.</span>' => "{!! setting('about.hero.title', 'Built Because We Were <span class=\"text-gradient\">Tired of Renting.</span>') !!}",
    'COOCA was born from frustration — with subscription traps, fragmented tools, and software that grows your vendor\'s business more than yours.' => "{!! setting('about.hero.subtitle', 'COOCA was born from frustration — with subscription traps, fragmented tools, and software that grows your vendor\'s business more than yours.') !!}",
    
    'Mission' => "{{ setting('about.mission.badge', 'Mission') }}",
    'Every Business Deserves to <span class="text-gradient">Own Its Infrastructure</span>' => "{!! setting('about.mission.title', 'Every Business Deserves to <span class=\"text-gradient\">Own Its Infrastructure</span>') !!}",
    'The SaaS model created a world where businesses rent the tools they depend on — indefinitely. Every month, cash flows out. Every year, the dependency deepens. And if you ever stop paying, you lose everything you built on top of it.' => "{!! setting('about.mission.p1', 'The SaaS model created a world where businesses rent the tools they depend on — indefinitely. Every month, cash flows out. Every year, the dependency deepens. And if you ever stop paying, you lose everything you built on top of it.') !!}",
    'COOCA flips this model. We believe business software should be an asset that appreciates, not a liability that bleeds. Our lifetime license model gives you permanent ownership with one investment — and our isolated infrastructure ensures your system belongs to you alone.' => "{!! setting('about.mission.p2', 'COOCA flips this model. We believe business software should be an asset that appreciates, not a liability that bleeds. Our lifetime license model gives you permanent ownership with one investment — and our isolated infrastructure ensures your system belongs to you alone.') !!}",
    
    'Core Values' => "{{ setting('about.values.badge', 'Core Values') }}",
    'The Principles We <span class="text-gradient">Never Compromise</span>' => "{!! setting('about.values.title', 'The Principles We <span class=\"text-gradient\">Never Compromise</span>') !!}",
    
    'Ownership First' => "{{ setting('about.values.1.title', 'Ownership First') }}",
    'We believe your business system should be an asset you own — not a service you rent. Every product decision is made with ownership in mind.' => "{!! setting('about.values.1.desc', 'We believe your business system should be an asset you own — not a service you rent. Every product decision is made with ownership in mind.') !!}",
    
    'Radical Transparency' => "{{ setting('about.values.2.title', 'Radical Transparency') }}",
    'No hidden fees, no surprise upgrades, no dark patterns. Pricing is honest, contracts are simple, and support is real humans — not bots.' => "{!! setting('about.values.2.desc', 'No hidden fees, no surprise upgrades, no dark patterns. Pricing is honest, contracts are simple, and support is real humans — not bots.') !!}",
    
    'Security as Architecture' => "{{ setting('about.values.3.title', 'Security as Architecture') }}",
    'Security isn\'t a feature we added — it\'s the foundation we built on. Isolation, encryption, and domain binding are non-negotiable defaults.' => "{!! setting('about.values.3.desc', 'Security isn\'t a feature we added — it\'s the foundation we built on. Isolation, encryption, and domain binding are non-negotiable defaults.') !!}",
    
    'Modular by Design' => "{{ setting('about.values.4.title', 'Modular by Design') }}",
    'Start small, scale intelligently. Every module is built to work alone or in concert with the others — no forced bundles, no wasted spend.' => "{!! setting('about.values.4.desc', 'Start small, scale intelligently. Every module is built to work alone or in concert with the others — no forced bundles, no wasted spend.') !!}",
    
    'Partner, Not Vendor' => "{{ setting('about.values.5.title', 'Partner, Not Vendor') }}",
    'When you grow, we grow. Our affiliate program, migration support, and dedicated success team exist because your success is our business model.' => "{!! setting('about.values.5.desc', 'When you grow, we grow. Our affiliate program, migration support, and dedicated success team exist because your success is our business model.') !!}",
    
    'Speed Matters' => "{{ setting('about.values.6.title', 'Speed Matters') }}",
    '30-minute provisioning, 24/7 support response, automated updates. Business doesn\'t wait — neither do we.' => "{!! setting('about.values.6.desc', '30-minute provisioning, 24/7 support response, automated updates. Business doesn\'t wait — neither do we.') !!}",
    
    'Our Journey' => "{{ setting('about.journey.badge', 'Our Journey') }}",
    'From a <span class="text-gradient">Frustrated Founder</span> to 10,000+ Businesses' => "{!! setting('about.journey.title', 'From a <span class=\"text-gradient\">Frustrated Founder</span> to 10,000+ Businesses') !!}",
    'COOCA started as a solution to a problem we lived. As the system matured, so did our conviction: businesses deserved better than the SaaS status quo.' => "{!! setting('about.journey.subtitle', 'COOCA started as a solution to a problem we lived. As the system matured, so did our conviction: businesses deserved better than the SaaS status quo.') !!}",
    
    'Leadership' => "{{ setting('about.team.badge', 'Leadership') }}",
    'The Team Behind <span class="text-gradient">the System</span>' => "{!! setting('about.team.title', 'The Team Behind <span class=\"text-gradient\">the System</span>') !!}",
    'Operators, engineers, and designers who\'ve built and run businesses — and built the tools they wished they had.' => "{!! setting('about.team.subtitle', 'Operators, engineers, and designers who\'ve built and run businesses — and built the tools they wished they had.') !!}",
    
    'Ready to Own Your <span class="text-gradient">Business Infrastructure?</span>' => "{!! setting('cta.title', 'Ready to Own Your <span class=\"text-gradient\">Business Infrastructure?</span>') !!}",
    'Join 10,000+ businesses that chose ownership over renting. Start your 30-day free trial — no credit card required.' => "{!! setting('cta.subtitle', 'Join 10,000+ businesses that chose ownership over renting. Start your 30-day free trial — no credit card required.') !!}",
    
    'Talk to Sales' => "{{ setting('cta.btn_outline', 'Talk to Sales') }}"
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced static strings in about/index.blade.php\n";
