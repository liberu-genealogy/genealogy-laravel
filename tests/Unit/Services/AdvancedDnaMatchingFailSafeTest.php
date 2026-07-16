<?php

namespace Tests\Unit\Services;

use App\Services\AdvancedDnaMatchingService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The real php-dna engine is unavailable at runtime (its classes do not exist),
 * so the matching path must degrade gracefully instead of letting an Error
 * escape. These tests assert that nothing fatal bubbles out of the service.
 */
class AdvancedDnaMatchingFailSafeTest extends TestCase
{
    protected AdvancedDnaMatchingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AdvancedDnaMatchingService();
        Storage::fake('private');
    }

    public function test_matching_degrades_gracefully_when_engine_unavailable(): void
    {
        // Files exist so loadDnaKit() reaches the (non-existent) php-dna classes
        // and raises a class-not-found Error - the exact runtime failure mode.
        Storage::disk('private')->put('kit_a.txt', "rsid\tchromosome\tposition\tgenotype\n");
        Storage::disk('private')->put('kit_b.txt', "rsid\tchromosome\tposition\tgenotype\n");

        $result = $this->service->performAdvancedMatching('var_a', 'kit_a.txt', 'var_b', 'kit_b.txt');

        // No Throwable escaped, and we got the basic-matching fallback shape.
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_cms', $result);
        $this->assertSame('Unknown (Basic Analysis)', $result['predicted_relationship']);
    }

    public function test_matching_does_not_throw_when_files_missing(): void
    {
        $result = $this->service->performAdvancedMatching('var_a', 'missing_a.txt', 'var_b', 'missing_b.txt');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_cms', $result);
    }
}
