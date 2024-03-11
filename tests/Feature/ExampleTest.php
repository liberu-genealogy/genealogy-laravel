<?php

/**
 * File: ExampleTest.php
 * Author: [Your Name]
 * Date: [Current Date]
 * Description: This file contains the ExampleTest class for testing the application.
 */

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
