<?php

namespace Tests\Filament\Resources;

use App\Filament\Resources\PersonResource;
use App\Models\Person;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_contains_relationship_fields()
    {
        $father = Person::factory()->create();
        $mother = Person::factory()->create();

        $form = PersonResource::form(null)->getSchema();

        $fatherField = collect($form)->firstWhere('name', 'father_id');
        $motherField = collect($form)->firstWhere('name', 'mother_id');

        $this->assertEquals('father_id', $fatherField->getName());
        $this->assertEquals('mother_id', $motherField->getName());
        $this->assertInstanceOf(Select::class, $fatherField);
        $this->assertInstanceOf(Select::class, $motherField);
    }

    public function test_table_filters_by_name()
    {
        $person1 = Person::factory()->create(['name' => 'John Doe']);
        $person2 = Person::factory()->create(['name' => 'Jane Doe']);

        $table = PersonResource::table(null);
        $filteredQuery = $table->applyFilters(
            query: Person::query(),
            filters: ['name' => 'John']
        );

        $this->assertTrue($filteredQuery->get()->contains($person1));
        $this->assertFalse($filteredQuery->get()->contains($person2));
    }

    public function test_bulk_delete_action()
    {
        $persons = Person::factory()->count(5)->create();

        $deleteAction = new DeleteBulkAction();

        $deleteAction->apply(
            query: Person::whereIn('id', $persons->pluck('id')),
            records: $persons->pluck('id')->toArray()
        );

        $this->assertDatabaseMissing('people', ['id' => $persons->first()->id]);
    }
}
