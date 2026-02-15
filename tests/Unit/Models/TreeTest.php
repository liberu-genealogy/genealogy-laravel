<?php

namespace Tests\Unit\Models;

use App\Models\Family;
use App\Models\Person;
use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreeTest extends TestCase
{
    use RefreshDatabase;

    public function testTreeHasRootPersonRelationship(): void
    {
        $person = Person::factory()->create();
        $user = User::factory()->create();
        $tree = Tree::factory()->create([
            'user_id' => $user->id,
            'root_person_id' => $person->id,
        ]);

        $this->assertInstanceOf(Person::class, $tree->rootPerson);
        $this->assertEquals($person->id, $tree->rootPerson->id);
    }

    public function testTreeHasUserRelationship(): void
    {
        $user = User::factory()->create();
        $tree = Tree::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $tree->user);
        $this->assertEquals($user->id, $tree->user->id);
    }

    public function testGetStatsReturnsEmptyStatsWhenNoRootPerson(): void
    {
        $user = User::factory()->create();
        $tree = Tree::factory()->create([
            'user_id' => $user->id,
            'root_person_id' => null,
        ]);

        $stats = $tree->getStats();

        $this->assertEquals(0, $stats['total_people']);
        $this->assertEquals(0, $stats['total_ancestors']);
        $this->assertEquals(0, $stats['total_descendants']);
        $this->assertEquals(0, $stats['total_generations']);
    }

    public function testGetStatsReturnsCorrectStatsWithRootPerson(): void
    {
        $user = User::factory()->create();
        
        // Create a simple family tree
        $family = Family::factory()->create();
        $father = Person::factory()->create(['sex' => 'M']);
        $mother = Person::factory()->create(['sex' => 'F']);
        
        $family->update([
            'husband_id' => $father->id,
            'wife_id' => $mother->id,
        ]);

        $child = Person::factory()->create(['child_in_family_id' => $family->id]);

        $tree = Tree::factory()->create([
            'user_id' => $user->id,
            'root_person_id' => $child->id,
        ]);

        $stats = $tree->getStats();

        $this->assertArrayHasKey('total_people', $stats);
        $this->assertArrayHasKey('total_ancestors', $stats);
        $this->assertArrayHasKey('total_descendants', $stats);
        $this->assertArrayHasKey('total_generations', $stats);

        $this->assertGreaterThanOrEqual(1, $stats['total_people']);
        $this->assertGreaterThanOrEqual(2, $stats['total_ancestors']);
    }

    public function testTreeCanBeCreatedWithRootPersonId(): void
    {
        $user = User::factory()->create();
        $person = Person::factory()->create();

        $tree = Tree::create([
            'user_id' => $user->id,
            'name' => 'Test Tree',
            'description' => 'Test Description',
            'root_person_id' => $person->id,
        ]);

        $this->assertDatabaseHas('trees', [
            'id' => $tree->id,
            'root_person_id' => $person->id,
        ]);
    }

    public function testTreeRootPersonCanBeNull(): void
    {
        $user = User::factory()->create();

        $tree = Tree::create([
            'user_id' => $user->id,
            'name' => 'Test Tree',
            'description' => 'Test Description',
            'root_person_id' => null,
        ]);

        $this->assertNull($tree->root_person_id);
        $this->assertNull($tree->rootPerson);
    }
}
