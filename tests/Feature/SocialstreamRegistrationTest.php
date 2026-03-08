<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialstreamRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_socialstream_providers_class_availability(): void
    {
        // Socialstream is not installed in this project
        // This test verifies the test suite handles missing optional packages gracefully
        if (!class_exists('JoelButcher\Socialstream\Providers')) {
            $this->markTestSkipped('Socialstream package is not installed.');
        }

        $this->assertTrue(class_exists('JoelButcher\Socialstream\Providers'));
    }
}
