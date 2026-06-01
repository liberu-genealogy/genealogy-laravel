<?php

namespace Tests\Unit\Services\RecordMatcher\Providers;

use App\Models\Person;
use App\Services\RecordMatcher\Providers\AncestryProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AncestryProviderTest extends TestCase
{
    use RefreshDatabase;

    protected AncestryProvider $provider;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        
        Config::set('services.ancestry.api_key', 'test-api-key');
        Config::set('services.ancestry.base_url', 'https://api.ancestry.test/v1');
        Config::set('services.ancestry.timeout', 30);
        
        $this->provider = new AncestryProvider();
    }

    public function testIsConfiguredReturnsTrueWhenApiKeySet(): void
    {
        $this->assertTrue($this->provider->isConfigured());
    }

    public function testGetNameReturnsAncestry(): void
    {
        $this->assertEquals('Ancestry', $this->provider->getName());
    }

    public function testSearchReturnsEmptyArrayWhenNotConfigured(): void
    {
        Config::set('services.ancestry.api_key', '');
        $provider = new AncestryProvider();
        
        $person = Person::factory()->create();
        $results = $provider->search($person);

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testSearchParsesResponseWithRecordsKey(): void
    {
        Http::fake([
            'api.ancestry.test/*' => Http::response([
                'records' => [
                    [
                        'id' => 'ANC-001',
                        'treeId' => 'tree-123',
                        'givenName' => 'Jane',
                        'surname' => 'Smith',
                        'birthYear' => 1890,
                        'birthLocation' => 'New York, USA',
                    ],
                ],
            ], 200),
        ]);

        $person = Person::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);

        $results = $this->provider->search($person);

        $this->assertCount(1, $results);
        $this->assertEquals('ANC-001', $results[0]['id']);
        $this->assertEquals('Jane', $results[0]['first_name']);
        $this->assertEquals('Smith', $results[0]['last_name']);
    }
}
