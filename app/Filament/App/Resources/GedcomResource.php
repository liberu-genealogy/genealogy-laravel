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
use App\Jobs\ExportGedcom;
use App\Jobs\ImportGedcom;
use App\Models\Gedcom;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GedcomResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Gedcom::class;

    protected static ?string $navigationLabel = 'Gedcom';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = true;

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udee0\ufe0f Data Management';

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
                FileUpload::make('filename')
                    ->multiple(false)
                    // ->required()
                    ->maxSize(100000)
                    ->directory('gedcom-form-imports')
                    ->visibility('private')

                    // ->afterStateUpdated(function ($state, $set, $livewire): void {
                    //     if ($state === null) {
                    //         return;
                    //     }
                    //     $path = $state->store('gedcom-form-imports', 'private');
                    //     Log::info($path);
                    //     //ImportGedcom::dispatch(Auth::user(), Storage::disk('private')->path($path));
                    // }),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
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
                Action::make('export')
                    ->action(fn () => static::exportGedcom())
                    ->label('Export GEDCOM'),
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
        $user = auth()->user(); // Assuming the user is authenticated
        $fileName = now()->format('Y-m-d_His').'_family_tree.ged'; // Generating a unique file name
        ExportGedCom::dispatch($fileName, $user);
    }
}
