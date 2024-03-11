<?php

namespace Tests;

use App\Http\Resources\GedcomResource;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup code if needed
    }

    public function testGedcomResourceReturnsExpectedData()
    {
        // Test code for the expected data returned by GedcomResource
    }

    public function testGedcomResourceIncludesAdditionalData()
    {
        // Test code for the additional data included by GedcomResource
    }

    // Add more test methods for other functionalities being tested

    // ...

    protected function tearDown(): void
    {
        // Additional teardown code if needed

        parent::tearDown();
    }
}
