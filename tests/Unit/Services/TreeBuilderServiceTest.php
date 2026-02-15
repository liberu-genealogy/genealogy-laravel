<?php

namespace Tests\Unit\Services;

use App\Models\Family;
use App\Models\Person;
use App\Modules\Tree\Services\TreeBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreeBuilderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TreeBuilderService $service;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TreeBuilderService();
    }

    public function testBuildFamilyTreeIncludesSiblings(): void
    {
        // Create a family with parents and multiple children
        $family = Family::factory()->create();
        $father = Person::factory()->create(['sex' => 'M']);
        $mother = Person::factory()->create(['sex' => 'F']);
        
        $family->update([
            'husband_id' => $father->id,
            'wife_id' => $mother->id,
        ]);

        $child1 = Person::factory()->create(['child_in_family_id' => $family->id]);
        $child2 = Person::factory()->create(['child_in_family_id' => $family->id]);
        $child3 = Person::factory()->create(['child_in_family_id' => $family->id]);

        $tree = $this->service->buildFamilyTree($child1, [
            'generations' => 2,
            'include_siblings' => true,
        ]);

        $this->assertArrayHasKey('siblings', $tree);
        $this->assertCount(2, $tree['siblings']);
        $this->assertEquals($child2->id, $tree['siblings'][0]['id']);
        $this->assertEquals($child3->id, $tree['siblings'][1]['id']);
    }

    public function testBuildFamilyTreeWithoutSiblings(): void
    {
        $person = Person::factory()->create();

        $tree = $this->service->buildFamilyTree($person, [
            'generations' => 2,
            'include_siblings' => false,
        ]);

        $this->assertArrayNotHasKey('siblings', $tree);
    }

    public function testCountTreePersonsReturnsCorrectCount(): void
    {
        // Create a simple family tree
        $family = Family::factory()->create();
        $father = Person::factory()->create(['sex' => 'M']);
        $mother = Person::factory()->create(['sex' => 'F']);
        
        $family->update([
            'husband_id' => $father->id,
            'wife_id' => $mother->id,
        ]);

        $child = Person::factory()->create(['child_in_family_id' => $family->id]);

        $tree = $this->service->buildFamilyTree($child, ['generations' => 2]);

        // Should include child + father + mother = 3 people
        $this->assertGreaterThanOrEqual(3, $tree['metadata']['total_persons']);
    }

    public function testGetSiblingsReturnsEmptyCollectionWhenNoParentFamily(): void
    {
        $person = Person::factory()->create();

        $siblings = $this->service->getSiblings($person);

        $this->assertTrue($siblings->isEmpty());
    }

    public function testGetSiblingsReturnsCorrectSiblings(): void
    {
        $family = Family::factory()->create();
        $child1 = Person::factory()->create([
            'child_in_family_id' => $family->id,
            'birthday' => '2000-01-01',
        ]);
        $child2 = Person::factory()->create([
            'child_in_family_id' => $family->id,
            'birthday' => '2001-01-01',
        ]);
        $child3 = Person::factory()->create([
            'child_in_family_id' => $family->id,
            'birthday' => '2002-01-01',
        ]);

        $siblings = $this->service->getSiblings($child1);

        $this->assertCount(2, $siblings);
        $this->assertEquals($child2->id, $siblings->first()->id);
        $this->assertEquals($child3->id, $siblings->last()->id);
    }

    public function testGetAllAncestorsReturnsCorrectAncestors(): void
    {
        // Create a 3-generation family
        $grandparentFamily = Family::factory()->create();
        $grandfather = Person::factory()->create(['sex' => 'M']);
        $grandmother = Person::factory()->create(['sex' => 'F']);
        
        $grandparentFamily->update([
            'husband_id' => $grandfather->id,
            'wife_id' => $grandmother->id,
        ]);

        $parentFamily = Family::factory()->create();
        $father = Person::factory()->create([
            'sex' => 'M',
            'child_in_family_id' => $grandparentFamily->id,
        ]);
        $mother = Person::factory()->create(['sex' => 'F']);
        
        $parentFamily->update([
            'husband_id' => $father->id,
            'wife_id' => $mother->id,
        ]);

        $child = Person::factory()->create(['child_in_family_id' => $parentFamily->id]);

        $ancestors = $this->service->getAllAncestors($child, 10);

        // Should include father, mother, grandfather, grandmother
        $this->assertGreaterThanOrEqual(3, $ancestors->count());
        $this->assertTrue($ancestors->contains('id', $father->id));
        $this->assertTrue($ancestors->contains('id', $mother->id));
        $this->assertTrue($ancestors->contains('id', $grandfather->id));
    }

    public function testGetAllDescendantsReturnsCorrectDescendants(): void
    {
        $parentFamily = Family::factory()->create();
        $parent = Person::factory()->create(['sex' => 'M']);
        
        $parentFamily->update(['husband_id' => $parent->id]);

        $child1 = Person::factory()->create(['child_in_family_id' => $parentFamily->id]);
        $child2 = Person::factory()->create(['child_in_family_id' => $parentFamily->id]);

        $descendants = $this->service->getAllDescendants($parent, 10);

        $this->assertCount(2, $descendants);
        $this->assertTrue($descendants->contains('id', $child1->id));
        $this->assertTrue($descendants->contains('id', $child2->id));
    }

    public function testGetTreeStatisticsReturnsCorrectData(): void
    {
        $family = Family::factory()->create();
        $father = Person::factory()->create(['sex' => 'M']);
        $mother = Person::factory()->create(['sex' => 'F', 'deathday' => '2020-01-01']);
        
        $family->update([
            'husband_id' => $father->id,
            'wife_id' => $mother->id,
        ]);

        $child = Person::factory()->create(['child_in_family_id' => $family->id]);

        $stats = $this->service->getTreeStatistics($child, 10);

        $this->assertArrayHasKey('total_people', $stats);
        $this->assertArrayHasKey('total_ancestors', $stats);
        $this->assertArrayHasKey('total_descendants', $stats);
        $this->assertArrayHasKey('total_siblings', $stats);
        $this->assertArrayHasKey('living_people', $stats);
        $this->assertArrayHasKey('deceased_people', $stats);
        $this->assertArrayHasKey('males', $stats);
        $this->assertArrayHasKey('females', $stats);
        $this->assertArrayHasKey('max_ancestor_depth', $stats);
        $this->assertArrayHasKey('max_descendant_depth', $stats);

        $this->assertGreaterThanOrEqual(1, $stats['total_people']);
        $this->assertGreaterThanOrEqual(1, $stats['deceased_people']);
        $this->assertGreaterThanOrEqual(1, $stats['males']);
        $this->assertGreaterThanOrEqual(1, $stats['females']);
    }

    public function testBuildPedigreeChartReturnsCorrectStructure(): void
    {
        $person = Person::factory()->create();

        $chart = $this->service->buildPedigreeChart($person, 4);

        $this->assertEquals('pedigree', $chart['type']);
        $this->assertArrayHasKey('root_person', $chart);
        $this->assertArrayHasKey('chart_data', $chart);
        $this->assertArrayHasKey('metadata', $chart);
        $this->assertEquals('pedigree', $chart['metadata']['chart_type']);
        $this->assertEquals(4, $chart['metadata']['generations']);
    }

    public function testBuildDescendantChartReturnsCorrectStructure(): void
    {
        $person = Person::factory()->create();

        $chart = $this->service->buildDescendantChart($person, 4);

        $this->assertEquals('descendant', $chart['type']);
        $this->assertArrayHasKey('root_person', $chart);
        $this->assertArrayHasKey('chart_data', $chart);
        $this->assertArrayHasKey('metadata', $chart);
        $this->assertEquals('descendant', $chart['metadata']['chart_type']);
        $this->assertEquals(4, $chart['metadata']['generations']);
    }

    public function testFormatPersonNodeIncludesAllRequiredFields(): void
    {
        $person = Person::factory()->create([
            'givn' => 'John',
            'surn' => 'Doe',
            'sex' => 'M',
            'birthday' => '1980-01-01',
            'deathday' => '2020-01-01',
        ]);

        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('formatPersonNode');
        $method->setAccessible(true);

        $formattedNode = $method->invoke($this->service, $person);

        $this->assertArrayHasKey('id', $formattedNode);
        $this->assertArrayHasKey('name', $formattedNode);
        $this->assertArrayHasKey('given_name', $formattedNode);
        $this->assertArrayHasKey('surname', $formattedNode);
        $this->assertArrayHasKey('sex', $formattedNode);
        $this->assertArrayHasKey('birth_date', $formattedNode);
        $this->assertArrayHasKey('birth_year', $formattedNode);
        $this->assertArrayHasKey('death_date', $formattedNode);
        $this->assertArrayHasKey('death_year', $formattedNode);
        $this->assertArrayHasKey('is_living', $formattedNode);
        $this->assertArrayHasKey('age', $formattedNode);
        $this->assertArrayHasKey('lifespan', $formattedNode);

        $this->assertEquals('John', $formattedNode['given_name']);
        $this->assertEquals('Doe', $formattedNode['surname']);
        $this->assertEquals('M', $formattedNode['sex']);
        $this->assertFalse($formattedNode['is_living']);
    }
}
