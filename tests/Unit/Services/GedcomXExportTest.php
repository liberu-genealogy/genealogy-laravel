<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Family;
use App\Models\Person;
use App\Services\GedcomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GedcomXExportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{persons: array<int, array<string, mixed>>, relationships: array<int, array<string, mixed>>}
     */
    private function exportTree(): array
    {
        $husband = Person::factory()->create(['name' => 'John /Smith/', 'givn' => 'John', 'surn' => 'Smith', 'sex' => 'M', 'birth_year' => 1900]);
        $wife = Person::factory()->create(['name' => 'Jane /Smith/', 'givn' => 'Jane', 'surn' => 'Smith', 'sex' => 'F']);
        $family = Family::factory()->create(['husband_id' => $husband->id, 'wife_id' => $wife->id]);
        Person::factory()->create(['name' => 'Child /Smith/', 'givn' => 'Child', 'surn' => 'Smith', 'sex' => 'M', 'child_in_family_id' => $family->id]);

        $json = (new GedcomService)->generateGedcomXContent();

        $data = json_decode($json, true);
        $this->assertIsArray($data, 'GEDCOM X output is not valid JSON');

        // Off-spec vendor refs ("#persons/pN") must be rewritten to bare id
        // fragments ("#pN"). The top-level "persons" key is fine; only the "#"
        // resource form is wrong.
        $this->assertStringNotContainsString('#persons', $json, 'resource refs still use the off-spec "#persons/" form');

        return $data;
    }

    public function test_gedcomx_export_has_persons_and_relationships(): void
    {
        $data = $this->exportTree();

        $this->assertArrayHasKey('persons', $data);
        $this->assertArrayHasKey('relationships', $data);
        $this->assertCount(3, $data['persons'], 'expected one GEDCOM X person per INDI');

        foreach ($data['persons'] as $person) {
            $this->assertArrayHasKey('id', $person);
            $this->assertStringStartsWith('p', (string) $person['id']);
        }
    }

    public function test_gedcomx_persons_use_gedcomx_type_uris(): void
    {
        $data = $this->exportTree();

        $genders = array_column($data['persons'], 'gender');
        $genderTypes = array_column(array_filter($genders), 'type');
        $this->assertContains('http://gedcomx.org/Male', $genderTypes);
        $this->assertContains('http://gedcomx.org/Female', $genderTypes);

        // Name part types are GEDCOM X URIs (assert on the decoded structure —
        // a re-encoded string would escape the slashes).
        $partTypes = [];
        foreach ($data['persons'] as $person) {
            foreach ($person['names'] ?? [] as $name) {
                foreach ($name['nameForms'] ?? [] as $form) {
                    $partTypes = array_merge($partTypes, array_column($form['parts'] ?? [], 'type'));
                }
            }
        }
        $this->assertContains('http://gedcomx.org/Given', $partTypes);
        $this->assertContains('http://gedcomx.org/Surname', $partTypes);
    }

    public function test_gedcomx_relationships_link_the_family(): void
    {
        $data = $this->exportTree();

        $types = array_column($data['relationships'], 'type');
        $this->assertContains('http://gedcomx.org/Couple', $types);
        $this->assertContains('http://gedcomx.org/ParentChild', $types);

        // Every relationship endpoint resolves to a person fragment "#pN".
        foreach ($data['relationships'] as $rel) {
            foreach (['person1', 'person2'] as $end) {
                if (isset($rel[$end]['resource'])) {
                    $this->assertMatchesRegularExpression('/^#p\d+$/', $rel[$end]['resource']);
                }
            }
        }
    }

    public function test_gedcomx_person_carries_birth_fact(): void
    {
        $data = $this->exportTree();

        $facts = array_merge(...array_map(fn ($p): array => $p['facts'] ?? [], $data['persons']));
        $factTypes = array_column($facts, 'type');

        $this->assertContains('http://gedcomx.org/Birth', $factTypes, 'birth date did not survive to a GEDCOM X fact');
    }
}
