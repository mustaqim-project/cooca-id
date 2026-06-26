<?php
$files = ['resources/views/pages/home/index.blade.php', 'resources/views/pages/solutions/index.blade.php', 'resources/views/pages/pricing/index.blade.php'];
foreach($files as $f) {
    file_put_contents($f, str_replace('route(\'product.show\'', 'route(\'products.show\'', file_get_contents($f)));
}
echo "Fixed route names.\n";
