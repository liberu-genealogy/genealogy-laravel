<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Family;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The family graph is GEDCOM-shaped: parents/father/mother/children all walk
 * through the family row, and Family deliberately unsets the vendor's public
 * $husband/$wife/$id so __get resolves the App\Models\Person overrides instead.
 * That machinery had no direct assertions — only that eager-loading didn't
 * throw. These pin the actual linkage and are revert-sensitive: drop the
 * unset() in Family::__construct or the husband()/wife() overrides and they
 * fail.
 */
class PersonFamilyGraphTest extends TestCase
{
    use RefreshDatabase;

    public function test_traversal_links_child_to_parents_and_back(): void
    {
        $husband = Person::factory()->create();
        $wife = Person::factory()->create();
        $family = Family::factory()->create([
            'husband_id' => $husband->id,
            'wife_id' => $wife->id,
        ]);
        $child = Person::factory()->create(['child_in_family_id' => $family->id]);

        // Re-fetch so nothing rides on relations hydrated at create time.
        $child = Person::findOrFail($child->id);

        // Upward: child → family → parent.
        $this->assertInstanceOf(Person::class, $child->father());
        $this->assertTrue($child->father()->is($husband), 'father() did not resolve the husband');
        $this->assertInstanceOf(Person::class, $child->mother());
        $this->assertTrue($child->mother()->is($wife), 'mother() did not resolve the wife');

        $parentIds = $child->parents()->pluck('id');
        $this->assertCount(2, $parentIds);
        $this->assertTrue($parentIds->contains($husband->id));
        $this->assertTrue($parentIds->contains($wife->id));

        // Downward: parent → children (hasManyThrough union, both sides).
        $this->assertTrue(
            Person::findOrFail($husband->id)->children()->get()->pluck('id')->contains($child->id),
            'husband->children() did not include the child'
        );
        $this->assertTrue(
            Person::findOrFail($wife->id)->children()->get()->pluck('id')->contains($child->id),
            'wife->children() did not include the child'
        );

        // The dynamic-property form is what the reports/charts use (e.g. HenryReport,
        // DeVilliersReport, descendant charts) — it must resolve the same rows.
        $this->assertTrue(
            Person::findOrFail($husband->id)->children->pluck('id')->contains($child->id),
            '$person->children property did not include the child'
        );
    }

    public function test_family_husband_and_wife_resolve_to_app_person(): void
    {
        $husband = Person::factory()->create();
        $wife = Person::factory()->create();
        $family = Family::factory()->create([
            'husband_id' => $husband->id,
            'wife_id' => $wife->id,
        ]);

        $family = Family::findOrFail($family->id);

        // Magic-property access only works because __construct unset the vendor's
        // shadowing public $husband/$wife; the overrides bind them to App\Models\Person.
        $this->assertInstanceOf(Person::class, $family->husband);
        $this->assertTrue($family->husband->is($husband));
        $this->assertInstanceOf(Person::class, $family->wife);
        $this->assertTrue($family->wife->is($wife));
    }

    public function test_family_children_use_app_person_and_exclude_soft_deleted(): void
    {
        $family = Family::factory()->create();
        $child = Person::factory()->create(['child_in_family_id' => $family->id]);
        $ghost = Person::factory()->create(['child_in_family_id' => $family->id]);

        $family = Family::findOrFail($family->id);

        // The override binds children to App\Models\Person (SoftDeletes + tenant
        // scope); the vendor relation points at its own scopeless Person.
        $this->assertInstanceOf(Person::class, $family->children->first());
        $this->assertTrue($family->children->pluck('id')->contains($child->id));

        // A soft-deleted child must drop out — proves the App model's SoftDeletes
        // scope is in play (the vendor Person has none, so it would still appear).
        $ghost->delete();
        $this->assertFalse(
            $family->fresh()->children->pluck('id')->contains($ghost->id),
            'A soft-deleted person is still returned as a family child'
        );
    }
}
