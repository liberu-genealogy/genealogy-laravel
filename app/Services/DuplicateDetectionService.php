<?php

namespace App\Services;

use App\Models\Person;
use App\Models\DuplicateMatch;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class DuplicateDetectionService
{
    /**
     * Scan persons and return collection of suggested duplicate pairs with score.
     * This will persist DuplicateMatch records.
     *
     * @param float $threshold minimal confidence to persist (0.0 - 1.0)
     * @param int $limitPerPerson maximum candidates per person
     * @return Collection DuplicateMatch[]
     */
    public function scan(float $threshold = 0.7, int $limitPerPerson = 10): Collection
    {
        $created = collect();

        $persons = Person::select(['id', 'givn', 'surn', 'name', 'email', 'phone', 'birthday'])->get();

        // Index persons by email and phone for cheap exact matches
        $emailIndex = $persons->filter(fn($p) => $p->email)->groupBy(fn($p) => Str::lower($p->email));
        $phoneIndex = $persons->filter(fn($p) => $p->phone)->groupBy(fn($p) => preg_replace('/\D+/', '', $p->phone));

        foreach ($persons as $primary) {
            $candidates = collect();

            // exact email matches
            if ($primary->email) {
                $email = Str::lower($primary->email);
                foreach ($emailIndex->get($email, []) as $p) {
                    if ($p->id === $primary->id) continue;
                    $score = 0.95;
                    $candidates->push([$p, $score, ['reason' => 'email_exact']]);
                }
            }

            // exact phone matches
            if ($primary->phone) {
                $phone = preg_replace('/\D+/', '', $primary->phone);
                foreach ($phoneIndex->get($phone, []) as $p) {
                    if ($p->id === $primary->id) continue;
                    $score = 0.93;
                    $candidates->push([$p, $score, ['reason' => 'phone_exact']]);
                }
            }

            // naive pass comparing birthdays and name similarity (O(n^2) but acceptable for small/medium datasets)
            foreach ($persons as $other) {
                if ($other->id === $primary->id) continue;

                // skip if already added by exact match
                if ($candidates->first(fn($t) => $t[0]->id === $other->id)) continue;

                $score = $this->computeScore($primary, $other);
                if ($score >= $threshold) {
                    $candidates->push([$other, $score, ['reason' => 'fuzzy_name']]);
                }
            }

            // keep top N per person
            $top = $candidates->sortByDesc(fn($t) => $t[1])->take($limitPerPerson);
            foreach ($top as [$other, $score, $meta]) {
                // ensure unique ordered pair (smaller id as primary to avoid duplicates)
                $primaryId = $primary->id;
                $duplicateId = $other->id;

                // do not create self-pairs
                if ($primaryId === $duplicateId) continue;

                // choose canonical ordering to avoid creating both (A,B) and (B,A)
                if ($primaryId > $duplicateId) {
                    $primaryKey = $duplicateId;
                    $duplicateKey = $primaryId;
                } else {
                    $primaryKey = $primaryId;
                    $duplicateKey = $duplicateId;
                }

                // create or update
                $record = DuplicateMatch::firstOrNew([
                    'primary_person_id' => $primaryKey,
                    'duplicate_person_id' => $duplicateKey,
                ]);

                // If new or confidence improved, store
                $existing = $record->exists ? (float) $record->confidence_score : 0.0;
                if (!$record->exists || $score > $existing) {
                    $record->confidence_score = $score;
                    $record->match_data = array_merge($record->match_data ?? [], [
                        'last_scanned_at' => now()->toDateTimeString(),
                        'reasons' => $meta,
                        'primary' => [
                            'id' => $primary->id,
                            'name' => $primary->name ?? ($primary->givn . ' ' . $primary->surn),
                            'email' => $primary->email,
                            'phone' => $primary->phone,
                            'birthday' => $primary->birthday,
                        ],
                        'candidate' => [
                            'id' => $other->id,
                            'name' => $other->name ?? ($other->givn . ' ' . $other->surn),
                            'email' => $other->email,
                            'phone' => $other->phone,
                            'birthday' => $other->birthday,
                        ],
                    ]);
                    $record->status = $record->status ?? 'pending';
                    $record->save();
                }

                $created->push($record);
            }
        }

        return $created;
    }

    /**
     * Compute a similarity score between two person records (0..1).
     */
    protected function computeScore(Person $a, Person $b): float
    {
        $score = 0.0;

        // email exact (very strong)
        if ($a->email && $b->email && Str::lower($a->email) === Str::lower($b->email)) {
            $score = max($score, 0.95);
        }

        // phone exact
        $pa = $a->phone ? preg_replace('/\D+/', '', $a->phone) : null;
        $pb = $b->phone ? preg_replace('/\D+/', '', $b->phone) : null;
        if ($pa && $pb && $pa === $pb) {
            $score = max($score, 0.93);
        }

        // birthday match
        if ($a->birthday && $b->birthday && $a->birthday == $b->birthday) {
            $score += 0.25;
        }

        // name similarity using normalized levenshtein and soundex
        $nameA = $this->normalizeName($a->name ?? ($a->givn . ' ' . $a->surn));
        $nameB = $this->normalizeName($b->name ?? ($b->givn . ' ' . $b->surn));

        if ($nameA && $nameB) {
            $lev = levenshtein($nameA, $nameB);
            $maxlen = max(strlen($nameA), strlen($nameB), 1);
            $nameSim = 1 - ($lev / $maxlen); // 0..1
            $score += $nameSim * 0.5; // name contributes up to 0.5
            // soundex boost
            if (soundex($nameA) === soundex($nameB)) {
                $score += 0.1;
            }
        }

        // clamp 0..1
        return min(1.0, (float) $score);
    }

    protected function normalizeName(?string $s): string
    {
        if (!$s) return '';
        $s = Str::lower($s);
        $s = preg_replace('/[^a-z0-9 ]+/', '', $s);
        $s = preg_replace('/\s+/', ' ', trim($s));
        return $s;
    }
}
