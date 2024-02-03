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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected $file, protected User $user)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $p_id = $this->user->person_id; // person_id
        $f_id = 0;                      // family_id

        $tenant = Manager::fromModel($this->user->company(), $this->user);
        $tenant->connect();

        $family = Family::where('husband_id', $this->user->id)
                ->orWhere('wife_id', $this->user->id)
                ->first();
        $manager = Manager::fromModel($this->user->company(), $this->user);
        if ($family == null) {
            $person = Person::where('child_in_family_id', $this->user->id)->first();

            $f_id = $person != null ? $person->child_in_family_id : 0;
        } else {
            $f_id = $family->id;
        }

        Log::info("Family Id => $f_id \n Person Id => $p_id");

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
        exec('chmod -R 0777 '.storage_path('/tenants/'));
        //exec ("find /home/genealogia/ap -type d -exec chmod 0750 {} +");
        //exec ("find /path/to/folder -type f -exec chmod 0644 {} +");
        // var_dump($path,'path');
    }
}
