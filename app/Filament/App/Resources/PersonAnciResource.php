<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonAnciResource\Pages\ListPersonAncis;
use App\Filament\App\Resources\PersonAnciResource\Pages\CreatePersonAnci;
use App\Filament\App\Resources\PersonAnciResource\Pages\EditPersonAnci;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\PersonAnciResource\Pages;
use App\Models\PersonAnci;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonAnciResource extends Resource
{
    protected static ?string $model = PersonAnci::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Person Anci';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udc65 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('anci')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('anci')
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

    public static function getPages(): array
    {
        return [
            'index'  => ListPersonAncis::route('/'),
            'create' => CreatePersonAnci::route('/create'),
            'edit'   => EditPersonAnci::route('/{record}/edit'),
        ];
    }
}
