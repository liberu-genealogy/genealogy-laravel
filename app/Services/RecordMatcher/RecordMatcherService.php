<?php

namespace App\Services\RecordMatcher;

use App\Models\AIMatchModel;
use App\Models\AISuggestedMatch;
use Illuminate\Support\Str;

class RecordMatcherService
{
    protected array $weights;

    public function __construct()
    {
        $this->loadWeights();
    }

    protected function loadWeights(): void
    {
        $model = AIMatchModel::orderBy('id', 'desc')->first();
        $this->weights = $model?->weights ?? [
            'first_name' => 1.0,
            'last_name' => 1.0,
            'birth_year' => 0.8,
            'birth_place' => 0.6,
            'parents' => 0.9,
        ];
    }

    /**
     * Predict matches for a local person given a set of external candidates.
     *
     * @param \App\Models\Person|int $localPerson
     * @param array $candidates
     * @return array array of ['candidate' => array, 'score' => float]
     */
    public function scoreCandidates($localPerson, array $candidates): array
    {
        // Normalize local person record
        $person = is_int($localPerson) ? \App\Models\Person::find($localPerson) : $localPerson;
        if (! $person) {
            return [];
        }

        $results = [];
        foreach ($candidates as $cand) {
            $score = $this->scoreSingle($person, $cand);
            $results[] = ['candidate' => $cand, 'score' => $score];
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return $results;
    }

    protected function scoreSingle($person, array $cand): float
    {
        $totalWeight = array_sum(array_values($this->weights));
        $score = 0.0;

        // first name similarity
        if (!empty($this->weights['first_name'])) {
            $firstPerson = Str::lower($person->first_name ?? '');
            $firstCand = Str::lower($cand['first_name'] ?? '');
            $sim = $this->stringSimilarity($firstPerson, $firstCand);
            $score += $this->weights['first_name'] * $sim;
        }

        // last name
        if (!empty($this->weights['last_name'])) {
            $lastPerson = Str::lower($person->last_name ?? '');
            $lastCand = Str::lower($cand['last_name'] ?? '');
            $sim = $this->stringSimilarity($lastPerson, $lastCand);
            $score += $this->weights['last_name'] * $sim;
        }

        // birth year exact/near
        if (!empty($this->weights['birth_year'])) {
            $py = $person->birth_year ? (int)$person->birth_year : null;
            $cy = isset($cand['birth_year']) ? (int)$cand['birth_year'] : null;
            $sim = 0.0;
            if ($py && $cy) {
                $diff = abs($py - $cy);
                if ($diff === 0) $sim = 1.0;
                elseif ($diff <= 2) $sim = 0.7;
                elseif ($diff <= 5) $sim = 0.4;
            }
            $score += $this->weights['birth_year'] * $sim;
        }

        // birth place fuzzy match
        if (!empty($this->weights['birth_place'])) {
            $pp = Str::lower($person->birth_place ?? '');
            $cp = Str::lower($cand['birth_place'] ?? '');
            $sim = $this->stringSimilarity($pp, $cp);
            $score += $this->weights['birth_place'] * $sim;
        }

        // parents - simplistic check if last names or parent names match
        if (!empty($this->weights['parents'])) {
            $sim = 0.0;
            // example: check if candidate last name equals person last_name or matches parent last_name fields
            if (!empty($cand['last_name']) && !empty($person->last_name)) {
                $sim = $this->stringSimilarity(Str::lower($person->last_name), Str::lower($cand['last_name']));
            }
            $score += $this->weights['parents'] * $sim;
        }

        if ($totalWeight <= 0) {
            return 0.0;
        }

        // normalize to 0..1
        return min(1.0, round($score / $totalWeight, 4));
    }

    protected function stringSimilarity(string $a, string $b): float
    {
        if ($a === '' || $b === '') {
            return 0.0;
        }
        // use PHP similar_text for a simple score, normalize by max length
        similar_text($a, $b, $perc);
        return $perc / 100.0;
    }

    /**
     * Persist suggestions into DB (upsert).
     *
     * @param int $localPersonId
     * @param string $provider
     * @param array $candidate
     * @param float $confidence
     * @return \App\Models\AISuggestedMatch
     */
    public function persistSuggestion(int $localPersonId, string $provider, array $candidate, float $confidence): AISuggestedMatch
    {
        return AISuggestedMatch::updateOrCreate(
            [
                'provider' => $provider,
                'external_record_id' => $candidate['id'] ?? ($candidate['external_id'] ?? null),
                'local_person_id' => $localPersonId,
            ],
            [
                'candidate_data' => $candidate,
                'confidence' => $confidence,
                'status' => 'pending',
            ]
        );
    }

    /**
     * Update model weights based on feedback (simple incremental algorithm).
     *
     * @param \App\Models\AISuggestedMatch $suggestedMatch
     * @param string $action 'confirm'|'reject'
     * @return void
     */
    public function learnFromFeedback($suggestedMatch, string $action): void
    {
        // Basic approach:
        // - If confirmed, slightly increase weights of fields that matched strongly for this candidate.
        // - If rejected, slightly decrease weights of those fields.
        $delta = $action === 'confirm' ? 0.02 : -0.03;

        $candidate = $suggestedMatch->candidate_data;
        $local = \App\Models\Person::find($suggestedMatch->local_person_id);
        if (!$local || !$candidate) {
            return;
        }

        // For each tracked field compute similarity; adjust weight by delta * similarity
        $fields = array_keys($this->weights);
        foreach ($fields as $field) {
            $sim = 0.0;
            if (in_array($field, ['first_name', 'last_name', 'birth_place', 'parents'])) {
                $lv = strtolower((string)($local->{$field} ?? ''));
                $cv = strtolower((string)($candidate[$field] ?? ''));
                $sim = $this->stringSimilarity($lv, $cv);
            } elseif ($field === 'birth_year') {
                $py = $local->birth_year ? (int)$local->birth_year : null;
                $cy = isset($candidate['birth_year']) ? (int)$candidate['birth_year'] : null;
                if ($py && $cy) {
                    $diff = abs($py - $cy);
                    $sim = $diff === 0 ? 1.0 : ($diff <= 2 ? 0.7 : ($diff <= 5 ? 0.4 : 0.0));
                }
            }

            $this->weights[$field] = max(0.0, ($this->weights[$field] ?? 0.0) + ($delta * $sim));
        }

        // Persist updated weights as a new model snapshot
        AIMatchModel::create([
            'name' => 'snapshot_' . now()->format('YmdHis'),
            'weights' => $this->weights,
        ]);
    }
}
