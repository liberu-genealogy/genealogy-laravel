<?php

namespace Tests\Unit\Models;

use App\Models\Family;
use Tests\TestCase;

class FamilyModelTest extends TestCase
{
    /**
     * The vendor GEDCOM parser (FamilyData::getFamily) hardcodes type_id = 0.
     * Since no `types` record with id = 0 exists, MySQL would reject the insert
     * with an FK constraint violation. The mutator converts 0 → null so the
     * nullable FK constraint is satisfied.
     */
    public function test_type_id_zero_is_converted_to_null(): void
    {
        $family = new Family();
        $family->type_id = 0;

        $this->assertNull($family->type_id);
    }

    public function test_type_id_string_zero_is_converted_to_null(): void
    {
        $family = new Family();
        $family->type_id = '0';

        $this->assertNull($family->type_id);
    }

    public function test_type_id_null_stays_null(): void
    {
        $family = new Family();
        $family->type_id = null;

        $this->assertNull($family->type_id);
    }

    public function test_type_id_valid_value_is_preserved(): void
    {
        $family = new Family();
        $family->type_id = 5;

        $this->assertSame(5, $family->type_id);
    }

    public function test_team_id_is_in_fillable(): void
    {
        $family = new Family();

        $this->assertContains('team_id', $family->getFillable());
    }
}
