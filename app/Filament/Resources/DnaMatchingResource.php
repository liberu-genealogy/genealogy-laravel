<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DnaMatchingResource\Pages;
use App\Models\DnaMatching;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DnaMatchingResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = DnaMatching::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Match DNA';

    protected static ?string $navigationGroup = 'Dna Matching';

    public static function form(Form $form): Form
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
            'index' => Pages\ListDnaMatchings::route('/'),
            'create' => Pages\CreateDnaMatching::route('/create'),
            'edit' => Pages\EditDnaMatching::route('/{record}/edit'),
        ];
    }
}
