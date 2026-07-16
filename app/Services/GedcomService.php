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

    /**
     * Structural sanity check for a GEDCOM document. Returns a list of
     * human-readable errors; an empty array means the content clears the
     * boundary checks.
     *
     * ponytail: envelope-only, NOT a full GEDCOM 5.5.1 validator. It confirms
     * the file is non-empty, opens at "0 HEAD", carries a "0 TRLR" terminator,
     * and that lines carry a numeric level + tag — enough to reject empty
     * uploads, wrong file types and truncated files before the (heavy) vendor
     * parser runs. Per-line linting stops at the first offending line (the
     * boundary), not a full report. Upgrade to a spec validator only if malformed
     * imports slip past the parser.
     *
     * @return list<string>
     */
    public function validateGedcom(string $content): array
    {
        // Strip a leading UTF-8 BOM and surrounding whitespace before inspecting.
        $trimmed = ltrim($content, "\xEF\xBB\xBF \t\r\n");

        if ($trimmed === '') {
            return ['GEDCOM is empty.'];
        }

        $lines = preg_split('/\r\n|\r|\n/', $trimmed) ?: [];
        $errors = [];

        if (! str_starts_with($lines[0], '0 HEAD')) {
            $errors[] = 'GEDCOM must begin with a "0 HEAD" record.';
        }

        $hasTrailer = false;
        foreach ($lines as $line) {
            if (rtrim($line) === '0 TRLR') {
                $hasTrailer = true;
                break;
            }
        }
        if (! $hasTrailer) {
            $errors[] = 'GEDCOM must contain a "0 TRLR" terminator.';
        }

        // Every non-blank line must be "<level> <tag-or-@xref@> ...".
        foreach ($lines as $i => $line) {
            if (trim($line) === '') {
                continue;
            }
            if (! preg_match('/^\d+\s+\S/', $line)) {
                $errors[] = 'Line ' . ($i + 1) . ' is not a valid GEDCOM line: ' . Str::limit(trim($line), 40);
                break;
            }
        }

        return $errors;
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
