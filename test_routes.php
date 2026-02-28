<?php
// Quick diagnostic test
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$urls = ['/', '/monitoring-map', '/login', '/api/markers'];
foreach ($urls as $url) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    try {
        $response = $kernel->handle($request);
        echo "[$url] Status: " . $response->getStatusCode() . "\n";
    } catch (Exception $e) {
        echo "[$url] ERROR: " . $e->getMessage() . "\n";
    }
}
echo "DONE\n";
