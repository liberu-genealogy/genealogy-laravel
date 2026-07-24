<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\ImportGedcom;
use App\Models\Family;
use App\Models\ImportJob;
use App\Models\Person;
use App\Models\Source;
use Carbon\Carbon;
use FamilyTree365\LaravelGedcom\Utils\GedcomGenerator;
use Gedcom\Gedcom;
use Gedcom\GedcomX\Generator as GedcomXGenerator;
use Gedcom\Parser as GedcomObjectParser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GedcomService
{
    public function generateGedcomString(int $personId, int $familyId = 0, int $upNest = 3, int $downNest = 3): string
    {
        $generator = new GedcomGenerator($personId, $familyId, $upNest, $downNest);

        return $generator->getGedcomPerson().$generator->getGedcomFamily();
    }

    /**
     * Generate a complete GEDCOM 5.5.1 document (the whole tree, team-scoped by
     * the Person/Family global scope) as a single HEAD..TRLR string, suitable for
     * writing to a file for download.
     *
     * The vendor GedcomGenerator is NOT used here: its getGedcomPerson() runs a
     * person-centric ancestor walk seeded with p_id=0 (addUpData), which emits a
     * single INDI plus a family — not the whole tree — and keys INDI on `gid`
     * while FAM points at DB ids, so pointers dangle. This builds the document
     * directly from the tables with one consistent xref scheme (@I{id}@ / @F{id}@),
     * a proper 5.5.1 HEAD, decomposed names and normalised SEX, so an exported
     * tree re-imports with stable counts and resolvable links.
     *
     * ponytail: emits INDI (name/sex/birth) + FAM (husb/wife/chil). Sources,
     * media, notes and citations are deliberately out of scope here (map →
     * "Not yet specified") — add them when a consumer needs them.
     */
    public function generateGedcomContent(): string
    {
        // GEDCOM 5.5.1 HEAD: GEDC.VERS 5.5.1 + FORM, explicit CHAR.
        return $this->assembleDocument([
            '0 HEAD',
            '1 SOUR '.config('app.name', 'Liberu Genealogy'),
            '1 GEDC',
            '2 VERS 5.5.1',
            '2 FORM LINEAGE-LINKED',
            '1 CHAR UTF-8',
        ]);
    }

    /**
     * Generate the whole tree as a GEDCOM 7.0 document.
     *
     * The record body (INDI/FAM/NAME/SEX/BIRT/SOUR) this app emits is already
     * valid 7.0 — 3-letter uppercase dates, no CONC, no extension tags — so the
     * only 5.5.1 -> 7.0 deltas are in the HEAD: GEDC.VERS is 7.0, GEDC.FORM is
     * gone, and CHAR is dropped (UTF-8 is mandatory and implicit). No SCHMA is
     * needed because no underscore/extension tags are written. See ticket 01's
     * delta checklist.
     */
    public function generateGedcom7Content(): string
    {
        return $this->assembleDocument([
            '0 HEAD',
            '1 GEDC',
            '2 VERS 7.0',
            '1 SOUR '.config('app.name', 'Liberu Genealogy'),
        ]);
    }

    /**
     * Append the shared record body (sources, people, families, trailer) to a
     * version-specific HEAD and return the whole document. Everything below the
     * HEAD is identical between the 5.5.1 and 7.0 outputs.
     *
     * @param  list<string>  $headLines
     */
    private function assembleDocument(array $headLines): string
    {
        $lines = $headLines;

        // Sources first (HEAD, SOUR, INDI, FAM, TRLR order). Team-scoped by the
        // BelongsToTenant global scope active inside the export job's team context,
        // so one team's export never embeds another team's source records.
        foreach (Source::query()->orderBy('id')->cursor() as $source) {
            $lines[] = '0 @S'.$source->id.'@ SOUR';
            if (! empty($source->titl)) {
                $lines[] = '1 TITL '.$source->titl;
            }
            if (! empty($source->sour)) {
                $lines[] = '1 ABBR '.$source->sour;
            }
        }

        foreach (Person::query()->orderBy('id')->cursor() as $person) {
            [$given, $surname, $display] = $this->nameParts($person);

            $lines[] = '0 @I'.$person->id.'@ INDI';
            $lines[] = '1 NAME '.$display;
            if ($given !== '') {
                $lines[] = '2 GIVN '.$given;
            }
            if ($surname !== '') {
                $lines[] = '2 SURN '.$surname;
            }

            $sex = $this->normaliseSex((string) ($person->sex ?? ''));
            if ($sex !== '') {
                $lines[] = '1 SEX '.$sex;
            }

            $birth = $this->birthDate($person);
            if ($birth !== '') {
                $lines[] = '1 BIRT';
                $lines[] = '2 DATE '.$birth;
            }
        }

        foreach (Family::query()->orderBy('id')->cursor() as $family) {
            $lines[] = '0 @F'.$family->id.'@ FAM';
            if ($family->husband_id) {
                $lines[] = '1 HUSB @I'.$family->husband_id.'@';
            }
            if ($family->wife_id) {
                $lines[] = '1 WIFE @I'.$family->wife_id.'@';
            }
            foreach (Person::query()->where('child_in_family_id', $family->id)->orderBy('id')->pluck('id') as $childId) {
                $lines[] = '1 CHIL @I'.$childId.'@';
            }
        }

        $lines[] = '0 TRLR';

        return implode("\n", $lines)."\n";
    }

    /**
     * Generate the whole tree as GEDCOM X JSON (FamilySearch flavour), matching
     * the format the app already imports via `gedcomx:import`.
     *
     * Bridges through the clean 5.5.1 document (ticket 03): serialise the tree,
     * parse it into a php-gedcom object model, then run the vendor
     * `Gedcom\GedcomX\Generator` — which already maps INDI/FAM/names/sex/events to
     * GEDCOM X persons + relationships. The vendor generator emits off-spec local
     * resource refs ("#persons/pN"); GEDCOM X wants a bare id fragment ("#pN"),
     * so fix those on the way out.
     *
     * ponytail: reuses the 5.5.1 serialiser + the vendor GEDCOM X mapper rather
     * than re-deriving persons/relationships from the DB. sourceDescriptions is a
     * vendor stub (stays empty) — source fidelity is follow-up (map → Not yet
     * specified).
     */
    public function generateGedcomXContent(): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'gedx').'.ged';
        file_put_contents($tmp, $this->generateGedcomContent());

        try {
            $gedcom = (new GedcomObjectParser)->parse($tmp) ?? new Gedcom;
        } finally {
            @unlink($tmp);
        }

        $json = (new GedcomXGenerator($gedcom))->generate();

        // "#persons/pN" -> "#pN": the resource fragment must be the person's id.
        // The vendor json_encode escapes the slash ("#persons\/pN"); handle both.
        return str_replace(['"#persons\\/', '"#persons/'], '"#', $json);
    }

    /**
     * Split a person's name into [given, surname, gedcom-display]. Prefers the
     * `name` column when it carries the GEDCOM slash convention ("Given /Surname/"),
     * otherwise builds from the givn/surn columns.
     *
     * @return array{0: string, 1: string, 2: string}
     */
    private function nameParts(Person $person): array
    {
        $raw = trim((string) ($person->name ?? ''));

        if ($raw !== '' && str_contains($raw, '/')) {
            preg_match('#^(.*?)\s*/([^/]*)/\s*(.*)$#', $raw, $m);

            return [trim($m[1] ?? ''), trim($m[2] ?? ''), $raw];
        }

        $given = trim((string) ($person->givn ?? ''));
        $surname = trim((string) ($person->surn ?? ''));

        if ($given === '' && $surname === '' && $raw !== '') {
            $given = $raw;
        }

        $display = trim($given.' /'.$surname.'/');

        return [$given, $surname, $display];
    }

    /**
     * GEDCOM 5.5.1 SEX is one of M / F / U. Map X → U, anything unknown → ''.
     */
    private function normaliseSex(string $sex): string
    {
        return match (strtoupper(trim($sex))) {
            'M' => 'M',
            'F' => 'F',
            'U', 'X' => 'U',
            default => '',
        };
    }

    /**
     * Best-effort GEDCOM birth date from the birthday / birth_year columns.
     */
    private function birthDate(Person $person): string
    {
        if (! empty($person->birthday)) {
            try {
                return strtoupper(Carbon::parse((string) $person->birthday)->format('j M Y'));
            } catch (\Throwable) {
                // fall through to the year
            }
        }

        return ! empty($person->birth_year) ? (string) $person->birth_year : '';
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
                $errors[] = 'Line '.($i + 1).' is not a valid GEDCOM line: '.Str::limit(trim($line), 40);
                break;
            }
        }

        return $errors;
    }

    public function queueImport(UploadedFile $file, ?int $treeId = null): ImportJob
    {
        $path = $file->store('gedcom-form-imports', 'private');

        $job = ImportJob::create([
            'user_id' => Auth::id(),
            'status' => 'queue',
            'progress' => 0,
            'slug' => Str::uuid()->toString(),
        ]);

        // ImportGedcom::__construct is (User $user, string $filePath, ?string $slug).
        // The old dispatch passed ($path, $slug) — the relative path landed in the
        // User slot (TypeError, every queued import died) and nothing was a User.
        // Pass the authenticated user, the ABSOLUTE file path (handle() reads it
        // with File::isFile/File::get, which need a real path, not the relative
        // one $file->store() returns), and the slug.
        ImportGedcom::dispatch(Auth::user(), Storage::disk('private')->path($path), $job->slug);

        return $job;
    }
}
