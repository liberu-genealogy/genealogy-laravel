<?php

namespace Tests\Unit\Livewire;

use App\Http\Livewire\PeopleSearch;
use Livewire\Livewire;
use Tests\TestCase;

class PeopleSearchTest extends TestCase
{
    public function testRenderFunctionReturnsCorrectViewWithData()
    {
        Livewire::test(PeopleSearch::class)
            ->assertViewHas('results', function ($results) {
                // Assuming the database or mocked data setup, we expect results to be an instance of a collection or an array.
                return is_array($results) || $results instanceof \Illuminate\Support\Collection;
            });
    }
}
