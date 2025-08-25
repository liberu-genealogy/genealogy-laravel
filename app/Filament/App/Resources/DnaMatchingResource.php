<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\DnaMatchingResource\Pages;
use App\Models\DnaMatching;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class DnaMatchingResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = DnaMatching::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'DNA Matches';

    protected static string | UnitEnum | null $navigationGroup = 'DNA Analysis';

    protected static ?int $navigationSort = 2;

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                ->required()
                ->numeric(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('file1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('file2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_shared_cm')
                    ->maxLength(255),
                Forms\Components\TextInput::make('largest_cm_segment')
                    ->maxLength(255),
                Forms\Components\TextInput::make('match_id')
                    ->numeric(),
                Forms\Components\TextInput::make('match_name')
                    ->maxLength(255),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('file1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_shared_cm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('largest_cm_segment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('match_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('match_name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDnaMatchings::route('/'),
            'create' => Pages\CreateDnaMatching::route('/create'),
            'edit'   => Pages\EditDnaMatching::route('/{record}/edit'),
        ];
    }
}
