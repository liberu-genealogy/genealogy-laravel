<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_x_content_type_options_header_is_set(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_x_frame_options_header_is_set(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_referrer_policy_header_is_set(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_content_security_policy_header_is_set(): void
    {
        $response = $this->get('/');

        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
    }

    public function test_permissions_policy_header_is_set(): void
    {
        $response = $this->get('/');

        $this->assertNotNull($response->headers->get('Permissions-Policy'));
    }

    public function test_x_powered_by_header_is_removed(): void
    {
        $response = $this->get('/');

        $this->assertNull($response->headers->get('X-Powered-By'));
    }

    public function test_plain_http_does_not_force_insecure_request_upgrades(): void
    {
        // upgrade-insecure-requests on a plain-http response makes the browser
        // refetch http-served assets over https, which the http dev host does not
        // serve — the reported CORS/blocked asset. It only belongs on https.
        $response = $this->get('http://localhost/');

        $this->assertStringNotContainsString(
            'upgrade-insecure-requests',
            (string) $response->headers->get('Content-Security-Policy')
        );
    }

    public function test_https_still_upgrades_insecure_requests(): void
    {
        $response = $this->get('https://localhost/');

        $this->assertStringContainsString(
            'upgrade-insecure-requests',
            (string) $response->headers->get('Content-Security-Policy')
        );
    }
}
