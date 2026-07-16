<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\SourceResource\Pages\EditSource;
use App\Filament\App\Resources\SourceResource\RelationManagers\CitationsRelationManager;
use App\Models\Citation;
use App\Models\Source;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * SCOPE §9 evidence linking: a Source exposes its citations (the evidence
 * records with confidence/page/volume) via CitationsRelationManager. Mount
 * covers the Filament v5 wiring; the relation assertion covers the model
 * override that keeps the link on the tenanted App\Models\Citation.
 */
class EvidenceLinkingTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_source_citations_relation_manager_mounts(): void
    {
        $this->actingUser();
        $source = Source::factory()->create();

        Livewire::test(CitationsRelationManager::class, [
            'ownerRecord' => $source,
            'pageClass'   => EditSource::class,
        ])->assertOk();
    }

    public function test_evidence_link_uses_tenanted_citation_with_confidence(): void
    {
        $user = $this->actingUser();
        $source = Source::factory()->create();

        $citation = $source->citations()->create([
            'name'       => 'Birth certificate',
            'confidence' => 3,
            'page'       => 12,
            'volume'     => 1,
        ]);

        // Relation resolves App\Models\Citation (not the untenanted vendor model)...
        $this->assertInstanceOf(Citation::class, $source->citations()->first());
        // ...linked by source_id, with the evidence attributes and tenant stamped.
        $this->assertSame($source->id, $citation->source_id);
        $this->assertSame(3, $citation->confidence);
        $this->assertSame($user->currentTeam->id, $citation->team_id);
    }
}
