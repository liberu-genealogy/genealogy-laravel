<?php

namespace Tests\Unit\Services;

use App\Models\Dna;
use App\Models\User;
use App\Models\DnaMatching;
use App\Services\DnaTriangulationService;
use App\Services\AdvancedDnaMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery;

class DnaTriangulationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DnaTriangulationService $service;
    protected AdvancedDnaMatchingService $mockMatchingService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the AdvancedDnaMatchingService
        $this->mockMatchingService = Mockery::mock(AdvancedDnaMatchingService::class);
        $this->service = new DnaTriangulationService($this->mockMatchingService);
        
        Storage::fake('private');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_triangulate_one_against_many_basic(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $kit1 = Dna::factory()->create(['user_id' => $user1->id, 'variable_name' => 'var_kit1']);
        $kit2 = Dna::factory()->create(['user_id' => $user2->id, 'variable_name' => 'var_kit2']);
        $kit3 = Dna::factory()->create(['user_id' => $user3->id, 'variable_name' => 'var_kit3']);

        // Mock matching results
        $this->mockMatchingService->shouldReceive('performAdvancedMatching')
            ->with('var_kit1', $kit1->file_name, 'var_kit2', $kit2->file_name)
            ->andReturn([
                'total_cms' => 150.0,
                'largest_cm' => 45.0,
                'confidence_level' => 70,
                'predicted_relationship' => 'Second Cousin',
                'shared_segments_count' => 12,
                'match_quality_score' => 75.0,
                'chromosome_breakdown' => [],
            ]);

        $this->mockMatchingService->shouldReceive('performAdvancedMatching')
            ->with('var_kit1', $kit1->file_name, 'var_kit3', $kit3->file_name)
            ->andReturn([
                'total_cms' => 10.0,
                'largest_cm' => 5.0,
                'confidence_level' => 30,
                'predicted_relationship' => 'Distant Cousin',
                'shared_segments_count' => 2,
                'match_quality_score' => 35.0,
                'chromosome_breakdown' => [],
            ]);

        $result = $this->service->triangulateOneAgainstMany($kit1->id, null, 20.0);

        $this->assertEquals($kit1->id, $result['base_kit']['id']);
        $this->assertEquals(2, $result['total_compared']);
        $this->assertEquals(1, $result['significant_matches']); // Only kit2 meets min threshold
        $this->assertCount(1, $result['matches']);
        $this->assertEquals(150.0, $result['matches'][0]['total_cms']);
    }

    public function test_triangulate_three_way(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $kit1 = Dna::factory()->create(['user_id' => $user1->id, 'variable_name' => 'var_kit1']);
        $kit2 = Dna::factory()->create(['user_id' => $user2->id, 'variable_name' => 'var_kit2']);
        $kit3 = Dna::factory()->create(['user_id' => $user3->id, 'variable_name' => 'var_kit3']);

        // Mock three pairwise matches
        $chromosomeBreakdown = [
            1 => ['total_cm' => 10.0, 'segment_count' => 2, 'largest_segment' => 7.0],
            2 => ['total_cm' => 8.0, 'segment_count' => 1, 'largest_segment' => 8.0],
        ];

        $this->mockMatchingService->shouldReceive('performAdvancedMatching')
            ->andReturn([
                'total_cms' => 150.0,
                'largest_cm' => 45.0,
                'confidence_level' => 70,
                'predicted_relationship' => 'Second Cousin',
                'shared_segments_count' => 12,
                'match_quality_score' => 75.0,
                'chromosome_breakdown' => $chromosomeBreakdown,
            ])
            ->times(3);

        $result = $this->service->triangulateThreeWay($kit1->id, $kit2->id, $kit3->id);

        $this->assertArrayHasKey('kits', $result);
        $this->assertCount(3, $result['kits']);
        $this->assertArrayHasKey('pairwise_matches', $result);
        $this->assertArrayHasKey('kit1_kit2', $result['pairwise_matches']);
        $this->assertArrayHasKey('kit1_kit3', $result['pairwise_matches']);
        $this->assertArrayHasKey('kit2_kit3', $result['pairwise_matches']);
        $this->assertArrayHasKey('triangulated_chromosomes', $result);
        $this->assertArrayHasKey('triangulation_score', $result);
    }

    public function test_find_triangulated_chromosomes(): void
    {
        $breakdown12 = [
            1 => ['total_cm' => 10.0, 'segment_count' => 2],
            2 => ['total_cm' => 8.0, 'segment_count' => 1],
            3 => ['total_cm' => 0, 'segment_count' => 0],
        ];

        $breakdown13 = [
            1 => ['total_cm' => 12.0, 'segment_count' => 3],
            2 => ['total_cm' => 5.0, 'segment_count' => 1],
            3 => ['total_cm' => 0, 'segment_count' => 0],
        ];

        $breakdown23 = [
            1 => ['total_cm' => 11.0, 'segment_count' => 2],
            2 => ['total_cm' => 7.0, 'segment_count' => 2],
            3 => ['total_cm' => 4.0, 'segment_count' => 1],
        ];

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('findTriangulatedChromosomes');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $breakdown12, $breakdown13, $breakdown23);

        // Only chromosomes 1 and 2 should be triangulated (all three pairs share DNA)
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(2, $result);
        $this->assertArrayNotHasKey(3, $result);
        
        $this->assertEquals(10.0, $result[1]['min_shared_cm']);
        $this->assertEquals(5.0, $result[2]['min_shared_cm']);
    }

    public function test_calculate_triangulation_score(): void
    {
        $triangulatedChromosomes = [
            1 => ['min_shared_cm' => 10.0],
            2 => ['min_shared_cm' => 8.0],
            3 => ['min_shared_cm' => 5.0],
        ];

        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calculateTriangulationScore');
        $method->setAccessible(true);

        $score = $method->invoke($this->service, $triangulatedChromosomes);

        $this->assertEquals(23.0, $score);
    }

    public function test_calculate_triangulation_score_empty(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calculateTriangulationScore');
        $method->setAccessible(true);

        $score = $method->invoke($this->service, []);

        $this->assertEquals(0.0, $score);
    }

    public function test_store_triangulation_results(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $kit1 = Dna::factory()->create(['user_id' => $user1->id]);
        $kit2 = Dna::factory()->create(['user_id' => $user2->id]);

        $results = [
            'base_kit' => ['id' => $kit1->id],
            'matches' => [
                [
                    'kit_id' => $kit2->id,
                    'kit_name' => 'Test Kit 2',
                    'user_id' => $user2->id,
                    'total_cms' => 150.0,
                    'largest_cm' => 45.0,
                    'confidence_level' => 70,
                    'predicted_relationship' => 'Second Cousin',
                    'shared_segments_count' => 12,
                    'match_quality_score' => 75.0,
                    'chromosome_breakdown' => [],
                ],
            ],
        ];

        $this->service->storeTriangulationResults($results, 'one_to_many');

        $this->assertDatabaseHas('dna_matchings', [
            'user_id' => $user1->id,
            'match_id' => $user2->id,
            'total_shared_cm' => 150.0,
            'predicted_relationship' => 'Second Cousin',
        ]);
    }
}
