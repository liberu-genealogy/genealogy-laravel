<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Person;
use PHPUnit\Framework\TestCase;

class PersonFullnameTest extends TestCase
{
    public function test_fullname_joins_givn_and_surn(): void
    {
        $person = new Person(['givn' => 'John', 'surn' => 'Doe']);

        $this->assertSame('John Doe', $person->fullname());
    }

    /** When both GEDCOM name parts are empty, it falls back to the legacy `name` column. */
    public function test_fullname_falls_back_to_legacy_name(): void
    {
        $person = new Person(['givn' => null, 'surn' => null, 'name' => 'Legacy Fullname']);

        $this->assertSame('Legacy Fullname', $person->fullname());
    }

    /** Property access ($person->fullname) must work too — Filament TextColumn::make('fullname'). */
    public function test_fullname_is_accessible_as_an_attribute(): void
    {
        $person = new Person(['givn' => 'Jane', 'surn' => 'Roe']);

        $this->assertSame('Jane Roe', $person->fullname);
    }
}
