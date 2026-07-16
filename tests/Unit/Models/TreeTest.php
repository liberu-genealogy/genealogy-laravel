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

    public function test_tree_has_root_person_relationship(): void
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

    public function test_tree_has_user_relationship(): void
    {
        $user = User::factory()->create();
        $tree = Tree::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $tree->user);
        $this->assertEquals($user->id, $tree->user->id);
    }

    public function test_get_stats_returns_empty_stats_when_no_root_person(): void
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

    public function test_get_stats_returns_correct_stats_with_root_person(): void
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

    public function test_tree_can_be_created_with_root_person_id(): void
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

    public function test_tree_root_person_can_be_null(): void
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
