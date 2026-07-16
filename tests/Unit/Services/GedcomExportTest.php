<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Family;
use App\Models\Person;
use App\Services\GedcomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedcomExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_gedcom_content_returns_head_document(): void
    {
        // Small in-memory tree. Family::factory() also creates a husband + wife Person.
        Person::factory()->count(2)->create();
        Family::factory()->create();

        $content = (new GedcomService)->generateGedcomContent();

        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('0 HEAD', $content);
    }
}
