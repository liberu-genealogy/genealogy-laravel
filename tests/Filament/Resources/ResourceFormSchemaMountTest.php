<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\ChecklistTemplateResource\Pages\CreateChecklistTemplate;
use App\Filament\App\Resources\PersonResource;
use App\Filament\App\Resources\PersonResource\Pages\CreatePerson;
use App\Filament\App\Resources\PersonResource\Pages\EditPerson;
use App\Filament\App\Resources\RecordTypeResource\Pages\CreateRecordType;
use App\Filament\App\Resources\VirtualEventResource\Pages\CreateVirtualEvent;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * These pages fataled on mount with "Class Filament\Forms\Components\Section (or
 * ...Grid) not found": in Filament v5 the layout components moved out of the
 * Forms/Infolists packages into Filament\Schemas, and these resources still
 * imported the old paths. The failure only fires when the form schema is built
 * — i.e. on page mount — so the pre-existing *ResourceTest classes, which only
 * assert getModel()/getPages(), never caught it. This mounts each create page
 * so a regression to the dead namespace fails loudly again.
 */
class ResourceFormSchemaMountTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_record_type_create_page_mounts(): void
    {
        $this->actingUser();

        Livewire::test(CreateRecordType::class)->assertOk();
    }

    public function test_person_create_page_mounts(): void
    {
        $this->actingUser();

        Livewire::test(CreatePerson::class)->assertOk();
    }

    /**
     * The edit page is the only one that instantiates relation managers, and
     * PersonResource::getRelations() named them `RelationManagers\Foo::class`
     * with no namespace import — resolving to App\Filament\App\Resources\
     * RelationManagers\Foo, which is not a directory that exists. ::class does
     * not autoload, so the dead name sat there until Filament tried to build it.
     * The create-page test above cannot catch this; only mounting edit can.
     */
    public function test_person_edit_page_mounts_with_its_relation_managers(): void
    {
        $this->actingUser();

        $person = Person::factory()->create();

        Livewire::test(EditPerson::class, ['record' => $person->getRouteKey()])->assertOk();

        foreach (PersonResource::getRelations() as $manager) {
            $this->assertTrue(
                class_exists($manager),
                "PersonResource::getRelations() names a class that does not exist: {$manager}"
            );
        }
    }

    public function test_virtual_event_create_page_mounts(): void
    {
        $this->actingUser();

        Livewire::test(CreateVirtualEvent::class)->assertOk();
    }

    public function test_checklist_template_create_page_mounts(): void
    {
        $this->actingUser();

        Livewire::test(CreateChecklistTemplate::class)->assertOk();
    }
}
