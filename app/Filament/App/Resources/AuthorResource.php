<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\AuthorResource\Pages\ListAuthors;
use App\Filament\App\Resources\AuthorResource\Pages\CreateAuthor;
use App\Filament\App\Resources\AuthorResource\Pages\EditAuthor;
use BackedEnum;
use UnitEnum;
use App\Filament\App\Resources\AuthorResource\Pages;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Author';

    protected static string | \UnitEnum | null $navigationGroup = 'Author';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required()
                ->maxLength(255),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                TextInput::make('is_active')
                    ->required()
                    ->numeric(),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('description')
                ->searchable(),
            TextColumn::make('is_active')
                ->numeric()
                ->sortable(),
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
            'index'  => ListAuthors::route('/'),
            'create' => CreateAuthor::route('/create'),
            'edit'   => EditAuthor::route('/{record}/edit'),
        ];
    }
}
