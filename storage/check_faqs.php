<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Faq;

$faqs = Faq::where('is_active', true)->orderBy('order')->get();
echo "Active FAQs count: " . $faqs->count() . "\n";
foreach ($faqs as $f) {
    echo "- " . $f->question . " (Category: " . ($f->category ?: 'General') . ")\n";
}
