<?php

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\PersonResource;
use App\Filament\App\Resources\PersonResource\Pages\CreatePerson;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PersonResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_model_is_correct(): void
    {
        $this->assertEquals(Person::class, PersonResource::getModel());
    }

    public function test_resource_pages_registered(): void
    {
        $pages = PersonResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_crud_operations(): void
    {
        $person = Person::factory()->create([
            'givn' => 'John',
            'surn' => 'Doe',
        ]);

        $this->assertDatabaseHas('people', ['givn' => 'John', 'surn' => 'Doe']);

        $retrieved = Person::find($person->id);
        $this->assertNotNull($retrieved);

        $person->update(['givn' => 'Jane']);
        $this->assertDatabaseHas('people', ['givn' => 'Jane']);

        $person->delete();
        $this->assertSoftDeleted('people', ['id' => $person->id]);
    }

    private function actingAsTenantUser(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);
    }

    /** child_in_family_id is a Family picker: a person saves linked to a chosen family. */
    public function test_person_form_saves_with_family_picker(): void
    {
        $this->actingAsTenantUser();
        $family = Family::factory()->create();

        Livewire::test(CreatePerson::class)
            ->fillForm(['givn' => 'Kid', 'surn' => 'Doe', 'child_in_family_id' => $family->id])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('people', ['givn' => 'Kid', 'child_in_family_id' => $family->id]);
    }

    /** A person with every name field empty is rejected, not silently saved blank. */
    public function test_person_form_rejects_blank_name(): void
    {
        $this->actingAsTenantUser();

        Livewire::test(CreatePerson::class)
            ->fillForm(['givn' => '', 'surn' => '', 'name' => ''])
            ->call('create')
            ->assertHasFormErrors(['givn']);
    }

    /** Any single name field satisfies the "must have a name" rule — here, only surn. */
    public function test_person_form_saves_with_only_one_name(): void
    {
        $this->actingAsTenantUser();

        Livewire::test(CreatePerson::class)
            ->fillForm(['givn' => '', 'surn' => 'Solo', 'name' => ''])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('people', ['surn' => 'Solo']);
    }
}
