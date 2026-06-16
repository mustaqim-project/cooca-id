<?php

$file = 'c:/laragon/www/cooca-id/resources/views/pages/contact/index.blade.php';
$content = file_get_contents($file);

$replacements = [
    'Get in Touch' => "{{ setting('contact.hero.badge', 'Get in Touch') }}",
    'We Respond Fast <span class="text-gradient">Because Business Can\'t Wait.</span>' => "{!! setting('contact.hero.title', 'We Respond Fast <span class=\"text-gradient\">Because Business Can\'t Wait.</span>') !!}",
    'Sales questions, technical support, partnership inquiries, or just not sure where to start — our team is ready.' => "{!! setting('contact.hero.subtitle', 'Sales questions, technical support, partnership inquiries, or just not sure where to start — our team is ready.') !!}",
    
    'Send Us a Message' => "{{ setting('contact.form.title', 'Send Us a Message') }}",
    'Fill out the form and we\'ll route it to the right person immediately.' => "{{ setting('contact.form.subtitle', 'Fill out the form and we\'ll route it to the right person immediately.') }}",
    
    'Find Us' => "{{ setting('contact.find.badge', 'Find Us') }}",
    
    'Headquarters' => "{{ setting('contact.address.title', 'Headquarters') }}",
    'Jl. Jend. Sudirman Kav. 52–53<br>Jakarta Selatan 12190, Indonesia' => "{!! setting('contact.address.detail', 'Jl. Jend. Sudirman Kav. 52–53<br>Jakarta Selatan 12190, Indonesia') !!}",
    
    'General Inquiries' => "{{ setting('contact.email.title', 'General Inquiries') }}",
    'support@cooca.io<br>sales@cooca.io' => "{!! setting('contact.email.detail', 'support@cooca.io<br>sales@cooca.io') !!}",
    
    'WhatsApp Sales' => "{{ setting('contact.wa.title', 'WhatsApp Sales') }}",
    '+62 812 3456 7890<br>Mon–Fri · 08:00–18:00 WIB' => "{!! setting('contact.wa.detail', '+62 812 3456 7890<br>Mon–Fri · 08:00–18:00 WIB') !!}",
    
    'Response Times' => "{{ setting('contact.response.title', 'Response Times') }}",
    'WhatsApp: &lt; 2 hours<br>Email: &lt; 8 business hours<br>Enterprise SLA: custom' => "{!! setting('contact.response.detail', 'WhatsApp: &lt; 2 hours<br>Email: &lt; 8 business hours<br>Enterprise SLA: custom') !!}",
    
    'Follow Us' => "{{ setting('contact.follow.badge', 'Follow Us') }}"
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced static strings in contact/index.blade.php\n";
