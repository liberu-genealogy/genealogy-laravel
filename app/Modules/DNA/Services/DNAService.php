<?php

namespace App\Modules\DNA\Services;

use App\Services\DnaImportService as BaseDnaImportService;
use App\Services\DnaTriangulationService as BaseDnaTriangulationService;
use App\Services\AdvancedDnaMatchingService;

class DNAService
{
    protected BaseDnaImportService $importService;
    protected BaseDnaTriangulationService $triangulationService;
    protected AdvancedDnaMatchingService $matchingService;

    public function __construct()
    {
        $this->importService = app(BaseDnaImportService::class);
        $this->triangulationService = app(BaseDnaTriangulationService::class);
        $this->matchingService = app(AdvancedDnaMatchingService::class);
    }

    /**
     * Get the DNA import service
     */
    public function import(): BaseDnaImportService
    {
        return $this->importService;
    }

    /**
     * Get the DNA triangulation service
     */
    public function triangulate(): BaseDnaTriangulationService
    {
        return $this->triangulationService;
    }

    /**
     * Get the DNA matching service
     */
    public function match(): AdvancedDnaMatchingService
    {
        return $this->matchingService;
    }
}
