<?php

namespace App\Console\Commands;

use App\Models\Dna;
use App\Services\DnaTriangulationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TriangulateDnaCommand extends Command
{
    protected $signature = 'dna:triangulate 
                            {base_kit_id : The primary DNA kit ID to match against}
                            {--kits=* : Specific kit IDs to compare (optional, defaults to all kits)}
                            {--min-cm=20 : Minimum shared cM threshold}
                            {--three-way : Perform three-way triangulation}
                            {--three-way-kits=* : Exactly three kit IDs required for three-way triangulation (used with --three-way)}
                            {--store : Store results in database}';

    protected $description = 'Perform DNA triangulation analysis to match one kit against many or find triangulated groups';

    protected DnaTriangulationService $triangulationService;

    public function __construct(DnaTriangulationService $triangulationService)
    {
        parent::__construct();
        $this->triangulationService = $triangulationService;
    }

    public function handle(): int
    {
        $baseKitId = (int) $this->argument('base_kit_id');
        $compareKits = $this->option('kits');
        $minCm = (float) $this->option('min-cm');
        $threeWay = $this->option('three-way');
        $threeWayKits = $this->option('three-way-kits');
        $store = $this->option('store');

        // Validate base kit exists
        $baseKit = Dna::find($baseKitId);
        if (!$baseKit) {
            $this->error("Base kit ID {$baseKitId} not found.");
            return Command::FAILURE;
        }

        if ($threeWay) {
            return $this->handleThreeWayTriangulation($threeWayKits);
        }

        return $this->handleOneToManyTriangulation($baseKitId, $compareKits, $minCm, $store);
    }

    protected function handleOneToManyTriangulation(int $baseKitId, array $compareKits, float $minCm, bool $store): int
    {
        $this->info("Starting one-to-many triangulation for kit ID: {$baseKitId}");
        $this->info("Minimum cM threshold: {$minCm}");
        $this->newLine();

        $compareKitIds = !empty($compareKits) ? array_map('intval', $compareKits) : null;

        try {
            $results = $this->triangulationService->triangulateOneAgainstMany(
                $baseKitId,
                $compareKitIds,
                $minCm
            );

            $this->displayOneToManyResults($results);

            if ($store) {
                $this->info('Storing triangulation results in database...');
                $this->triangulationService->storeTriangulationResults($results, 'one_to_many');
                $this->info('Results stored successfully.');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Triangulation failed: ' . $e->getMessage());
            Log::error('DNA triangulation command failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function handleThreeWayTriangulation(array $threeWayKits): int
    {
        if (count($threeWayKits) !== 3) {
            $this->error('Three-way triangulation requires exactly 3 kit IDs. Use --three-way-kits option.');
            return Command::FAILURE;
        }

        $kitIds = array_map('intval', $threeWayKits);
        
        $this->info("Starting three-way triangulation for kits: " . implode(', ', $kitIds));
        $this->newLine();

        try {
            $results = $this->triangulationService->triangulateThreeWay(
                $kitIds[0],
                $kitIds[1],
                $kitIds[2]
            );

            $this->displayThreeWayResults($results);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Three-way triangulation failed: ' . $e->getMessage());
            Log::error('DNA three-way triangulation failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function displayOneToManyResults(array $results): void
    {
        $this->info('Base Kit: ' . $results['base_kit']['name']);
        $this->info('Total Compared: ' . $results['total_compared']);
        $this->info('Significant Matches: ' . $results['significant_matches']);
        $this->newLine();

        if (empty($results['matches'])) {
            $this->warn('No significant matches found.');
            return;
        }

        $this->info('Top Matches:');
        $this->table(
            ['Kit ID', 'Kit Name', 'Shared cM', 'Largest Segment', 'Relationship', 'Confidence', 'Quality Score'],
            array_map(fn($match) => [
                $match['kit_id'],
                substr($match['kit_name'], 0, 30),
                $match['total_cms'],
                $match['largest_cm'],
                $match['predicted_relationship'],
                $match['confidence_level'] . '%',
                $match['match_quality_score'],
            ], array_slice($results['matches'], 0, 20)) // Show top 20
        );
    }

    protected function displayThreeWayResults(array $results): void
    {
        $this->info('Three-Way Triangulation Results');
        $this->newLine();

        $this->info('Kits:');
        foreach ($results['kits'] as $idx => $kit) {
            $this->info(($idx + 1) . ". Kit ID {$kit['id']}: {$kit['name']}");
        }
        $this->newLine();

        $this->info('Pairwise Matches:');
        $this->table(
            ['Pair', 'Shared cM', 'Relationship'],
            [
                ['Kit 1 <-> Kit 2', $results['pairwise_matches']['kit1_kit2']['total_cms'], $results['pairwise_matches']['kit1_kit2']['relationship']],
                ['Kit 1 <-> Kit 3', $results['pairwise_matches']['kit1_kit3']['total_cms'], $results['pairwise_matches']['kit1_kit3']['relationship']],
                ['Kit 2 <-> Kit 3', $results['pairwise_matches']['kit2_kit3']['total_cms'], $results['pairwise_matches']['kit2_kit3']['relationship']],
            ]
        );

        $this->newLine();
        $this->info('Triangulation Score: ' . $results['triangulation_score']);
        $this->info('Triangulated Chromosomes: ' . count($results['triangulated_chromosomes']));

        if (!empty($results['triangulated_chromosomes'])) {
            $this->newLine();
            $this->info('Chromosome Breakdown:');
            $this->table(
                ['Chr', 'Kit1-Kit2 cM', 'Kit1-Kit3 cM', 'Kit2-Kit3 cM', 'Min cM', 'Avg cM'],
                array_map(fn($chr) => [
                    $chr['chromosome'],
                    $chr['kit1_kit2_cm'],
                    $chr['kit1_kit3_cm'],
                    $chr['kit2_kit3_cm'],
                    $chr['min_shared_cm'],
                    $chr['avg_shared_cm'],
                ], $results['triangulated_chromosomes'])
            );
        }
    }
}
