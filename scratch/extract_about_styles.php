<?php
$content = file_get_contents('c:/laragon/www/cooca-id/resources/views/html/about.html');
if (preg_match('/<style>(.*?)<\/style>/s', $content, $matches)) {
    file_put_contents('c:/laragon/www/cooca-id/scratch/about_styles.css', $matches[1]);
    echo "Extracted to about_styles.css\n";
} else {
    echo "No style block found.\n";
}
