<?php

namespace App\Modules\DNA\Services;

use App\Services\DnaTriangulationService;
use App\Services\AdvancedDnaMatchingService;

class DNAMatchService
{
    protected DnaTriangulationService $triangulationService;
    protected AdvancedDnaMatchingService $matchingService;

    public function __construct()
    {
        $this->triangulationService = app(DnaTriangulationService::class);
        $this->matchingService = app(AdvancedDnaMatchingService::class);
    }

    /**
     * Match one kit against many
     */
    public function matchOneAgainstMany(int $baseKitId, ?array $compareKitIds = null, float $minSharedCm = 20.0): array
    {
        return $this->triangulationService->triangulateOneAgainstMany($baseKitId, $compareKitIds, $minSharedCm);
    }

    /**
     * Perform three-way triangulation
     */
    public function matchThreeWay(int $kit1Id, int $kit2Id, int $kit3Id): array
    {
        return $this->triangulationService->triangulateThreeWay($kit1Id, $kit2Id, $kit3Id);
    }

    /**
     * Find triangulated groups
     */
    public function findTriangulatedGroups(array $kitIds, float $minSharedCm = 20.0): array
    {
        return $this->triangulationService->findTriangulatedGroups($kitIds, $minSharedCm);
    }

    /**
     * Perform advanced matching between two kits
     */
    public function performAdvancedMatching(string $varName1, string $fileName1, string $varName2, string $fileName2): array
    {
        return $this->matchingService->performAdvancedMatching($varName1, $fileName1, $varName2, $fileName2);
    }
}
