<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Person;
use App\Http\Livewire\FanChartComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FanChartComponentTest extends TestCase
{
    use RefreshDatabase;

    public function testCanFetchAllPeople()
    {
        // Seed the database with a known set of Person records
        $expectedPeople = Person::factory()->count(5)->create();

        Livewire::test(FanChartComponent::class)
            ->assertSet('people', $expectedPeople->toArray());

        // Additionally, ensure the view is correctly receiving the 'people' data
        $component = Livewire::test(FanChartComponent::class);
        $viewData = $component->viewData('people');
        $this->assertEquals($expectedPeople->toArray(), $viewData);
    }
}
