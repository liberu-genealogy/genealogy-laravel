<?php

namespace App\Services;

use Exception;
use DateTime;
use App\Models\Person;
use App\Models\User;
use App\Models\DuplicateCheck;
use Illuminate\Support\Collection;

class DuplicateCheckerService
{
    /**
     * Run duplicate check for user's family tree
     */
    public function runDuplicateCheck(User $user): DuplicateCheck
    {
        // Create duplicate check record
        $duplicateCheck = DuplicateCheck::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'results' => [],
            'duplicates_found' => 0,
        ]);

        try {
            // Get all people for this user's team
            $people = Person::whereHas('user', function ($query) use ($user) {
                $query->where('current_team_id', $user->current_team_id);
            })->get();

            $duplicates = $this->findDuplicates($people);

            // Update duplicate check with results
            $duplicateCheck->update([
                'status' => 'completed',
                'results' => $duplicates,
                'duplicates_found' => count($duplicates),
                'completed_at' => now(),
            ]);

        } catch (Exception $e) {
            $duplicateCheck->update([
                'status' => 'failed',
                'results' => ['error' => $e->getMessage()],
            ]);
        }

        return $duplicateCheck;
    }

    /**
     * Find potential duplicates in the collection of people
     */
    private function findDuplicates(Collection $people): array
    {
        $duplicates = [];
        $processed = [];

        foreach ($people as $person) {
            if (in_array($person->id, $processed)) {
                continue;
            }

            $potentialDuplicates = $this->findPotentialDuplicates($person, $people);
            
            if (!empty($potentialDuplicates)) {
                $duplicates[] = [
                    'primary_person' => [
                        'id' => $person->id,
                        'name' => $person->fullname(),
                        'birth_date' => $person->birthday?->format('Y-m-d'),
                        'death_date' => $person->deathday?->format('Y-m-d'),
                    ],
                    'potential_duplicates' => $potentialDuplicates,
                    'confidence_scores' => $this->calculateConfidenceScores($person, $potentialDuplicates),
                ];

                // Mark all as processed
                $processed[] = $person->id;
                foreach ($potentialDuplicates as $duplicate) {
                    $processed[] = $duplicate['id'];
                }
            }
        }

        return $duplicates;
    }

    /**
     * Find potential duplicates for a specific person
     */
    private function findPotentialDuplicates(Person $person, Collection $people): array
    {
        $potentialDuplicates = [];

        foreach ($people as $otherPerson) {
            if ($person->id === $otherPerson->id) {
                continue;
            }

            $similarity = $this->calculateSimilarity($person, $otherPerson);
            
            if ($similarity >= 0.7) { // 70% similarity threshold
                $potentialDuplicates[] = [
                    'id' => $otherPerson->id,
                    'name' => $otherPerson->fullname(),
                    'birth_date' => $otherPerson->birthday?->format('Y-m-d'),
                    'death_date' => $otherPerson->deathday?->format('Y-m-d'),
                    'similarity_score' => round($similarity * 100, 2),
                ];
            }
        }

        return $potentialDuplicates;
    }

    /**
     * Calculate similarity between two people
     */
    private function calculateSimilarity(Person $person1, Person $person2): float
    {
        $score = 0;
        $factors = 0;

        // Name similarity (most important factor)
        $nameSimilarity = $this->calculateNameSimilarity($person1->fullname(), $person2->fullname());
        $score += $nameSimilarity * 0.5;
        $factors += 0.5;

        // Birth date similarity
        if ($person1->birthday && $person2->birthday) {
            $birthSimilarity = $this->calculateDateSimilarity($person1->birthday, $person2->birthday);
            $score += $birthSimilarity * 0.3;
            $factors += 0.3;
        }

        // Death date similarity
        if ($person1->deathday && $person2->deathday) {
            $deathSimilarity = $this->calculateDateSimilarity($person1->deathday, $person2->deathday);
            $score += $deathSimilarity * 0.2;
            $factors += 0.2;
        }

        return $factors > 0 ? $score / $factors : 0;
    }

    /**
     * Calculate name similarity using Levenshtein distance
     */
    private function calculateNameSimilarity(string $name1, string $name2): float
    {
        $name1 = strtolower(trim($name1));
        $name2 = strtolower(trim($name2));

        if ($name1 === $name2) {
            return 1.0;
        }

        $maxLength = max(strlen($name1), strlen($name2));
        if ($maxLength === 0) {
            return 0.0;
        }

        $distance = levenshtein($name1, $name2);
        return 1 - ($distance / $maxLength);
    }

    /**
     * Calculate date similarity
     */
    private function calculateDateSimilarity(DateTime $date1, DateTime $date2): float
    {
        $diff = abs($date1->getTimestamp() - $date2->getTimestamp());
        $daysDiff = $diff / (60 * 60 * 24);

        // Perfect match
        if ($daysDiff === 0) {
            return 1.0;
        }

        // Within 1 year = high similarity
        if ($daysDiff <= 365) {
            return 0.9;
        }

        // Within 5 years = medium similarity
        if ($daysDiff <= 1825) {
            return 0.7;
        }

        // More than 5 years = low similarity
        return 0.3;
    }

    /**
     * Calculate confidence scores for potential duplicates
     */
    private function calculateConfidenceScores(Person $person, array $potentialDuplicates): array
    {
        $scores = [];

        foreach ($potentialDuplicates as $duplicate) {
            $scores[$duplicate['id']] = [
                'overall' => $duplicate['similarity_score'],
                'name_match' => $this->calculateNameSimilarity($person->fullname(), $duplicate['name']) * 100,
                'date_match' => $this->calculateDateMatchScore($person, $duplicate),
            ];
        }

        return $scores;
    }

    /**
     * Calculate date match score for display
     */
    private function calculateDateMatchScore(Person $person, array $duplicate): float
    {
        $score = 0;
        $factors = 0;

        if ($person->birthday && $duplicate['birth_date']) {
            $birthDate = new DateTime($duplicate['birth_date']);
            $score += $this->calculateDateSimilarity($person->birthday, $birthDate) * 50;
            $factors += 50;
        }

        if ($person->deathday && $duplicate['death_date']) {
            $deathDate = new DateTime($duplicate['death_date']);
            $score += $this->calculateDateSimilarity($person->deathday, $deathDate) * 50;
            $factors += 50;
        }

        return $factors > 0 ? $score / $factors * 100 : 0;
    }
}