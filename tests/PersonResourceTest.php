<?php

namespace Tests;

use App\Http\Resources\PersonResource;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonResourceTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup code if needed
    }

    public function testPersonResourceReturnsExpectedData()
    {
        // Test code for the expected data returned by PersonResource
    }

    public function testPersonResourceIncludesAdditionalData()
    {
        // Test code for the additional data included by PersonResource
    }

    // Add more test methods for other functionalities being tested

    // ...

    protected function tearDown(): void
    {
        // Additional teardown code if needed

        parent::tearDown();
    }
}
