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
        Log::info("Exporting " . $people->count() . " people and " . $families->count() . " families.");

        // Generating GEDCOM content
        $writer = new GedcomGenerator($people, $families);
        $content = $writer->generate();

        // Storing the GEDCOM file
        $manager->storage()->put($this->file, $content);
        Log::info("GEDCOM file generated and stored: " . $this->file);
/**
 * Handles the process of exporting GEDCOM data.
 * Establishes a tenant connection, fetches related data (people and families), generates GEDCOM content, and stores the file.
 * Logs the process and handles potential errors.
 *
 * @return void
 */
        $up_nest = 3;
        $down_nest = 3;

        $writer = new GedcomGenerator($p_id, $f_id, $up_nest, $down_nest);
        $content = $writer->getGedcomPerson();

//        Log::info("content from getGedcomPerson function => \n $content");
        // var_dump(\Storage::disk('public')->path($this->file), "job");
        $manager->storage()->put($this->file, $content);
 //       $filePath = 'public/' . $this->file;
//        $filePath = $manager->storage()->path($filePath);
        //	chmod_r('/home/genealogia/domains/api.genealogia.co.uk/genealogy/storage/tenants/');
        // Setting permissions for the GEDCOM file
        exec('chmod 0644 '. $manager->storage()->path($this->file));
        Log::info("Permissions set for GEDCOM file.");
        
        // Handling errors and exceptions
        try {
            // Export logic here
        } catch (\Exception $e) {
            Log::error("Error during GEDCOM export: " . $e->getMessage());
        }
        //exec ("find /home/genealogia/ap -type d -exec chmod 0750 {} +");
        //exec ("find /path/to/folder -type f -exec chmod 0644 {} +");
        // var_dump($path,'path');
    }
}
