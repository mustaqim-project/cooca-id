<?php
$viewsDir = __DIR__ . '/resources/views';
$controllersDir = __DIR__ . '/app/Http/Controllers';

$results = [
    'missing_csrf' => [],
    'missing_method' => [],
    'missing_ids' => [],
];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        $relPath = str_replace($viewsDir . '\\', '', $path);
        
        // 1 & 2: Check forms
        preg_match_all('/<form[^>]*method="([^"]+)"[^>]*>/i', $content, $formMatches, PREG_OFFSET_CAPTURE);
        foreach ($formMatches[1] as $index => $match) {
            $method = strtoupper($match[0]);
            $formTag = $formMatches[0][$index][0];
            $formOffset = $formMatches[0][$index][1];
            
            // Extract the body of the form to look for @csrf and @method
            // This is a naive approach: grab next 1000 chars after form
            $formBody = substr($content, $formOffset, 1500);
            
            if ($method === 'POST') {
                if (stripos($formBody, '@csrf') === false && stripos($formBody, 'csrf_field()') === false) {
                    // It could be that the form action is an external URL, but let's flag it
                    $results['missing_csrf'][] = "Form in {$relPath} (offset $formOffset)";
                }
            }
        }
        
        // 3: Check getElementById
        preg_match_all("/getElementById\(['\"]([^'\"]+)['\"]\)/", $content, $idMatches);
        if (!empty($idMatches[1])) {
            foreach ($idMatches[1] as $id) {
                // If the id is not defined as id="x" in the same file
                // It might be in layout, but let's track anyway
                if (stripos($content, 'id="' . $id . '"') === false && stripos($content, "id='" . $id . "'") === false) {
                    $results['missing_ids'][] = "'{$id}' referenced in {$relPath} but not found in file.";
                }
            }
        }
    }
}

echo "--- MISSING CSRF ---\n";
foreach ($results['missing_csrf'] as $msg) echo "- $msg\n";

echo "\n--- MISSING IDs (getElementById) ---\n";
foreach (array_unique($results['missing_ids']) as $msg) {
    // Ignore layout ids like 'searchInput' which might be standard
    echo "- $msg\n";
}
