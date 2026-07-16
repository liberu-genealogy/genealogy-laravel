<?php

declare(strict_types=1);

namespace Tests\Feature\Reports;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use App\Modules\Core\Services\TreeService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipCalculatorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Person / Family are tenant-scoped (BelongsToTenant); reads no-op without
     * an authed tenant context. Mirror DnaMatchingTenantScopeTest's setup.
     */
    private function actAsTenant(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);
    }

    public function test_relationships_are_computed_from_the_lowest_common_ancestor(): void
    {
        $this->actAsTenant();

        // Grandparent -> {parent, auncle} -> {cousinA, cousinB}
        $grandparent = Person::factory()->create();
        $gpFamily = Family::factory()->create(['husband_id' => $grandparent->id, 'wife_id' => null]);

        $parent = Person::factory()->create(['child_in_family_id' => $gpFamily->id]);
        $auncle = Person::factory()->create(['child_in_family_id' => $gpFamily->id]);

        $parentFamily = Family::factory()->create(['husband_id' => $parent->id, 'wife_id' => null]);
        $auncleFamily = Family::factory()->create(['husband_id' => $auncle->id, 'wife_id' => null]);

        $cousinA = Person::factory()->create(['child_in_family_id' => $parentFamily->id]);
        $cousinB = Person::factory()->create(['child_in_family_id' => $auncleFamily->id]);

        $service = app(TreeService::class);

        $this->assertSame('sibling', $service->calculateRelationship($parent, $auncle));
        $this->assertSame('parent', $service->calculateRelationship($parent, $cousinA));
        $this->assertSame('grandparent', $service->calculateRelationship($grandparent, $cousinA));
        $this->assertSame('1st cousin', $service->calculateRelationship($cousinA, $cousinB));

        // Framing is person1-relative and unrelated trees are labelled as such.
        $this->assertSame('grandchild', $service->calculateRelationship($cousinA, $grandparent));
        $this->assertSame('self', $service->calculateRelationship($parent, $parent));
        $this->assertSame(
            'no traceable relationship',
            $service->calculateRelationship($parent, Person::factory()->create()),
        );
    }
}
