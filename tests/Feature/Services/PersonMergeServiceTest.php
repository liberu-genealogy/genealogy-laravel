<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Family;
use App\Models\Person;
use App\Models\PersonAsso;
use App\Models\PersonName;
use App\Models\SourceRef;
use App\Models\User;
use App\Services\PersonMergeService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PersonMergeService had no tests at all, and did not work at all: it looped a
 * `person_id` update over nine models of which only two have that column, so
 * every merge threw "Unknown column 'person_id'" on the second entry. The rest
 * of the method — including a line that would corrupt unrelated records — was
 * therefore unreachable, and the merge UI has never completed a single merge.
 *
 * These models are tenant-scoped via BelongsToTenant, which keys off
 * auth()->user()->currentTeam, so each test acts as a user with a team; without
 * that the global scope no-ops and the assertions prove nothing.
 */
class PersonMergeServiceTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_merging_two_plain_people_does_not_throw(): void
    {
        $this->actingUser();

        $primary = Person::factory()->create(['givn' => 'John', 'surn' => 'Doe']);
        $duplicate = Person::factory()->create(['givn' => 'Jon', 'surn' => 'Doe']);

        $merged = (new PersonMergeService)->merge($primary, $duplicate);

        $this->assertSame($primary->id, $merged->id);
        $this->assertSoftDeleted('people', ['id' => $duplicate->id]);
    }

    public function test_merge_repoints_gedcom_group_gid_records_onto_the_primary(): void
    {
        $this->actingUser();

        $primary = Person::factory()->create();
        $duplicate = Person::factory()->create();

        $name = PersonName::create([
            'group' => 'indi',
            'gid' => $duplicate->id,
            'givn' => 'Alternate',
            'surn' => 'Spelling',
        ]);

        $sourceRef = SourceRef::create([
            'group' => 'indi',
            'gid' => $duplicate->id,
            'sour_id' => 1,
            'page' => 'p. 12',
        ]);

        (new PersonMergeService)->merge($primary, $duplicate);

        $this->assertSame($primary->id, $name->fresh()->gid, 'person_name was left on the deleted duplicate');
        $this->assertSame($primary->id, $sourceRef->fresh()->gid, 'source_ref was left on the deleted duplicate');
    }

    public function test_merge_repoints_associations_in_both_directions(): void
    {
        $this->actingUser();

        $primary = Person::factory()->create();
        $duplicate = Person::factory()->create();
        $other = Person::factory()->create();

        // The duplicate as the subject of an association...
        $asSubject = PersonAsso::create([
            'group' => 'indi',
            'gid' => $duplicate->id,
            'indi' => (string) $other->id,
            'rela' => 'guardian',
            'import_confirm' => 1,
        ]);

        // ...and as the person someone else's association points at.
        $asAssociate = PersonAsso::create([
            'group' => 'indi',
            'gid' => $other->id,
            'indi' => (string) $duplicate->id,
            'rela' => 'godparent',
            'import_confirm' => 1,
        ]);

        (new PersonMergeService)->merge($primary, $duplicate);

        $this->assertSame($primary->id, $asSubject->fresh()->gid);
        $this->assertSame((string) $primary->id, $asAssociate->fresh()->indi);
    }

    /**
     * The regression that matters most. `child_in_family_id` is a FAMILY id, but
     * the service matched it against the duplicate's PERSON id and rewrote every
     * hit. Person ids and family ids are both small integers drawn from separate
     * sequences, so they collide constantly — this test builds that collision
     * deliberately: an unrelated child sits in the family whose id equals the
     * duplicate person's id, and must not be touched by the merge.
     */
    public function test_merge_does_not_reassign_unrelated_children_whose_family_id_matches_the_duplicate_id(): void
    {
        $this->actingUser();

        $primary = Person::factory()->create();
        $duplicate = Person::factory()->create();

        $family = Family::factory()->create();
        // Force the collision the old code assumed could never happen.
        $family->forceFill(['id' => $duplicate->id])->saveQuietly();

        $bystander = Person::factory()->create(['child_in_family_id' => $duplicate->id]);

        (new PersonMergeService)->merge($primary, $duplicate);

        $this->assertSame(
            $duplicate->id,
            $bystander->fresh()->child_in_family_id,
            'merge reassigned an unrelated child to a different family by treating a family id as a person id'
        );
    }

    public function test_merge_adopts_the_duplicates_parentage_only_when_the_primary_has_none(): void
    {
        $this->actingUser();

        $duplicateFamily = Family::factory()->create();
        $primaryFamily = Family::factory()->create();

        $primary = Person::factory()->create(['child_in_family_id' => null]);
        $duplicate = Person::factory()->create(['child_in_family_id' => $duplicateFamily->id]);

        (new PersonMergeService)->merge($primary, $duplicate);

        $this->assertSame($duplicateFamily->id, $primary->fresh()->child_in_family_id);

        // And when the primary already has parentage, the duplicate's is ignored.
        $keeper = Person::factory()->create(['child_in_family_id' => $primaryFamily->id]);
        $other = Person::factory()->create(['child_in_family_id' => $duplicateFamily->id]);

        (new PersonMergeService)->merge($keeper, $other);

        $this->assertSame($primaryFamily->id, $keeper->fresh()->child_in_family_id);
    }
}
