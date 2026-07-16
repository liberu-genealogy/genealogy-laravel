<?php

declare(strict_types=1);

namespace Tests\Feature\Reports;

use App\Models\Family;
use App\Models\Person;
use App\Models\SourceRef;
use App\Models\Tree;
use App\Models\User;
use App\Services\CompletenessService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompletenessReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Person / Family / Tree / SourceRef are tenant-scoped (BelongsToTenant), so
     * every read must run under an authed tenant context or the global scope
     * no-ops. Mirror the setup used by DnaMatchingTenantScopeTest.
     */
    private function actAsTenant(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_tree_completeness_is_partial_for_a_root_with_one_known_parent(): void
    {
        $this->actAsTenant();

        $father = Person::factory()->create();
        $family = Family::factory()->create(['husband_id' => $father->id, 'wife_id' => null]);
        $root   = Person::factory()->create(['child_in_family_id' => $family->id]);
        $tree   = Tree::factory()->create(['root_person_id' => $root->id]);

        $stats = app(CompletenessService::class)->treeCompleteness($tree);

        // One of the 30 four-generation ancestor slots (the father) is filled.
        $this->assertSame(1, $stats['filled_slots']);
        $this->assertSame(30, $stats['total_slots']);
        $this->assertGreaterThan(0, $stats['completeness']);
        $this->assertLessThan(100, $stats['completeness']);

        // Root's mother + the father's two parents are all unknown.
        $this->assertGreaterThan(0, $stats['missing_parents']);
    }

    public function test_source_completeness_reflects_linked_source_refs(): void
    {
        $this->actAsTenant();

        $sourced = Person::factory()->create();
        Person::factory()->create(); // no source

        SourceRef::create(['group' => 'indi', 'gid' => $sourced->id, 'sour_id' => 1]);

        $report = app(CompletenessService::class)->sourceCompleteness();

        $this->assertSame(2, $report['persons']['total']);
        $this->assertSame(1, $report['persons']['with_source']);
        $this->assertSame(50.0, $report['persons']['percentage']);
        $this->assertGreaterThan(0, $report['overall']);
        $this->assertLessThanOrEqual(100, $report['overall']);
    }
}
