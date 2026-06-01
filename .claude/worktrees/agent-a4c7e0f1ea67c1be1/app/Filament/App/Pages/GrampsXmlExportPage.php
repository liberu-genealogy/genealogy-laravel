<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Jobs\ExportGrampsXml;
use Illuminate\Support\Facades\Auth;

class GrampsXmlExportPage extends Page
{
    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-tray';

    #[\Override]
    protected static ?string $navigationLabel = 'GrampsXML Export';

    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = "🛠️ Data Management";

    #[\Override]
    protected static ?int $navigationSort = 3;

    #[\Override]
    protected static ?string $title = 'Export GrampsXML';

    #[\Override]
    protected static ?string $slug = 'grampsxml-export';

    #[\Override]
    protected string $view = 'filament.app.pages.grampsxml-export-page';

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Generate GrampsXML')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action('startExport'),
        ];
    }

    public function startExport(): void
    {
        $user = Auth::user();
        $fileName = now()->format('Y-m-d_His').'_family_tree.gramps';
        ExportGrampsXml::dispatch($fileName, $user);

        Notification::make()
            ->title('Export started')
            ->body('We are generating your GrampsXML file. You will be able to download it from your storage when ready.')
            ->success()
            ->send();
    }
}
