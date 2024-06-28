<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GedcomResource\Pages;
use App\Jobs\ExportGedcom;
use App\Jobs\ImportGedcom;
use App\Models\Gedcom;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GedcomResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Gedcom::class;

    protected static ?string $navigationLabel = 'Gedcom';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('attachment')
                    ->required()
                    ->maxSize(100000)
                    ->directory('gedcom-form-imports')
                    ->visibility('private')
                    ->afterStateUpdated(function ($state, $set, $livewire) {
                        if ($state === null) {
                            return;
                        }
                        $path = $state->store('gedcom-form-imports', 'private');
                        ImportGedcom::dispatch(Auth::user(), Storage::disk('private')->path($path));
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_name')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export')
                    ->action(fn () => static::exportGedcom())
                    ->label('Export GEDCOM'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
