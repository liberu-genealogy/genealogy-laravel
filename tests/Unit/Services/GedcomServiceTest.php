<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\GedcomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedcomServiceTest extends TestCase
{
    use RefreshDatabase;

    private GedcomService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GedcomService;
    }

    public function test_service_can_be_instantiated(): void
    {
        $this->assertInstanceOf(GedcomService::class, $this->service);
    }

    public function test_generate_gedcom_string_returns_string(): void
    {
        $result = $this->service->generateGedcomString(0, 0, 0, 0);

        $this->assertIsString($result);
    }

    public function test_generate_gedcom_string_with_invalid_person_returns_string(): void
    {
        $result = $this->service->generateGedcomString(99999);

        $this->assertIsString($result);
    }
}
