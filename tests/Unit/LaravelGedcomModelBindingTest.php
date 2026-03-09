<?php

namespace Tests\Unit;

use App\Models\Family;
use App\Models\Person;
use Tests\TestCase;

/**
 * Verifies that the IoC container bindings registered by LaravelGedcomServiceProvider
 * redirect the vendor model classes to the application model classes.
 *
 * When the vendor's GedcomParser calls app(\FamilyTree365\LaravelGedcom\Models\Family::class),
 * it must receive an App\Models\Family instance so that:
 *  - team_id is in $fillable (families get assigned to the right team)
 *  - the setTypeIdAttribute mutator applies (converting 0 → null to satisfy the FK)
 *
 * Similarly, app(\FamilyTree365\LaravelGedcom\Models\Person::class) must return
 * App\Models\Person so lookups share the same table and global-scope logic.
 */
class LaravelGedcomModelBindingTest extends TestCase
{
    public function test_vendor_family_class_resolves_to_app_family(): void
    {
        $resolved = app(\FamilyTree365\LaravelGedcom\Models\Family::class);

        $this->assertInstanceOf(Family::class, $resolved);
    }

    public function test_vendor_person_class_resolves_to_app_person(): void
    {
        $resolved = app(\FamilyTree365\LaravelGedcom\Models\Person::class);

        $this->assertInstanceOf(Person::class, $resolved);
    }
}
