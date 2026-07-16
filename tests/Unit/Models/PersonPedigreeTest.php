<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\PedigreeType;
use App\Models\Person;
use Tests\TestCase;

class PersonPedigreeTest extends TestCase
{
    public function test_pedigree_cast_round_trips_to_enum(): void
    {
        $person = new Person(['pedigree' => 'adopted']);

        $this->assertSame(PedigreeType::ADOPTED, $person->pedigree);
    }

    public function test_is_adopted_true_only_for_adopted(): void
    {
        $this->assertTrue((new Person(['pedigree' => 'adopted']))->isAdopted());
        $this->assertFalse((new Person(['pedigree' => 'foster']))->isAdopted());
        $this->assertFalse((new Person(['pedigree' => 'birth']))->isAdopted());
    }

    public function test_null_pedigree_is_not_adopted_and_reads_biological(): void
    {
        $person = new Person;

        $this->assertNull($person->pedigree);
        $this->assertFalse($person->isAdopted());
        $this->assertSame('Biological', $person->pedigreeLabel());
    }
}
