<?php

namespace App\Filament\App\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Jobs\ExportGrampsXml;
use Illuminate\Support\Facades\Auth;

class GrampsXmlExportPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'GrampsXML Export';

    protected static string | \UnitEnum | null $navigationGroup = "🛠️ Data Management";

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Export GrampsXML';

    protected static ?string $slug = 'grampsxml-export';

    protected string $view = 'filament.app.pages.grampsxml-export-page';

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
