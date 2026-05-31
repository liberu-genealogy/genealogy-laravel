<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies OWASP-recommended HTTP security headers to every response.
 *
 * References:
 *  - https://owasp.org/www-project-secure-headers/
 *  - https://cheatsheetseries.owasp.org/cheatsheets/HTTP_Headers_Cheat_Sheet.html
 */
final class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Prevent MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking — allow framing only from same origin
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Legacy XSS filter (modern browsers ignore this; kept for older ones)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disable unnecessary browser features
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=(), usb=(), fullscreen=(self)'
        );

        // Content Security Policy
        // Allow inline styles/scripts for Filament/Livewire compatibility; tighten per route if needed
        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' data: https://fonts.gstatic.com",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' ws: wss: https://api.stripe.com",
            "frame-src 'self' https://js.stripe.com https://hooks.stripe.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests",
        ]));

        // HSTS — only set over HTTPS
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Remove potentially leaky headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
