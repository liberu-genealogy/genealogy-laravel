<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ImportJob;
use FamilyTree365\LaravelGedcom\Utils\GedcomGenerator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GedcomService
{
    public function generateGedcomString(int $personId, int $familyId = 0, int $upNest = 3, int $downNest = 3): string
    {
        $generator = new GedcomGenerator($personId, $familyId, $upNest, $downNest);

        return $generator->getGedcomPerson() . $generator->getGedcomFamily();
    }

    /**
     * Generate a complete GEDCOM document (the whole tree) as a single
     * HEAD..TRLR string, suitable for writing to a file for download.
     *
     * The generator queries the database itself (person id 0 = every person
     * and family), so no models are passed in.
     */
    public function generateGedcomContent(): string
    {
        // ponytail: reuse getGedcomPerson() — it already emits one full HEAD..TRLR
        // document for the whole tree. generateGedcomString() concatenates a second
        // getGedcomFamily() pass, which re-emits a duplicate HEAD (invalid in a file).
        $gedcom = (new GedcomGenerator(0, 0, 0, 0))->getGedcomPerson();

        // The vendor generator prepends a "Format: gedcom5.5" marker line; a valid
        // GEDCOM file must begin at the 0 HEAD record, so drop anything before it.
        $head = strpos($gedcom, '0 HEAD');

        return $head === false ? $gedcom : substr($gedcom, $head);
    }

    public function queueImport(UploadedFile $file, ?int $treeId = null): ImportJob
    {
        $path = $file->store('gedcom-form-imports', 'private');

        $job = ImportJob::create([
            'user_id'  => Auth::id(),
            'status'   => 'queue',
            'progress' => 0,
            'slug'     => Str::uuid()->toString(),
        ]);

        \App\Jobs\ImportGedcom::dispatch($path, $job->slug);

        return $job;
    }
}
