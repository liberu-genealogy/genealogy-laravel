<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Family;
use App\Models\Person;
use App\Services\GedcomService;
use FamilyTree365\LaravelGedcom\Utils\GedcomParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Import / export round-trip harness for the GEDCOM fixture corpus (issue #1619,
 * ticket 02). Runs the REAL vendor parser (`GedcomParser::parse`) against the
 * SQLite :memory: test DB — the first tests in the suite to actually exercise it
 * (the queue-import tests only assert dispatch).
 *
 * Every test here is a green assertion for what import preserves (counts,
 * referential integrity, name decomposition, birth dates) — the regression net
 * later export tickets run against. The two import-fidelity defects the ticket-02
 * probe surfaced (NAME not decomposed, birth date dropped) were `markTestIncomplete`
 * markers; ticket 07 fixed them at the app seam (Person/PersonBuilder decompose the
 * slashed NAME on the bulk-upsert path; a migration makes person_events.places_id
 * nullable so the vendor event import stops aborting) and they are now real assertions.
 */
class GedcomCorpusRoundTripTest extends TestCase
{
    use RefreshDatabase;

    private function import(string $fixture): void
    {
        $file = __DIR__.'/../../fixtures/gedcom/'.$fixture;

        (new GedcomParser)->parse(config('database.default'), $file, (string) Str::uuid(), false, null);
    }

    /**
     * @return array<string, array{0: string, 1: int, 2: int}>
     */
    public static function corpus(): array
    {
        return [
            'minimal 5.5.1' => ['minimal-5.5.1.ged', 3, 1],
            'lower version 5.5' => ['lower-version-5.5.ged', 2, 1],
            'notes + continuation + place' => ['notes-continuation-5.5.1.ged', 1, 0],
        ];
    }

    #[DataProvider('corpus')]
    public function test_fixture_imports_expected_person_and_family_counts(string $fixture, int $people, int $families): void
    {
        $this->import($fixture);

        $this->assertSame($people, Person::count(), "people count for {$fixture}");
        $this->assertSame($families, Family::count(), "family count for {$fixture}");
    }

    public function test_import_resolves_family_pointers(): void
    {
        $this->import('minimal-5.5.1.ged');

        $family = Family::firstOrFail();

        // HUSB / WIFE resolve to real people (referential integrity of the import).
        $this->assertNotNull(Person::find($family->husband_id), 'husband pointer dangles');
        $this->assertNotNull(Person::find($family->wife_id), 'wife pointer dangles');

        // Children link upward via people.child_in_family_id (this repo has no
        // children pivot — see CLAUDE.md). The @I3@ CHIL must point back at @F1@.
        $child = Person::whereNotNull('child_in_family_id')->firstOrFail();
        $this->assertSame($family->id, $child->child_in_family_id, 'CHIL not linked to the family');
    }

    public function test_import_preserves_sex(): void
    {
        $this->import('minimal-5.5.1.ged');

        $this->assertSame(['F', 'M', 'M'], Person::query()->orderBy('sex')->pluck('sex')->all());
    }

    // ---------------------------------------------------------------------
    // Import-side fidelity — ticket 07 (split out of 03). The two defects the
    // probe confirmed (NAME not decomposed, birth date dropped) are now fixed
    // and asserted below.
    // ---------------------------------------------------------------------

    public function test_import_decomposes_name_into_givn_and_surn(): void
    {
        $this->import('minimal-5.5.1.ged');

        $john = Person::where('gid', 'I1')->firstOrFail();

        // ticket 07 (fixed): the Person `saving` hook decomposes the raw slashed
        // GEDCOM NAME payload "John /Smith/" into givn/surn on import.
        $this->assertSame('John', $john->givn, 'givn not decomposed from slashed NAME');
        $this->assertSame('Smith', $john->surn, 'surn not decomposed from slashed NAME');

        // I2 has no events (no per-event save), so this confirms the decomposition
        // fires on the bulk upsert path too — every imported person, not just I1.
        $jane = Person::where('gid', 'I2')->firstOrFail();
        $this->assertSame('Jane', $jane->givn, 'givn not decomposed for event-less person');
        $this->assertSame('Smith', $jane->surn, 'surn not decomposed for event-less person');
    }

    public function test_import_preserves_birth_date(): void
    {
        $this->import('minimal-5.5.1.ged');

        $john = Person::where('gid', 'I1')->firstOrFail();

        // ticket 07 (fixed): the vendor event import was aborting on a NOT NULL
        // person_events.places_id constraint (create migration chained
        // ->constrained()->nullable() in the wrong order), so every BIRT/DEAT/EVEN
        // was lost. Making places_id nullable lets "1 BIRT / 2 DATE 1 JAN 1900"
        // survive to BOTH a person_events BIRT row and the person.birthday column.
        $this->assertSame('1900-01-01', $john->birthday?->format('Y-m-d'), 'birthday not set on import');

        $birt = DB::table('person_events')
            ->where('person_id', $john->id)
            ->where('type', 'BIRT')
            ->first();
        $this->assertNotNull($birt, 'no BIRT person_events row for the imported person');
        $this->assertSame('1900-01-01', $birt->date, 'BIRT event date not preserved');
    }

    public function test_exported_tree_reimports_with_stable_counts(): void
    {
        // The core round-trip: import -> export -> re-import -> counts stable.
        $this->import('minimal-5.5.1.ged');
        $people = Person::count();
        $families = Family::count();
        $this->assertGreaterThan(0, $people);

        $export = (new GedcomService)->generateGedcomContent();

        // Wipe the tree, then re-import the export and confirm it reproduces the
        // same record counts (referential detail is covered by the other tests).
        // people <-> families is a circular FK, and SQLite can't toggle FK
        // enforcement inside RefreshDatabase's transaction — so null the two
        // linking columns first, then delete in FK-safe order.
        DB::table('person_events')->delete();
        DB::table('people')->update(['child_in_family_id' => null]);
        DB::table('families')->delete();
        DB::table('people')->delete();

        $tmp = tempnam(sys_get_temp_dir(), 'ged').'.ged';
        file_put_contents($tmp, $export);

        try {
            (new GedcomParser)->parse(config('database.default'), $tmp, (string) Str::uuid(), false, null);
        } finally {
            @unlink($tmp);
        }

        $this->assertSame($people, Person::count(), 'people count changed across a round-trip');
        $this->assertSame($families, Family::count(), 'family count changed across a round-trip');
    }
}
