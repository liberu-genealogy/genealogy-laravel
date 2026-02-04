<?php

namespace App\Services;

use DateTimeInterface;
use App\Models\HistoricalEvent;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HistoricalEventService
{
    /**
     * Fetch historical events within a date range and optionally by country.
     *
     * @param string|DateTimeInterface $start
     * @param string|DateTimeInterface $end
     * @param  string|null  $country
     * @return Collection
     */
    public function fetchForPeriod($start, $end, ?string $country = null): Collection
    {
        $query = HistoricalEvent::query()
            ->betweenDates(Carbon::parse($start)->toDateString(), Carbon::parse($end)->toDateString());

        if ($country) {
            $query->where('country', $country);
        }

        return $query->orderBy('date')->get();
    }

    /**
     * Fetch historical events relevant to a person.
     * By default this returns events within the person's life +/- $bufferYears.
     *
     * @param  Person  $person
     * @param  int  $bufferYears
     * @return Collection
     */
    public function fetchForPerson(Person $person, int $bufferYears = 5): Collection
    {
        // Best-effort date limits using birthday/deathday if present
        $start = null;
        $end = null;

        if ($person->birthday) {
            $start = Carbon::parse($person->birthday)->subYears($bufferYears)->startOfYear();
        }

        if ($person->deathday) {
            $end = Carbon::parse($person->deathday)->addYears($bufferYears)->endOfYear();
        }

        // If only one bound is present, expand by buffer
        if ($start && !$end) {
            $end = Carbon::parse($start)->copy()->addYears(100); // arbitrary far future
        }

        if ($end && !$start) {
            $start = Carbon::parse($end)->copy()->subYears(120); // arbitrary far past
        }

        if (!$start || !$end) {
            // fallback: use birth-year +/- buffer if year available
            $year = $person->birthday ? Carbon::parse($person->birthday)->year : null;
            if ($year) {
                $start = Carbon::create($year - $bufferYears, 1, 1);
                $end = Carbon::create($year + $bufferYears, 12, 31);
            }
        }

        if (!$start || !$end) {
            // final fallback: last 100 years
            $start = Carbon::now()->subYears(100)->startOfYear();
            $end = Carbon::now()->endOfYear();
        }

        return $this->fetchForPeriod($start, $end);
    }
}
