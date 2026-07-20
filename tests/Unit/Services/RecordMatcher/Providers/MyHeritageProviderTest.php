<?php

namespace Tests\Unit\Services\RecordMatcher\Providers;

use App\Models\Person;
use App\Services\RecordMatcher\Providers\MyHeritageProvider;
use App\Support\Unavailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MyHeritageProviderTest extends TestCase
{
    use RefreshDatabase;

    protected MyHeritageProvider $provider;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        // Set up test configuration
        Config::set('services.myheritage.api_key', 'test-api-key');
        Config::set('services.myheritage.base_url', 'https://api.myheritage.test/v1');
        Config::set('services.myheritage.timeout', 30);

        $this->provider = new MyHeritageProvider;
    }

    public function test_is_configured_returns_true_when_api_key_set(): void
    {
        $this->assertTrue($this->provider->isConfigured());
    }

    public function test_is_configured_returns_false_when_api_key_not_set(): void
    {
        Config::set('services.myheritage.api_key', '');
        $provider = new MyHeritageProvider;

        $this->assertFalse($provider->isConfigured());
    }

    public function test_get_name_returns_my_heritage(): void
    {
        $this->assertEquals('MyHeritage', $this->provider->getName());
    }

    public function test_search_reports_unavailable_when_api_key_not_configured(): void
    {
        Config::set('services.myheritage.api_key', '');
        $provider = new MyHeritageProvider;

        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $result = $provider->search($person);

        $this->assertInstanceOf(Unavailable::class, $result);
        $this->assertStringContainsString('not configured', $result->reason);
    }

    public function test_search_returns_empty_array_for_invalid_person(): void
    {
        $results = $this->provider->search(99999);

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function test_search_calls_api_with_correct_parameters(): void
    {
        Http::fake([
            'api.myheritage.test/*' => Http::response([
                'persons' => [
                    [
                        'id' => 'MH-12345',
                        'tree_id' => 'tree-789',
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'birth_year' => 1880,
                        'birth_place' => 'London, England',
                    ],
                ],
            ], 200),
        ]);

        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $results = $this->provider->search($person);

        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer test-api-key') &&
               $request->hasHeader('Accept', 'application/json') &&
               str_contains((string) $request->url(), 'api.myheritage.test'));

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function test_search_parses_response_correctly(): void
    {
        Http::fake([
            'api.myheritage.test/*' => Http::response([
                'persons' => [
                    [
                        'id' => 'MH-12345',
                        'tree_id' => 'tree-789',
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'birth_year' => 1880,
                        'birth_date' => '1880-05-15',
                        'birth_place' => 'London, England',
                        'death_year' => 1950,
                        'gender' => 'M',
                        'url' => 'https://myheritage.com/person/12345',
                        'tree_name' => 'Family Tree',
                        'tree_owner' => 'Jane Doe',
                    ],
                ],
            ], 200),
        ]);

        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $results = $this->provider->search($person);

        $this->assertCount(1, $results);
        $this->assertEquals('MH-12345', $results[0]['id']);
        $this->assertEquals('tree-789', $results[0]['tree_id']);
        $this->assertEquals('John', $results[0]['first_name']);
        $this->assertEquals('Doe', $results[0]['last_name']);
        $this->assertEquals(1880, $results[0]['birth_year']);
        $this->assertEquals('London, England', $results[0]['birth_place']);
        $this->assertEquals('M', $results[0]['gender']);
    }

    public function test_search_reports_unavailable_on_a_failed_request(): void
    {
        Http::fake([
            'api.myheritage.test/*' => Http::response([], 500),
        ]);

        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $result = $this->provider->search($person);

        $this->assertInstanceOf(Unavailable::class, $result);
        $this->assertStringContainsString('request failed', $result->reason);
    }

    public function test_search_accepts_person_model(): void
    {
        Http::fake([
            'api.myheritage.test/*' => Http::response([
                'persons' => [],
            ], 200),
        ]);

        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $results = $this->provider->search($person);

        $this->assertIsArray($results);
    }

    public function test_search_accepts_person_id(): void
    {
        Http::fake([
            'api.myheritage.test/*' => Http::response([
                'persons' => [],
            ], 200),
        ]);

        $person = Person::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $results = $this->provider->search($person->id);

        $this->assertIsArray($results);
    }
}
