<?php

namespace Tests\Unit\Models;

use App\Models\Family;
use Tests\TestCase;

class FamilyTest extends TestCase
{
        /**
     * Test to cover the removed children() method.
     *
     * @test
     * @return void
     */
    public function testRemovedChildrenMethod()
    {
            // Test if the children method returns the correct number of children
    $family = Family::factory()->create();
    $child1 = Person::factory()->create(['child_in_family_id' => $family->id]);
    $child2 = Person::factory()->create(['child_in_family_id' => $family->id]);
    $children = $family->children;
    $this->assertCount(2, $children);
    $this->assertTrue($children->contains($child1));
    $this->assertTrue($children->contains($child2));
    }
}
