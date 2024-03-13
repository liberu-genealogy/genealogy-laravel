<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\PeopleSearch;
use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;

class PeopleSearchTest extends TestCase
{
    public function test_initial_state()
    {
        Livewire::test(PeopleSearch::class)
            ->assertSet('query', '')
            ->assertSet('results', []);
    }

    public function test_search_people_with_results()
    {
        $mockedResults = new Collection([new Person(['givn' => 'John', 'surn' => 'Doe']), new Person(['givn' => 'Jane', 'surn' => 'Doe'])]);
        Person::shouldReceive('where')
              ->with('givn', 'like', '%John%')
              ->andReturnSelf()
              ->shouldReceive('orWhere')
              ->with('surn', 'like', '%Doe%')
              ->andReturnSelf()
              ->shouldReceive('get')
              ->andReturn($mockedResults);

        Livewire::test(PeopleSearch::class)
            ->set('query', 'John')
            ->assertSet('results', $mockedResults);
    }

    public function test_search_people_with_no_results()
    {
        Person::shouldReceive('where')
              ->andReturnSelf()
              ->shouldReceive('orWhere')
              ->andReturnSelf()
              ->shouldReceive('get')
              ->andReturn(new Collection());

        Livewire::test(PeopleSearch::class)
            ->set('query', 'Nonexistent')
            ->assertSet('results', []);
    }

    public function test_search_people_with_special_characters()
    {
        $mockedResults = new Collection([new Person(['givn' => 'John@', 'surn' => 'Doe#'])]);
        Person::shouldReceive('where')
              ->with('givn', 'like', '%John@%')
              ->andReturnSelf()
              ->shouldReceive('orWhere')
              ->with('surn', 'like', '%Doe#%')
              ->andReturnSelf()
              ->shouldReceive('get')
              ->andReturn($mockedResults);

        Livewire::test(PeopleSearch::class)
            ->set('query', 'John@')
            ->assertSet('results', $mockedResults);
    }
}
