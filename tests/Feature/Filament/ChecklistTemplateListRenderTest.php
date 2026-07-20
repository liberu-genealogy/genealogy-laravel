<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\App\Resources\ChecklistTemplateResource\Pages\ListChecklistTemplates;
use App\Models\ChecklistTemplate;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The category column formats state through a helper. It used title_case(),
 * removed from Laravel in v6, so listing templates fatalled as soon as a row
 * rendered. Rendering the table with a real row exercises that formatter.
 */
class ChecklistTemplateListRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_renders_the_category_column(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        $template = ChecklistTemplate::create([
            'name' => 'Vital Records',
            'created_by' => $user->id,
            'category' => 'vital_records',
        ]);

        Livewire::test(ListChecklistTemplates::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$template]);
    }
}
