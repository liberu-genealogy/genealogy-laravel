<?php

namespace App\Filament\App\Pages;

use App\Jobs\ExportGedCom;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;

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
            ->body('Your GEDCOM file is being generated. Refresh this page in a moment to find it in the list below.')
            ->success()
            ->send();
    }

    public function deleteFile(string $filename): void
    {
        // Validate filename to prevent path traversal — only allow exact expected pattern
        if (! preg_match('/^\d{4}-\d{2}-\d{2}_\d{6}_family_tree\.ged$/', $filename)) {
            return;
        }

        Storage::disk('private')->delete($filename);

        Notification::make()
            ->title('File deleted')
            ->success()
            ->send();
    }

    #[Computed]
    public function exportedFiles(): array
    {
        $disk = Storage::disk('private');

        return collect($disk->files('/'))
            ->filter(fn (string $file) => str_ends_with($file, '_family_tree.ged'))
            ->map(function (string $file) use ($disk) {
                return [
                    'name'      => basename($file),
                    'size'      => $this->formatBytes($disk->size($file)),
                    'modified'  => date('d M Y, H:i', $disk->lastModified($file)),
                    'timestamp' => $disk->lastModified($file),
                    'url'       => $disk->temporaryUrl($file, now()->addMinutes(30)),
                ];
            })
            ->sortByDesc('timestamp')
            ->values()
            ->toArray();
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1_048_576) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1_048_576, 2).' MB';
    }
}
