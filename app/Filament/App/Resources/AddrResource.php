<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AddrResource\Pages\CreateAddr;
use App\Filament\App\Resources\AddrResource\Pages\EditAddr;
use App\Filament\App\Resources\AddrResource\Pages\ListAddrs;
use App\Models\Addr;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class AddrResource extends AppResource
{
    #[Override]
    protected static ?string $model = Addr::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    #[Override]
    protected static ?string $navigationLabel = 'Address';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🗂️ GEDCOM Detail';

    #[Override]
    public static function canCreate(): bool
    {
        return auth()->check();
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('adr1')
                    ->label('Address Line 1')
                    ->maxLength(255),
                TextInput::make('adr2')
                    ->label('Address Line 2')
                    ->maxLength(255),
                TextInput::make('city')
                    ->label('City')
                    ->maxLength(255),
                TextInput::make('stae')
                    ->label('State / County')
                    ->maxLength(255),
                TextInput::make('post')
                    ->label('Postal Code')
                    ->maxLength(255),
                TextInput::make('ctry')
                    ->label('Country')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('adr1')
                    ->searchable(),
                TextColumn::make('adr2')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('stae')
                    ->searchable(),
                TextColumn::make('post')
                    ->searchable(),
                TextColumn::make('ctry')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListAddrs::route('/'),
            'create' => CreateAddr::route('/create'),
            'edit' => EditAddr::route('/{record}/edit'),
        ];
    }
}
