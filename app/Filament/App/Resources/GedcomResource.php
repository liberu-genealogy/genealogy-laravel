<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\GedcomResource\Pages\CreateGedcom;
use App\Filament\App\Resources\GedcomResource\Pages\EditGedcom;
use App\Filament\App\Resources\GedcomResource\Pages\ListGedcoms;
use App\Filament\App\Resources\GedcomResource\Pages\ViewGedcom;
use App\Jobs\ExportGedCom;
use App\Jobs\ExportGrampsXml;
use App\Models\Gedcom;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class GedcomResource extends AppResource
{
    #[Override]
    protected static bool $isScopedToTenant = false;

    #[Override]
    protected static ?string $model = Gedcom::class;

    #[Override]
    protected static ?string $navigationLabel = 'Gedcom';

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document';

    #[Override]
    protected static ?int $navigationSort = 10;

    #[Override]
    protected static bool $shouldRegisterNavigation = true;

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🛠️ Data & Import';

    #[Override]
    public static function canCreate(): bool
    {
        return auth()->check();
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListGedcoms::route('/'),
            'create' => CreateGedcom::route('/create'),
            'view' => ViewGedcom::route('/{record}'),
            'edit' => EditGedcom::route('/{record}/edit'),
        ];
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Import Family Tree')
                    ->description(
                        'Import your family tree data by uploading a GEDCOM (.ged) or GrampsXML (.gramps, .xml) file. '
                        .'The file will be processed in the background and you will be redirected to the Import Logs page to monitor progress.'
                    )
                    ->schema([
                        FileUpload::make('filename')
                            ->label('Family tree file')
                            ->required()
                            // Validated by extension, not by media type.
                            //
                            // This used to accept a list of media types, and no
                            // .ged or .gramps file matched any of them, so every
                            // upload of either was rejected before a record was
                            // created — which is why nothing appeared in the
                            // table and the page never redirected. Only a plain
                            // .xml got through.
                            //
                            // Enumerating the missing types is not the fix: what
                            // PHP reports for a GEDCOM depends on the file. A
                            // minimal one is application/x-gedcom, while a real
                            // export carrying a FamilySearch header comes back as
                            // text/vnd.familysearch.gedcom, and other tools
                            // produce text/plain or application/octet-stream. A
                            // list long enough to be safe would be so broad it
                            // checked nothing.
                            //
                            // The extension is what the application acts on
                            // anyway — CreateGedcom picks the import job by it —
                            // so it is the honest thing to validate.
                            ->rules(['extensions:ged,gramps,xml'])
                            ->maxSize(100000)
                            ->disk('private')
                            ->directory('gedcom-form-imports')
                            ->visibility('private')
                            ->helperText('Supported formats: GEDCOM (.ged), GrampsXML (.gramps, .xml) — max 100 MB')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Processing note')
                    ->description(
                        'After submitting, your file will be queued for processing and you will be redirected to the '
                        .'Import Logs page where you can monitor the import progress in real time. '
                        .'Large files may take several minutes to process.'
                    )
                    ->columnSpanFull(),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('filename')
                    ->label('File name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('export_gedcom')
                    ->action(fn () => static::exportGedcom())
                    ->label('Export GEDCOM')
                    ->icon('heroicon-o-arrow-down-tray'),
                Action::make('export_grampsxml')
                    ->action(fn () => static::exportGrampsXml())
                    ->label('Export GrampsXML')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // private static function import(): array
    // {
    //     // Implement import functionality if needed
    // }

    public static function exportGedcom(): void
    {
        $user = auth()->user();
        $tenant = Filament::getTenant();

        // The team is required, not optional. Exports are written into the
        // exporting team's directory and the export page lists only that
        // directory, so a file produced without one would be both unscoped and
        // invisible. Refusing is better than either.
        if (! $user || ! $tenant) {
            return;
        }

        $fileName = now()->format('Y-m-d_His').'_family_tree.ged'; // Generating a unique file name
        ExportGedCom::dispatch($fileName, $user, (int) $tenant->getKey());
    }

    public static function exportGrampsXml(): void
    {
        $user = auth()->user();
        $tenant = Filament::getTenant();

        // Same requirement as the GEDCOM export above: without a team the file
        // has nowhere of its own to go, and nothing to scope its contents to.
        if (! $user || ! $tenant) {
            return;
        }

        $fileName = now()->format('Y-m-d_His').'_family_tree.gramps';
        ExportGrampsXml::dispatch($fileName, $user, (int) $tenant->getKey());
    }
}
