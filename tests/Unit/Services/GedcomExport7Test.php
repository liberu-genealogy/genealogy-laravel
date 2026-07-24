<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Family;
use App\Models\Person;
use App\Services\GedcomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedcomExport7Test extends TestCase
{
    use RefreshDatabase;

    private function export7(): string
    {
        $husband = Person::factory()->create(['name' => 'John /Smith/', 'givn' => 'John', 'surn' => 'Smith', 'sex' => 'M']);
        $wife = Person::factory()->create(['name' => 'Jane /Smith/', 'givn' => 'Jane', 'surn' => 'Smith', 'sex' => 'F']);
        $family = Family::factory()->create(['husband_id' => $husband->id, 'wife_id' => $wife->id]);
        Person::factory()->create(['name' => 'Child /Smith/', 'givn' => 'Child', 'surn' => 'Smith', 'sex' => 'M', 'child_in_family_id' => $family->id]);

        return (new GedcomService)->generateGedcom7Content();
    }

    public function test_head_declares_gedcom_7_and_drops_5551_only_structures(): void
    {
        $content = $this->export7();

        $this->assertStringStartsWith('0 HEAD', $content);
        $this->assertStringContainsString("1 GEDC\n2 VERS 7.0", $content, 'HEAD must declare GEDC.VERS 7.0');

        // 5.5.1-only HEAD structures are invalid in 7.0.
        $this->assertStringNotContainsString('2 FORM', $content, 'GEDC.FORM was removed in 7.0');
        $this->assertStringNotContainsString('1 CHAR', $content, 'CHAR is dropped in 7.0 (UTF-8 implicit)');
    }

    public function test_no_conc_continuation(): void
    {
        // CONC was removed in 7.0. Match the tag as a whole line token.
        $this->assertDoesNotMatchRegularExpression('/^\d+ CONC\b/m', $this->export7());
    }

    public function test_emits_every_person_and_valid_sex(): void
    {
        $content = $this->export7();

        $this->assertSame(3, substr_count($content, ' INDI'), 'one INDI per person');
        $this->assertSame(1, substr_count($content, ' FAM'), 'one FAM per family');

        // SEX values are restricted to the 7.0 enum.
        preg_match_all('/^1 SEX (.*)$/m', $content, $m);
        foreach ($m[1] as $sex) {
            $this->assertContains($sex, ['M', 'F', 'X', 'U'], "SEX '{$sex}' is not a valid 7.0 value");
        }
    }

    public function test_every_family_pointer_resolves_to_a_defined_record(): void
    {
        $content = $this->export7();

        // Collect defined INDI xrefs, then every pointer used in a FAM.
        preg_match_all('/^0 (@I\d+@) INDI$/m', $content, $defined);
        preg_match_all('/^1 (?:HUSB|WIFE|CHIL) (@I\d+@)$/m', $content, $used);

        foreach (array_unique($used[1]) as $pointer) {
            $this->assertContains($pointer, $defined[1], "FAM pointer {$pointer} has no INDI record");
        }

        $this->assertStringEndsWith('0 TRLR', rtrim($content));
    }
}
