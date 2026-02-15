<?php

namespace App\Jobs;

use Artisan;
use Throwable;
use Exception;
use App\Models\ImportJob;
use App\Models\User;
use App\Services\GrampsXmlService;
use FamilyTree365\LaravelGedcom\Utils\GedcomParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportGrampsXml implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 0;
    public int $tries = 1;

    public function __construct(protected User $user, protected string $filePath, protected ?string $slug = null)
    {
    }

    public function handle(): int
    {
        throw_unless(File::isFile($this->filePath), Exception::class, "{$this->filePath} does not exist.");

        $slug = $this->slug ?? Str::uuid();

        $job = ImportJob::create([
            'user_id' => $this->user->getKey(),
            'status'  => 'queue',
            'slug'    => $slug,
        ]);

        try {
            // Parse GrampsXML file
            $grampsXmlService = new GrampsXmlService();
            $grampsData = $grampsXmlService->parseGrampsXml($this->filePath);

            Log::info('GrampsXML parsed successfully', [
                'people_count' => $grampsData['stats']['people_count'] ?? 0,
                'families_count' => $grampsData['stats']['families_count'] ?? 0,
            ]);

            // Convert GrampsXML to GEDCOM format and import using existing parser
            // This leverages the existing GEDCOM import infrastructure
            $gedcomContent = $this->convertGrampsToGedcom($grampsData['data']);
            $tempGedcomPath = storage_path('app/temp/' . $slug . '.ged');
            
            // Ensure temp directory exists
            File::ensureDirectoryExists(dirname($tempGedcomPath));
            File::put($tempGedcomPath, $gedcomContent);

            // Use existing GEDCOM parser
            $parser = new GedcomParser();
            $team_id = $this->user->currentTeam?->id;
            $parser->parse($job->getConnectionName(), $tempGedcomPath, $slug, true, $team_id);

            // Clean up temp file
            File::delete($tempGedcomPath);

            $job->update(['status' => 'complete']);

            // Clear application caches
            try {
                Artisan::call('cache:clear');
                Artisan::call('view:clear');
                Artisan::call('config:clear');
            } catch (Throwable $e) {
                // swallow cache clear errors
            }
        } catch (Throwable $e) {
            Log::error('GrampsXML import failed', [
                'error' => $e->getMessage(),
                'file' => $this->filePath,
            ]);
            $job->update(['status' => 'failed']);
            throw $e;
        }

        return 0;
    }

    /**
     * Convert GrampsXML data to GEDCOM format
     * This is a basic conversion that maps GrampsXML structure to GEDCOM
     *
     * @param array $grampsData
     * @return string
     */
    private function convertGrampsToGedcom(array $grampsData): string
    {
        $gedcom = "0 HEAD\n";
        $gedcom .= "1 SOUR GrampsXML\n";
        $gedcom .= "1 GEDC\n";
        $gedcom .= "2 VERS 5.5.1\n";
        $gedcom .= "2 FORM LINEAGE-LINKED\n";
        $gedcom .= "1 CHAR UTF-8\n";

        // Convert people
        foreach ($grampsData['people'] ?? [] as $person) {
            $gedcom .= "0 @{$person['id']}@ INDI\n";
            
            // Add names
            if (!empty($person['names'])) {
                foreach ($person['names'] as $name) {
                    $first = $name['first'] ?? '';
                    $surname = $name['surname'] ?? '';
                    $gedcom .= "1 NAME {$first} /{$surname}/\n";
                    if ($first) {
                        $gedcom .= "2 GIVN {$first}\n";
                    }
                    if ($surname) {
                        $gedcom .= "2 SURN {$surname}\n";
                    }
                }
            }
            
            // Add gender
            if (isset($person['gender'])) {
                $gedcom .= "1 SEX {$person['gender']}\n";
            }
        }

        // Convert families
        foreach ($grampsData['families'] ?? [] as $family) {
            $gedcom .= "0 @{$family['id']}@ FAM\n";
            
            if (isset($family['father'])) {
                // Convert handle to ID format
                $fatherId = $this->handleToId($family['father'], $grampsData['people'] ?? []);
                if ($fatherId) {
                    $gedcom .= "1 HUSB @{$fatherId}@\n";
                }
            }
            
            if (isset($family['mother'])) {
                $motherId = $this->handleToId($family['mother'], $grampsData['people'] ?? []);
                if ($motherId) {
                    $gedcom .= "1 WIFE @{$motherId}@\n";
                }
            }

            // Add children
            if (!empty($family['children'])) {
                foreach ($family['children'] as $child) {
                    $childId = $this->handleToId($child['hlink'], $grampsData['people'] ?? []);
                    if ($childId) {
                        $gedcom .= "1 CHIL @{$childId}@\n";
                    }
                }
            }
        }

        $gedcom .= "0 TRLR\n";

        return $gedcom;
    }

    /**
     * Convert GrampsXML handle to GEDCOM ID
     *
     * @param string $handle
     * @param array $people
     * @return string|null
     */
    private function handleToId(string $handle, array $people): ?string
    {
        foreach ($people as $person) {
            if (($person['handle'] ?? '') === $handle) {
                return $person['id'] ?? null;
            }
        }
        return null;
    }
}
