<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Models\Tree;
use App\Services\CompletenessService;
use Filament\Pages\Page;

class TreeCompletenessReport extends Page
{
    #[\Override]
    protected string $view = 'filament.app.pages.tree-completeness-report';

    #[\Override]
    protected static ?string $title = 'Tree Completeness';

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-pie';

    #[\Override]
    protected static ?string $navigationLabel = 'Tree Completeness';

    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = '📄 Reports';

    /**
     * Completeness stats for every tree in the current tenant.
     *
     * @return list<array{tree: Tree, stats: array}>
     */
    public function reports(): array
    {
        $service = app(CompletenessService::class);

        return Tree::all()
            ->map(fn (Tree $tree): array => [
                'tree'  => $tree,
                'stats' => $service->treeCompleteness($tree),
            ])
            ->all();
    }
}
