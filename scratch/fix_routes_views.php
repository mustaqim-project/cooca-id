<?php
$fixes = [
    // Fix wrong route names
    "resources/views/pages/products/index.blade.php" => [
        "route('product.show'" => "route('products.show'",
    ],
    "resources/views/pages/blog/index.blade.php" => [
        "route('subscribe')" => "route('newsletter.subscribe')",
    ],
    "resources/views/pages/blog/detail.blade.php" => [
        "route('subscribe')" => "route('newsletter.subscribe')",
    ],
];

foreach ($fixes as $file => $replacements) {
    $content = file_get_contents($file);
    $original = $content;
    foreach ($replacements as $from => $to) {
        $content = str_replace($from, $to, $content);
    }
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Fixed: $file\n";
    } else {
        echo "No change needed: $file\n";
    }
}
echo "Done.\n";
