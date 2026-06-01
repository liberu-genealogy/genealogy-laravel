<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Family;
use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_person_can_be_created_with_factory(): void
    {
        $person = Person::factory()->create();

        $this->assertInstanceOf(Person::class, $person);
        $this->assertDatabaseHas('people', ['id' => $person->id]);
    }

    public function test_person_has_gender_constants(): void
    {
        $this->assertSame('M', Person::GENDER_MALE);
        $this->assertSame('F', Person::GENDER_FEMALE);
        $this->assertSame('U', Person::GENDER_UNKNOWN);
    }

    public function test_person_can_be_created_with_all_genders(): void
    {
        foreach ([Person::GENDER_MALE, Person::GENDER_FEMALE, Person::GENDER_UNKNOWN] as $sex) {
            $person = Person::factory()->create(['sex' => $sex]);
            $this->assertSame($sex, $person->sex);
        }
    }

    public function test_person_fillable_attributes(): void
    {
        $person = Person::factory()->create([
            'givn' => 'John',
            'surn' => 'Doe',
            'sex'  => 'M',
            'name' => 'John Doe',
        ]);

        $this->assertSame('John', $person->givn);
        $this->assertSame('Doe', $person->surn);
        $this->assertSame('M', $person->sex);
        $this->assertSame('John Doe', $person->name);
    }

    public function test_person_can_be_updated(): void
    {
        $person = Person::factory()->create(['givn' => 'Jane']);
        $person->update(['givn' => 'Mary']);

        $this->assertSame('Mary', $person->fresh()->givn);
    }

    public function test_person_can_be_soft_or_hard_deleted(): void
    {
        $person = Person::factory()->create();
        $id = $person->id;

        $person->delete();
        $this->assertSoftDeleted('people', ['id' => $id]);

        $person->forceDelete();
        $this->assertDatabaseMissing('people', ['id' => $id]);
    }

    public function test_multiple_people_can_be_created(): void
    {
        Person::factory()->count(5)->create();

        $this->assertGreaterThanOrEqual(5, Person::count());
    }
}
