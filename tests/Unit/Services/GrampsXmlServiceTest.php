<?php

namespace Tests\Unit\Services;

use App\Models\Person;
use App\Models\Family;
use App\Services\GrampsXmlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GrampsXmlServiceTest extends TestCase
{
    use RefreshDatabase;

    protected GrampsXmlService $service;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GrampsXmlService();
    }

    public function testGenerateGrampsXmlContentReturnsXmlString(): void
    {
        $people = collect([]);
        $families = collect([]);

        $result = $this->service->generateGrampsXmlContent($people, $families);

        $this->assertIsString($result);
        $this->assertStringContainsString('<?xml', $result);
        $this->assertStringContainsString('database', $result);
        $this->assertStringContainsString('xmlns="http://gramps-project.org/xml/1.7.2/"', $result);
    }

    public function testGenerateGrampsXmlContentWithPeople(): void
    {
        // Create a mock person without saving to database
        $person = new Person([
            'id' => 1,
            'givn' => 'John',
            'surn' => 'Doe',
            'sex' => 'M',
        ]);
        $person->updated_at = now();

        $people = collect([$person]);
        $families = collect([]);

        $result = $this->service->generateGrampsXmlContent($people, $families);

        $this->assertStringContainsString('<people>', $result);
        $this->assertStringContainsString('<person', $result);
        $this->assertStringContainsString('John', $result);
        $this->assertStringContainsString('Doe', $result);
        $this->assertStringContainsString('<gender>M</gender>', $result);
    }

    public function testGenerateGrampsXmlContentWithFamilies(): void
    {
        $person1 = new Person([
            'id' => 1,
            'givn' => 'John',
            'surn' => 'Doe',
            'sex' => 'M',
        ]);
        $person1->updated_at = now();

        $person2 = new Person([
            'id' => 2,
            'givn' => 'Jane',
            'surn' => 'Doe',
            'sex' => 'F',
        ]);
        $person2->updated_at = now();

        $family = new Family([
            'id' => 1,
            'husband_id' => 1,
            'wife_id' => 2,
        ]);
        $family->updated_at = now();

        $people = collect([$person1, $person2]);
        $families = collect([$family]);

        $result = $this->service->generateGrampsXmlContent($people, $families);

        $this->assertStringContainsString('<families>', $result);
        $this->assertStringContainsString('<family', $result);
        $this->assertStringContainsString('person_1', $result);
        $this->assertStringContainsString('person_2', $result);
    }

    public function testGenderMapping(): void
    {
        $malePerson = new Person(['id' => 1, 'sex' => 'M']);
        $malePerson->updated_at = now();

        $femalePerson = new Person(['id' => 2, 'sex' => 'F']);
        $femalePerson->updated_at = now();

        $unknownPerson = new Person(['id' => 3, 'sex' => 'U']);
        $unknownPerson->updated_at = now();

        $people = collect([$malePerson, $femalePerson, $unknownPerson]);

        $result = $this->service->generateGrampsXmlContent($people, collect([]));

        $this->assertStringContainsString('<gender>M</gender>', $result);
        $this->assertStringContainsString('<gender>F</gender>', $result);
        $this->assertStringContainsString('<gender>U</gender>', $result);
    }
}
