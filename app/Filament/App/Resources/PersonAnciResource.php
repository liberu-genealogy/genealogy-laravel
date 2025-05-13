<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\PersonAnciResource\Pages;
use App\Models\PersonAnci;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PersonAnciResource extends Resource
{
    protected static ?string $model = PersonAnci::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Person Anci';

    protected static UnitEnum|string|null $navigationGroup = 'Person';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                ->maxLength(255),
                Forms\Components\TextInput::make('gid')
                    ->numeric(),
                Forms\Components\TextInput::make('anci')
                    ->maxLength(255),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anci')
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPersonAncis::route('/'),
            'create' => Pages\CreatePersonAnci::route('/create'),
            'edit'   => Pages\EditPersonAnci::route('/{record}/edit'),
        ];
    }
}
