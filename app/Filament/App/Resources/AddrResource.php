<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\AddrResource\Pages\ListAddrs;
use App\Filament\App\Resources\AddrResource\Pages\CreateAddr;
use App\Filament\App\Resources\AddrResource\Pages\EditAddr;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\AddrResource\Pages;
use App\Models\Addr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class AddrResource extends Resource
{
    protected static ?string $model = Addr::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Address';

    protected static string | \UnitEnum | null $navigationGroup = 'Person';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('adr1')
                ->maxLength(255),
                TextInput::make('adr2')
                    ->maxLength(255),
                TextInput::make('city')
                    ->maxLength(255),
                TextInput::make('stae')
                    ->maxLength(255),
                TextInput::make('post')
                    ->maxLength(255),
                TextInput::make('ctry')
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

    public static function getPages(): array
    {
        return [
            'index'  => ListAddrs::route('/'),
            'create' => CreateAddr::route('/create'),
            'edit'   => EditAddr::route('/{record}/edit'),
        ];
    }
}
