<?php
// Simple healthcheck script for Laravel app container.
// Returns 0 (success) when the app is healthy; non-zero otherwise.

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

try {
    // Check database connection
    DB::connection()->getPdo();

    // Optionally check a simple artisan command; octane:status may not be available or configured
    $exitCode = null;
    exec('php artisan octane:status 2>&1', $output, $exitCode);

    // If octane isn't configured, consider the app healthy if DB is reachable
    if ($exitCode !== 0) {
        // If it fails but DB is OK, return success to avoid false negatives
        exit(0);
    }

    exit(0);
} catch (\Exception $e) {
    // Log to STDERR and return non-zero for Docker to mark unhealthy
    file_put_contents('php://stderr', "Healthcheck failed: " . $e->getMessage());
    exit(1);
}
