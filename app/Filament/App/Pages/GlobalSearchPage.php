<?php

namespace App\Filament\App\Pages;

use App\Services\PersonSearchService;
use Filament\Pages\Page;

class GlobalSearchPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    #[\Override]
    protected string $view = 'filament.app.pages.global-search-page';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

    #[\Override]
    protected static ?string $navigationLabel = 'Global Search';

    #[\Override]
    protected static ?int $navigationSort = 1;

    public string $query = '';

    public bool $searchGlobal = true;

    // Optional year-range filter (kept as strings so an empty input stays null).
    public ?string $fromYear = null;

    public ?string $toYear = null;

    /** @var array{people: array, places: array, sources: array, events: array}|array */
    public array $groups = [];

    public int $totalResults = 0;

    public function search(): void
    {
        if (in_array(trim($this->query), ['', '0'], true)) {
            $this->groups = [];
            $this->totalResults = 0;

            return;
        }

        $groups = app(PersonSearchService::class)->searchAll(
            $this->query,
            $this->yearOrNull($this->fromYear),
            $this->yearOrNull($this->toYear),
            $this->searchGlobal,
        );

        $this->groups = [
            'people' => $groups['people']->all(),
            'places' => $groups['places']->all(),
            'sources' => $groups['sources']->all(),
            'events' => $groups['events']->all(),
        ];
        $this->totalResults = collect($this->groups)->sum(fn (array $g): int => count($g));
    }

    private function yearOrNull(?string $value): ?int
    {
        return $value === null || trim($value) === '' ? null : (int) $value;
    }

    public function updatedQuery(): void
    {
        if (strlen(trim($this->query)) >= 2) {
            $this->search();
        } else {
            $this->groups = [];
            $this->totalResults = 0;
        }
    }

    public function updatedSearchGlobal(): void
    {
        if (! in_array(trim($this->query), ['', '0'], true)) {
            $this->search();
        }
    }
}
