<?php

namespace App\Filament\App\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Jobs\ExportGedCom;
use Illuminate\Support\Facades\Auth;

class GedcomExportPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'GEDCOM Export';

    protected static string | \UnitEnum | null $navigationGroup = "🛠️ Data Management";

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Export GEDCOM';

    protected static ?string $slug = 'gedcom-export';

    protected string $view = 'filament.app.pages.gedcom-export-page';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Generate GEDCOM')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action('startExport'),
        ];
    }

    public function startExport(): void
    {
        $user = Auth::user();
        $fileName = now()->format('Y-m-d_His').'_family_tree.ged';
        ExportGedCom::dispatch($fileName, $user);

        Notification::make()
            ->title('Export started')
            ->body('We are generating your GEDCOM file. You will be able to download it from your storage when ready.')
            ->success()
            ->send();
    }
}
