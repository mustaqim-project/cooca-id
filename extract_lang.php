<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
$strings = [];
foreach ($files as $file) {
    if ($file->isDir()) continue;
    $content = file_get_contents($file->getPathname());
    
    // Extract __('')
    preg_match_all("/__\('(.*?)'\)/s", $content, $matches);
    foreach ($matches[1] as $match) {
        $strings[$match] = $match;
    }
    
    // Extract __("")
    preg_match_all('/__\("(.*?)"\)/s', $content, $matches);
    foreach ($matches[1] as $match) {
        $strings[$match] = $match;
    }
}

$idFile = 'lang/id.json';
$enFile = 'lang/en.json';

$existingId = file_exists($idFile) ? (json_decode(file_get_contents($idFile), true) ?: []) : [];
$existingEn = file_exists($enFile) ? (json_decode(file_get_contents($enFile), true) ?: []) : [];

$mergedId = array_merge($strings, $existingId);
$mergedEn = array_merge($strings, $existingEn);

// Let's add any english strings from existing files back into the defaults if not already present
foreach ($existingId as $k => $v) {
    if (!isset($mergedId[$k])) $mergedId[$k] = $v;
    if (!isset($mergedEn[$k])) $mergedEn[$k] = $k;
}
foreach ($existingEn as $k => $v) {
    if (!isset($mergedEn[$k])) $mergedEn[$k] = $v;
    if (!isset($mergedId[$k])) $mergedId[$k] = $k;
}

ksort($mergedId);
ksort($mergedEn);

file_put_contents('lang/id.json', json_encode($mergedId, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents('lang/en.json', json_encode($mergedEn, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Extracted strings and merged to lang/id.json and lang/en.json\n";
