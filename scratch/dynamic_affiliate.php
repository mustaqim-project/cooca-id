<?php

$file = 'c:/laragon/www/cooca-id/resources/views/pages/affiliate/index.blade.php';
$content = file_get_contents($file);

$replacements = [
    'Affiliate Partner Program' => "{{ setting('affiliate.hero.badge', 'Affiliate Partner Program') }}",
    'Earn <span class="text-gradient">Recurring Revenue</span><br>with COOCA<br><span class="highlight">Affiliate Program</span>' => "{!! setting('affiliate.hero.title', 'Earn <span class=\"text-gradient\">Recurring Revenue</span><br>with COOCA<br><span class=\"highlight\">Affiliate Program</span>') !!}",
    '<strong>Promosikan COOCA dan dapatkan komisi dari setiap customer</strong> yang berhasil Anda referensikan. Sistem komisi transparan dengan potensi penghasilan berulang setiap bulan.' => "{!! setting('affiliate.hero.subtitle', '<strong>Promosikan COOCA dan dapatkan komisi dari setiap customer</strong> yang berhasil Anda referensikan. Sistem komisi transparan dengan potensi penghasilan berulang setiap bulan.') !!}",
    
    'Benefits' => "{{ setting('affiliate.why.badge', 'Benefits') }}",
    'Why Join <span class="text-gradient">COOCA Affiliate</span>' => "{!! setting('affiliate.why.title', 'Why Join <span class=\"text-gradient\">COOCA Affiliate</span>') !!}",
    'Enam alasan kuat mengapa program affiliate COOCA adalah peluang penghasilan terbaik untuk Anda.' => "{!! setting('affiliate.why.subtitle', 'Enam alasan kuat mengapa program affiliate COOCA adalah peluang penghasilan terbaik untuk Anda.') !!}",
    
    'Commission Tiers' => "{{ setting('affiliate.commission.badge', 'Commission Tiers') }}",
    'Commission <span class="text-gradient">Structure</span>' => "{!! setting('affiliate.commission.title', 'Commission <span class=\"text-gradient\">Structure</span>') !!}",
    'Tiga tingkatan partnership yang dirancang untuk mengakomodasi berbagai level kontribusi Anda.' => "{!! setting('affiliate.commission.subtitle', 'Tiga tingkatan partnership yang dirancang untuk mengakomodasi berbagai level kontribusi Anda.') !!}",
    
    'Cara Kerja Komisi' => "{{ setting('affiliate.flow.badge', 'Cara Kerja Komisi') }}",
    'Visual <span class="text-gradient">Alur Komisi</span>' => "{!! setting('affiliate.flow.title', 'Visual <span class=\"text-gradient\">Alur Komisi</span>') !!}",
    
    'Earnings Simulator' => "{{ setting('affiliate.calc.badge', 'Earnings Simulator') }}",
    'Hitung Potensi <span class="text-gradient">Komisi Anda</span>' => "{!! setting('affiliate.calc.title', 'Hitung Potensi <span class=\"text-gradient\">Komisi Anda</span>') !!}",
    
    'Getting Started' => "{{ setting('affiliate.how.badge', 'Getting Started') }}",
    'How It <span class="text-gradient">Works</span>' => "{!! setting('affiliate.how.title', 'How It <span class=\"text-gradient\">Works</span>') !!}",
    
    'Partner Toolkit' => "{{ setting('affiliate.resources.badge', 'Partner Toolkit') }}",
    'Marketing <span class="text-gradient">Resources</span>' => "{!! setting('affiliate.resources.title', 'Marketing <span class=\"text-gradient\">Resources</span>') !!}",
    
    'Performance' => "{{ setting('affiliate.metrics.badge', 'Performance') }}",
    'Affiliate <span class="text-gradient">Success Metrics</span>' => "{!! setting('affiliate.metrics.title', 'Affiliate <span class=\"text-gradient\">Success Metrics</span>') !!}",
    
    'Partner Stories' => "{{ setting('affiliate.testimonials.badge', 'Partner Stories') }}",
    'Affiliate <span class="text-gradient">Testimonials</span>' => "{!! setting('affiliate.testimonials.title', 'Affiliate <span class=\"text-gradient\">Testimonials</span>') !!}",
    
    'FAQ' => "{{ setting('affiliate.faq.badge', 'FAQ') }}",
    'Frequently Asked <span class="text-gradient">Questions</span>' => "{!! setting('affiliate.faq.title', 'Frequently Asked <span class=\"text-gradient\">Questions</span>') !!}",
    
    'Ready to Start?' => "{{ setting('affiliate.cta.badge', 'Ready to Start?') }}",
    'Mulai Hasilkan <span class="text-gradient">Passive Income</span><br>Bersama COOCA' => "{!! setting('affiliate.cta.title', 'Mulai Hasilkan <span class=\"text-gradient\">Passive Income</span><br>Bersama COOCA') !!}",
    'Bergabung dengan ribuan affiliator lainnya yang telah mendapatkan penghasilan rutin bulanan dari mereferensikan solusi bisnis terpercaya.' => "{!! setting('affiliate.cta.subtitle', 'Bergabung dengan ribuan affiliator lainnya yang telah mendapatkan penghasilan rutin bulanan dari mereferensikan solusi bisnis terpercaya.') !!}"
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced static strings in affiliate/index.blade.php\n";
