<?php

$files = [
    'App\\Http\\Controllers\\Web\\BlogController' => 'c:/laragon/www/cooca-id/app/Http/Controllers/Web/BlogController.php',
    'App\\Http\\Controllers\\Web\\ProductController' => 'c:/laragon/www/cooca-id/app/Http/Controllers/Web/ProductController.php',
    'App\\Http\\Controllers\\Web\\NewsletterController' => 'c:/laragon/www/cooca-id/app/Http/Controllers/Web/NewsletterController.php',
    'App\\Http\\Controllers\\AuthController' => 'c:/laragon/www/cooca-id/app/Http/Controllers/AuthController.php',
    'App\\Http\\Controllers\\Auth\\PasswordResetController' => 'c:/laragon/www/cooca-id/app/Http/Controllers/Auth/PasswordResetController.php',
];

$landingFile = 'c:/laragon/www/cooca-id/app/Http/Controllers/Web/LandingController.php';

$allUses = [];
$allBodies = [];

// Helper to extract use statements and class body
function extractParts($file, &$allUses, &$allBodies) {
    if (!file_exists($file)) return;
    $content = file_get_contents($file);
    
    // Extract use statements at the top
    preg_match_all('/^use\s+([^;]+);/m', $content, $matches);
    foreach ($matches[0] as $useStmt) {
        $allUses[] = trim($useStmt);
    }
    
    // Extract class body
    $startPos = strpos($content, '{', strpos($content, 'class '));
    $endPos = strrpos($content, '}');
    
    if ($startPos !== false && $endPos !== false) {
        $body = substr($content, $startPos + 1, $endPos - $startPos - 1);
        $allBodies[] = trim($body);
    }
}

// 1. Process LandingController first to keep its methods at top
extractParts($landingFile, $allUses, $allBodies);

// 2. Process all other controllers
foreach ($files as $class => $file) {
    extractParts($file, $allUses, $allBodies);
}

// 3. Clean up use statements
$allUses = array_unique($allUses);
// Remove self-references or base controller if needed, but array_unique is usually enough.
$useBlock = implode("\n", $allUses);

// 4. Clean up bodies. AuthController has a constructor, others don't.
// Let's just concatenate them. The only constructor is in AuthController.
$bodyBlock = implode("\n\n    /* ========================================== */\n\n    ", $allBodies);

// 5. Generate new LandingController
$newContent = <<<PHP
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

$useBlock

/**
 * Landing Controller (Unified)
 *
 * Handles public-facing landing pages, blog, product catalog, newsletter, auth, and password resets.
 */
class LandingController extends Controller
{
    $bodyBlock
}
PHP;

file_put_contents($landingFile, $newContent);
echo "Updated LandingController.php\n";

// 6. Update routes/web.php
$webPhpPath = 'c:/laragon/www/cooca-id/routes/web.php';
$webPhpContent = file_get_contents($webPhpPath);

$replacements = [
    'BlogController::class' => 'LandingController::class',
    'ProductController::class' => 'LandingController::class',
    'NewsletterController::class' => 'LandingController::class',
    'AuthController::class' => 'LandingController::class',
    'PasswordResetController::class' => 'LandingController::class',
];

$webPhpContent = str_replace(array_keys($replacements), array_values($replacements), $webPhpContent);

file_put_contents($webPhpPath, $webPhpContent);
echo "Updated web.php\n";
