<?php

declare(strict_types=1);

namespace Tests\Filament\Widgets;

use App\Filament\App\Pages\Dashboard;
use App\Filament\App\Widgets\PeopleWidget;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PeopleWidgetTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($this->user);
        Filament::setTenant($this->user->currentTeam, isQuiet: true);
    }

    public function test_widget_renders_and_lists_people(): void
    {
        $person = Person::factory()->create(['givn' => 'Ada', 'surn' => 'Lovelace']);

        Livewire::test(PeopleWidget::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$person]);
    }

    public function test_widget_is_registered_on_the_dashboard(): void
    {
        $this->assertContains(PeopleWidget::class, (new Dashboard)->getWidgets());
    }
}
