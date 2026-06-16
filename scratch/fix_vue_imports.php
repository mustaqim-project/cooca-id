<?php
$dir = new RecursiveDirectoryIterator('c:/laragon/www/cooca-id/resources/js/Pages');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $file) {
    if ($file->getExtension() === 'vue') {
        $content = file_get_contents($file->getPathname());
        $changed = false;
        
        if (strpos($content, '@/Layouts/AuthenticatedLayout.vue') !== false) {
            $content = str_replace('@/Layouts/AuthenticatedLayout.vue', '@/Layouts/AdminLayout.vue', $content);
            $changed = true;
        }
        if (strpos($content, '@/Components/SelectInput.vue') !== false) {
            $content = str_replace('@/Components/SelectInput.vue', '@/Components/forms/SelectInput.vue', $content);
            $changed = true;
        }
        if (strpos($content, '@/Components/Modal.vue') !== false) {
            $content = str_replace('@/Components/Modal.vue', '@/Components/modals/Modal.vue', $content);
            $changed = true;
        }
        
        if ($changed) {
            file_put_contents($file->getPathname(), $content);
            echo 'Fixed ' . $file->getPathname() . "\n";
        }
    }
}
echo "Done replacing paths.\n";
