<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $file) {
    if(pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        if(strpos($content, 'href="#"') !== false) {
            $content = str_replace('href="#"', 'href="javascript:void(0)"', $content);
            file_put_contents($path, $content);
            echo "Replaced in: " . $path . "\n";
        }
    }
}
