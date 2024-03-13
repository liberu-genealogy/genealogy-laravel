<?php

namespace App\Jobs;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use App\Tenant\Manager;
use FamilyTree365\LaravelGedcom\Utils\GedcomGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ExportGedCom implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected string $file, protected User $user)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Establishing tenant connection
        $tenant = Manager::fromModel($this->user->company(), $this->user);
        $tenant->connect();

        // Fetching all people and families related to the user
        $people = Person::all();
        $families = Family::all();

        // Logging the count of people and families to be exported
        Log::info('Exporting '.$people->count().' people and '.$families->count().' families.');

        // Generating GEDCOM content with Ahnentafel numbers
        $this->generateAhnentafelNumbers($people, $families);
        $writer = new GedcomGenerator($people, $families);
        $content = $writer->generateWithAhnentafel();

        // Storing the GEDCOM file with Ahnentafel numbers
        $manager->storage()->put($this->file, $content);
        Log::info('GEDCOM file generated and stored with Ahnentafel numbers: '.$this->file);

        // Generating GEDCOM content with Ahnentafel numbers
        $this->generateAhnentafelNumbers($people, $families);
        $writer = new GedcomGenerator($people, $families);
        $content = $writer->generateWithAhnentafel();

        // Storing the GEDCOM file with Ahnentafel numbers
        $manager->storage()->put($this->file, $content);
        Log::info('GEDCOM file generated and stored with Ahnentafel numbers: '.$this->file);

        // Setting permissions for the GEDCOM file
        exec('chmod 0644 '.$manager->storage()->path($this->file));
        Log::info('Permissions set for GEDCOM file.');

        // Handling errors and exceptions
        try {
            // Export logic here
        } catch (\Exception $e) {
            Log::error('Error during GEDCOM export: '.$e->getMessage());
        }
        //exec ("find /home/genealogia/ap -type d -exec chmod 0750 {} +");
        //exec ("find /path/to/folder -type f -exec chmod 0644 {} +");
        // var_dump($path,'path');
    }
}
