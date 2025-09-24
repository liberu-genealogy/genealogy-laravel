<?php

namespace App\Jobs;

use Exception;
use App\Models\ImportJob;
use App\Models\User;
use App\Tenant\Manager;
use FamilyTree365\LaravelGedcom\Utils\GedcomParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
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
        throw_unless(File::isFile($this->filePath), Exception::class, "{$this->filePath} does not exist.");

        // $tenant = Manager::fromModel($this->user->company(), $this->user);
        // if (!$tenant->databaseExists()) {
        //     //$tenant->dropDatabase();
        //     $tenant->createDatabase();
        //     $tenant->connect();
        //     $tenant->migrateDatabase();
        // }
        // $tenant->connect();
        $slug = $this->slug ?? Str::uuid();

        $job = ImportJob::create([
            'user_id' => $this->user->getKey(),
            'status'  => 'queue',
            'slug'    => $slug,
        ]);
        $parser = new GedcomParser();
        $team_id = $this->user->currentTeam->id;
        $parser->parse($job->getConnectionName(), $this->filePath, $slug, false, $team_id);
        // with(new GedcomParser())->parse($tenant->connectionName(), $this->filePath, $slug, true);

        // File::delete($this->filePath);

        $job->update(['status' => 'complete']);

        // $tenant->disconnect();

        return 0;
    }
}
