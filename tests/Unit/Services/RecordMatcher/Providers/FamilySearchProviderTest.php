<?php

namespace Tests\Unit\Services\RecordMatcher\Providers;

use App\Models\Person;
use App\Services\RecordMatcher\Providers\FamilySearchProvider;
use App\Support\Unavailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FamilySearchProviderTest extends TestCase
{
    use RefreshDatabase;

    protected FamilySearchProvider $provider;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.familysearch.api_key', 'test-api-key');
        Config::set('services.familysearch.base_url', 'https://api.familysearch.test/platform');
        Config::set('services.familysearch.timeout', 30);

        $this->provider = new FamilySearchProvider;
    }

    public function test_is_configured_returns_true_when_api_key_set(): void
    {
        $this->assertTrue($this->provider->isConfigured());
    }

    public function test_get_name_returns_family_search(): void
    {
        $this->assertEquals('FamilySearch', $this->provider->getName());
    }

    public function test_search_reports_unavailable_when_not_configured(): void
    {
        Config::set('services.familysearch.api_key', '');
        $provider = new FamilySearchProvider;

        $person = Person::factory()->create();
        $result = $provider->search($person);

        $this->assertInstanceOf(Unavailable::class, $result);
        $this->assertStringContainsString('not configured', $result->reason);
    }

    public function test_search_parses_gedcomx_format_correctly(): void
    {
        Http::fake([
            'api.familysearch.test/*' => Http::response([
                'entries' => [
                    [
                        'content' => [
                            'gedcomx' => [
                                'persons' => [
                                    [
                                        'id' => 'FS-12345',
                                        'names' => [
                                            [
                                                'nameForms' => [
                                                    [
                                                        'parts' => [
                                                            ['type' => 'given', 'value' => 'Robert'],
                                                            ['type' => 'surname', 'value' => 'Johnson'],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'gender' => ['type' => 'male'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $person = Person::factory()->create([
            'first_name' => 'Robert',
            'last_name' => 'Johnson',
        ]);

        $results = $this->provider->search($person);

        $this->assertCount(1, $results);
        $this->assertEquals('FS-12345', $results[0]['id']);
        $this->assertEquals('Robert', $results[0]['first_name']);
        $this->assertEquals('Johnson', $results[0]['last_name']);
    }
}
