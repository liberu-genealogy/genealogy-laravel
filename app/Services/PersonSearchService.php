<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonSearchService
{
    /**
     * Search people within the current user's team only.
     * Living persons are included since this is the user's own data.
     */
    public function searchOwnTeam(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return Person::query()
            ->where(function (Builder $q) use ($query) {
                $this->applySearchConditions($q, $query);
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * Search across all public teams (and optionally the user's own team).
     * Living individuals (no death record + born < 100 years ago) are excluded
     * from other teams' results for privacy.
     */
    public function searchGlobal(string $query, int $perPage = 20, bool $includeOwnTeam = true): LengthAwarePaginator
    {
        $currentTeamId = Auth::user()?->currentTeam?->id;

        // Build query without the BelongsToTenant global scope
        return Person::withoutGlobalScope('team')
            ->where(function (Builder $outer) use ($query, $currentTeamId, $includeOwnTeam) {
                // Own team — include all people (living + deceased)
                if ($includeOwnTeam && $currentTeamId) {
                    $outer->where(function (Builder $own) use ($query, $currentTeamId) {
                        $own->where('people.team_id', $currentTeamId)
                            ->where(function (Builder $q) use ($query) {
                                $this->applySearchConditions($q, $query);
                            });
                    });
                }

                // Public teams — deceased/historical persons only
                $publicTeamIds = Team::where('is_public', true)
                    ->when($currentTeamId, fn ($q) => $q->where('id', '!=', $currentTeamId))
                    ->pluck('id');

                if ($publicTeamIds->isNotEmpty()) {
                    $outer->orWhere(function (Builder $pub) use ($query, $publicTeamIds) {
                        $pub->whereIn('people.team_id', $publicTeamIds)
                            ->deceased()
                            ->where(function (Builder $q) use ($query) {
                                $this->applySearchConditions($q, $query);
                            });
                    });
                }
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * Apply search conditions — uses FULLTEXT when available, falls back to LIKE.
     */
    private function applySearchConditions(Builder $query, string $searchTerm): void
    {
        $term = trim($searchTerm);

        if (empty($term)) {
            return;
        }

        // Try MySQL fulltext match first
        if ($this->supportsFulltext()) {
            $query->where(function (Builder $q) use ($term) {
                $q->whereRaw(
                    'MATCH(givn, surn, name, description) AGAINST(? IN BOOLEAN MODE)',
                    [$this->prepareFulltextTerm($term)]
                )
                ->orWhere('birthday_plac', 'LIKE', '%' . $term . '%')
                ->orWhere('deathday_plac', 'LIKE', '%' . $term . '%');
            });
        } else {
            $likeTerm = '%' . $term . '%';
            $query->where(function (Builder $q) use ($likeTerm) {
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
        return implode(' ', array_map(fn ($w) => '+' . $w . '*', $words));
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
