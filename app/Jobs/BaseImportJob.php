<?php

namespace App\Jobs;

use Artisan;
use Throwable;
use Exception;
use App\Models\ImportJob;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

abstract class BaseImportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 0;
    public int $tries = 1;

    protected User $user;
    protected string $filePath;
    protected ?string $slug;

    public function __construct(User $user, string $filePath, ?string $slug = null)
    {
        $this->user = $user;
        $this->filePath = $filePath;
        $this->slug = $slug;
    }

    public function handle(): int
    {
        throw_unless(File::isFile($this->filePath), Exception::class, "{$this->filePath} does not exist.");

        $slug = $this->slug ?? \Illuminate\Support\Str::uuid();

        $job = ImportJob::create([
            'user_id' => $this->user->getKey(),
            'status'  => 'queue',
            'slug'    => $slug,
        ]);

        // let subclass perform the actual import
        $this->performImport($job, $slug);

        $job->update(['status' => 'complete']);

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

    /**
     * Implement import-specific logic in subclasses.
     *
     * @param ImportJob $job
     * @param string $slug
     */
    abstract protected function performImport(ImportJob $job, string $slug): void;
}
