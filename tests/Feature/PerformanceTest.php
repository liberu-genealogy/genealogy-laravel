<?php

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function testLargeDatasetRetrieval()
    {
        // Create a large dataset
        Person::factory()->count(10000)->create();

        // Test uncached retrieval
        $start = microtime(true);
        $result = Person::all();
        $end = microtime(true);
        $timeUncached = $end - $start;

        // Test cached retrieval
        $start = microtime(true);
        $result = Person::getListCached();
        $end = microtime(true);
        $timeCached = $end - $start;

        $this->assertLessThan($timeUncached, $timeCached);
        $this->assertLessThan(1.0, $timeCached); // Ensure retrieval takes less than 1 second
    }

    public function testQueryPerformance()
    {
        Person::factory()->count(10000)->create();

        DB::enableQueryLog();

        $person = Person::withBasicInfo()->first();

        $queries = DB::getQueryLog();
        
        $this->assertCount(1, $queries);
        $this->assertStringContainsString('select `id`, `givn`, `surn`, `sex`, `child_in_family_id`, `birthday`, `deathday`', $queries[0]['query']);
    }
}