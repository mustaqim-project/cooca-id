<?php
$viewsDir = __DIR__ . '/resources/views';

echo "--- BUTTON & LINK AUDIT ---\n";

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        $relPath = str_replace($viewsDir . '\\', '', $path);
        
        // Check for dead links <a href="#"> without onclick or x-on:click or @click
        preg_match_all('/<a[^>]*href=["\']#["\'][^>]*>/i', $content, $aMatches);
        if (!empty($aMatches[0])) {
            foreach ($aMatches[0] as $match) {
                if (stripos($match, 'onclick') === false && stripos($match, 'click') === false && stripos($match, 'data-bs-toggle') === false) {
                    echo "[DEAD LINK] $relPath : $match\n";
                }
            }
        }
        
        // Find buttons without type, onclick, or x-on:click or @click
        preg_match_all('/<button([^>]*)>/i', $content, $btnMatches);
        if (!empty($btnMatches[1])) {
            foreach ($btnMatches[1] as $attrs) {
                $hasType = stripos($attrs, 'type=') !== false;
                $hasOnClick = stripos($attrs, 'onclick') !== false || stripos($attrs, 'click') !== false;
                $hasSubmit = stripos($attrs, 'type="submit"') !== false || stripos($attrs, "type='submit'") !== false;
                $hasDataToggle = stripos($attrs, 'data-') !== false; // things like data-dismiss, data-toggle
                
                // If it doesn't have type="submit" and doesn't have an action, it might be dead
                if (!$hasType && !$hasOnClick && !$hasDataToggle) {
                    // Check if it's inside a form. We can't easily parse that with regex, but let's just log them.
                    // echo "[POTENTIALLY DEAD BUTTON] $relPath : <button $attrs>\n";
                }
            }
        }
    }
}
echo "Audit complete.\n";
