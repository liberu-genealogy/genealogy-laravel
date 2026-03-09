<?php

namespace App\Jobs;

use Artisan;
use Exception;
use Throwable;
use App\Models\ImportJob;
use App\Models\User;
use FamilyTree365\LaravelGedcom\Utils\GedcomParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportGedcom implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 0;
    public int $tries = 1;

    public function __construct(protected User $user, protected string $filePath, public ?string $slug = null)
    {
    }

    public function handle(): int
    {
        Log::info('ImportGedcom job started', [
            'file_path' => $this->filePath,
            'user_id'   => $this->user->getKey(),
        ]);

        // Find or create the ImportJob record
        $slug = $this->slug ?? (string) Str::uuid();
        $importJob = ImportJob::firstOrCreate(
            ['slug' => $slug],
            [
                'user_id' => $this->user->getKey(),
                'status'  => 'queue',
                'progress' => 0,
            ],
        );

        $importJob->update(['status' => 'processing', 'progress' => 10]);

        try {
            throw_unless(File::isFile($this->filePath), Exception::class, "{$this->filePath} does not exist.");

            $importJob->update(['progress' => 25]);

            $parser = new GedcomParser();
            $team_id = $this->user->currentTeam?->id;

            $importJob->update(['status' => 'processing', 'progress' => 50]);

            $parser->parse(config('database.default'), $this->filePath, $slug, true, $team_id);

            $importJob->update(['progress' => 90]);
        } catch (Throwable $e) {
            $importJob->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error('ImportGedcom parser failed', [
                'file_path' => $this->filePath,
                'user_id'   => $this->user->getKey(),
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        $importJob->update(['status' => 'complete', 'progress' => 100]);

        Log::info('ImportGedcom job completed', [
            'file_path' => $this->filePath,
            'user_id'   => $this->user->getKey(),
            'slug'      => $slug,
        ]);

        // Clear application caches so new records are visible immediately
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
        } catch (Throwable $e) {
            // swallow cache clear errors
        }

        return 0;
    }
}
