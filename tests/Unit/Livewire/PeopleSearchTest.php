<?php

namespace Tests\Unit\Livewire;

use App\Livewire\PeopleSearch;
use Livewire\Livewire;
use Tests\TestCase;

class PeopleSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // ensure the database schema is prepared for the component
        $this->artisan('migrate');
    }

    public function test_render_function_returns_correct_view_with_data(): void
    {
        Livewire::test(PeopleSearch::class)
            ->assertViewHas('results', fn ($results): bool =>
                // Assuming the database or mocked data setup, we expect results to be an instance of a collection or an array.
                is_array($results) || $results instanceof \Illuminate\Support\Collection);
    }
}
