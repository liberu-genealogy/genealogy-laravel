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
}
