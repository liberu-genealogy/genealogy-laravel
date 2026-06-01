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
    protected static string|\UnitEnum|null $navigationGroup = '🔍 Research & Analysis';

    #[\Override]
    protected static ?string $navigationLabel = 'Global Search';

    #[\Override]
    protected static ?int $navigationSort = 1;

    public string $query = '';

    public bool $searchGlobal = true;

    public $results = [];

    public int $currentPage = 1;

    public int $lastPage = 1;

    public int $totalResults = 0;

    public function search(): void
    {
        if (in_array(trim($this->query), ['', '0'], true)) {
            $this->results = [];
            $this->totalResults = 0;

            return;
        }

        $service = app(PersonSearchService::class);

        $paginator = $this->searchGlobal
            ? $service->searchGlobal($this->query, 20, true, $this->currentPage)
            : $service->searchOwnTeam($this->query, 20, $this->currentPage);

        $this->results = $paginator->items();
        $this->currentPage = $paginator->currentPage();
        $this->lastPage = $paginator->lastPage();
        $this->totalResults = $paginator->total();
    }

    public function updatedQuery(): void
    {
        $this->currentPage = 1;
        if (strlen(trim($this->query)) >= 2) {
            $this->search();
        } else {
            $this->results = [];
            $this->totalResults = 0;
        }
    }

    public function updatedSearchGlobal(): void
    {
        $this->currentPage = 1;
        if (!in_array(trim($this->query), ['', '0'], true)) {
            $this->search();
        }
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->search();
        }
    }

    public function nextPage(): void
    {
        if ($this->currentPage < $this->lastPage) {
            $this->currentPage++;
            $this->search();
        }
    }
}
