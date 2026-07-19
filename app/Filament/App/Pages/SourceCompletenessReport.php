<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Services\CompletenessService;
use Filament\Pages\Page;

class SourceCompletenessReport extends Page
{
    #[\Override]
    protected string $view = 'filament.app.pages.source-completeness-report';

    #[\Override]
    protected static ?string $title = 'Source Completeness';

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    #[\Override]
    protected static ?string $navigationLabel = 'Source Completeness';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📊 Charts & Reports';

    /**
     * Source-coverage stats for the current tenant.
     */
    public function report(): array
    {
        return app(CompletenessService::class)->sourceCompleteness();
    }
}
