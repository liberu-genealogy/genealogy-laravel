<?php

namespace Tests\Unit\Services\RecordMatcher\Providers;

use App\Models\Person;
use App\Services\RecordMatcher\Providers\FamilySearchProvider;
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
        
        $this->provider = new FamilySearchProvider();
    }

    public function testIsConfiguredReturnsTrueWhenApiKeySet(): void
    {
        $this->assertTrue($this->provider->isConfigured());
    }

    public function testGetNameReturnsFamilySearch(): void
    {
        $this->assertEquals('FamilySearch', $this->provider->getName());
    }

    public function testSearchReturnsEmptyArrayWhenNotConfigured(): void
    {
        Config::set('services.familysearch.api_key', '');
        $provider = new FamilySearchProvider();
        
        $person = Person::factory()->create();
        $results = $provider->search($person);

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testSearchParsesGedcomxFormatCorrectly(): void
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
