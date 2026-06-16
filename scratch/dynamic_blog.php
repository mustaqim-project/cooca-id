<?php

$file = 'c:/laragon/www/cooca-id/resources/views/pages/blog/index.blade.php';
$content = file_get_contents($file);

$replacements = [
    'Business Insights' => "{{ setting('blog.hero.badge', 'Business Insights') }}",
    'Guides for Businesses That <span class="text-gradient">Play to Win</span>' => "{!! setting('blog.hero.title', 'Guides for Businesses That <span class=\"text-gradient\">Play to Win</span>') !!}",
    'Practical strategies, industry benchmarks, and operational playbooks — written for operators, not consultants.' => "{!! setting('blog.hero.subtitle', 'Practical strategies, industry benchmarks, and operational playbooks — written for operators, not consultants.') !!}",
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Replaced static strings in blog/index.blade.php\n";
