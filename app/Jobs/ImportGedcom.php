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

    public function __construct(protected User $user, protected string $filePath, protected ?string $slug = null)
    {
    }

    public function handle(): int
    {
        Log::info('ImportGedcom job started', [
            'file_path' => $this->filePath,
            'user_id'   => $this->user->getKey(),
        ]);

        throw_unless(File::isFile($this->filePath), Exception::class, "{$this->filePath} does not exist.");

        $slug = $this->slug ?? Str::uuid();

        $job = ImportJob::create([
            'user_id' => $this->user->getKey(),
            'status'  => 'queue',
            'slug'    => $slug,
        ]);

        try {
            $parser = new GedcomParser();
            $team_id = $this->user->currentTeam?->id;
            $parser->parse(config('database.default'), $this->filePath, $slug, true, $team_id);
        } catch (Throwable $e) {
            $job->update(['status' => 'failed']);
            Log::error('ImportGedcom parser failed', [
                'file_path' => $this->filePath,
                'user_id'   => $this->user->getKey(),
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        $job->update(['status' => 'complete']);

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
