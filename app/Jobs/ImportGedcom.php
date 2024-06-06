<?php

namespace App\Jobs;

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

    protected string $conn;

    public function __construct(protected User $user, protected string $filePath, protected string $conn, protected ?string $slug = null)
    {
        $this->conn = $conn;
    }

    public function handle(): int
    {
        throw_unless(File::isFile($this->filePath), \Exception::class, "{$this->filePath} does not exist.");

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

        $parser->parse($this->conn, $this->filePath, $slug, false);
        // with(new GedcomParser())->parse($tenant->connectionName(), $this->filePath, $slug, true);

        File::delete($this->filePath);

        $job->update(['status' => 'complete']);

        // $tenant->disconnect();

        return 0;
    }
}
