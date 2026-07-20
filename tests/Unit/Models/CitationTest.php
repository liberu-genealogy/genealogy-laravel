<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Citation;
use App\Models\Source;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * citations.source_id is a single FK into sources (Source hasMany Citation), but
 * Citation::sources() was declared belongsToMany, which queries a citation_source
 * pivot that no migration creates — reading it fataled with "no such table". The
 * relation is a belongsTo.
 */
class CitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_citation_belongs_to_its_source(): void
    {
        $source = Source::factory()->create();
        $citation = Citation::factory()->create(['source_id' => $source->id]);

        $this->assertInstanceOf(BelongsTo::class, $citation->source());
        $this->assertTrue($citation->source->is($source));
    }
}
