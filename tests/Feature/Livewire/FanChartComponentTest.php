<?php

namespace Tests\Feature\Livewire;

use App\Livewire\FanChartComponent;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FanChartComponentTest extends TestCase
{
    use RefreshDatabase;

    public function testComponentCanBeRendered(): void
    {
        Person::factory()->count(3)->create();

        Livewire::test(FanChartComponent::class)
            ->assertStatus(200);
    }

    public function testComponentLoadsAllPeople(): void
    {
        $people = Person::factory()->count(5)->create();

        $component = Livewire::test(FanChartComponent::class);

        $this->assertCount(5, Person::all());
    }
}
