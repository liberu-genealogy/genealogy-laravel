<?php

namespace App\Console\Commands;

use App\Services\DnaImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BulkImportDnaCommand extends Command
{
    protected $signature = 'dna:import 
                            {user_id : The user ID to import kits for}
                            {--directory= : Directory containing DNA files to import}
                            {--files=* : Specific file paths to import}
                            {--no-match : Skip automatic matching after import}';

    protected $description = 'Bulk import DNA kits from files';

    protected DnaImportService $importService;

    public function __construct(DnaImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    public function handle(): int
    {
        $userId = $this->argument('user_id');
        $directory = $this->option('directory');
        $files = $this->option('files');
        $autoMatch = !$this->option('no-match');

        // Collect files to import
        $filesToImport = [];

        if ($directory) {
            // Import all files from directory
            $this->info("Scanning directory: {$directory}");
            $diskFiles = Storage::disk('private')->files($directory);
            $filesToImport = array_merge($filesToImport, $diskFiles);
        }

        if (!empty($files)) {
            // Import specific files
            $filesToImport = array_merge($filesToImport, $files);
        }

        if (empty($filesToImport)) {
            $this->error('No files to import. Use --directory or --files option.');
            return Command::FAILURE;
        }

        $this->info('Starting bulk DNA import...');
        $this->info('Files to import: ' . count($filesToImport));
        $this->newLine();

        $progressBar = $this->output->createProgressBar(count($filesToImport));
        $progressBar->start();

        $results = [
            'successful' => [],
            'failed' => [],
        ];

        foreach ($filesToImport as $file) {
            try {
                $result = $this->importService->importSingleKit($file, $userId, $autoMatch);
                $results['successful'][] = $result;
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'file' => $file,
                    'error' => $e->getMessage(),
                ];
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display results
        $this->displayResults($results);

        return Command::SUCCESS;
    }

    protected function displayResults(array $results): void
    {
        $successCount = count($results['successful']);
        $failCount = count($results['failed']);
        $total = $successCount + $failCount;

        $this->info("Import complete!");
        $this->newLine();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Files', $total],
                ['Successful', $successCount],
                ['Failed', $failCount],
                ['Success Rate', $total > 0 ? round(($successCount / $total) * 100, 2) . '%' : 'N/A'],
            ]
        );

        if ($successCount > 0) {
            $this->newLine();
            $this->info('Successfully imported kits:');
            $this->table(
                ['DNA ID', 'Variable Name', 'SNP Count', 'Format'],
                array_map(fn($r) => [
                    $r['dna_id'],
                    $r['variable_name'],
                    $r['snp_count'] ?? 'N/A',
                    $r['format'] ?? 'unknown',
                ], $results['successful'])
            );
        }

        if ($failCount > 0) {
            $this->newLine();
            $this->error('Failed imports:');
            $this->table(
                ['File', 'Error'],
                array_map(fn($r) => [$r['file'], $r['error']], $results['failed'])
            );
        }
    }
}
