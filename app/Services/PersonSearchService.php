<?php

namespace App\Services;

use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\Place;
use App\Models\Source;
use App\Models\Team;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonSearchService
{
    /** Run the phonetic fallback only when the exact/LIKE pass returns fewer than this. */
    private const PHONETIC_THRESHOLD = 3;

    /** Hard cap on same-first-letter rows the PHP soundex pass scans, so it can't explode. */
    private const PHONETIC_SCAN_CAP = 200;

    /**
     * Search people within the current user's team only.
     * Living persons are included since this is the user's own data.
     */
    public function searchOwnTeam(string $query, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        return Person::query()
            ->where(function (Builder $q) use ($query): void {
                $this->applySearchConditions($q, $query);
            })
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Search across all public teams (and optionally the user's own team).
     * Living individuals (no death record + born < 100 years ago) are excluded
     * from other teams' results for privacy.
     */
    public function searchGlobal(string $query, int $perPage = 20, bool $includeOwnTeam = true, int $page = 1): LengthAwarePaginator
    {
        return $this->globalPersonQuery(
            fn (Builder $q) => $this->applySearchConditions($q, $query),
            $includeOwnTeam,
        )
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Global search across every searchable entity type, returned as labelled
     * result groups. People honour the same public-team privacy filter as
     * searchGlobal(); places, sources and events are team-scoped by the
     * BelongsToTenant global scope. An optional from/to year range narrows the
     * person and event groups.
     *
     * @return array{people: Collection, places: Collection, sources: Collection, events: Collection}
     */
    public function searchAll(string $query, ?int $fromYear = null, ?int $toYear = null, bool $global = true, int $perGroup = 20): array
    {
        $term = trim($query);

        if ($term === '' || $term === '0') {
            return ['people' => new Collection, 'places' => new Collection, 'sources' => new Collection, 'events' => new Collection];
        }

        return [
            'people' => $this->searchPeople($term, $fromYear, $toYear, $global, $perGroup),
            'places' => $this->searchPlaces($term, $perGroup),
            'sources' => $this->searchSources($term, $perGroup),
            'events' => $this->searchEvents($term, $fromYear, $toYear, $perGroup),
        ];
    }

    /**
     * People group: exact/LIKE pass, with a bounded Soundex fallback when it
     * returns little.
     */
    private function searchPeople(string $term, ?int $fromYear, ?int $toYear, bool $global, int $limit): Collection
    {
        $query = $this->buildPeopleQuery($global, fn (Builder $q) => $this->applySearchConditions($q, $term));
        $this->applyPersonYearRange($query, $fromYear, $toYear);

        $people = $query->orderByDesc('id')->limit($limit)->get();

        if ($people->count() < self::PHONETIC_THRESHOLD) {
            $people = $this->addPhoneticMatches($people, $term, $fromYear, $toYear, $global, $limit);
        }

        return $people;
    }

    /**
     * Soundex near-miss fallback (e.g. "Smyth" → "Smith"). Bounded three ways:
     * candidates must share the term's first letter (Soundex keeps it), stay
     * inside the same privacy scope, and are capped at PHONETIC_SCAN_CAP rows
     * before the PHP soundex() comparison; the merged result is capped at $limit.
     */
    private function addPhoneticMatches(Collection $exact, string $term, ?int $fromYear, ?int $toYear, bool $global, int $limit): Collection
    {
        $firstWord = preg_split('/\s+/', trim($term), -1, PREG_SPLIT_NO_EMPTY)[0] ?? '';

        if ($firstWord === '') {
            return $exact;
        }

        $code = soundex($firstWord);
        $like = mb_substr($firstWord, 0, 1).'%';

        $candidates = $this->buildPeopleQuery($global, function (Builder $q) use ($like): void {
            $q->where('surn', 'LIKE', $like)->orWhere('givn', 'LIKE', $like);
        });
        $this->applyPersonYearRange($candidates, $fromYear, $toYear);

        $matches = $candidates
            ->limit(self::PHONETIC_SCAN_CAP)
            ->get()
            ->filter(fn (Person $p): bool => soundex((string) $p->surn) === $code || soundex((string) $p->givn) === $code);

        return $exact->concat($matches)->unique('id')->take($limit)->values();
    }

    /**
     * Places group — title/description.
     */
    private function searchPlaces(string $term, int $limit): Collection
    {
        $like = '%'.$term.'%';

        return Place::query()
            ->where(fn (Builder $q) => $q->where('title', 'LIKE', $like)->orWhere('description', 'LIKE', $like))
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /**
     * Sources group — name/title.
     */
    private function searchSources(string $term, int $limit): Collection
    {
        $like = '%'.$term.'%';

        return Source::query()
            ->where(fn (Builder $q) => $q->where('name', 'LIKE', $like)->orWhere('titl', 'LIKE', $like))
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /**
     * Events group — title/type, optionally narrowed by year range.
     */
    private function searchEvents(string $term, ?int $fromYear, ?int $toYear, int $limit): Collection
    {
        $like = '%'.$term.'%';

        $query = PersonEvent::query()
            ->where(fn (Builder $q) => $q->where('title', 'LIKE', $like)->orWhere('type', 'LIKE', $like));

        if ($fromYear !== null) {
            $query->where('year', '>=', $fromYear);
        }
        if ($toYear !== null) {
            $query->where('year', '<=', $toYear);
        }

        return $query->orderByDesc('id')->limit($limit)->get();
    }

    /**
     * Build the person query for either the own-team path (tenant-scoped) or the
     * cross-team global path (privacy-filtered), applying $match as the search
     * predicate. Shared by the exact and phonetic passes.
     */
    private function buildPeopleQuery(bool $global, Closure $match): Builder
    {
        return $global
            ? $this->globalPersonQuery($match, true)
            : Person::query()->where($match);
    }

    /**
     * Cross-team person query: own team (all people) OR public teams
     * (deceased/historical only). $match applies the search predicate inside
     * each branch.
     */
    private function globalPersonQuery(Closure $match, bool $includeOwnTeam): Builder
    {
        $currentTeamId = Auth::user()?->currentTeam?->id;

        return Person::withoutGlobalScope('team')
            ->where(function (Builder $outer) use ($match, $currentTeamId, $includeOwnTeam): void {
                if ($includeOwnTeam && $currentTeamId) {
                    $outer->where(function (Builder $own) use ($match, $currentTeamId): void {
                        $own->where('people.team_id', $currentTeamId)->where($match);
                    });
                }

                $publicTeamIds = Team::where('is_public', true)
                    ->when($currentTeamId, fn ($q) => $q->where('id', '!=', $currentTeamId))
                    ->pluck('id');

                if ($publicTeamIds->isNotEmpty()) {
                    $outer->orWhere(function (Builder $pub) use ($match, $publicTeamIds): void {
                        $pub->whereIn('people.team_id', $publicTeamIds)
                            ->deceased()
                            ->where($match);
                    });
                }
            });
    }

    /**
     * Narrow a person query to a birth-year range when either bound is given.
     */
    private function applyPersonYearRange(Builder $query, ?int $fromYear, ?int $toYear): void
    {
        if ($fromYear !== null) {
            $query->where('people.birth_year', '>=', $fromYear);
        }
        if ($toYear !== null) {
            $query->where('people.birth_year', '<=', $toYear);
        }
    }

    /**
     * Apply search conditions — uses FULLTEXT when available, falls back to LIKE.
     */
    private function applySearchConditions(Builder $query, string $searchTerm): void
    {
        $term = trim($searchTerm);

        if ($term === '' || $term === '0') {
            return;
        }

        // Try MySQL fulltext match first
        if ($this->supportsFulltext()) {
            $query->where(function (Builder $q) use ($term): void {
                $q->whereRaw(
                    'MATCH(givn, surn, name, description) AGAINST(? IN BOOLEAN MODE)',
                    [$this->prepareFulltextTerm($term)]
                )
                    ->orWhere('birthday_plac', 'LIKE', '%'.$term.'%')
                    ->orWhere('deathday_plac', 'LIKE', '%'.$term.'%');
            });
        } else {
            $likeTerm = '%'.$term.'%';
            $query->where(function (Builder $q) use ($likeTerm): void {
                $q->where('givn', 'LIKE', $likeTerm)
                    ->orWhere('surn', 'LIKE', $likeTerm)
                    ->orWhere('name', 'LIKE', $likeTerm)
                    ->orWhere('description', 'LIKE', $likeTerm)
                    ->orWhere('birthday_plac', 'LIKE', $likeTerm)
                    ->orWhere('deathday_plac', 'LIKE', $likeTerm);
            });
        }
    }

    /**
     * Prepare a search term for MySQL FULLTEXT boolean mode.
     */
    private function prepareFulltextTerm(string $term): string
    {
        $words = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY);

        // Add + prefix to each word for AND matching, * suffix for prefix matching
        return implode(' ', array_map(fn ($w): string => '+'.$w.'*', $words));
    }

    /**
     * Check if the fulltext index exists on the people table.
     */
    private function supportsFulltext(): bool
    {
        static $supported = null;

        if ($supported !== null) {
            return $supported;
        }

        try {
            $driver = DB::connection()->getDriverName();
            if (! in_array($driver, ['mysql', 'mariadb'])) {
                return $supported = false;
            }

            $indexes = DB::select("SHOW INDEX FROM people WHERE Key_name = 'people_fulltext_index'");

            return $supported = count($indexes) > 0;
        } catch (\Throwable) {
            return $supported = false;
        }
    }
}
