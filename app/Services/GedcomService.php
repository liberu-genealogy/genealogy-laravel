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
