<?php

$landingFile = 'c:/laragon/www/cooca-id/app/Http/Controllers/Web/LandingController.php';
$content = file_get_contents($landingFile);

// The first 'public function index(' is LandingController's. 
// The second 'public function index(' is BlogController's.
// Let's replace the one from BlogController. We can identify it because it has '$posts = BlogPost::' inside it.
// Wait, a better way is to do string replacement with specific context.

// Replace BlogController index
$content = str_replace(
    "    public function index(Request \$request)\n    {\n        \$posts = BlogPost::where('is_published', true)",
    "    public function blogIndex(Request \$request)\n    {\n        \$posts = BlogPost::where('is_published', true)",
    $content
);

// Replace BlogController show
$content = str_replace(
    "    public function show(string \$slug)\n    {\n        \$post = BlogPost::where('slug', \$slug)",
    "    public function blogShow(string \$slug)\n    {\n        \$post = BlogPost::where('slug', \$slug)",
    $content
);

// Replace ProductController show
$content = str_replace(
    "    public function show(string \$slug, Request \$request)\n    {\n        // Load product",
    "    public function productShow(string \$slug, Request \$request)\n    {\n        // Load product",
    $content
);

file_put_contents($landingFile, $content);
echo "Renamed methods in LandingController.php\n";

$webPhpPath = 'c:/laragon/www/cooca-id/routes/web.php';
$webPhpContent = file_get_contents($webPhpPath);

$webPhpContent = str_replace(
    "[LandingController::class, 'index'])->name('blog.index');",
    "[LandingController::class, 'blogIndex'])->name('blog.index');",
    $webPhpContent
);

$webPhpContent = str_replace(
    "[LandingController::class, 'show'])->name('blog.show');",
    "[LandingController::class, 'blogShow'])->name('blog.show');",
    $webPhpContent
);

$webPhpContent = str_replace(
    "[LandingController::class, 'show'])->name('show');",
    "[LandingController::class, 'productShow'])->name('show');",
    $webPhpContent
);

file_put_contents($webPhpPath, $webPhpContent);
echo "Updated web.php method references\n";
