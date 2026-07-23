<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$app->instance(\App\Http\Middleware\VerifyCsrfToken::class, new class {
    public function handle($request, $next) { return $next($request); }
});

try {
    $request = Illuminate\Http\Request::create('/affiliator/login', 'POST', [
        'email' => 'affiliate@cooca.id',
        'password' => 'password123',
    ]);
    
    $response = $app->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);
    echo $response->getContent();
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
