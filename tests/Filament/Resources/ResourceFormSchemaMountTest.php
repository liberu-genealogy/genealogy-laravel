<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\ChecklistTemplateResource\Pages\CreateChecklistTemplate;
use App\Filament\App\Resources\PersonResource\Pages\CreatePerson;
use App\Filament\App\Resources\RecordTypeResource\Pages\CreateRecordType;
use App\Filament\App\Resources\VirtualEventResource\Pages\CreateVirtualEvent;
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
