<?php

namespace App\Filament\App\Pages;

use App\Jobs\ExportGedCom;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;

class GedcomExportPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';

    #[\Override]
    protected static ?string $navigationLabel = 'GEDCOM Export';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '🛠️ Data & Import';

    #[\Override]
    protected static ?int $navigationSort = 2;

    #[\Override]
    protected static ?string $title = 'Export GEDCOM';

    #[\Override]
    protected static ?string $slug = 'gedcom-export';

    #[\Override]
    protected string $view = 'filament.app.pages.gedcom-export-page';

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Generate GEDCOM')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->schema([
                    Select::make('format')
                        ->label('Format')
                        ->options([
                            '5.5.1' => 'GEDCOM 5.5.1',
                            '7.0' => 'GEDCOM 7',
                            'gedcomx' => 'GEDCOM X',
                        ])
                        ->default('5.5.1')
                        ->required(),
                ])
                ->action(fn (array $data) => $this->startExport($data)),
        ];
    }

    /**
     * Every path below is confined to the current team's export directory.
     *
     * This page used to list every file at the root of the shared private disk
     * whose name ended in the export suffix, and hand out a signed download URL
     * for each. Names were timestamps, so nothing distinguished one team's tree
     * from another's: every member of every team was shown, and could download,
     * every other team's exported family tree. Deleting worked the same way — a
     * name matching the expected pattern was sufficient, whoever it belonged to.
     *
     * The team is read from the tenant rather than from the user's stored team,
     * so it is the team on screen that is exported and listed.
     *
     * @param  array<string, mixed>  $data
     */
    public function startExport(array $data = []): void
    {
        $user = Auth::user();
        $teamId = $this->teamId();

        abort_unless($user && $teamId, 403);

        $format = in_array($data['format'] ?? null, ['5.5.1', '7.0', 'gedcomx'], true)
            ? $data['format']
            : '5.5.1';

        // GEDCOM X is JSON; 5.5.1 and 7 are GEDCOM text.
        $extension = $format === 'gedcomx' ? 'json' : 'ged';
        $fileName = now()->format('Y-m-d_His').'_family_tree.'.$extension;
        ExportGedCom::dispatch($fileName, $user, $teamId, $format);

        Notification::make()
            ->title('Export started')
            ->body('Your GEDCOM file is being generated. Refresh this page in a moment to find it in the list below.')
            ->success()
            ->send();
    }

    public function deleteFile(string $filename): void
    {
        // The pattern check stays: it stops path traversal, which prefixing a
        // directory would not. It is not, on its own, evidence of ownership —
        // every team's exports match it, which is how this deleted other
        // teams' files.
        if (! preg_match('/^\d{4}-\d{2}-\d{2}_\d{6}_family_tree\.(ged|json)$/', $filename)) {
            return;
        }

        $teamId = $this->teamId();

        abort_unless((bool) $teamId, 403);

        Storage::disk('private')->delete(ExportGedCom::directoryFor($teamId).'/'.$filename);

        Notification::make()
            ->title('File deleted')
            ->success()
            ->send();
    }

    #[Computed]
    public function exportedFiles(): array
    {
        $teamId = $this->teamId();

        if (! $teamId) {
            return [];
        }

        $disk = Storage::disk('private');

        return collect($disk->files(ExportGedCom::directoryFor($teamId)))
            ->filter(fn (string $file): bool => str_ends_with($file, '_family_tree.ged') || str_ends_with($file, '_family_tree.json'))
            ->map(fn (string $file) => [
                'name' => basename($file),
                'size' => $this->formatBytes($disk->size($file)),
                'modified' => date('d M Y, H:i', $disk->lastModified($file)),
                'timestamp' => $disk->lastModified($file),
                'url' => $disk->temporaryUrl($file, now()->addMinutes(30)),
            ])
            ->sortByDesc('timestamp')
            ->values()
            ->toArray();
    }

    private function teamId(): ?int
    {
        $tenant = Filament::getTenant();

        return $tenant ? (int) $tenant->getKey() : null;
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
