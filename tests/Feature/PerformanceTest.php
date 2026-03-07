<?php

namespace Tests\Feature;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function testLargeDatasetRetrieval(): void
    {
        // Create a dataset (reduced size for CI stability)
        Person::factory()->count(100)->create();

        // Test uncached retrieval
        $start = microtime(true);
        Person::all();
        $end = microtime(true);
        $timeUncached = $end - $start;

        // Warm up the cache before measuring cached retrieval
        Person::getListCached();

        // Test cached retrieval (cache is now warm)
        $start = microtime(true);
        Person::getListCached();
        $end = microtime(true);
        $timeCached = $end - $start;

        $this->assertLessThan($timeUncached, $timeCached);
        $this->assertLessThan(0.5, $timeCached); // Cached retrieval should complete well under 0.5 seconds
    }

    public function testQueryPerformance(): void
    {
        Person::factory()->count(10)->create();

        DB::enableQueryLog();

        Person::withBasicInfo()->first();

        $queries = DB::getQueryLog();

        $this->assertCount(1, $queries);
        $this->assertStringContainsString('select `id`, `givn`, `surn`, `sex`, `child_in_family_id`, `birthday`, `deathday`', $queries[0]['query']);
    }
}