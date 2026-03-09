<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\GedcomResource\Pages\ListGedcoms;
use App\Filament\App\Resources\GedcomResource\Pages\CreateGedcom;
use App\Filament\App\Resources\GedcomResource\Pages\ViewGedcom;
use App\Filament\App\Resources\GedcomResource\Pages\EditGedcom;
use Override;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use BackedEnum;
use App\Filament\App\Resources\GedcomResource\Pages;
use App\Jobs\ExportGedCom;
use App\Jobs\ExportGrampsXml;
use App\Jobs\ImportGedcom;
use App\Jobs\ImportGrampsXml;
use App\Models\Gedcom;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use App\Filament\App\Resources\AppResource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class GedcomResource extends AppResource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Gedcom::class;

    protected static ?string $navigationLabel = 'Gedcom';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document';

    protected static ?int $navigationSort = 10;

    protected static bool $shouldRegisterNavigation = true;

    protected static string | \UnitEnum | null $navigationGroup = "🛠️ Data Management";

    public static function canCreate(): bool
    {
        return auth()->check();
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGedcoms::route('/'),
            'create' => CreateGedcom::route('/create'),
            'view'   => ViewGedcom::route('/{record}'),
            'edit'   => EditGedcom::route('/{record}/edit'),
        ];
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('import_info')
                    ->label('')
                    ->content(new HtmlString(
                        '<p class="text-sm text-gray-600 dark:text-gray-400">'
                        . 'Import your family tree data by uploading a GEDCOM (<code>.ged</code>) or GrampsXML '
                        . '(<code>.gramps</code>, <code>.xml</code>) file. The file will be processed in the background '
                        . 'and you will be redirected to the Import Logs page to monitor progress.</p>'
                    ))
                    ->columnSpanFull(),

                Placeholder::make('supported_formats')
                    ->label('Supported file formats')
                    ->content(new HtmlString(
                        '<ul class="list-disc list-inside text-sm space-y-1">'
                        . '<li><strong>.ged</strong> – Standard GEDCOM format (most genealogy software)</li>'
                        . '<li><strong>.gramps</strong> – Gramps native XML format</li>'
                        . '<li><strong>.xml</strong> – GrampsXML format</li>'
                        . '</ul>'
                    ))
                    ->columnSpanFull(),

                FileUpload::make('filename')
                    ->multiple(false)
                    ->required()
                    ->acceptedFileTypes(['.ged', '.gramps', 'text/plain', 'application/xml', 'text/xml'])
                    ->mimeTypeMap(['ged' => 'text/plain', 'gramps' => 'application/xml'])
                    ->maxSize(100000)
                    ->disk('private')
                    ->directory('gedcom-form-imports')
                    ->visibility('private')
                    ->helperText('Upload GEDCOM (.ged) or GrampsXML (.gramps, .xml) files')
                    ->columnSpanFull(),

                Placeholder::make('processing_note')
                    ->label('')
                    ->content(new HtmlString(
                        '<p class="text-sm text-yellow-800 dark:text-yellow-200">'
                        . '<strong>Note:</strong> After submitting, your file will be queued for processing '
                        . 'and you will be redirected to the <strong>Import Logs</strong> page where you can '
                        . 'monitor the import progress in real time. Large files may take several minutes to process.'
                        . '</p>'
                    ))
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
        if (! $user) {
            return;
        }
        $fileName = now()->format('Y-m-d_His').'_family_tree.ged'; // Generating a unique file name
        ExportGedCom::dispatch($fileName, $user);
    }

    public static function exportGrampsXml(): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }
        $fileName = now()->format('Y-m-d_His').'_family_tree.gramps';
        ExportGrampsXml::dispatch($fileName, $user);
    }
}
