<?php

namespace App\Filament\App\Resources;

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

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = true;

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGedcoms::route('/'),
            'create' => Pages\CreateGedcom::route('/create'),
            'view'   => Pages\ViewGedcom::route('/{record}'),
            'edit'   => Pages\EditGedcom::route('/{record}/edit'),
        ];
    }

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
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

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('filename')
                    ->label('File name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\Action::make('export')
                    ->action(fn () => static::exportGedcom())
                    ->label('Export GEDCOM'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
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
