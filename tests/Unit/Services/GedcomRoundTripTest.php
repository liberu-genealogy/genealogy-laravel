<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Family;
use App\Models\Person;
use App\Services\GedcomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedcomRoundTripTest extends TestCase
{
    use RefreshDatabase;

    public function test_exported_tree_passes_structural_validation(): void
    {
        // Small tree, no auth: the BelongsToTenant scope no-ops without a current team.
        Person::factory()->count(2)->create();
        Family::factory()->create(); // also creates a husband + wife Person

        $service = new GedcomService;
        $content = $service->generateGedcomContent();

        // Export must round-trip through our own validator with zero errors.
        $errors = $service->validateGedcom($content);
        $this->assertSame([], $errors, 'validateGedcom errors: ' . implode(' | ', $errors));

        // Envelope: a valid single-document GEDCOM opens at HEAD and closes at TRLR.
        $this->assertStringStartsWith('0 HEAD', $content);
        $this->assertStringEndsWith('0 TRLR', rtrim($content));

        // ponytail: round-trip stops at export -> validate. Parsing the string back
        // in needs the vendor GedcomParser to write to the DB from a file on disk
        // (queue/file wiring), which is out of scope for a unit test. Follow-up if a
        // full parse-back assertion is wanted.
    }

    public function test_validate_gedcom_rejects_malformed_input(): void
    {
        // Proves the validator actually bites — each case must yield >=1 error.
        $service = new GedcomService;

        $this->assertNotEmpty($service->validateGedcom(''), 'empty content');
        $this->assertNotEmpty($service->validateGedcom("0 INDI @I1@\n0 TRLR"), 'missing HEAD');
        $this->assertNotEmpty($service->validateGedcom("0 HEAD\n1 SOUR X"), 'missing TRLR');
        $this->assertNotEmpty($service->validateGedcom('not gedcom at all'), 'not gedcom');
    }
}
