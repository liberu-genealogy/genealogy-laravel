<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewPersonAliaResource\Pages;
use App\Filament\Resources\NewPersonAliaResource\RelationManagers;
use App\Models\NewPersonAlia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewPersonAliaResource extends Resource
{
    protected static ?string $model = NewPersonAlia::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Person Alia';

    protected static ?string $navigationGroup = 'Person';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                ->maxLength(255),
            Forms\Components\TextInput::make('gid')
                ->numeric(),
            Forms\Components\TextInput::make('alia')
                ->maxLength(255),
            Forms\Components\TextInput::make('import_confirm')
                ->required()
                ->numeric()
                ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                ->searchable(),
            Tables\Columns\TextColumn::make('gid')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('alia')
                ->searchable(),
            Tables\Columns\TextColumn::make('import_confirm')
                ->numeric()
                ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewPersonAlias::route('/'),
            'create' => Pages\CreateNewPersonAlia::route('/create'),
            'edit' => Pages\EditNewPersonAlia::route('/{record}/edit'),
        ];
    }
}
