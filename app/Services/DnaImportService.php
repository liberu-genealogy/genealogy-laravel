<?php

namespace App\Services;

use Exception;
use App\Models\Dna;
use App\Jobs\DnaMatching;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DnaImportService
{
    const VAR_NAME_PREFIX = 'var_';
    const VAR_NAME_LENGTH = 5;
    const EXPECTED_VAR_NAME_FULL_LENGTH = 9; // prefix (4) + random (5)
    
    /**
     * Import multiple DNA kits from files
     *
     * @param array $files Array of file paths to import
     * @param int $userId User ID to associate kits with
     * @param bool $autoMatch Whether to automatically match after import
     * @return array Results of import operations
     */
    public function importMultipleKits(array $files, int $userId, bool $autoMatch = true): array
    {
        $results = [
            'successful' => [],
            'failed' => [],
            'total' => count($files),
        ];

        foreach ($files as $file) {
            try {
                $result = $this->importSingleKit($file, $userId, $autoMatch);
                $results['successful'][] = $result;
            } catch (Exception $e) {
                $results['failed'][] = [
                    'file' => $file,
                    'error' => $e->getMessage(),
                ];
                Log::error("DNA kit import failed for file {$file}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Import a single DNA kit
     *
     * @param string $filePath Path to DNA file
     * @param int $userId User ID
     * @param bool $autoMatch Whether to dispatch matching job
     * @return array Import result
     */
    public function importSingleKit(string $filePath, int $userId, bool $autoMatch = true, bool $consentGiven = false): array
    {
        // Validate file exists
        if (!Storage::disk('private')->exists($filePath)) {
            throw new Exception("DNA file not found: {$filePath}");
        }

        // Validate file format (on the plaintext, before it is encrypted below)
        $validation = $this->validateDnaFile($filePath);
        if (!$validation['valid']) {
            throw new Exception("Invalid DNA file format: " . $validation['error']);
        }

        // Encrypt the raw DNA file at rest now that it is validated (SCOPE §20).
        $vault = app(\App\Services\Dna\DnaFileVault::class);
        $vault->store(Storage::disk('private')->get($filePath), $filePath);

        // Generate unique variable name
        $varName = $this->generateUniqueVarName();

        // Create DNA record
        $dna = new Dna();
        $dna->name = 'DNA Kit for user ' . $userId . ' (' . basename($filePath) . ')';
        $dna->user_id = $userId;
        $dna->variable_name = $varName;
        $dna->file_name = $filePath;
        if ($consentGiven) {
            $dna->consent_given = true;
            $dna->consent_given_at = now();
        }
        $dna->save();

        // Dispatch matching only for a consented kit — never match DNA without consent.
        if ($autoMatch && $dna->hasConsent()) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                DnaMatching::dispatch($user, $varName, $filePath);
                Log::info("DNA matching job dispatched for kit {$varName}");
            }
        }

        return [
            'dna_id' => $dna->id,
            'variable_name' => $varName,
            'file_name' => $filePath,
            'snp_count' => $validation['snp_count'] ?? 0,
            'format' => $validation['format'] ?? 'unknown',
        ];
    }

    /**
     * Validate DNA file format and content
     *
     * @param string $filePath Path to DNA file
     * @return array Validation result
     */
    public function validateDnaFile(string $filePath): array
    {
        try {
            $fullPath = Storage::disk('private')->path($filePath);

            // Check file exists and is readable
            if (!file_exists($fullPath) || !is_readable($fullPath)) {
                return [
                    'valid' => false,
                    'error' => 'File is not readable',
                ];
            }

            // Check file size (should be at least 1KB, max 100MB)
            $fileSize = filesize($fullPath);
            if ($fileSize < 1024) {
                return [
                    'valid' => false,
                    'error' => 'File is too small to be a valid DNA file',
                ];
            }

            if ($fileSize > 100 * 1024 * 1024) {
                return [
                    'valid' => false,
                    'error' => 'File exceeds maximum size of 100MB',
                ];
            }

            // Detect format by reading first few lines
            $handle = fopen($fullPath, 'r');
            $firstLine = fgets($handle);
            $secondLine = fgets($handle);
            fclose($handle);

            $format = $this->detectFileFormat($firstLine, $secondLine);

            if ($format === 'unknown') {
                return [
                    'valid' => false,
                    'error' => 'Unrecognized DNA file format',
                ];
            }

            // Count SNPs with the real parser. (The vendor php-dna Snps class does
            // not exist / is non-functional, so this replaces the old guarded
            // class_exists(Snps::class) block that always left the count at 0.)
            $snpCount = 0;
            try {
                foreach (app(\App\Services\Dna\RawDnaParser::class)->parse($fullPath) as $positions) {
                    $snpCount += count($positions);
                }
            } catch (\Throwable $e) {
                return [
                    'valid' => false,
                    'error' => 'Could not parse DNA file: ' . $e->getMessage(),
                ];
            }

            return [
                'valid' => true,
                'format' => $format,
                'snp_count' => $snpCount,
            ];

        } catch (\Throwable $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Detect DNA file format from header lines
     *
     * @param string $firstLine First line of file
     * @param string $secondLine Second line of file
     * @return string Format identifier
     */
    protected function detectFileFormat(string $firstLine, string $secondLine): string
    {
        // 23andMe format
        if (str_contains($firstLine, '# This data file generated by 23andMe')) {
            return '23andme';
        }

        // AncestryDNA format
        if (str_contains($firstLine, 'rsid') && str_contains($firstLine, 'chromosome')) {
            return 'ancestry';
        }

        // MyHeritage format
        if (str_contains($firstLine, 'RSID') && str_contains($firstLine, 'Chr')) {
            return 'myheritage';
        }

        // FamilyTreeDNA format
        if (str_contains($firstLine, 'RSID') && str_contains($firstLine, 'CHROMOSOME')) {
            return 'ftdna';
        }

        // Generic CSV/TSV format with rsid
        if (preg_match('/rs\d+/', $firstLine . $secondLine)) {
            return 'generic';
        }

        return 'unknown';
    }

    /**
     * Generate unique variable name for DNA kit
     *
     * @return string Unique variable name
     */
    protected function generateUniqueVarName(): string
    {
        $varName = self::VAR_NAME_PREFIX . Str::random(self::VAR_NAME_LENGTH);
        
        while (Dna::where('variable_name', $varName)->exists()) {
            $varName = self::VAR_NAME_PREFIX . Str::random(self::VAR_NAME_LENGTH);
        }

        return $varName;
    }

    /**
     * Get import statistics for a user
     *
     * @param int $userId User ID
     * @return array Statistics
     */
    public function getImportStatistics(int $userId): array
    {
        $kits = Dna::where('user_id', $userId)->get();

        return [
            'total_kits' => $kits->count(),
            'oldest_kit' => $kits->min('created_at'),
            'newest_kit' => $kits->max('created_at'),
        ];
    }
}
